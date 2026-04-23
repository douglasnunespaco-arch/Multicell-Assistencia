-- ==============================================================================
-- Multi Cell · seed_c1-home-extras.sql
-- AD­ITIVO E IDEMPOTENTE · NÃO altera schema.sql nem seed.sql originais.
--
-- Aplique via phpMyAdmin no Hostinger para ter:
--   - 6 promoções ativas visíveis na home (eram 3)
--   - 6 depoimentos ativos visíveis na home (eram 4)
--   - 4 bio_links iniciais no /links
--
-- Todas as instruções usam INSERT IGNORE (não sobrescreve IDs existentes).
-- Pode rodar múltiplas vezes sem efeito colateral.
-- ==============================================================================

-- Tabela bio_links (criada na Fase C1) -------------------------------------------
CREATE TABLE IF NOT EXISTS bio_links (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  title VARCHAR(120) NOT NULL,
  url VARCHAR(500) NOT NULL,
  icon VARCHAR(40) DEFAULT NULL,
  sort_order INT NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  open_new_tab TINYINT(1) NOT NULL DEFAULT 1,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_active_sort (is_active, sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO bio_links (id, title, url, icon, sort_order, is_active, open_new_tab) VALUES
  (1, 'Falar no WhatsApp', 'https://wa.me/5565000000000', 'whatsapp', 10, 1, 1),
  (2, 'Ver produtos',      '/produtos',                   'package',  20, 1, 0),
  (3, 'Promoções ativas',  '/promocoes',                  'tag',      30, 1, 0),
  (4, 'Como chegar',       '/go/map',                     'map',      40, 1, 1);

-- +3 Promoções (completa 6 na home)
-- image_path fica NULL de propósito: o sistema resolve via promo_image($slug) -> Unsplash por slug
INSERT IGNORE INTO promotions (id, title, slug, image_path, description, old_price, new_price, starts_at, ends_at, cta_label, cta_url, is_active, sort_order) VALUES
(4, 'Película 3D + Instalação Grátis', 'pelicula-3d-instalacao-gratis', NULL,
 'Película 3D cerâmica premium com instalação grátis na loja. Garantia de encaixe e resistência.',
 79.90, 39.90, NULL, NULL, 'Quero essa', NULL, 1, 40),
(5, 'Bateria Original + Troca no Mesmo Dia', 'bateria-original-troca-mesmo-dia', NULL,
 'Bateria original com diagnóstico completo e troca concluída em até 2h. 90 dias de garantia.',
 199.00, 149.00, NULL, NULL, 'Aproveitar', NULL, 1, 50),
(6, 'Kit Fone Bluetooth + Carregador USB-C', 'kit-fone-bluetooth-carregador', NULL,
 'Fone TWS com cancelamento + carregador 20W USB-C. Combo ideal para quem quer praticidade.',
 299.00, 199.00, NULL, NULL, 'Pegar combo', NULL, 1, 60);

-- +2 Depoimentos inicialmente (IDs 5 e 6)
INSERT IGNORE INTO testimonials (id, author_name, author_photo, rating, content, source, is_active, sort_order) VALUES
(5, 'Marina F.', NULL, 5, 'Orçamento na hora, atendimento educado e meu iPhone voltou novo em 3 horas. Nota 10!', 'google',   1, 50),
(6, 'Carlos E.', NULL, 5, 'Pesquisei em várias lojas e a Multi Cell foi a mais justa no preço. Explicaram cada etapa.', 'instagram', 1, 60);

-- +2 Depoimentos extras (IDs 7 e 8) para completar 6 visíveis na home
-- (o Testimonial::active() filtra por source IN ('google','instagram','facebook','tiktok'),
--  por isso precisamos garantir diversidade de sources sociais — whatsapp é excluído no filtro)
INSERT IGNORE INTO testimonials (id, author_name, author_photo, rating, content, source, is_active, sort_order) VALUES
(7, 'Luana R.',   NULL, 5, 'Atendimento rápido e transparente. Troquei a tela do meu Samsung e fiquei super satisfeita.', 'facebook', 1, 70),
(8, 'Eduardo P.', NULL, 5, 'Indicaram exatamente o que precisava, sem empurrar nada. Serviço impecável e preço justo.',    'tiktok',   1, 80);
