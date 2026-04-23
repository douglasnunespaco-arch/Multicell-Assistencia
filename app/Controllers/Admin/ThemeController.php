<?php
namespace App\Controllers\Admin;

use App\Core\View;
use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Flash;
use App\Models\Setting;

/**
 * ThemeController — cor primária, tema padrão, ajustes de identidade leve.
 * Persiste em `settings` (key-value). Zero schema.
 */
final class ThemeController
{
    private const KEYS = [
        'brand_color',        // cor primária (hex) — ex: #14F195
        'brand_color_ink',    // cor do texto sobre o brand
        'default_theme',      // 'dark' ou 'light'
        'display_font',       // Sora / Manrope / Space Grotesk / etc (referencial)
    ];

    public function index(): string
    {
        Auth::requireLogin();
        return View::render('admin/theme/index', [
            'page_title' => 'Tema e Identidade • Admin',
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
                if ($k === 'default_theme' && !in_array($v, ['dark','light'], true)) $v = 'dark';
                if (($k === 'brand_color' || $k === 'brand_color_ink') && $v !== '' && !preg_match('/^#[0-9a-fA-F]{6}$/', $v)) {
                    throw new \RuntimeException("Cor inválida em {$k}: use formato #RRGGBB");
                }
                Setting::set($k, $v);
            }
            Flash::success('Tema atualizado.');
        } catch (\Throwable $e) { Flash::error($e->getMessage()); }
        header('Location: /admin/theme'); exit;
    }
}
