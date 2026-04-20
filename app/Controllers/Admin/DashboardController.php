<?php
namespace App\Controllers\Admin;

use App\Core\View;
use App\Core\Auth;
use App\Core\Database;

/**
 * DashboardController — visão geral inicial (Sub-rodada 3A).
 *
 * Métricas simples baseadas em counts. Sem charts, sem filtros.
 * Rankings/comparações entram na Fase 5.
 */
final class DashboardController
{
    public function index(): string
    {
        Auth::requireLogin();

        $today = date('Y-m-d');
        $weekAgo = date('Y-m-d', strtotime('-7 days'));

        $stats = [
            'leads_total'      => (int) Database::fetch('SELECT COUNT(*) AS c FROM lead_reservations')['c'],
            'leads_new'        => (int) Database::fetch("SELECT COUNT(*) AS c FROM lead_reservations WHERE status = 'novo'")['c'],
            'leads_week'       => (int) Database::fetch(
                "SELECT COUNT(*) AS c FROM lead_reservations WHERE created_at >= :d",
                [':d' => $weekAgo . ' 00:00:00']
            )['c'],
            'pageviews_today'  => (int) Database::fetch(
                "SELECT COUNT(*) AS c FROM analytics_events WHERE event_type = 'page_view' AND created_at >= :d",
                [':d' => $today . ' 00:00:00']
            )['c'],
            'wa_clicks_today'  => (int) Database::fetch(
                "SELECT COUNT(*) AS c FROM analytics_events WHERE event_type = 'whatsapp_click' AND created_at >= :d",
                [':d' => $today . ' 00:00:00']
            )['c'],
            'reservations_today' => (int) Database::fetch(
                "SELECT COUNT(*) AS c FROM analytics_events WHERE event_type = 'reservation_submit' AND created_at >= :d",
                [':d' => $today . ' 00:00:00']
            )['c'],
        ];

        $recent = Database::fetchAll(
            "SELECT id, customer_name, phone, service_type, status, created_at
             FROM lead_reservations ORDER BY created_at DESC LIMIT 5"
        );

        return View::render('admin/dashboard', [
            'page_title' => 'Painel • Admin Multi Cell',
            'stats'      => $stats,
            'recent'     => $recent,
        ], 'admin');
    }
}
