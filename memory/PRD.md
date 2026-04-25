# Multi Cell — PRD

## Problema original
Projeto PHP/MariaDB conectado via GitHub. Pedidos:
1. No painel admin, padronizar os inputs (não deixar com fundo branco do navegador) — alinhar com o resto do sistema.
2. Fazer o modo Light funcionar **por completo** no painel admin (e não só em pedaços como trophy-cards).
3. Disponibilizar preview do site rodando.

## Stack
- PHP 8.2 (server embutido) · MariaDB 10.11
- Frontend: HTML/CSS/JS vanilla (público) + admin com `admin.css`, tokens via `[data-theme]`
- Sem build step

## O que foi entregue (24/Apr/2026)
- **Bug 1 (inputs brancos)** corrigido em `app/Views/admin/theme/index.php` — campos `brand_color` e `brand_color_ink` migrados de `<input>` cru para `<input class="admin-input">` dentro de `.admin-field`. Adicionado color picker nativo (`<input type="color">`) sincronizado via JS.
- Mesmo tratamento aplicado em `app/Views/admin/about/form.php` (estilos inline migrados para o padrão).
- **Bug 2 (light theme incompleto)** resolvido em `assets/css/admin.css`:
  - Tokens locais `--admin-*` declarados em `:root` e sobrescritos em `[data-theme="light"]` (shell, surface, elevate, border, text, input-bg, shadow, row-hover).
  - Overrides explícitos para sidebar, topbar, cards, stats, tabela, inputs/selects/textareas/file, botões (default/primary/wa/danger), filtros, tags de status, flash, form sections, login, lead detail, period card, roadmap, thumb, paginação.
  - Tema Light usa paleta verde escurecida (#0fb878) para garantir contraste sobre fundo claro.
- **Preview vivo**: PHP server na porta 3000 + MariaDB local via supervisor (`/etc/supervisor/conf.d/php-app.conf`). Banco importado de `database/schema.sql` + seeds.

## Arquivos modificados
- `app/Views/admin/theme/index.php` — inputs com `.admin-input` + color picker
- `app/Views/admin/about/form.php` — campos image migrados para padrão `.admin-field`
- `assets/css/admin.css` — bloco final com tokens `--admin-*` e overrides `[data-theme="light"]`
- `assets/js/admin.js` — sync color picker ↔ input hex
- `config/config.php` — gerado para apontar pro MariaDB local
- `/etc/supervisor/conf.d/php-app.conf` — supervisor para PHP + MariaDB

## URL de preview
https://d7099f3a-94fe-4a54-a29e-10d9871d55c8.preview.emergentagent.com/admin/login

## Próximos itens (backlog)
- P1 · Persistir preferência de tema do admin no servidor (hoje só em localStorage).
- P1 · Validar contraste WCAG dos badges de status no light theme.
- P2 · Mesmo passe nos forms em `app/Views/admin/settings/index.php`.
- P2 · Opção "Auto" (segue OS) no toggle do tema.
