# Multi Cell · Varredura pública (leve) — JAN 2026

Varredura realizada em Instagram + Facebook + Google com foco em extrair apenas
informação pública verificável. Sem inferência / sem invenção.

---

## ✅ FATOS PÚBLICOS ENCONTRADOS

### Identidade
- **Nome fantasia**: Multi Cell / Multicell Assistência Técnica
- **Atividade**: assistência técnica de celulares + venda de aparelhos e
  acessórios (varejo + serviço)
- **Região de atuação**: Várzea Grande/MT (com entregas mencionadas em
  Cuiabá/MT em alguns anúncios)

### Registro empresarial (fonte: econodata/cnpj.biz)
- **Razão social**: JACKSON M. R. LEITE LTDA
- **CNPJ**: 40.395.065/0001-06
- **Classificação**: Microempresa (ME) — Setor de Serviços
- **Atividade principal (CNAE)**: S-9512-6/00 — Reparação e manutenção de
  equipamentos de comunicação

### Redes sociais mencionadas
- Instagram: `@multicell_assistencia_`
- Facebook: perfil público com anúncios da loja

---

## ⚠️ CONFLITOS DE INFORMAÇÃO (confirmar com o cliente)

Três fontes públicas apresentam dados divergentes. **TODOS precisam de
confirmação direta com o cliente antes de serem fixados no site.**

### Endereço (3 versões diferentes)

| Fonte | Endereço | CEP |
|---|---|---|
| Instagram público (citação do problem statement) | Rua Nossa Senhora do Carmo, nº 46, Centro-Norte, Várzea Grande/MT | — |
| Facebook público (citação do problem statement) | Avenida Couto Magalhães, 1429, Várzea Grande/MT | — |
| Registro CNPJ (econodata) | Avenida Filinto Muller (Lot S Mateus), 12, Quadra 50 Lote 12 - São Matheus, Várzea Grande/MT | 78.152-141 |

> **Observação**: O endereço do CNPJ pode ser apenas o registrado na Receita,
> enquanto a loja física opera em outro ponto. Normal em pequenos negócios.
> → **Confirmar com o cliente qual é o endereço da loja física atendendo o
> público.**

### Telefone (2 versões)

| Fonte | Número |
|---|---|
| Instagram público (citação do problem statement) | (65) 99338-1930 |
| cnpj.biz | (65) 3624-**** ou (65) 99292-**** (números truncados) |

> → **Confirmar com o cliente o WhatsApp oficial.**

### Seed atual (valor de fallback no banco)
O `seed.sql` atualmente tem:
- Endereço: "Av. Sen. Filinto Müller - Parque do Sabiá, Várzea Grande/MT, 78152-112"
- Telefone: "(00) 0000-0000" (placeholder)
- WhatsApp: "5500000000000" (placeholder)

Esses valores são substituídos pelo admin após o `install.php`. Recomenda-se
que o cliente já ajuste na primeira configuração.

---

## 💡 SUGESTÕES DE COPY baseadas nos fatos públicos

Use quando tiver confirmação dos dados. Tudo abaixo é coerente com anúncios
públicos encontrados nos perfis.

### Hero · eyebrow / trust bar
- "Assistência técnica especializada — Várzea Grande e Cuiabá"
- Garantia mencionada em anúncios: "garantia em serviços e peças"
- "Aparelhos novos e seminovos"
- "Entregas em Cuiabá e Várzea Grande"

### Serviços recorrentes nos perfis
- Troca de tela (diversas marcas)
- Troca de bateria
- Limpeza / manutenção
- Reparos de placa
- Desbloqueio / software
- → Já todos cobertos no seed atual

### Produtos recorrentes nos anúncios
- Capas e películas
- Fones (Bluetooth · com destaque para marca QCY)
- Smartwatches
- Carregadores rápidos
- Aparelhos seminovos
- → Já cobertos no seed + categorias

### Linguagem da marca observada
- Tom direto, próximo, comercial-técnico
- Uso de "a gente resolve", "chame no zap", "venha conferir"
- Foco em preço + prazo + garantia

### Diferenciais percebidos
- Entrega em Cuiabá/Várzea Grande (logística)
- Atendimento humano (pelo tom dos posts)
- Variedade: serviço + produto no mesmo lugar

---

## 📝 SEÇÕES DO SITE QUE PODEM FICAR MAIS COMPLETAS

Recomendações (todas dependem de confirmação do cliente):

1. **Trust bar do hero** → adicionar "Entregas em VG e Cuiabá" quando
   confirmado
2. **Cards de serviços** → mencionar "para diversas marcas" / "Apple · Samsung
   · Xiaomi · Motorola"
3. **Sobre** → incluir bloco "Atendemos também em Cuiabá" se confirmado
4. **Produtos** → destacar QCY como marca frequentemente vendida, se
   confirmado (usar o mesmo copy "trabalhamos com acessórios de diversas
   marcas" — não sugerir parceria oficial)
5. **Footer** → adicionar frase de entrega regional quando confirmada

---

## 🚫 O QUE NÃO FOI FEITO (e por quê)

- **Não baixamos imagens do Instagram CDN** → decisão explícita do cliente
  (usar stock premium Unsplash + placeholders nomeados para upload posterior
  de fotos reais via admin)
- **Não afirmamos parceria oficial com marcas** → QCY, Apple, Xiaomi, Samsung,
  OPPO só aparecem como "trabalhamos com acessórios e assistência para
  diversas marcas" quando confirmado
- **Não fixamos endereço/telefone no seed** → aguardando confirmação do cliente
- **Não buscamos avaliações específicas** → `avg_rating=4.9` e
  `total_reviews=120` são valores padrão do seed; serão ajustáveis pelo admin

---

## Ações pendentes do cliente
- [ ] Confirmar endereço oficial da loja física (3 versões divergentes)
- [ ] Confirmar WhatsApp oficial (99338-1930 vs 99292-****)
- [ ] Autorizar uso da marca "QCY" como destaque em produtos
- [ ] Subir fotos reais da loja/bancada/equipe via admin (Fase 4)
- [ ] Definir URL exata do Instagram, Facebook e Google Maps Place
