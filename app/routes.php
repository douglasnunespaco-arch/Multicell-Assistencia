<?php
/**
 * Rotas da aplicação — Fase 2 (frontend público premium).
 *
 * @var \App\Core\Router $router
 */

// -------- Páginas públicas --------
$router->get('/',                             'Public\\HomeController@index');
$router->get('/assistencia-tecnica',          'Public\\ServicesController@index');
$router->get('/assistencia-tecnica/{slug}',   'Public\\ServicesController@show');
$router->get('/produtos',                     'Public\\ProductsController@index');
$router->get('/produtos/{slug}',              'Public\\ProductsController@show');
$router->get('/promocoes',                    'Public\\PromotionsController@index');
$router->get('/promocoes/{slug}',             'Public\\PromotionsController@show');
$router->get('/reservar',                     'Public\\ReservationController@create');
$router->get('/sobre',                        'Public\\AboutController@index');
$router->get('/contato',                      'Public\\ContactController@index');
$router->get('/links',                        'Public\\LinksController@index');

// -------- Admin (Sub-rodada 3A: Auth + Dashboard) --------
$router->get('/admin/login',                  'Admin\\AuthController@showLogin');
$router->post('/admin/login',                 'Admin\\AuthController@login');
$router->post('/admin/logout',                'Admin\\AuthController@logout');
$router->get('/admin',                        'Admin\\DashboardController@index');

// -------- Admin (Sub-rodada 3C: Leads / Reservas) --------
$router->get('/admin/leads',                  'Admin\\LeadsController@index');
$router->get('/admin/leads/{id}',             'Admin\\LeadsController@show');
$router->post('/admin/leads/{id}/status',     'Admin\\LeadsController@updateStatus');

// Redirecionamentos rastreados (CTA → canal externo)
$router->get('/go/whatsapp',                  'Public\\RedirectController@whatsapp');
$router->get('/go/phone',                     'Public\\RedirectController@phone');
$router->get('/go/map',                       'Public\\RedirectController@map');

// Handlers internos de erro
$router->get('/_errors/404', function () {
    http_response_code(404);
    return \App\Core\View::render('public/404', [], '');
});
$router->get('/_errors/500', function () {
    http_response_code(500);
    return \App\Core\View::render('public/500', [], '');
});

/*
 * Pendente para próximas fases:
 *
 * Fase 4 — Admin
 *   $router->get('/admin/login', ...); ... demais rotas /admin/*
 *
 * Fase 5 — Analytics endpoints
 *   $router->get('/go/whatsapp',                'Public\\RedirectController@whatsapp');
 *   $router->get('/go/phone',                   'Public\\RedirectController@phone');
 *   $router->get('/go/map',                     'Public\\RedirectController@map');
 *   $router->post('/api/track',                 'Public\\TrackController@store');
 *
 * Fase 6 — SEO
 *   $router->get('/sitemap.xml',                'Public\\HomeController@sitemap');
 */
