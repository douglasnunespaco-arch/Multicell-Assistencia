<?php
namespace App\Controllers\Public;

use App\Core\View;
use App\Core\Analytics;

/**
 * HomeController — stub Fase 1.
 * Página Home premium completa será implementada na Fase 2.
 */
final class HomeController
{
    public function index(): string
    {
        Analytics::track('page_view', '/', null, null, 'home');
        return View::render('public/home', [
            'title' => 'Multi Cell Assistência Técnica',
        ], 'public');
    }
}
