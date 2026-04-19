<?php
/**
 * Multi Cell Assistência Técnica
 * Arquivo de configuração de exemplo.
 *
 * O arquivo real (config/config.php) é gerado pelo install.php.
 * Nunca commite credenciais reais neste repositório.
 */

// Ambiente: production ou development
define('APP_ENV', 'production');

// URL base pública (sem barra final). Ex: https://multicell.com.br
define('APP_URL', 'https://exemplo.com.br');

// Nome público
define('APP_NAME', 'Multi Cell Assistência Técnica');

// Timezone
define('APP_TIMEZONE', 'America/Cuiaba');

// Chave secreta (rotacionar em produção). 32+ chars aleatórios.
define('APP_KEY', 'TROCAR-POR-CHAVE-ALEATORIA-32+CHARS');

// Banco de dados MySQL / MariaDB
define('DB_HOST', 'localhost');
define('DB_PORT', '3306');
define('DB_NAME', 'multicell');
define('DB_USER', 'usuario_db');
define('DB_PASS', 'senha_db');
define('DB_CHARSET', 'utf8mb4');

// Upload
define('UPLOAD_MAX_MB', 5);
