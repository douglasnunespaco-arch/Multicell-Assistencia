# Credenciais de teste (preview Emergent)

## Admin
- URL: `/admin/login`
- E-mail: `admin@multicell.local`
- Senha: `Admin123!`

## Banco de dados (preview local)
- Engine: MariaDB 10.11 (bind 127.0.0.1:3306)
- DB: `multicell` · user: `multicell` · pass: `multicell_pw`
- Socket: `/var/run/mysqld/mysqld.sock`

## Ambiente
- PHP built-in server em `0.0.0.0:3000` via `/app/router.php`
- Config preview: `/app/config/config.php`
- Lock: `/app/storage/install.lock`

## Leads de exemplo (inseridos manualmente para validação visual)
1. Maria Silva · iPhone 13 · Troca de tela · status=novo
2. João Pereira · Xiaomi Redmi Note 12 · Troca de bateria · status=em_atendimento
3. Ana Costa · Samsung Galaxy S22 · Conector de carga · status=concluido
