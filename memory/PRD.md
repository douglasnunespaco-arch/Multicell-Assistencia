# Multi Cell — PRD

## Stack
PHP 8.2 + MariaDB 10.11 · vanilla HTML/CSS/JS · Sora/Manrope · paleta verde #14F195

## Histórico

### 24/Apr/2026 — Sessão 1: Bug fix admin
Inputs brancos da Tema corrigidos. Light theme completo no admin via tokens `--admin-*`.

### 24/Apr/2026 — Sessão 2: UX upgrade
Trofeus refinados, animação welcome 10s, theme cards Dark/Light/Auto, toggle 3-state.

### 25/Apr/2026 — Sessão 3: Premium polish
- **Persistência de tema por usuário no servidor** — `App\Models\AdminPref` (novo) usa a tabela `settings` (zero schema change) com chave `admin_pref_theme_user_{id}`. Login carrega para `$_SESSION['theme_pref']`. SSR em `layouts/admin.php` aplica tema correto antes do JS rodar (sem flash). Endpoint `POST /admin/theme/preference` (JSON, CSRF) chamado em background pelo topbar quando admin troca o tema.
- **Streak counter** — `DashboardController::computeStreak()` calcula dias consecutivos batendo `goal_clicks_day`, limitado a 90 dias (bounded cost). Aparece como banner discreto no topo do dashboard quando streak >= 3, e como chip verde "⚡ N dias seguidos" no welcome.
- **Maior conquista do mês** — `computeMonthlyLead()` retorna delta entre mês corrente e melhor mês passado. Renderizado como chip dourado "🏆 mês atual +X cliques acima do recorde" no welcome quando delta > 0.
- **Welcome enriquecido** — título muda para "Você está em chamas!" se houver streak/delta/hit. Chips com stagger animation (delay 0.35s). Confete dispara em qualquer celebração.
- **WCAG no light** — refinos de contraste em `.admin-tag--*` (5 status) e `.admin-flash--*` (4 níveis) atingindo 4.5:1+ via texto mais escuro (#075c3a, #5a3a00, #053f28, #173f80, #7a0a25). Foco visível em botões/filtros.
- **Mini-CTAs no dashboard** — todos os 6 stat cards agora são `<a>` clicáveis: leads_new → `/admin/leads?status=novo`, leads_today/total/week → `/admin/leads`, pageviews → `/admin/seo`, wa_clicks → `/admin/leads?status=novo`. Streak banner tem CTA "→ ver leads".

## Arquivos modificados / criados (sessão 3)
- `app/Models/AdminPref.php` — **NOVO** (~32 linhas). Wrapper de pref por usuário sobre `settings`.
- `app/Controllers/Admin/AuthController.php` — popula `$_SESSION['theme_pref']` no login
- `app/Controllers/Admin/ThemeController.php` — método `preference()` JSON
- `app/Controllers/Admin/DashboardController.php` — `computeStreak()` + `computeMonthlyLead()`
- `app/routes.php` — rota `POST /admin/theme/preference`
- `app/Views/layouts/admin.php` — SSR do tema + data-welcome-streak/delta no body + data-csrf
- `app/Views/partials/admin/topbar.php` — fetch silencioso ao toggle, prioriza SSR sobre localStorage
- `app/Views/admin/dashboard.php` — streak-banner + stats todos como `<a>`
- `assets/js/admin-welcome.js` — chips de streak e delta com stagger
- `assets/css/admin.css` — `.streak-banner*`, `.mc-welcome__chip*`, refinos WCAG light

## Backlog
- P2 · Personalizar título do welcome com nome de produto/serviço top do mês
- P2 · "Hot path" → mostrar no dashboard qual produto/serviço mais converte na semana
- P2 · Notificação push quando novo recorde é batido (web push API)
- P2 · Modo "foco" do dashboard (esconde sidebar e dá fullwidth aos cards)

## Login (preview)
- URL: https://d7099f3a-94fe-4a54-a29e-10d9871d55c8.preview.emergentagent.com/admin/login
- E-mail: `admin@multicell.local` · Senha: `ChangeMe123!`
