<?php
namespace App\Controllers\Public;

use App\Core\View;
use App\Core\Analytics;

final class LinksController
{
    public function index(): string
    {
        Analytics::track('page_view', '/links');
        return View::render('public/links', [
            'page_title' => '@multicell • Links',
            'page_desc'  => 'Todos os links da Multi Cell Assistência Técnica em um só lugar.',
            'branch'     => \App\Models\Branch::primary(),
        ], 'links');
    }
}
