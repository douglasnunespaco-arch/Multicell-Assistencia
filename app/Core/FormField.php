<?php
namespace App\Core;

/**
 * FormField — render consistente de campos de form no admin.
 * Mantém markup mínimo e herda CSS de .admin-* existente.
 */
final class FormField
{
    public static function text(string $name, string $label, $value = '', array $opts = []): string
    {
        return self::wrap($name, $label, self::input('text', $name, $value, $opts), $opts);
    }
    public static function number(string $name, string $label, $value = '', array $opts = []): string
    {
        return self::wrap($name, $label, self::input('number', $name, $value, $opts + ['step' => 'any']), $opts);
    }
    public static function date(string $name, string $label, $value = '', array $opts = []): string
    {
        return self::wrap($name, $label, self::input('date', $name, $value, $opts), $opts);
    }
    public static function url(string $name, string $label, $value = '', array $opts = []): string
    {
        return self::wrap($name, $label, self::input('url', $name, $value, $opts), $opts);
    }

    public static function textarea(string $name, string $label, $value = '', array $opts = []): string
    {
        $rows = $opts['rows'] ?? 4;
        $ph   = isset($opts['placeholder']) ? ' placeholder="' . e((string) $opts['placeholder']) . '"' : '';
        $html = '<textarea class="admin-input" id="f_' . e($name) . '" name="' . e($name) . '" rows="' . (int) $rows . '"' . $ph . '>' . e((string) $value) . '</textarea>';
        return self::wrap($name, $label, $html, $opts);
    }

    public static function select(string $name, string $label, array $options, $value = '', array $opts = []): string
    {
        $html = '<select class="admin-input" id="f_' . e($name) . '" name="' . e($name) . '">';
        if (!empty($opts['empty_label'])) {
            $html .= '<option value="">' . e((string) $opts['empty_label']) . '</option>';
        }
        foreach ($options as $val => $lab) {
            $sel = ((string) $val === (string) $value) ? ' selected' : '';
            $html .= '<option value="' . e((string) $val) . '"' . $sel . '>' . e((string) $lab) . '</option>';
        }
        $html .= '</select>';
        return self::wrap($name, $label, $html, $opts);
    }

    public static function checkbox(string $name, string $label, $value = 0, array $opts = []): string
    {
        $checked = !empty($value) ? ' checked' : '';
        $html = '<label class="admin-checkbox"><input type="checkbox" id="f_' . e($name) . '" name="' . e($name) . '" value="1"' . $checked . '><span>' . e($label) . '</span></label>';
        $hint = !empty($opts['hint']) ? '<small class="admin-field__hint">' . e($opts['hint']) . '</small>' : '';
        return '<div class="admin-field admin-field--checkbox">' . $html . $hint . '</div>';
    }

    public static function file(string $name, string $label, ?string $current = null, array $opts = []): string
    {
        $accept = $opts['accept'] ?? 'image/*';
        $html = '<input type="file" class="admin-input admin-input--file" id="f_' . e($name) . '" name="' . e($name) . '" accept="' . e($accept) . '" data-file-preview>';
        if ($current) {
            $url = '/' . ltrim($current, '/');
            $html .= '<div class="admin-field__current"><img src="' . e($url) . '" alt="" loading="lazy" data-file-preview-current><label class="admin-checkbox admin-checkbox--sm"><input type="checkbox" name="' . e($name) . '_remove" value="1"><span>Remover imagem atual</span></label></div>';
        }
        return self::wrap($name, $label, $html, $opts);
    }

    private static function input(string $type, string $name, $value, array $opts): string
    {
        $ph   = isset($opts['placeholder']) ? ' placeholder="' . e((string) $opts['placeholder']) . '"' : '';
        $step = isset($opts['step']) ? ' step="' . e((string) $opts['step']) . '"' : '';
        $min  = isset($opts['min'])  ? ' min="' . e((string) $opts['min']) . '"'   : '';
        $max  = isset($opts['max'])  ? ' max="' . e((string) $opts['max']) . '"'   : '';
        $req  = !empty($opts['required']) ? ' required' : '';
        return '<input type="' . $type . '" class="admin-input" id="f_' . e($name) . '" name="' . e($name) . '" value="' . e((string) $value) . '"' . $ph . $step . $min . $max . $req . '>';
    }

    private static function wrap(string $name, string $label, string $control, array $opts): string
    {
        $req  = !empty($opts['required']) ? ' <span class="admin-field__req">*</span>' : '';
        $hint = !empty($opts['hint']) ? '<small class="admin-field__hint">' . e($opts['hint']) . '</small>' : '';
        return '<div class="admin-field"><label for="f_' . e($name) . '">' . e($label) . $req . '</label>' . $control . $hint . '</div>';
    }
}
