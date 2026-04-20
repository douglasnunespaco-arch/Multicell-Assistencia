# Multi Cell · Catálogo de imagens por contexto

Todas as imagens foram mapeadas por **slug / categoria / ordem** via funções
helper em `app/Views/partials/public/icons.php` (seção "Helpers de imagem").

> **Estratégia**: URLs estáveis do CDN Unsplash (stock premium coerente com
> tema de assistência técnica / celulares / acessórios). Todas nomeadas e
> organizadas para que o admin substitua por fotos reais da loja depois.

## Como o admin substitui
Em cada tabela (`hero_slides`, `services`, `products`, `promotions`,
`about_blocks`), o campo `image_path` tem prioridade. Se estiver preenchido
(via upload no admin), ele é usado. Se estiver vazio, o helper injeta uma
imagem stock coerente automaticamente.

---

## HERO SLIDES (por `sort_order`)

| Ordem | Título seed | Imagem |
|---|---|---|
| 1 | Seu celular em boas mãos | bancada técnica com celular |
| 2 | Troca de tela em até 1h | mãos reparando tela de celular |
| 3 | Acessórios premium | fones/acessórios premium |
| 4+ | fallback | componentes eletrônicos |

## SERVIÇOS (por `slug`)

| Slug | Imagem |
|---|---|
| troca-de-tela | celular moderno close-up |
| troca-de-bateria | reparo de celular |
| conector-de-carga | cabo/tech |
| reparo-de-placa | placa de circuito PCB |
| software-e-desbloqueio | teclado/diagnóstico |
| limpeza-interna | componentes/chip |
| outros (por ícone `chip`, `battery`, etc) | mapa de fallback por ícone |

## PRODUTOS (por `slug` → `category` → fallback)

| Slug / Categoria | Imagem |
|---|---|
| capa-anti-impacto-premium / Capas | capa escura premium |
| pelicula-3d-ceramica / Películas | película cerâmica |
| carregador-rapido-30w / Carregadores | carregador branco |
| fone-bluetooth-pro / Fones | fone Bluetooth over-ear |
| cabo-usb-c-reforcado / Cabos | cabo tech |
| carregador-veicular-20w / Carregadores | carregador veicular |
| smartwatch-multi-fit / Wearables | smartwatch |
| suporte-magnetico-veicular / Acessórios | acessório magnético |
| fallback por categoria | genérico premium |

## PROMOÇÕES (por `slug`)

| Slug | Imagem |
|---|---|
| combo-protecao-total | capa premium |
| troca-de-tela-mais-bateria | reparo em andamento |
| promo-carregador-rapido | carregador |
| fallback | acessórios premium |

## ABOUT BLOCKS (por ordem)

| Ordem | Bloco seed | Imagem |
|---|---|---|
| 1 | Nossa história | bancada/loja |
| 2 | Garantia real | reparo close-up |
| 3 | Equipe certificada | mãos técnicas |
| 4+ | fallback | bancada técnica |

---

## ⚠️ Placeholders recomendados para substituição pelo cliente
Ordem de prioridade:
1. **Fotos REAIS da bancada técnica da loja** (hero slide 1 + about block 1)
2. **Fotos REAIS da equipe trabalhando** (about block 3)
3. **Foto REAL da fachada/loja** (footer / localização)
4. **Produtos em estoque real** (cards de produto)

Até que essas fotos reais estejam no admin, os placeholders premium garantem
visual coerente e profissional.

---

## URLs estáticas usadas (Unsplash · /photos/)
Todas são URLs com `?auto=format&fit=crop&w={size}&q={quality}` — otimização
automática WebP + tamanho adaptativo.

Formato: `https://images.unsplash.com/photo-{ID}?auto=format&fit=crop&w=1920&q=78`

### Photo IDs mapeados
- `1574944985070-8f3ebc6b79d2` · `1512499617640-c74ae3a79d37` · `1606820854416-439b3305ff39`
- `1526406915894-7bcd65f60845` · `1580910051074-3eb694886505` · `1616348436168-de43ad0db179`
- `1591337676887-a217a6970a8a` · `1583394838336-acd977736f90` · `1511707171634-5f897ff02aa9`
- `1546435770-a3e426bf472b` · `1593305841991-05c297ba4575` · `1617043786394-f977fa12eddf`
- `1611532736597-de2d4265fba3` · `1572044162444-ad60f128bdea` · `1496181133206-80ce9b88a853`
- `1558618666-fcd25c85cd64` · `1567721913486-6585f069b332` · `1621330396173-e41b1cafd17f`
- `1610945415295-d9bbf067e59c` · `1519558260268-cde7e03a0152` · `1550009158-9ebf69173e03`

Todas validadas com HTTP 200 antes de fixar no código.
