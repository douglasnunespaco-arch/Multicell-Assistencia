<?php
namespace App\Core;

/**
 * Analytics — registrador central de eventos no banco.
 * Usado server-side e via endpoint /api/track (beacon do JS).
 */
final class Analytics
{
    public const EVENT_TYPES = [
        'page_view',
        'whatsapp_click',
        'cta_click',
        'service_click',
        'product_click',
        'promotion_click',
        'slide_click',
        'reservation_submit',
        'map_click',
        'phone_click',
    ];

    public static function track(
        string $eventType,
        ?string $pagePath = null,
        ?string $refType = null,
        ?int $refId = null,
        ?string $source = null,
        array $meta = []
    ): void {
        if (!in_array($eventType, self::EVENT_TYPES, true)) {
            return;
        }
        try {
            Database::insert('analytics_events', [
                'event_type' => $eventType,
                'page_path'  => $pagePath !== null ? mb_substr($pagePath, 0, 191) : null,
                'ref_type'   => $refType,
                'ref_id'     => $refId,
                'source'     => $source !== null ? mb_substr($source, 0, 80) : null,
                'session_id' => Session::sid(),
                'ip_hash'    => self::hashIp(),
                'user_agent' => mb_substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255),
                'meta_json'  => $meta ? json_encode($meta, JSON_UNESCAPED_UNICODE) : null,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        } catch (\Throwable $e) {
            // Nunca quebrar o fluxo por causa de analytics
            error_log('[analytics] ' . $e->getMessage());
        }
    }

    private static function hashIp(): string
    {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '';
        $ip = trim(explode(',', $ip)[0] ?? '');
        return substr(hash('sha256', $ip . '|' . (defined('APP_KEY') ? APP_KEY : '')), 0, 64);
    }
}
