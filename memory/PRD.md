# Multi Cell — PRD

## Stack
PHP 8.2 + MariaDB 10.11 · vanilla HTML/CSS/JS · Sora/Manrope · paleta verde #14F195

## Histórico

### 24/Apr/2026 — Sessão 1: Bug fix admin
- Inputs brancos da aba Tema corrigidos (use `.admin-input`).
- Light theme completo no admin via tokens `--admin-*` em `[data-theme="light"]`.
- Setup de PHP + MariaDB para preview vivo.
- Login: `admin@multicell.local` / `ChangeMe123!`

### 24/Apr/2026 — Sessão 2: UX upgrade
- **Trofeus**: novo ícone `trophy-solid` (SVG bem desenhado · alças + base + brilho), tile verde com efeito glass shine no hover, card cinza sólido com borda verde no hover, X minimiza (já existia).
- **Welcome animation pós-login (10s)**: overlay com backdrop blur, badge troféu verde animado, emojis flutuantes (estrelas, troféus, sparkles, raios, coroa, medalha) caindo do topo. Confete extra disparado se a meta diária estiver batida no momento do login. Dispensável com clique/Esc. Respeita `prefers-reduced-motion`. Flag de sessão para mostrar 1× por login.
- **Theme cards**: substituição do `<select>` por 3 cards visuais Dark/Light/Auto com mini-preview de sidebar + hero + chips. Aplica preview ao vivo ao clicar.
- **3-state toggle no topbar**: alterna Dark → Light → Auto (segue SO via `prefers-color-scheme`). Persistido em localStorage (`mc_theme_pref`).
- **Suporte server-side a "auto"**: ThemeController aceita o valor; layout público faz fallback para `dark` em SSR e respeita o tema do SO no client.
- Settings já estava no padrão (FormField).

## Arquivos modificados / criados
- `app/Views/partials/public/icons.php` — `trophy-solid`, `crown`, `sparkle-solid` (filled, sem stroke)
- `app/Views/admin/dashboard.php` — usa `trophy-solid` no tile
- `app/Views/admin/theme/index.php` — theme cards Dark/Light/Auto
- `app/Views/partials/admin/topbar.php` — toggle 3-state com `data-theme-pref`
- `app/Views/layouts/admin.php` — passa `data-welcome*` para o body, carrega `admin-welcome.js`
- `app/Views/layouts/public.php` — suporte `data-theme-default="auto"`
- `app/Controllers/Admin/AuthController.php` — flag `_welcome_show` no login
- `app/Controllers/Admin/ThemeController.php` — aceita `auto`
- `assets/css/admin.css` — `.mc-welcome*`, `.theme-cards*`, `.admin-theme-toggle__icon--auto`, refinos `.trophy-card__icon`
- `assets/js/admin-welcome.js` (novo) — animação 10s com 6 emojis SVG
- `assets/js/admin.js` — sync color picker

## Backlog
- P1 · Persistir preferência de tema do admin **por usuário no servidor** (hoje: localStorage no client).
- P1 · Validar WCAG real dos badges de status no light theme com ferramenta automática.
- P2 · Personalizar mensagem do welcome com a maior conquista do mês ("você bateu o recorde mensal!").
- P2 · Adicionar contador de "dias seguidos sem queda de cliques" no welcome (gamification).
- P2 · Botão "Repetir animação" em desenvolvimento, escondido em produção.

## URL preview
https://d7099f3a-94fe-4a54-a29e-10d9871d55c8.preview.emergentagent.com/admin/login
