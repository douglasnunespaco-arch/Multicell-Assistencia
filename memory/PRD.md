# Multi Cell Assistência Técnica · PRD

## Problema original
Site comercial premium em PHP 8 + MySQL/MariaDB para a Multi Cell Assistência
Técnica (Várzea Grande/MT). Deploy flat em `public_html` (Hostinger / shared
hosting). Sem Node em produção, sem MongoDB, sem dependências pesadas.

Instagram da loja: https://www.instagram.com/multicell_assistencia_/

## Arquitetura
- PHP 8 + MySQL/MariaDB · sem frameworks pesados (Core próprio)
- Autoload PSR-4 (namespace `App\`)
- Estrutura: `app/Controllers`, `app/Core`, `app/Models`, `app/Views`
- Frontend público: CSS self-hosted + fontes Sora/Manrope self-hosted
- JS vanilla (`assets/js/public.js`) · sem React/framework
- Site instalável via `install.php` → gera `config/config.php` + `storage/install.lock`

## Fases
- [x] **Fase 1** — Base estrutural + instalador + schema SQL + seed
- [x] **Fase 2** — Frontend público premium
- [x] **Fase 2.5 (rodada atual)** — Refinamento visual premium · ícones, imagens, pegada tech, varredura pública
- [ ] **Fase 3** — Reserva de Atendimento (backend) · NÃO INICIADA (bloqueada por decisão do usuário)
- [ ] **Fase 4** — Painel Admin completo · NÃO INICIADA
- [ ] Fase 5 — Analytics interno (dashboards + rankings)
- [ ] Fase 6 — SEO, hardening e pacote final

## User personas
- **Cliente final local (Várzea Grande/Cuiabá/MT)**: procura assistência técnica
  rápida via WhatsApp, valoriza garantia real, orçamento grátis e transparência
- **Cliente de acessórios**: compra capas, películas, carregadores, fones
- **Lojista (admin Multi Cell)**: cadastra slides/serviços/produtos/promos via
  painel admin (Fase 4 futura)

## Requisitos centrais (estáveis)
1. Conversão para WhatsApp: botão presente em hero, header, footer, cards, float
2. Paleta preto/branco/verde da marca (verde neon `#14F195`) — **congelada**
3. Tipografia Sora (display) + Manrope (body)
4. Hero full-bleed com slider autoplay + reserva inteligente de CTAs
5. Temático: assistência técnica de celulares / loja / manutenção
6. Compatibilidade Hostinger (PHP puro, sem Node em produção)

---

## ✅ Fase 2.5 — Refinamento visual premium (rodada atual · JAN 2026)

### Entregas desta rodada
1. **Padronização completa de ícones** — biblioteca unificada Lucide-style SVG
   inline (stroke 2px, round caps) + ícones de marca (WhatsApp, Instagram,
   Facebook, TikTok) em paths oficiais, filled
2. **Imagens reais/coerentes** — helpers `hero_slide_image()`, `service_image()`,
   `product_image()`, `promo_image()`, `about_image()` mapeados por contexto
   (slug/categoria/ordem) usando CDN Unsplash premium
3. **Pegada tech (PCB/circuito)** — overlay SVG inline verde da marca
   (`circuit_overlay()`) aplicado no hero, CTA final, banners internos, cartões
   de diferenciais — sutil, sem poluição visual
4. **Varredura pública** — documentada em `/app/memory/scan-publico.md`
   (fatos, sugestões, conflitos que precisam confirmação do cliente)

### Arquivos alterados nesta rodada
| Arquivo | Mudança |
|---|---|
| `app/Views/partials/public/icons.php` | Reescrito: biblioteca Lucide expandida + 4 ícones de marca + 6 helpers de imagem + `circuit_overlay()` |
| `app/Views/partials/public/header.php` | SVGs inline removidos → `icon()` (moon, menu, close, whatsapp) |
| `app/Views/partials/public/footer.php` | Ícones de marca consistentes · contato com ícones inline · e-mail ganhou ícone mail |
| `app/Views/partials/public/whatsapp-float.php` | Ícone WhatsApp correto de marca |
| `app/Views/partials/public/card-service.php` | Ganhou `card__media` com imagem real + badge do ícone técnico |
| `app/Views/partials/public/card-product.php` | Fallback inteligente via `product_image()` + ícone WA no CTA |
| `app/Views/partials/public/card-promotion.php` | Imagem real via `promo_image()` + tag premium |
| `app/Views/public/home.php` | Hero com imagens via helper, overlay PCB, ícones refinados, CTA final com overlay tech |
| `app/Views/public/about.php` | Imagens reais via `about_image()` + overlay PCB no CTA |
| `app/Views/public/contact.php` | Ícones refinados (mail, map, whatsapp, calendar) |
| `app/Views/public/services-index.php` | CTAs com ícones corretos |
| `app/Views/public/reservation.php` | Ícones refinados (shield-check, whatsapp, clock) |
| `app/Views/public/links.php` | Bio page com ícones consistentes (calendar, package, facebook, tiktok, map) |
| `assets/css/public.css` | +230 linhas: circuit overlay, card-service media, promo tag, tech divider, refinos de ícones |

### Ícones revisados (32 na biblioteca)
**Lucide-style stroke**: check, check-circle, shield, shield-check, bolt, zap,
menu, close, chevron-left, chevron-right, arrow-right, sun, moon, phone,
phone-call, mail, pin, map, clock, calendar, globe, wrench, tools, battery,
smartphone, screen, chip, cpu, plug, code, terminal, sparkle, headphones,
package, cable, star (filled), tag, image, award, users, user, heart, truck,
message, search, info

**Marca (filled, paths oficiais)**: whatsapp, instagram, facebook, tiktok

### O que estava errado antes
- SVGs inline em header.php, footer.php, whatsapp-float.php misturavam
  stroke/fill de forma inconsistente
- WhatsApp no header/footer/float usava um path simplificado que parecia
  "balão com traço" — agora usa o símbolo oficial WA
- Ícones sociais do footer: Instagram estilo stroke, Facebook/TikTok estilo
  filled — inconsistência visual
- Ícone de e-mail usava `users` (amontoado de pessoas) no footer e contato
- Ícone de mapa usava `pin` (gota) quando o contexto pedia o símbolo de mapa
- Ícone de "reservar horário" usava `clock` — mais adequado `calendar`
- Ícone "sparkle" desenhado como asterisco simples — substituído por versão
  dupla (grande + pequeno) no mesmo path
- `battery` sem as linhas internas de nível de carga
- Inconsistência de tamanho: alguns 16, outros 18, outros 22 sem padrão claro

### Padrão adotado
- 1 única família: Lucide-style (stroke 2px · linecap round · linejoin round)
- Exceção controlada para ícones de marca (WhatsApp, IG, FB, TT) em paths
  oficiais filled, mesmo container visual
- Tamanhos: 14 (inline), 16 (hero trust / small CTAs), 18 (botões · bio), 20
  (locate/contact), 22 (diffs), 24 (service cards), 30 (WA float)
- Cor: herdada de `currentColor` + acento em `var(--brand)`
- Classe automática `icon icon--{nome}` para CSS targeting

### Imagens mapeadas
Ver `/app/memory/imagens-por-contexto.md` para catálogo completo.

---

---

## ✅ Fase 2.5.1 — Fechamento visual (JAN 2026)

### Entregas
1. **Hero com 5 slides garantidos hoje** — padding sintético em `home.php`
   (só entra se o banco retornar < 5); cai automaticamente no pool premium
   `hero_slide_image()` (Apple · Xiaomi · OPPO · QCY · Assistência). Solução
   econômica aprovada como temporária até o admin nativo assumir (Fase 4).
2. **Badge de promoção premium/comercial** — pílula verde sólida,
   texto preto "Promoção", ícone `tag` 12px, sem neon/borda/CAPS/blur.
3. **Opção A aprovada** para imagens: premium/coerentes, não-oficiais.
4. **Hero full-bleed 100% preservado** · backend/admin/SQL intactos.

### Arquivos alterados
- `app/Views/public/home.php` — `$syntheticSlides` + loop padding até 5
- `app/Views/partials/public/card-promotion.php` — "Promo" → "Promoção" · ícone 12px
- `assets/css/public.css` — regra `.promo-card__tag` reescrita (verde sólido · preto · sem borda/blur)

---

## ✅ Sub-rodada 3C — Gestão de Reservas/Leads no admin (JAN 2026)

### Entregas
1. **Módulo Reservas** (`/admin/leads`) com listagem, filtro por status, paginação 20/página.
2. **Detalhe da reserva** (`/admin/leads/{id}`) com os 12 campos + botão WhatsApp direto.
3. **Update de status server-side** (whitelist: novo · em_atendimento · concluido · cancelado) com CSRF.
4. **Dashboard refletindo o módulo**: cards "Reservas novas", "Reservas hoje", "Reservas totais" viraram links clicáveis; `leads_today` calculado direto da tabela `lead_reservations`.
5. **Sidebar**: item Reservas ativo (era placeholder `is-disabled` da Fase 3A).

### Arquivos (8)
**Criados**
- `app/Controllers/Admin/LeadsController.php` · 100 linhas (index/show/updateStatus)
- `app/Views/admin/leads/index.php` · listagem + filtros + paginação
- `app/Views/admin/leads/show.php` · detalhe + form de status + botão WA

**Alterados**
- `app/routes.php` · +3 rotas admin
- `app/Views/partials/admin/sidebar.php` · Reservas ativo
- `app/Controllers/Admin/DashboardController.php` · +1 query `leads_today`
- `app/Views/admin/dashboard.php` · cards linkados · roadmap atualizado
- `assets/css/admin.css` · +2 tags (em_atendimento, concluido) · bloco "Leads · 3C"

### Fora de escopo (economia aprovada)
- Sem busca textual, filtros avançados, exportação, charts, automação externa, WhatsApp Business API, CRUDs 3B.
- Campo `admin_note` existe no schema mas não exposto (futuro, se pedido).

### Preservado
- Front público, hero full-bleed, `database/schema.sql`, `database/seed.sql`, rotas públicas, todos os helpers/overlays/imagens.

---

## ✅ Rodada cirúrgica · ajustes visuais (JAN 2026)

### Entregas
1. **Botão `.btn--ghost` light theme** — 4 estados (default, hover, focus-visible, active) + regra específica dentro do hero para contraste sobre imagem.
2. **Depoimentos filtrados** — `rating >= 5 AND source IN ('google','instagram','facebook','tiktok')`. Schema não tocado.
3. **Ícone de mapa no footer alinhado** — novo `.footer-address` com flex `align-items: flex-start` + `margin-top: 3px` no svg.
4. **Faixa de marcas** — marquee CSS-only com 10 marcas (Apple · Samsung · Xiaomi · Motorola · OPPO · Realme · JBL · QCY · Asus · Lenovo); mask-image lateral; pausa no hover; `prefers-reduced-motion` suportado.
5. **Link "Painel"** no `footer-bottom` · discreto (opacity .55, 11px, cinza) · verde marca no hover · `rel="nofollow"`.

### Arquivos (5)
**Criado**: `app/Views/partials/public/brands-strip.php`
**Alterados**: `app/Views/public/home.php` · `app/Views/partials/public/footer.php` · `app/Models/Testimonial.php` · `assets/css/public.css`

### Preservado
- Hero full-bleed · 5 slides · badge "Promoção" · admin 3C · schema · seed · rotas públicas.

---

## Próximas ações (prioridade)
1. **Fase 3C primeiro** — ROI via leads (captura + fluxo WhatsApp otimizado)
2. **Fase 3B depois** — Edição de conteúdo via painel admin (assume os slides sintéticos nativamente)
3. **Cliente confirmar**: endereço oficial + telefone WhatsApp definitivos
4. **Cliente subir fotos reais**: bancada, loja, equipe, produtos em estoque

## Backlog / Futuro
- P1: Fase 3 (form de reserva → grava em DB + abre WhatsApp) + listagem admin
- P1: Fase 4 (CRUDs completos de serviços/produtos/promos/depoimentos/slides)
- P2: Upload de imagens via admin com redimensionamento automático
- P2: Gerador automático de Open Graph por página
- P2: Analytics interno com dashboards
- P3: Export CSV de leads
- P3: Integração WhatsApp Business API para agendamento automatizado
