<?php
namespace App\Core;

/**
 * View — renderização de templates PHP com layout master.
 */
final class View
{
    /** Renderiza uma view pública com layout padrão. */
    public static function render(string $view, array $data = [], string $layout = 'public'): string
    {
        $content = self::capture($view, $data);
        if ($layout === '') {
            return $content;
        }
        $layoutFile = APP_DIR . '/Views/layouts/' . $layout . '.php';
        if (!is_file($layoutFile)) {
            return $content;
        }
        return self::captureFile($layoutFile, array_merge($data, ['content' => $content]));
    }

    /** Captura uma view específica. */
    public static function capture(string $view, array $data = []): string
    {
        $file = APP_DIR . '/Views/' . $view . '.php';
        if (!is_file($file)) {
            return '';
        }
        return self::captureFile($file, $data);
    }

    private static function captureFile(string $file, array $data): string
    {
        extract($data, EXTR_SKIP);
        ob_start();
        require $file;
        return (string) ob_get_clean();
    }

    /** Escapa para HTML. */
    public static function e($v): string
    {
        return htmlspecialchars((string) ($v ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}
