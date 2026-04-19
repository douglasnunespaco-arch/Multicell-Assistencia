<?php
namespace App\Core;

/**
 * Upload — validação e salvamento seguro de imagens.
 * Whitelist de MIME + extensão + tamanho.
 */
final class Upload
{
    private const ALLOWED_MIME = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
        'image/gif'  => 'gif',
    ];

    /**
     * Salva um arquivo enviado e retorna caminho relativo (ex: uploads/products/abc.jpg).
     * Retorna null se não houver upload válido.
     *
     * @throws \RuntimeException em caso de erro de validação.
     */
    public static function save(array $file, string $subdir): ?string
    {
        if (!isset($file['error']) || $file['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new \RuntimeException('Erro no upload do arquivo.');
        }

        $max = (defined('UPLOAD_MAX_MB') ? UPLOAD_MAX_MB : 5) * 1024 * 1024;
        if ($file['size'] > $max) {
            throw new \RuntimeException('Arquivo excede o tamanho permitido.');
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime  = $finfo->file($file['tmp_name']) ?: '';
        if (!isset(self::ALLOWED_MIME[$mime])) {
            throw new \RuntimeException('Tipo de arquivo não permitido.');
        }
        $ext = self::ALLOWED_MIME[$mime];

        $subdir = trim($subdir, '/');
        $destDir = UPLOADS_DIR . '/' . $subdir;
        if (!is_dir($destDir)) {
            @mkdir($destDir, 0755, true);
        }

        $base = bin2hex(random_bytes(8)) . '_' . time() . '.' . $ext;
        $destFs = $destDir . '/' . $base;

        if (!move_uploaded_file($file['tmp_name'], $destFs)) {
            throw new \RuntimeException('Não foi possível salvar o arquivo.');
        }
        @chmod($destFs, 0644);

        return 'uploads/' . $subdir . '/' . $base;
    }

    public static function delete(?string $relPath): void
    {
        if (!$relPath) return;
        $fs = APP_ROOT . '/' . ltrim($relPath, '/');
        if (is_file($fs) && str_starts_with(realpath($fs) ?: '', UPLOADS_DIR)) {
            @unlink($fs);
        }
    }
}
