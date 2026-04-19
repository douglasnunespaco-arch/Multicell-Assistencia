<?php
/**
 * Rotas da aplicação.
 * Nas fases seguintes esta tabela se expande para páginas públicas e admin.
 *
 * @var \App\Core\Router $router  (disponível no escopo do require em index.php)
 */

// Página inicial — stub Fase 1 (placeholder até Fase 2 construir a Home premium)
$router->get('/', 'Public\\HomeController@index');

// Handlers internos de erro (invocados via rewrite do .htaccess)
$router->get('/_errors/404', function () {
    http_response_code(404);
    return \App\Core\View::render('public/404', [], '');
});
$router->get('/_errors/500', function () {
    http_response_code(500);
    return \App\Core\View::render('public/500', [], '');
});

/*
 * Rotas a serem adicionadas nas próximas fases:
 *
 * Fase 2 — Frontend público
 *   $router->get('/assistencia-tecnica',        'Public\\ServicesController@index');
 *   $router->get('/assistencia-tecnica/{slug}', 'Public\\ServicesController@show');
 *   $router->get('/produtos',                   'Public\\ProductsController@index');
 *   $router->get('/produtos/{slug}',            'Public\\ProductsController@show');
 *   $router->get('/promocoes',                  'Public\\PromotionsController@index');
 *   $router->get('/promocoes/{slug}',           'Public\\PromotionsController@show');
 *   $router->get('/sobre',                      'Public\\AboutController@index');
 *   $router->get('/contato',                    'Public\\ContactController@index');
 *   $router->get('/links',                      'Public\\LinksController@index');
 *
 * Fase 3 — Reserva
 *   $router->get('/reservar',                   'Public\\ReservationController@create');
 *   $router->post('/reservar',                  'Public\\ReservationController@store');
 *   $router->get('/reservar/sucesso',           'Public\\ReservationController@success');
 *
 * Fase 4 — Admin
 *   $router->get('/admin/login',                'Admin\\AuthController@showLogin');
 *   $router->post('/admin/login',               'Admin\\AuthController@login');
 *   $router->get('/admin/logout',               'Admin\\AuthController@logout');
 *   $router->get('/admin',                      'Admin\\DashboardController@index');
 *   ... (Hero, Services, Products, Promotions, Testimonials, About, Branch, Settings, Leads, Analytics)
 *
 * Fase 5 — Analytics
 *   $router->get('/go/whatsapp',                'Public\\RedirectController@whatsapp');
 *   $router->get('/go/phone',                   'Public\\RedirectController@phone');
 *   $router->get('/go/map',                     'Public\\RedirectController@map');
 *   $router->post('/api/track',                 'Public\\TrackController@store');
 *
 * Fase 6 — SEO
 *   $router->get('/sitemap.xml',                'Public\\HomeController@sitemap');
 */
