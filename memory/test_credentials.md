# Credenciais de teste — ambiente de preview

## Admin
- URL: `/admin/login`
- Email: `admin@multicell.local`
- Senha: `admin123` (bcrypt 12)

## MySQL (ambiente preview)
- Host: 127.0.0.1:3306
- DB: multicell
- User: mc
- Pass: mc

## Rotas novas (Fase C1)
- `/admin/about`   · `/admin/about/new`   · `/admin/about/{id}/edit`
- `/admin/links`   · `/admin/links/new`   · `/admin/links/{id}/edit`
- `/admin/units`   · `/admin/units/new`   · `/admin/units/{id}/edit`

## Tabela adicionada
- `bio_links` (id, title, url, icon, sort_order, is_active, open_new_tab, timestamps)
- 4 seeds iniciais (WhatsApp, produtos, promoções, mapa)
