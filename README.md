# Multi Cell Assistência Técnica — Site Oficial

Site comercial premium em **PHP 8 + MySQL/MariaDB** para deploy flat em
`public_html` (Hostinger / shared hosting). Sem Node em produção,
sem MongoDB, sem dependências pesadas.

## Status

- [x] **Fase 1** — Base estrutural + instalador + schema SQL + seed
- [ ] Fase 2 — Frontend público premium
- [ ] Fase 3 — Reserva de Atendimento
- [ ] Fase 4 — Painel Admin completo
- [ ] Fase 5 — Analytics interno (dashboards + rankings)
- [ ] Fase 6 — SEO, hardening e pacote final

## Publicação rápida

1. Envie todo o conteúdo desta pasta para `public_html/` via FTP ou File Manager.
2. Crie um banco MySQL vazio no painel da Hostinger e anote usuário/senha.
3. Acesse `https://seu-dominio/install.php` no navegador.
4. Preencha as credenciais do banco e o administrador inicial.
5. Após o sucesso, **remova o arquivo `install.php`** (ou confirme que ele
   foi renomeado para `install.php.done`).

### Fallback manual (sem instalador)

Se preferir, importe `database/schema.sql` e `database/seed.sql` via
phpMyAdmin, gere o `config/config.php` a partir de `config/config.sample.php`
e crie o admin manualmente com senha `password_hash()`.

## Estrutura

```
public_html/
├── index.php           # Front controller
├── install.php         # Instalador (self-disable após sucesso)
├── .htaccess           # Rewrite + segurança
├── robots.txt
├── app/                # Core, controllers, models, views (protegido)
├── config/             # config.php (gerado) — protegido
├── storage/            # logs + install.lock — protegido
├── database/           # schema.sql + seed.sql — protegido
├── assets/             # CSS / JS / imagens / fontes
└── uploads/            # Imagens carregadas pelo admin
```

## Segurança aplicada nesta fase

- Senhas com `password_hash()` (bcrypt cost 12).
- CSRF token em todos os forms (núcleo pronto).
- Sessão `HttpOnly` + `SameSite=Lax` + regeneração no login.
- `.htaccess` bloqueando `app/`, `config/`, `storage/`, `database/`, `.git`, `*.sql`, `*.md`, `*.lock`.
- Uploads impedem execução de PHP e validam MIME + tamanho.
- `APP_ENV=production` por padrão, sem display de erros.
- Instalador auto-travável via `storage/install.lock` + renomeação.
