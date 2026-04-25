<?php
/**
 * Multi Cell · Biblioteca central de ícones + helpers de imagem
 * -----------------------------------------------------------------
 * Uma única família visual (Lucide-style · stroke 2px · linecap round)
 * para todo o site público. Sem dependências JS/React. SVG inline.
 *
 * Exceção controlada: ícones de marca (WhatsApp, Instagram, Facebook,
 * TikTok) usam paths oficiais simplificados, renderizados em filled
 * para ficarem reconhecíveis — mas no mesmo container/tamanho dos demais.
 */

if (!function_exists('icon')) {
    function icon(string $name, int $size = 20, string $extraClass = ''): string
    {
        // ---------- Ícones Lucide-style (stroke) ----------
        $strokePaths = [
            // Essenciais
            'check'      => '<polyline points="20 6 9 17 4 12"/>',
            'check-circle' => '<circle cx="12" cy="12" r="10"/><polyline points="9 12 11 14 15 10"/>',
            'shield'     => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>',
            'shield-check' => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/>',
            'bolt'       => '<polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>',
            'zap'        => '<polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>',
            // Navegação / UI
            'menu'       => '<line x1="4" y1="7" x2="20" y2="7"/><line x1="4" y1="12" x2="20" y2="12"/><line x1="4" y1="17" x2="20" y2="17"/>',
            'close'      => '<line x1="6" y1="6" x2="18" y2="18"/><line x1="6" y1="18" x2="18" y2="6"/>',
            'chevron-left'  => '<polyline points="15 18 9 12 15 6"/>',
            'chevron-right' => '<polyline points="9 18 15 12 9 6"/>',
            'arrow-right' => '<line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>',
            'sun'        => '<circle cx="12" cy="12" r="4"/><line x1="12" y1="2" x2="12" y2="4"/><line x1="12" y1="20" x2="12" y2="22"/><line x1="4.93" y1="4.93" x2="6.34" y2="6.34"/><line x1="17.66" y1="17.66" x2="19.07" y2="19.07"/><line x1="2" y1="12" x2="4" y2="12"/><line x1="20" y1="12" x2="22" y2="12"/><line x1="4.93" y1="19.07" x2="6.34" y2="17.66"/><line x1="17.66" y1="6.34" x2="19.07" y2="4.93"/>',
            'moon'       => '<path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>',
            // Contato / localização
            'phone'      => '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.8 19.8 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13 1.05.37 2.08.72 3.06a2 2 0 0 1-.45 2.11L8.09 10.2a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.98.35 2.01.59 3.06.72A2 2 0 0 1 22 16.92z"/>',
            'phone-call' => '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.8 19.8 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13 1.05.37 2.08.72 3.06a2 2 0 0 1-.45 2.11L8.09 10.2a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.98.35 2.01.59 3.06.72A2 2 0 0 1 22 16.92z"/>',
            'mail'       => '<rect x="2" y="4" width="20" height="16" rx="2"/><polyline points="22 6 12 13 2 6"/>',
            'pin'        => '<path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>',
            'map'        => '<polygon points="1 6 8 3 16 6 23 3 23 18 16 21 8 18 1 21 1 6"/><line x1="8" y1="3" x2="8" y2="18"/><line x1="16" y1="6" x2="16" y2="21"/>',
            'clock'      => '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>',
            'calendar'   => '<rect x="3" y="5" width="18" height="16" rx="2"/><line x1="16" y1="3" x2="16" y2="7"/><line x1="8" y1="3" x2="8" y2="7"/><line x1="3" y1="10" x2="21" y2="10"/>',
            'globe'      => '<circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>',
            // Assistência técnica (temático)
            'wrench'     => '<path d="M14.7 6.3a4 4 0 0 0-5.4 5.4L3 18l3 3 6.3-6.3a4 4 0 0 0 5.4-5.4l-3 3-2-2 3-3z"/>',
            'tools'      => '<path d="M14.7 6.3a4 4 0 0 0-5.4 5.4L3 18l3 3 6.3-6.3a4 4 0 0 0 5.4-5.4l-3 3-2-2 3-3z"/>',
            'battery'    => '<rect x="2" y="7" width="16" height="10" rx="2"/><line x1="22" y1="11" x2="22" y2="13"/><line x1="6" y1="11" x2="6" y2="13"/><line x1="10" y1="11" x2="10" y2="13"/><line x1="14" y1="11" x2="14" y2="13"/>',
            'smartphone' => '<rect x="6" y="2" width="12" height="20" rx="2.5"/><line x1="11" y1="18" x2="13" y2="18"/>',
            'screen'     => '<rect x="6" y="2" width="12" height="20" rx="2.5"/><line x1="11" y1="18" x2="13" y2="18"/>',
            'chip'       => '<rect x="5" y="5" width="14" height="14" rx="2"/><rect x="9" y="9" width="6" height="6"/><line x1="9" y1="1" x2="9" y2="5"/><line x1="15" y1="1" x2="15" y2="5"/><line x1="9" y1="19" x2="9" y2="23"/><line x1="15" y1="19" x2="15" y2="23"/><line x1="1" y1="9" x2="5" y2="9"/><line x1="1" y1="15" x2="5" y2="15"/><line x1="19" y1="9" x2="23" y2="9"/><line x1="19" y1="15" x2="23" y2="15"/>',
            'cpu'        => '<rect x="5" y="5" width="14" height="14" rx="2"/><rect x="9" y="9" width="6" height="6"/><line x1="9" y1="1" x2="9" y2="5"/><line x1="15" y1="1" x2="15" y2="5"/><line x1="9" y1="19" x2="9" y2="23"/><line x1="15" y1="19" x2="15" y2="23"/><line x1="1" y1="9" x2="5" y2="9"/><line x1="1" y1="15" x2="5" y2="15"/><line x1="19" y1="9" x2="23" y2="9"/><line x1="19" y1="15" x2="23" y2="15"/>',
            'plug'       => '<path d="M12 22v-5"/><path d="M9 7V2"/><path d="M15 7V2"/><path d="M6 13V8a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v5a4 4 0 0 1-4 4h-4a4 4 0 0 1-4-4Z"/>',
            'code'       => '<polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/>',
            'terminal'   => '<polyline points="4 17 10 11 4 5"/><line x1="12" y1="19" x2="20" y2="19"/>',
            'sparkle'    => '<path d="M12 2 14 8 20 10 14 12 12 18 10 12 4 10 10 8Z"/><path d="M20 16 L21 18 L23 19 L21 20 L20 22 L19 20 L17 19 L19 18 Z"/>',
            'headphones' => '<path d="M3 18v-6a9 9 0 0 1 18 0v6"/><path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3zM3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"/>',
            'package'    => '<line x1="16.5" y1="9.4" x2="7.5" y2="4.21"/><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/>',
            'cable'      => '<path d="M4 9a2 2 0 0 1 2-2h4v4H6a2 2 0 0 1-2-2Z"/><path d="M14 13h4a2 2 0 0 1 2 2v0a2 2 0 0 1-2 2h-4Z"/><line x1="10" y1="7" x2="10" y2="11"/><line x1="14" y1="13" x2="14" y2="17"/><path d="M10 9h4"/>',
            // Atributos
            'star'       => '<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>',
            'tag'        => '<path d="M20.59 13.41 11 3.83V3H3v8h0.83l9.58 9.58a2 2 0 0 0 2.83 0l4.35-4.35a2 2 0 0 0 0-2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/>',
            'image'      => '<rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/>',
            'award'      => '<circle cx="12" cy="8" r="7"/><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"/>',
            'trophy'     => '<path d="M8 21h8M12 17v4M7 4h10v4a5 5 0 0 1-10 0V4z"/><path d="M17 4h3v3a3 3 0 0 1-3 3M7 4H4v3a3 3 0 0 0 3 3"/>',
            'trophy-solid' => '<path d="M6 2.75A.75.75 0 0 1 6.75 2h10.5a.75.75 0 0 1 .75.75V4h2.25c.69 0 1.25.56 1.25 1.25V7.5c0 2.45-1.76 4.49-4.08 4.92a6.01 6.01 0 0 1-3.42 3.41V18h2a2 2 0 0 1 2 2v1.25a.75.75 0 0 1-.75.75H6.75a.75.75 0 0 1-.75-.75V20a2 2 0 0 1 2-2h2v-2.17a6.01 6.01 0 0 1-3.42-3.41C4.26 11.99 2.5 9.95 2.5 7.5V5.25C2.5 4.56 3.06 4 3.75 4H6V2.75ZM6 5.5H4v2c0 1.4.91 2.59 2.17 3.02A6.04 6.04 0 0 1 6 9V5.5Zm14 0h-2V9c0 .52-.06 1.03-.17 1.52A3.18 3.18 0 0 0 20 7.5v-2Z" fill="currentColor"/><path d="M9.25 6.5a.75.75 0 0 1 .75.75c0 .55.32 1.05.84 1.32a.75.75 0 1 1-.68 1.34A2.94 2.94 0 0 1 8.5 7.25a.75.75 0 0 1 .75-.75Z" fill="rgba(255,255,255,.45)"/>',
            'crown' => '<path d="M3 18h18l-1.5-9-4.5 4.5L12 7l-3 6.5L4.5 9 3 18Z"/><path d="M3 21h18"/>',
            'sparkle-solid' => '<path d="M12 2 13.6 8.4 20 10l-6.4 1.6L12 18l-1.6-6.4L4 10l6.4-1.6L12 2Zm7 12 .8 3.2 3.2.8-3.2.8L19 22l-.8-3.2L15 18l3.2-.8.8-3.2Z" fill="currentColor"/>',
            'users'      => '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
            'user'       => '<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>',
            'heart'      => '<path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>',
            'truck'      => '<rect x="1" y="5" width="15" height="11" rx="1"/><polygon points="16 10 20 10 23 13 23 16 16 16 16 10"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>',
            'message'    => '<path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>',
            'search'     => '<circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>',
            'info'       => '<circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/>',
        ];

        // ---------- Ícones de marca (filled, paths oficiais simplificados) ----------
        $brandPaths = [
            'whatsapp' => '<path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/>',
            'instagram' => '<path d="M12 2.163c3.204 0 3.584.012 4.849.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.849.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 1 0 0 12.324 6.162 6.162 0 0 0 0-12.324zM12 16a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm6.406-11.845a1.44 1.44 0 1 0 0 2.881 1.44 1.44 0 0 0 0-2.881z"/>',
            'facebook' => '<path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>',
            'tiktok' => '<path d="M19.321 5.562a5.122 5.122 0 0 1-3.108-4.03 5.12 5.12 0 0 1-.08-.96V.5h-3.324v13.235a3.07 3.07 0 0 1-3.07 3.07 3.07 3.07 0 0 1-3.069-3.07 3.07 3.07 0 0 1 3.07-3.07c.31 0 .612.046.896.132V7.422a6.438 6.438 0 0 0-.896-.062 6.395 6.395 0 0 0-6.395 6.395A6.395 6.395 0 0 0 9.74 20.15a6.395 6.395 0 0 0 6.394-6.395V7.152a8.442 8.442 0 0 0 4.924 1.577V5.405a5.11 5.11 0 0 1-1.737.157Z"/>',
        ];

        $class = 'icon icon--' . $name . ($extraClass ? ' ' . $extraClass : '');

        // Marca → filled
        if (isset($brandPaths[$name])) {
            return '<svg class="' . $class . '" width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">' . $brandPaths[$name] . '</svg>';
        }

        // Lucide-style → stroke
        $isFilled = in_array($name, ['star', 'trophy-solid', 'sparkle-solid'], true);
        $body = $strokePaths[$name] ?? $strokePaths['sparkle'];
        if ($isFilled) {
            return '<svg class="' . $class . '" width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="currentColor" stroke="none" aria-hidden="true">' . $body . '</svg>';
        }
        return '<svg class="' . $class . '" width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $body . '</svg>';
    }
}

if (!function_exists('service_icon')) {
    /** Mapeia slug ou nome de ícone salvo ao nome da lib. */
    function service_icon(?string $v, int $size = 24): string
    {
        $map = [
            'screen' => 'smartphone', 'smartphone' => 'smartphone',
            'battery' => 'battery',
            'plug' => 'plug',
            'chip' => 'cpu', 'cpu' => 'cpu',
            'code' => 'code', 'terminal' => 'terminal',
            'sparkle' => 'sparkle',
            'wrench' => 'wrench', 'tools' => 'wrench',
        ];
        return icon($map[$v] ?? 'wrench', $size);
    }
}

/* =============================================================================
 *  HELPERS DE IMAGEM PREMIUM (stock coerente por contexto)
 *  -----------------------------------------------------------------------------
 *  Política:
 *   · Usa Unsplash como CDN de imagens premium coerentes com assistência
 *     técnica / celulares / acessórios (não scraping de Instagram).
 *   · Tudo com fallback gracioso para o gradiente+ícone já definido no CSS.
 *   · Todas as URLs são nomeadas e organizadas para o admin substituir por
 *     fotos reais da loja depois, via upload (image_path do banco).
 * ========================================================================== */

if (!function_exists('mc_img')) {
    function mc_img(string $photoId, int $w = 1200, int $q = 80): string
    {
        // URL estável do Unsplash CDN
        return 'https://images.unsplash.com/photo-' . $photoId
            . '?auto=format&fit=crop&w=' . $w . '&q=' . $q;
    }
}

if (!function_exists('hero_slide_image')) {
    function hero_slide_image(int $sortOrderOrIndex): string
    {
        // Pool premium por marca/contexto — alinhado a Apple, Xiaomi, OPPO, QCY, assistência.
        // Quando admin cadastrar mais slides sem image_path, eles puxam esta rotação.
        $pool = [
            1 => '1511707171634-5f897ff02aa9', // Apple · ecossistema iPhone em bancada dark
            2 => '1580910051074-3eb694886505', // Xiaomi/Android · smartphone moderno close-up
            3 => '1526406915894-7bcd65f60845', // OPPO · flat-lay premium de celular
            4 => '1606220588913-b3aacb4d2f46', // QCY · fones bluetooth/earbuds premium
            5 => '1593305841991-05c297ba4575', // Assistência · bancada técnica profissional
        ];
        $k  = max(1, $sortOrderOrIndex);
        $id = $pool[$k] ?? $pool[(($k - 1) % count($pool)) + 1];
        return mc_img($id, 1920, 78);
    }
}

if (!function_exists('service_image')) {
    function service_image(?string $slug, ?string $iconHint = null): string
    {
        // Imagens 1:1 com o que o card descreve (trocar tela, bateria, etc).
        $map = [
            'troca-de-tela'          => '1601784551446-20c9e07cdbdb', // tela trincada de smartphone
            'troca-de-bateria'       => '1609692814858-f7cd2f0afa4f', // bateria sendo trocada em bancada
            'conector-de-carga'      => '1583863788434-e58a36330cf0', // carregador USB-C em bancada (conector)
            'reparo-de-placa'        => '1518770660439-4636190af475', // placa-mãe / microeletrônica
            'software-e-desbloqueio' => '1517430816045-df4b7de11d1d', // laptop + celular / software
            'limpeza-interna'        => '1588508065123-287b28e013da', // ferramenta de precisão / limpeza
        ];
        if ($slug && isset($map[$slug])) return mc_img($map[$slug], 900, 78);

        $iconMap = [
            'screen'   => '1601784551446-20c9e07cdbdb',
            'battery'  => '1609692814858-f7cd2f0afa4f',
            'plug'     => '1583863788434-e58a36330cf0',
            'chip'     => '1518770660439-4636190af475',
            'code'     => '1517430816045-df4b7de11d1d',
            'sparkle'  => '1588508065123-287b28e013da',
            'wrench'   => '1593305841991-05c297ba4575',
        ];
        if ($iconHint && isset($iconMap[$iconHint])) return mc_img($iconMap[$iconHint], 900, 78);
        return mc_img('1593305841991-05c297ba4575', 900, 78);
    }
}

if (!function_exists('product_image')) {
    function product_image(?string $category, ?string $slug = null): string
    {
        // Cada slug puxa uma imagem coerente com o produto real.
        $slugMap = [
            'capa-anti-impacto-premium'   => '1556656793-08538906a9f8', // celular com capa robusta
            'pelicula-3d-ceramica'        => '1585060544812-6b45742d762f', // película/vidro sendo aplicada
            'carregador-rapido-30w'       => '1583863788434-e58a36330cf0', // carregador USB-C premium
            'fone-bluetooth-pro'          => '1606220588913-b3aacb4d2f46', // earbuds premium (QCY-style)
            'cabo-usb-c-reforcado'        => '1588362951121-3ee319b018b2', // cabo USB-C trançado
            'carregador-veicular-20w'     => '1581594549595-35f6edc7b762', // celular carregando no carro
            'smartwatch-multi-fit'        => '1523275335684-37898b6baf30', // smartwatch no pulso
            'suporte-magnetico-veicular'  => '1558618666-fcd25c85cd64',   // suporte no painel do carro
        ];
        if ($slug && isset($slugMap[$slug])) return mc_img($slugMap[$slug], 900, 78);

        $cat = mb_strtolower($category ?? '');
        $catMap = [
            'capas'         => '1556656793-08538906a9f8',
            'películas'     => '1585060544812-6b45742d762f',
            'peliculas'     => '1585060544812-6b45742d762f',
            'carregadores'  => '1583863788434-e58a36330cf0',
            'fones'         => '1606220588913-b3aacb4d2f46',
            'cabos'         => '1588362951121-3ee319b018b2',
            'wearables'     => '1523275335684-37898b6baf30',
            'acessórios'    => '1558618666-fcd25c85cd64',
            'acessorios'    => '1558618666-fcd25c85cd64',
        ];
        foreach ($catMap as $k => $id) if ($cat !== '' && str_contains($cat, $k)) return mc_img($id, 900, 78);
        return mc_img('1558618666-fcd25c85cd64', 900, 78);
    }
}

if (!function_exists('promo_image')) {
    function promo_image(?string $slug): string
    {
        $map = [
            'combo-protecao-total'              => '1610945415295-d9bbf067e59c', // celular com capa/acessórios
            'troca-de-tela-mais-bateria'        => '1512499617640-c74ae3a79d37', // iPhone tela iluminada
            'promo-carregador-rapido'           => '1588362951121-3ee319b018b2', // cabo/carregador USB-C
            'pelicula-3d-instalacao-gratis'     => '1585060544812-6b45742d762f', // iPhone em superfície
            'bateria-original-troca-mesmo-dia'  => '1556656793-08538906a9f8',    // celular com capa (serviço)
            'kit-fone-bluetooth-carregador'     => '1606220588913-b3aacb4d2f46', // earbuds TWS
        ];
        if ($slug && isset($map[$slug])) return mc_img($map[$slug], 1200, 80);
        return mc_img('1606820854416-439b3305ff39', 1200, 80);
    }
}

if (!function_exists('about_image')) {
    function about_image(int $index): string
    {
        $pool = [
            1 => '1519558260268-cde7e03a0152', // bancada/loja
            2 => '1550009158-9ebf69173e03',    // reparo de perto
            3 => '1593305841991-05c297ba4575', // equipe técnica
            4 => '1574944985070-8f3ebc6b79d2',
        ];
        $i = max(1, $index);
        $id = $pool[$i] ?? $pool[(($i - 1) % count($pool)) + 1];
        return mc_img($id, 1200, 80);
    }
}

/* =============================================================================
 *  OVERLAY DE CIRCUITO (SVG inline pattern · verde da marca · sutil)
 *  Usado no hero e em seções escuras como assinatura visual.
 * ========================================================================== */
if (!function_exists('circuit_overlay')) {
    function circuit_overlay(string $variant = 'default'): string
    {
        // Pattern desenhado inline: trilhas + nós (PCB)
        // Renderiza apenas a tag; o posicionamento é feito via classe no CSS.
        $svg = <<<SVG
<svg class="circuit-overlay circuit-overlay--{$variant}" viewBox="0 0 400 400" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" preserveAspectRatio="xMidYMid slice">
  <defs>
    <pattern id="mc-pcb" width="120" height="120" patternUnits="userSpaceOnUse">
      <g fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round">
        <path d="M10 20 H60 L80 40 H110"/>
        <path d="M10 60 H40 L60 80 H110"/>
        <path d="M10 100 H30 L50 80"/>
        <path d="M20 10 V50 L40 70 V110"/>
        <path d="M70 10 V30 L90 50 V90 L110 110"/>
        <path d="M100 10 V70"/>
      </g>
      <g fill="currentColor">
        <circle cx="10" cy="20" r="1.8"/>
        <circle cx="60" cy="20" r="1.8"/>
        <circle cx="80" cy="40" r="1.8"/>
        <circle cx="110" cy="40" r="1.8"/>
        <circle cx="40" cy="60" r="1.8"/>
        <circle cx="60" cy="80" r="1.8"/>
        <circle cx="110" cy="80" r="1.8"/>
        <circle cx="30" cy="100" r="1.8"/>
        <circle cx="50" cy="80" r="1.8"/>
        <circle cx="20" cy="10" r="1.8"/>
        <circle cx="40" cy="70" r="1.8"/>
        <circle cx="70" cy="10" r="1.8"/>
        <circle cx="90" cy="50" r="1.8"/>
        <circle cx="100" cy="10" r="1.8"/>
        <circle cx="100" cy="70" r="1.8"/>
      </g>
    </pattern>
  </defs>
  <rect width="400" height="400" fill="url(#mc-pcb)"/>
</svg>
SVG;
        return $svg;
    }
}

/* =============================================================================
 *  PREVIEW URL HELPER (Fase 2.5 · rota A · econômica)
 *  -----------------------------------------------------------------------------
 *  Gera URL pública para preview por contexto. Usa rotas reais quando existem;
 *  senão, faz fallback para a home com âncora da seção. Respeita APP_URL
 *  (via url() do Core/Helpers.php) para produção / shared hosting.
 *
 *  Uso:
 *    preview_url('home')                          → /
 *    preview_url('hero')                          → /#hero
 *    preview_url('services')                      → /assistencia-tecnica
 *    preview_url('service', 'troca-de-tela')      → /assistencia-tecnica/troca-de-tela
 *    preview_url('products')                      → /produtos
 *    preview_url('product', 'capa-anti-impacto')  → /produtos/capa-anti-impacto
 *    preview_url('promotions')                    → /promocoes
 *    preview_url('promotion', 'combo-total')      → /promocoes/combo-total
 *    preview_url('testimonials')                  → /#testimonials
 *    preview_url('diffs')                         → /#diffs
 *    preview_url('units')                         → /contato#units
 *    preview_url('about')                         → /sobre
 *    preview_url('contact')                       → /contato
 *    preview_url('reservation')                   → /reservar
 *    preview_url('links')                         → /links
 *    preview_url('slide')                         → /#hero
 * ========================================================================== */
if (!function_exists('preview_url')) {
    function preview_url(string $context, ?string $slug = null): string
    {
        $base = function_exists('url') ? url('/') : '/';
        $base = rtrim($base, '/');
        $slug = $slug ? trim($slug, '/') : null;

        switch ($context) {
            case 'home':                                   return $base . '/';
            case 'services':                               return $base . '/assistencia-tecnica';
            case 'service':
                return $slug ? $base . '/assistencia-tecnica/' . $slug : $base . '/assistencia-tecnica';
            case 'products':                               return $base . '/produtos';
            case 'product':
                return $slug ? $base . '/produtos/' . $slug : $base . '/produtos';
            case 'promotions':                             return $base . '/promocoes';
            case 'promotion':
                return $slug ? $base . '/promocoes/' . $slug : $base . '/promocoes';
            case 'about':                                  return $base . '/sobre';
            case 'contact':                                return $base . '/contato';
            case 'reservation': case 'reserve':            return $base . '/reservar';
            case 'links': case 'bio':                      return $base . '/links';
            case 'hero': case 'slide': case 'hero_slide':  return $base . '/#hero';
            case 'testimonials': case 'testimonial':       return $base . '/#testimonials';
            case 'diffs': case 'differentials':            return $base . '/#diffs';
            case 'units': case 'unit': case 'branch': case 'branches':
                return $base . '/contato#units';
            default:                                       return $base . '/';
        }
    }
}
