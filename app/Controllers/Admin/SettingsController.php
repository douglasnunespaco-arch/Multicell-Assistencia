<?php
namespace App\Controllers\Admin;

use App\Core\View;
use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Flash;
use App\Core\Upload;
use App\Models\Setting;

/**
 * SettingsController — configurações gerais + branding (logo/favicon/og).
 * 1 página, 1 form. Campos persistidos key-value em `settings`.
 */
final class SettingsController
{
    /** @var string[] Campos texto suportados no form. */
    private const TEXT_KEYS = [
        'site_name','site_tagline','site_description',
        'contact_phone_whatsapp','contact_phone_display','contact_email',
        'contact_address','contact_city','contact_state','contact_zip',
        'business_hours','google_maps_url',
        'instagram_url','facebook_url','tiktok_url','youtube_url',
        'links_rating_label','links_social_proof',
    ];
    /** @var string[] Campos de imagem (upload) suportados. */
    private const IMAGE_KEYS = ['site_logo_path','site_favicon_path','site_og_path'];

    public function index(): string
    {
        Auth::requireLogin();
        return View::render('admin/settings/index', [
            'page_title' => 'Configurações • Admin',
            'values'     => Setting::all(true),
            'text_keys'  => self::TEXT_KEYS,
            'image_keys' => self::IMAGE_KEYS,
        ], 'admin');
    }

    public function update(): string
    {
        Auth::requireLogin(); Csrf::verifyOrFail();

        try {
            foreach (self::TEXT_KEYS as $k) {
                if (array_key_exists($k, $_POST)) {
                    Setting::set($k, trim((string) $_POST[$k]));
                }
            }
            foreach (self::IMAGE_KEYS as $k) {
                $current = Setting::get($k);
                $subdir  = 'branding';
                $newPath = Upload::save($_FILES[$k] ?? [], $subdir);
                if ($newPath) {
                    Upload::delete($current);
                    Setting::set($k, $newPath);
                } elseif (!empty($_POST[$k . '_remove'])) {
                    Upload::delete($current);
                    Setting::set($k, null);
                }
            }
            Flash::success('Configurações salvas.');
        } catch (\Throwable $e) {
            Flash::error($e->getMessage());
        }
        header('Location: /admin/settings'); exit;
    }
}
