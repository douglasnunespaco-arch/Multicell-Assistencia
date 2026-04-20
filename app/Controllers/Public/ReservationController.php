<?php
namespace App\Controllers\Public;

use App\Core\View;
use App\Core\Analytics;
use App\Core\Csrf;
use App\Core\Flash;
use App\Core\Session;
use App\Core\Validator;
use App\Models\Branch;
use App\Models\LeadReservation;
use App\Models\Setting;

/**
 * ReservationController — Fase 3: formulário + POST funcional.
 *
 * Fluxo:
 *   GET  /reservar           → create()  : exibe formulário
 *   POST /reservar           → store()   : valida, grava, registra analytics, redireciona
 *   GET  /reservar/sucesso   → success() : tela de confirmação com link WhatsApp
 */
final class ReservationController
{
    /** Períodos aceitos (whitelist para validação). */
    private const PERIODS = ['', 'manha', 'tarde', 'noite'];
    private const SERVICE_TYPES = ['loja', 'retirar', 'expressa'];

    public function create(): string
    {
        Analytics::track('page_view', '/reservar');
        $errors = $_SESSION['_errors'] ?? [];
        $old    = $_SESSION['_old']    ?? [];
        unset($_SESSION['_errors'], $_SESSION['_old']);
        return View::render('public/reservation', [
            'page_title' => 'Reservar atendimento • Multi Cell',
            'page_desc'  => 'Reserve seu horário em 30 segundos. Atendimento personalizado em Várzea Grande/MT.',
            'branch'     => Branch::primary(),
            'errors'     => $errors,
            'old'        => $old,
        ], 'public');
    }

    public function store(): string
    {
        // 1. CSRF
        Csrf::verifyOrFail();

        // 2. Honeypot anti-bot — campo "website" deve estar vazio
        if (!empty($_POST['website'])) {
            // Silencia: finge sucesso sem gravar
            return $this->silentFake();
        }

        // 3. Validação server-side
        $v = Validator::make($_POST)
            ->required('customer_name', 'Nome')->max('customer_name', 160, 'Nome')
            ->required('phone', 'Telefone')->max('phone', 20, 'Telefone')->phoneBR('phone', 'Telefone')
            ->max('device_brand', 80, 'Marca')
            ->max('device_model', 120, 'Modelo')
            ->max('issue_description', 2000, 'Descrição')
            ->max('notes', 2000, 'Observação')
            ->in('service_type', self::SERVICE_TYPES, 'Tipo de atendimento')
            ->in('preferred_period', self::PERIODS, 'Período');

        // Data: aceita vazio ou YYYY-MM-DD >= hoje
        $date = (string) ($_POST['preferred_date'] ?? '');
        if ($date !== '' && !\DateTime::createFromFormat('Y-m-d', $date)) {
            $_SESSION['_errors']['preferred_date'] = 'Data inválida.';
        }

        if (!$v->passes() || !empty($_SESSION['_errors']['preferred_date'])) {
            $_SESSION['_errors'] = array_merge($_SESSION['_errors'] ?? [], $v->errors());
            $_SESSION['_old']    = $_POST;
            Flash::error('Por favor corrija os campos destacados.');
            header('Location: /reservar#form');
            exit;
        }

        // 4. Monta payload (whitelist via Model)
        $leadId = LeadReservation::create([
            'customer_name'     => trim((string) $_POST['customer_name']),
            'phone'             => self::normalizePhone((string) $_POST['phone']),
            'device_brand'      => self::nullIfEmpty($_POST['device_brand'] ?? ''),
            'device_model'      => self::nullIfEmpty($_POST['device_model'] ?? ''),
            'issue_description' => self::nullIfEmpty($_POST['issue_description'] ?? ''),
            'service_type'      => self::nullIfEmpty($_POST['service_type'] ?? ''),
            'preferred_date'    => $date !== '' ? $date : null,
            'preferred_period'  => self::nullIfEmpty($_POST['preferred_period'] ?? ''),
            'notes'             => self::nullIfEmpty($_POST['notes'] ?? ''),
            'source'            => 'site_reservation_form',
            'ip_hash'           => self::hashIp(),
            'user_agent'        => mb_substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255),
        ]);

        // 5. Analytics
        Analytics::track('reservation_submit', '/reservar', 'lead', $leadId, 'reservation_form', [
            'service_type'     => $_POST['service_type'] ?? null,
            'preferred_period' => $_POST['preferred_period'] ?? null,
        ]);

        // 6. Limpa old/errors e guarda dados da tela de sucesso
        unset($_SESSION['_old'], $_SESSION['_errors']);
        Session::regenerate();
        $_SESSION['_reservation_success'] = [
            'lead_id'       => $leadId,
            'customer_name' => trim((string) $_POST['customer_name']),
            'wa_url'        => $this->buildWhatsAppUrl($leadId, $_POST),
        ];

        header('Location: /reservar/sucesso');
        exit;
    }

    public function success(): string
    {
        $ctx = $_SESSION['_reservation_success'] ?? null;
        if (!$ctx) {
            // Acesso direto à URL: manda para /reservar
            header('Location: /reservar');
            exit;
        }
        // Consome (one-shot)
        unset($_SESSION['_reservation_success']);

        Analytics::track('page_view', '/reservar/sucesso', 'lead', (int) $ctx['lead_id']);

        return View::render('public/reservation-success', [
            'page_title'    => 'Reserva confirmada • Multi Cell',
            'page_desc'     => 'Sua reserva foi registrada. Continue no WhatsApp.',
            'leadId'        => (int) $ctx['lead_id'],
            'waUrl'         => (string) $ctx['wa_url'],
            'customerName'  => (string) $ctx['customer_name'],
        ], 'public');
    }

    // -------------- helpers privados --------------

    private function silentFake(): string
    {
        // Bot detectado: redireciona sem gravar, sem analytics
        header('Location: /reservar/sucesso');
        // ...mas sem contexto de sessão o success() vai redirecionar de volta para /reservar
        exit;
    }

    private function buildWhatsAppUrl(int $leadId, array $post): string
    {
        $number = preg_replace('/\D+/', '', (string) Setting::get('whatsapp_number', ''));
        $msg = sprintf(
            "Olá! Acabei de enviar uma reserva pelo site (#%d).\n"
          . "Nome: %s\nTelefone: %s\n%s%s%s%s%s",
            $leadId,
            trim((string) $post['customer_name']),
            trim((string) $post['phone']),
            !empty($post['device_brand']) ? "Marca: {$post['device_brand']}\n" : '',
            !empty($post['device_model']) ? "Modelo: {$post['device_model']}\n" : '',
            !empty($post['service_type']) ? "Atendimento: {$post['service_type']}\n" : '',
            !empty($post['preferred_date']) ? "Dia desejado: {$post['preferred_date']}" . (!empty($post['preferred_period']) ? " ({$post['preferred_period']})" : '') . "\n" : '',
            !empty($post['issue_description']) ? "Problema: " . mb_substr((string) $post['issue_description'], 0, 300) : ''
        );

        if ($number === '') {
            // Fallback: volta pro /go/whatsapp que registra clique adicional
            return '/go/whatsapp?src=reservation_success&msg=' . rawurlencode($msg);
        }
        return 'https://wa.me/' . $number . '?text=' . rawurlencode($msg);
    }

    private static function nullIfEmpty($v): ?string
    {
        $v = is_string($v) ? trim($v) : $v;
        return ($v === '' || $v === null) ? null : (string) $v;
    }

    private static function normalizePhone(string $v): string
    {
        return mb_substr(trim($v), 0, 32);
    }

    private static function hashIp(): string
    {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '';
        $ip = trim(explode(',', $ip)[0] ?? '');
        return substr(hash('sha256', $ip . '|' . (defined('APP_KEY') ? APP_KEY : '')), 0, 64);
    }
}
