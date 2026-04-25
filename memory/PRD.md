# Multi Cell — PRD

## Stack
PHP 8.2 + MariaDB 10.11 · vanilla HTML/CSS/JS · Sora/Manrope · paleta verde #14F195

## Histórico

### 25/Apr/2026 — Sessão 5: Yesterday recap (hábito diário)
- **Mini relatório de ontem** — faixa compacta no topo do dashboard com clicks + reservas + top item + status da meta. Single render, query bounded. Aparece só se houve atividade (clicks > 0 OU leads > 0). Estilo light/dark com pill verde quando meta batida. `DashboardController::computeYesterdayRecap()`.

### 25/Apr/2026 — Sessão 4: Retenção & gamification
- **Hot path · 7 dias** — novo card no dashboard com top 5 produtos/serviços/promoções mais clicados, com link direto pra editar. Single SQL com LEFT JOIN nas 3 tabelas + GROUP BY + LIMIT 5 (bounded cost). `DashboardController::computeHotPath()`.
- **Welcome personalizado com top bucket** — query no layout extrai o item mais clicado da semana e injeta como `data-welcome-top`. JS renderiza linha "★ Destaque da semana: **Capa Anti-impacto Premium**" no overlay.
- **Modo Foco** — botão no topbar (ícone search) + atalho tecla `F` colapsam a sidebar (transform translateX -110%) e centralizam content (max-width 1240px). Persiste em localStorage `mc_focus_mode`. `applyFocus()` em `admin.js`.
- **Notifications API + polling leve** (alternativa enxuta a Web Push) — endpoint `GET /admin/api/achievements` retorna JSON com `record_week`. `admin.js` pede permissão 1× por sessão (delay 4s pra não atropelar welcome), faz polling de 60s quando aba visível, dispara `new Notification()` com tag/sig anti-duplicata. Funciona em qualquer hospedagem (zero VAPID, zero Service Worker).
- **Email semanal automático** — `app/Console/WeeklyDigest.php` (CLI) calcula stats da semana (cliques, delta vs anterior, leads, dias com meta batida, hot path top 5), monta HTML inline-styled em `Views/emails/weekly-digest.php`, envia via `mail()` nativo PHP para todos admins ativos. Idempotência: trava de 5 dias em settings (`weekly_digest_last_run`). Cron documentado em `README-PUBLICACAO.txt`.

## Arquivos modificados / criados (sessão 4)
- `app/Controllers/Admin/AchievementsApiController.php` — **NOVO** (~38 linhas)
- `app/Console/WeeklyDigest.php` — **NOVO** (~110 linhas, CLI standalone)
- `app/Views/emails/weekly-digest.php` — **NOVO** (~80 linhas, HTML email)
- `app/Controllers/Admin/DashboardController.php` — `computeHotPath()` + `computeTopBucket()`
- `app/Views/admin/dashboard.php` — bloco `.hot-path` antes de "Últimas reservas"
- `app/Views/layouts/admin.php` — query top bucket + `data-welcome-top`
- `app/Views/partials/admin/topbar.php` — botão `[data-focus-toggle]`
- `assets/js/admin-welcome.js` — usa top bucket no welcome
- `assets/js/admin.js` — focus mode + Notifications API + polling
- `assets/css/admin.css` — `.hot-path*`, `.admin-shell--focus`, `.mc-welcome__top`
- `app/routes.php` — rota `/admin/api/achievements`
- `README-PUBLICACAO.txt` — linha de cron sugerida

## Backlog
- P2 · Web Push API real (Service Worker + VAPID + web-push-php) — só vale se sair de hospedagem compartilhada.
- P2 · Modo foco com tecla `?` mostrando atalhos disponíveis (cheatsheet)
- P2 · Email semanal: opção de unsubscribe / changing frequency
- P2 · Dashboard widget configurável (drag-drop dos cards)

## Login (preview)
- URL: https://d7099f3a-94fe-4a54-a29e-10d9871d55c8.preview.emergentagent.com/admin/login
- E-mail: `admin@multicell.local` · Senha: `ChangeMe123!`

## Cron sugerido (Hostinger · hPanel → Avançado → Tarefas Cron)
```
0 9 * * 1 /usr/bin/php /home/USUARIO/public_html/app/Console/WeeklyDigest.php >> /home/USUARIO/public_html/storage/logs/digest.log 2>&1
```
