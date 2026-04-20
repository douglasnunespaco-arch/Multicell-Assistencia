<?php
namespace App\Controllers\Public;

use App\Core\View;
use App\Core\Analytics;

final class ContactController
{
    public function index(): string
    {
        Analytics::track('page_view', '/contato');
        return View::render('public/contact', [
            'page_title' => 'Contato e Localização • Multi Cell',
            'page_desc'  => 'Endereço, horário e canais de atendimento da Multi Cell em Várzea Grande/MT.',
            'branch'     => \App\Models\Branch::primary(),
        ], 'public');
    }
}
