<?php
namespace App\Controllers\Public;

use App\Core\View;
use App\Core\Analytics;
use App\Models\Product;

/**
 * Seminovos — reaproveita Products filtrando por category='Seminovos'.
 * Conteúdo administrável via /admin/products (imagens, preços, texto livre em short_description para estado/garantia).
 */
final class SeminovosController
{
    public function index(): string
    {
        Analytics::track('page_view', '/seminovos', null, null, 'seminovos');
        return View::render('public/seminovos', [
            'page_title' => 'Seminovos • Multi Cell',
            'page_desc'  => 'Aparelhos seminovos revisados com garantia. Procedência verificada. Consulte no WhatsApp.',
            'products'   => Product::active('Seminovos'),
        ], 'public');
    }
}
