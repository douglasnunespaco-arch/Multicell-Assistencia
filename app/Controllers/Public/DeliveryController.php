<?php
namespace App\Controllers\Public;

use App\Core\View;
use App\Core\Analytics;
use App\Models\Setting;

/**
 * Delivery / Compre pelo WhatsApp — página editável via 5 settings + fallback.
 * Sem dependência de tabela nova; etapas "como funciona" ficam no view com defaults premium.
 */
final class DeliveryController
{
    public function index(): string
    {
        Analytics::track('page_view', '/delivery', null, null, 'delivery');
        return View::render('public/delivery', [
            'page_title' => 'Compre pelo WhatsApp · Delivery • Multi Cell',
            'page_desc'  => 'Escolha, confirme no WhatsApp e receba em casa. Atendimento rápido e humano.',
            'hero_title'     => Setting::get('delivery_hero_title',    'Compre sem sair de casa'),
            'hero_subtitle'  => Setting::get('delivery_hero_subtitle', 'Escolha no catálogo, finalize pelo WhatsApp e receba na porta de casa.'),
            'delivery_area'  => Setting::get('delivery_area',          'Várzea Grande e Cuiabá / MT'),
            'delivery_hours' => Setting::get('delivery_hours',         'Seg a Sáb · 9h às 18h'),
            'wa_message'     => Setting::get('delivery_whatsapp_message', 'Olá! Quero fazer um pedido com entrega.'),
        ], 'public');
    }
}
