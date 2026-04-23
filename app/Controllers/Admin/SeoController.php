<?php
namespace App\Controllers\Admin;

use App\Core\View;
use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Flash;
use App\Models\Setting;

/**
 * SeoController — SEO avançado + tracking.
 * Persiste em `settings` key-value. Complementa os já existentes (seo_title, seo_description, site_og_path).
 */
final class SeoController
{
    private const KEYS = [
        'seo_title',            // já existe · reforçado aqui
        'seo_description',      // já existe · reforçado aqui
        'seo_canonical_url',    // URL base canônica do site
        'seo_keywords',          // keywords opcional
        'seo_twitter_card',     // summary | summary_large_image
        'seo_twitter_handle',   // @multicell
        'tracking_ga4_id',      // G-XXXXXXXX
        'tracking_meta_pixel_id', // 1234567890
    ];

    public function index(): string
    {
        Auth::requireLogin();
        return View::render('admin/seo/index', [
            'page_title' => 'SEO e Social • Admin',
            'values'     => Setting::all(true),
            'keys'       => self::KEYS,
        ], 'admin');
    }

    public function update(): string
    {
        Auth::requireLogin(); Csrf::verifyOrFail();
        try {
            foreach (self::KEYS as $k) {
                if (!array_key_exists($k, $_POST)) continue;
                $v = trim((string) $_POST[$k]);
                if ($k === 'seo_twitter_card' && $v !== '' && !in_array($v, ['summary','summary_large_image'], true)) {
                    $v = 'summary_large_image';
                }
                Setting::set($k, $v);
            }
            Flash::success('SEO atualizado.');
        } catch (\Throwable $e) { Flash::error($e->getMessage()); }
        header('Location: /admin/seo'); exit;
    }
}
