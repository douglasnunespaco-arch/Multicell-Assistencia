<?php
/**
 * Biblioteca central de ícones SVG stroke-based.
 * Uso: echo icon('check') ou icon('phone', 24).
 */
if (!function_exists('icon')) {
    function icon(string $name, int $size = 20, string $extraClass = ''): string {
        $paths = [
            'check'    => '<polyline points="20 6 9 17 4 12"/>',
            'shield'   => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>',
            'bolt'     => '<polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>',
            'phone'    => '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.8 19.8 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13 1.05.37 2.08.72 3.06a2 2 0 0 1-.45 2.11L8.09 10.2a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.98.35 2.01.59 3.06.72A2 2 0 0 1 22 16.92z"/>',
            'pin'      => '<path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>',
            'clock'    => '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>',
            'wrench'   => '<path d="M14.7 6.3a4 4 0 0 0-5.4 5.4L3 18l3 3 6.3-6.3a4 4 0 0 0 5.4-5.4l-3 3-2-2 3-3z"/>',
            'battery'  => '<rect x="2" y="8" width="16" height="8" rx="2" ry="2"/><line x1="22" y1="11" x2="22" y2="13"/>',
            'screen'   => '<rect x="6" y="2" width="12" height="20" rx="2" ry="2"/><line x1="10" y1="18" x2="14" y2="18"/>',
            'chip'     => '<rect x="5" y="5" width="14" height="14" rx="2"/><rect x="9" y="9" width="6" height="6"/><line x1="9" y1="1" x2="9" y2="5"/><line x1="15" y1="1" x2="15" y2="5"/><line x1="9" y1="19" x2="9" y2="23"/><line x1="15" y1="19" x2="15" y2="23"/><line x1="1" y1="9" x2="5" y2="9"/><line x1="1" y1="15" x2="5" y2="15"/><line x1="19" y1="9" x2="23" y2="9"/><line x1="19" y1="15" x2="23" y2="15"/>',
            'plug'     => '<path d="M9 2v6M15 2v6M6 11v3a6 6 0 0 0 12 0v-3H6z"/><path d="M12 20v2"/>',
            'code'     => '<polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/>',
            'sparkle'  => '<path d="M12 2v6M12 16v6M2 12h6M16 12h6M5 5l4 4M15 15l4 4M5 19l4-4M15 9l4-4"/>',
            'star'     => '<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>',
            'tag'      => '<path d="M20.59 13.41 11 3.83V3H3v8h0.83l9.58 9.58a2 2 0 0 0 2.83 0l4.35-4.35a2 2 0 0 0 0-2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/>',
            'image'    => '<rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/>',
            'arrow-right' => '<line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>',
            'instagram' => '<rect x="3" y="3" width="18" height="18" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/>',
            'award'    => '<circle cx="12" cy="8" r="7"/><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"/>',
            'users'    => '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
            'heart'    => '<path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>',
        ];
        $body = $paths[$name] ?? $paths['sparkle'];
        // Tag preenchida precisa fill=currentColor
        $isFilled = in_array($name, ['star'], true);
        $fill = $isFilled ? 'currentColor' : 'none';
        $class = 'icon' . ($extraClass ? ' ' . $extraClass : '');
        return '<svg class="' . $class . '" width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="' . $fill . '" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $body . '</svg>';
    }
}
if (!function_exists('service_icon')) {
    /** Mapeia slug ou nome de ícone salvo ao nome da lib. */
    function service_icon(?string $v, int $size = 24): string {
        $map = ['screen'=>'screen','battery'=>'battery','plug'=>'plug','chip'=>'chip','code'=>'code','sparkle'=>'sparkle','wrench'=>'wrench'];
        return icon($map[$v] ?? 'wrench', $size);
    }
}
