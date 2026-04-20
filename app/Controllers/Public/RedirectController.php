<?php
namespace App\Controllers\Public;

use App\Core\Analytics;
use App\Models\Setting;
use App\Models\Branch;

/**
 * RedirectController — registra clique e redireciona para canal externo.
 * Implementação mínima para suportar os CTAs da Fase 2.
 * Endpoints adicionais (sms, email) podem ser expandidos depois.
 */
final class RedirectController
{
    public function whatsapp(): string
    {
        $src     = (string) ($_GET['src'] ?? 'generic');
        $message = (string) ($_GET['msg'] ?? Setting::get('whatsapp_message_template', 'Olá! Vim pelo site.'));
        $number  = self::onlyDigits((string) Setting::get('whatsapp_number', ''));

        Analytics::track('whatsapp_click', $_SERVER['HTTP_REFERER'] ?? null, null, null, $src, ['msg_len' => mb_strlen($message)]);

        if ($number === '') {
            // Sem número configurado: volta para a página anterior com aviso leve
            header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/'));
            exit;
        }
        $url = 'https://wa.me/' . $number . '?text=' . rawurlencode($message);
        header('Location: ' . $url, true, 302);
        exit;
    }

    public function phone(): string
    {
        $src = (string) ($_GET['src'] ?? 'generic');
        Analytics::track('phone_click', $_SERVER['HTTP_REFERER'] ?? null, null, null, $src);
        $phone = self::onlyDigits((string) Setting::get('phone', ''));
        if ($phone === '') {
            header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/'));
            exit;
        }
        header('Location: tel:+' . $phone, true, 302);
        exit;
    }

    public function map(): string
    {
        $src = (string) ($_GET['src'] ?? 'generic');
        Analytics::track('map_click', $_SERVER['HTTP_REFERER'] ?? null, null, null, $src);
        $url = (string) Setting::get('google_maps_url', '');
        if ($url === '') {
            $b = Branch::primary();
            $q = $b ? ($b['address'] . ', ' . $b['city'] . ' ' . $b['state']) : 'Multi Cell Várzea Grande';
            $url = 'https://maps.google.com/?q=' . rawurlencode($q);
        }
        header('Location: ' . $url, true, 302);
        exit;
    }

    private static function onlyDigits(string $v): string
    {
        return preg_replace('/\D+/', '', $v) ?? '';
    }
}
