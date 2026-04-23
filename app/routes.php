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
$router->get('/acessorios',                   'Public\\AcessoriosController@index');
$router->get('/seminovos',                    'Public\\SeminovosController@index');
$router->get('/delivery',                     'Public\\DeliveryController@index');
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

// -------- Admin (B2 reduzida · CRUDs de conteúdo) --------
// Slides
$router->get ('/admin/slides',                      'Admin\\SlidesController@index');
$router->get ('/admin/slides/new',                  'Admin\\SlidesController@create');
$router->post('/admin/slides/store',                'Admin\\SlidesController@store');
$router->get ('/admin/slides/{id}/edit',            'Admin\\SlidesController@edit');
$router->post('/admin/slides/{id}/update',          'Admin\\SlidesController@update');
$router->post('/admin/slides/{id}/delete',          'Admin\\SlidesController@delete');
// Services
$router->get ('/admin/services',                    'Admin\\ServicesController@index');
$router->get ('/admin/services/new',                'Admin\\ServicesController@create');
$router->post('/admin/services/store',              'Admin\\ServicesController@store');
$router->get ('/admin/services/{id}/edit',          'Admin\\ServicesController@edit');
$router->post('/admin/services/{id}/update',        'Admin\\ServicesController@update');
$router->post('/admin/services/{id}/delete',        'Admin\\ServicesController@delete');
// Products
$router->get ('/admin/products',                    'Admin\\ProductsController@index');
$router->get ('/admin/products/new',                'Admin\\ProductsController@create');
$router->post('/admin/products/store',              'Admin\\ProductsController@store');
$router->get ('/admin/products/{id}/edit',          'Admin\\ProductsController@edit');
$router->post('/admin/products/{id}/update',        'Admin\\ProductsController@update');
$router->post('/admin/products/{id}/delete',        'Admin\\ProductsController@delete');
// Promotions
$router->get ('/admin/promotions',                  'Admin\\PromotionsController@index');
$router->get ('/admin/promotions/new',              'Admin\\PromotionsController@create');
$router->post('/admin/promotions/store',            'Admin\\PromotionsController@store');
$router->get ('/admin/promotions/{id}/edit',        'Admin\\PromotionsController@edit');
$router->post('/admin/promotions/{id}/update',      'Admin\\PromotionsController@update');
$router->post('/admin/promotions/{id}/delete',      'Admin\\PromotionsController@delete');
// Testimonials
$router->get ('/admin/testimonials',                'Admin\\TestimonialsController@index');
$router->get ('/admin/testimonials/new',            'Admin\\TestimonialsController@create');
$router->post('/admin/testimonials/store',          'Admin\\TestimonialsController@store');
$router->get ('/admin/testimonials/{id}/edit',      'Admin\\TestimonialsController@edit');
$router->post('/admin/testimonials/{id}/update',    'Admin\\TestimonialsController@update');
$router->post('/admin/testimonials/{id}/delete',    'Admin\\TestimonialsController@delete');
// Settings
$router->get ('/admin/settings',                    'Admin\\SettingsController@index');
$router->post('/admin/settings/update',             'Admin\\SettingsController@update');

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

// -------- Admin (Fase C1 · Sobre / Links / Unidades) --------
$router->get ('/admin/about',                       'Admin\\AboutController@index');
$router->get ('/admin/about/new',                   'Admin\\AboutController@create');
$router->post('/admin/about/store',                 'Admin\\AboutController@store');
$router->get ('/admin/about/{id}/edit',             'Admin\\AboutController@edit');
$router->post('/admin/about/{id}/update',           'Admin\\AboutController@update');
$router->post('/admin/about/{id}/delete',           'Admin\\AboutController@delete');

$router->get ('/admin/links',                       'Admin\\LinksController@index');
$router->get ('/admin/links/new',                   'Admin\\LinksController@create');
$router->post('/admin/links/store',                 'Admin\\LinksController@store');
$router->get ('/admin/links/{id}/edit',             'Admin\\LinksController@edit');
$router->post('/admin/links/{id}/update',           'Admin\\LinksController@update');
$router->post('/admin/links/{id}/delete',           'Admin\\LinksController@delete');

$router->get ('/admin/units',                       'Admin\\UnitsController@index');
$router->get ('/admin/units/new',                   'Admin\\UnitsController@create');
$router->post('/admin/units/store',                 'Admin\\UnitsController@store');
$router->get ('/admin/units/{id}/edit',             'Admin\\UnitsController@edit');
$router->post('/admin/units/{id}/update',           'Admin\\UnitsController@update');
$router->post('/admin/units/{id}/delete',           'Admin\\UnitsController@delete');

// -------- Admin (Fase C2 · Tema / SEO / Seções) --------
$router->get ('/admin/theme',                       'Admin\\ThemeController@index');
$router->post('/admin/theme/update',                'Admin\\ThemeController@update');

$router->get ('/admin/seo',                         'Admin\\SeoController@index');
$router->post('/admin/seo/update',                  'Admin\\SeoController@update');

$router->get ('/admin/sections',                    'Admin\\SectionsController@index');
$router->post('/admin/sections/update',             'Admin\\SectionsController@update');

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
