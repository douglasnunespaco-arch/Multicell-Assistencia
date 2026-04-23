# Credenciais de teste — ambiente de preview

## Admin (preview atual)
- URL: `/admin/login`
- Email: `admin@multicell.local`
- Senha: `Admin@2026`  (bcrypt, must_change_password=0)

## MySQL (ambiente preview container)
- Host: 127.0.0.1:3306
- DB: multicell
- User: multicell
- Pass: multicell_preview_2026
- Socket local (CLI): /run/mysqld/mysqld.sock

## Rotas novas (Fase C1)
- `/admin/about`   · `/admin/about/new`   · `/admin/about/{id}/edit`
- `/admin/links`   · `/admin/links/new`   · `/admin/links/{id}/edit`
- `/admin/units`   · `/admin/units/new`   · `/admin/units/{id}/edit`

## Tabela adicionada
- `bio_links` (id, title, url, icon, sort_order, is_active, open_new_tab, timestamps)
- 4 seeds iniciais (WhatsApp, produtos, promoções, mapa)
