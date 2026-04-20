<?php
namespace App\Controllers\Public;

use App\Core\View;
use App\Core\Analytics;

final class AboutController
{
    public function index(): string
    {
        Analytics::track('page_view', '/sobre');
        return View::render('public/about', [
            'page_title' => 'Sobre • Multi Cell Assistência Técnica',
            'page_desc'  => 'Conheça a Multi Cell: equipe certificada, peças selecionadas e garantia real.',
            'blocks'     => \App\Models\AboutBlock::active(),
            'branch'     => \App\Models\Branch::primary(),
        ], 'public');
    }
}
