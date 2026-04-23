<?php
namespace App\Controllers\Admin;

use App\Core\View;
use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Flash;
use App\Models\Setting;

/**
 * SectionsController — toggles on/off de blocos da home.
 * Persiste flags "1"/"0" em settings key-value. Zero schema.
 * A home consome via Setting::get('section_xxx_visible', '1') === '1'.
 */
final class SectionsController
{
    private const SECTIONS = [
        'section_services_visible'     => 'Serviços',
        'section_products_visible'     => 'Produtos',
        'section_promotions_visible'   => 'Promoções',
        'section_testimonials_visible' => 'Depoimentos',
        'section_about_visible'        => 'Sobre (trecho na home)',
        'section_brands_visible'       => 'Faixa de marcas',
        'section_diffs_visible'        => 'Diferenciais',
    ];

    public function index(): string
    {
        Auth::requireLogin();
        return View::render('admin/sections/index', [
            'page_title' => 'Seções da home • Admin',
            'values'     => Setting::all(true),
            'sections'   => self::SECTIONS,
        ], 'admin');
    }

    public function update(): string
    {
        Auth::requireLogin(); Csrf::verifyOrFail();
        try {
            foreach (array_keys(self::SECTIONS) as $k) {
                Setting::set($k, !empty($_POST[$k]) ? '1' : '0');
            }
            Flash::success('Seções atualizadas.');
        } catch (\Throwable $e) { Flash::error($e->getMessage()); }
        header('Location: /admin/sections'); exit;
    }
}
