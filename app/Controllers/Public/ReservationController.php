<?php
namespace App\Controllers\Public;

use App\Core\View;
use App\Core\Analytics;

/**
 * ReservationController — Fase 2: apenas a tela do formulário.
 * O POST /reservar e o redirecionamento ao WhatsApp ficam para a Fase 3.
 */
final class ReservationController
{
    public function create(): string
    {
        Analytics::track('page_view', '/reservar');
        return View::render('public/reservation', [
            'page_title' => 'Reservar atendimento • Multi Cell',
            'page_desc'  => 'Reserve seu horário em 30 segundos. Atendimento personalizado em Várzea Grande/MT.',
            'branch'     => \App\Models\Branch::primary(),
        ], 'public');
    }
}
