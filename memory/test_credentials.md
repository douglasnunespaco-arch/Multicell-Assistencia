# Credenciais de teste — ambiente de preview

## Admin (preview atual) — VÁLIDAS E TESTADAS
- URL: `/admin/login`
- Email: `admin@multicell.local`
- Senha: `Admin@2026`  (bcrypt cost=10, must_change_password=0, is_active=1)

## MySQL (ambiente preview container)
- Host: 127.0.0.1:3306
- DB: multicell
- User: multicell
- Pass: multicell
- Socket local (CLI): /run/mysqld/mysqld.sock

## Rotas admin disponíveis
- `/admin/login` · `/admin/logout` · `/admin` (dashboard)
- `/admin/slides` · `/admin/services` · `/admin/products` · `/admin/promotions` · `/admin/testimonials`
- `/admin/about`  · `/admin/links`   · `/admin/units`   · `/admin/settings`
- `/admin/leads`  · `/admin/sections` · `/admin/seo`    · `/admin/theme`
