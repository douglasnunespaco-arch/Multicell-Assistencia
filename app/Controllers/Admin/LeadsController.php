<?php
namespace App\Controllers\Admin;

use App\Core\View;
use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Flash;
use App\Models\LeadReservation;

/**
 * LeadsController — gestão de Reservas/Leads (Sub-rodada 3C).
 *
 * Escopo enxuto: listar (com filtro por status e paginação simples),
 * ver detalhe, atualizar status. Sem busca, sem export, sem charts.
 */
final class LeadsController
{
    /** Status aceitos (whitelist). */
    private const STATUSES = ['novo', 'em_atendimento', 'concluido', 'cancelado'];

    /** Listagem paginada · GET /admin/leads[?status=...&page=N] */
    public function index(): string
    {
        Auth::requireLogin();

        $status = $_GET['status'] ?? null;
        if ($status !== null && !in_array($status, self::STATUSES, true)) {
            $status = null;
        }
        $page    = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = 20;

        $result = LeadReservation::paginate($page, $perPage, $status);
        $total    = (int) $result['total'];
        $lastPage = max(1, (int) ceil($total / $perPage));

        return View::render('admin/leads/index', [
            'page_title'   => 'Reservas • Admin Multi Cell',
            'rows'         => $result['rows'],
            'total'        => $total,
            'page'         => $page,
            'last_page'    => $lastPage,
            'per_page'     => $perPage,
            'status'       => $status,
            'statuses'     => self::STATUSES,
        ], 'admin');
    }

    /** Detalhe · GET /admin/leads/{id} */
    public function show(array $params): string
    {
        Auth::requireLogin();

        $id   = (int) ($params['id'] ?? 0);
        $lead = $id > 0 ? LeadReservation::find($id) : null;

        if (!$lead) {
            Flash::error('Reserva não encontrada.');
            header('Location: /admin/leads');
            exit;
        }

        return View::render('admin/leads/show', [
            'page_title' => 'Reserva #' . $id . ' • Admin Multi Cell',
            'lead'       => $lead,
            'statuses'   => self::STATUSES,
        ], 'admin');
    }

    /** Atualiza status · POST /admin/leads/{id}/status */
    public function updateStatus(array $params): string
    {
        Auth::requireLogin();
        Csrf::verifyOrFail();

        $id     = (int) ($params['id'] ?? 0);
        $status = (string) ($_POST['status'] ?? '');

        if ($id <= 0 || !in_array($status, self::STATUSES, true)) {
            Flash::error('Dados inválidos para atualização de status.');
            header('Location: /admin/leads');
            exit;
        }

        $lead = LeadReservation::find($id);
        if (!$lead) {
            Flash::error('Reserva não encontrada.');
            header('Location: /admin/leads');
            exit;
        }

        LeadReservation::updateStatus($id, $status);
        Flash::success('Status atualizado para: ' . $this->statusLabel($status));

        header('Location: /admin/leads/' . $id);
        exit;
    }

    private function statusLabel(string $status): string
    {
        return [
            'novo'           => 'Novo',
            'em_atendimento' => 'Em atendimento',
            'concluido'      => 'Concluído',
            'cancelado'      => 'Cancelado',
        ][$status] ?? $status;
    }
}
