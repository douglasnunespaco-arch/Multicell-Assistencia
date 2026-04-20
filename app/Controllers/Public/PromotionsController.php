<?php
namespace App\Controllers\Public;

use App\Core\View;
use App\Core\Analytics;
use App\Models\Promotion;

final class PromotionsController
{
    public function index(): string
    {
        Analytics::track('page_view', '/promocoes');
        return View::render('public/promotions-index', [
            'page_title' => 'Promoções • Multi Cell',
            'page_desc'  => 'Promoções ativas em serviços e acessórios. Aproveite enquanto estão valendo.',
            'promotions' => Promotion::active(),
        ], 'public');
    }

    public function show(array $params): string
    {
        $promo = Promotion::findBySlug($params['slug'] ?? '');
        if (!$promo) {
            http_response_code(404);
            return View::render('public/404', [], '');
        }
        Analytics::track('page_view', '/promocoes/' . $promo['slug'], 'promotion', (int) $promo['id']);
        return View::render('public/promotions-show', [
            'page_title' => $promo['title'] . ' • Promoção Multi Cell',
            'page_desc'  => $promo['description'] ?? '',
            'promo'      => $promo,
            'others'     => array_slice(array_filter(Promotion::active(), fn ($p) => $p['id'] !== $promo['id']), 0, 3),
        ], 'public');
    }
}
