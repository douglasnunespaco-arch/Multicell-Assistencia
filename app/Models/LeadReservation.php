<?php
namespace App\Models;

use App\Core\Database;

/**
 * LeadReservation — reservas de atendimento submetidas via formulário público.
 * Fase 3: create + leituras básicas. Admin (Fase 4C) consumirá `paginate` e `updateStatus`.
 */
final class LeadReservation
{
    /** Campos aceitos pelo INSERT (whitelist). */
    private const FIELDS = [
        'customer_name', 'phone', 'device_brand', 'device_model',
        'issue_description', 'service_type', 'preferred_date',
        'preferred_period', 'notes', 'source', 'ip_hash', 'user_agent',
    ];

    /** Cria uma reserva. Retorna o ID gerado. */
    public static function create(array $data): int
    {
        $payload = [];
        foreach (self::FIELDS as $k) {
            $payload[$k] = $data[$k] ?? null;
        }
        $payload['status']     = 'novo';
        $payload['created_at'] = date('Y-m-d H:i:s');
        return Database::insert('lead_reservations', $payload);
    }

    public static function find(int $id): ?array
    {
        return Database::fetch('SELECT * FROM lead_reservations WHERE id = :id', [':id' => $id]);
    }

    /**
     * Listagem paginada para o admin (Fase 4C).
     * @return array{rows:array, total:int, page:int, per_page:int}
     */
    public static function paginate(int $page = 1, int $perPage = 20, ?string $status = null): array
    {
        $page    = max(1, $page);
        $perPage = max(1, min(100, $perPage));
        $offset  = ($page - 1) * $perPage;

        $where  = '';
        $params = [];
        if ($status !== null && $status !== '') {
            $where = 'WHERE status = :status';
            $params[':status'] = $status;
        }

        $total = (int) Database::fetch("SELECT COUNT(*) AS c FROM lead_reservations $where", $params)['c'];
        $rows  = Database::fetchAll(
            "SELECT * FROM lead_reservations $where ORDER BY created_at DESC LIMIT $perPage OFFSET $offset",
            $params
        );
        return compact('rows', 'total', 'page', 'perPage');
    }

    public static function updateStatus(int $id, string $status, ?string $adminNote = null): int
    {
        $data = ['status' => $status];
        if ($adminNote !== null) $data['admin_note'] = $adminNote;
        return Database::update('lead_reservations', $data, ['id' => $id]);
    }
}
