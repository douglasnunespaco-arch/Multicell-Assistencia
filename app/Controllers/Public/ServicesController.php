<?php
namespace App\Controllers\Public;

use App\Core\View;
use App\Core\Analytics;
use App\Models\Service;

final class ServicesController
{
    public function index(): string
    {
        Analytics::track('page_view', '/assistencia-tecnica');
        return View::render('public/services-index', [
            'page_title' => 'Assistência Técnica • Multi Cell',
            'page_desc'  => 'Serviços especializados para celulares: troca de tela, bateria, placa e mais. Garantia real.',
            'services'   => Service::active(),
        ], 'public');
    }

    public function show(array $params): string
    {
        $service = Service::findBySlug($params['slug'] ?? '');
        if (!$service) {
            http_response_code(404);
            return View::render('public/404', [], '');
        }
        Analytics::track('page_view', '/assistencia-tecnica/' . $service['slug'], 'service', (int) $service['id']);
        return View::render('public/services-show', [
            'page_title' => $service['name'] . ' • Multi Cell',
            'page_desc'  => $service['short_description'] ?? '',
            'service'    => $service,
            'related'    => array_slice(array_filter(Service::active(), fn ($s) => $s['id'] !== $service['id']), 0, 3),
        ], 'public');
    }
}
