-- ==============================================================================
-- Multi Cell Assistência Técnica — seed.sql
-- Dados iniciais mínimos. Sem credenciais reais.
--
-- O install.php substitui o admin seed por um usuário com senha hasheada via
-- password_hash() em PHP. Este arquivo mantém apenas settings e unidade como
-- fallback para importação manual via phpMyAdmin.
-- ==============================================================================

SET NAMES utf8mb4;

-- Admin seed placeholder (hash de 'ChangeMe123!'; o install.php regera em tempo
-- real para evitar hash estático em produção — mantido aqui apenas p/ fallback).
INSERT IGNORE INTO `admins` (`name`, `email`, `password_hash`, `role`, `is_active`, `must_change_password`)
VALUES ('Administrador', 'admin@multicell.local',
        '$2y$12$9Y8PqXJt8p2QK3eQk9Z4y.O2K5R1uS3aL8vV9mC0rN7bYkWxT6hMe',
        'admin', 1, 1);

-- Settings padrão
INSERT IGNORE INTO `settings` (`setting_key`, `setting_value`, `setting_type`, `setting_group`) VALUES
('site_name',                 'Multi Cell Assistência Técnica',            'text',  'general'),
('tagline',                   'Reparo rápido, peças originais e garantia real.', 'text', 'general'),
('whatsapp_number',           '5500000000000',                             'text',  'contact'),
('whatsapp_message_template', 'Olá! Vim pelo site da Multi Cell e gostaria de um atendimento.', 'text', 'contact'),
('phone',                     '(00) 0000-0000',                            'text',  'contact'),
('email',                     'contato@multicell.local',                   'text',  'contact'),
('instagram_url',             '',                                          'text',  'social'),
('facebook_url',              '',                                          'text',  'social'),
('tiktok_url',                '',                                          'text',  'social'),
('google_maps_url',           'https://maps.google.com/?q=Av.+Sen.+Filinto+M%C3%BCller+V%C3%A1rzea+Grande+MT', 'text', 'social'),
('google_reviews_url',        '',                                          'text',  'social'),
('default_theme',             'dark',                                      'text',  'appearance'),
('seo_title',                 'Multi Cell Assistência Técnica • Várzea Grande/MT', 'text', 'seo'),
('seo_description',           'Assistência técnica de celulares em Várzea Grande/MT. Troca de tela, bateria, placa, acessórios e garantia real.', 'text', 'seo'),
('logo_path',                 '',                                          'image', 'appearance'),
('logo_dark_path',            '',                                          'image', 'appearance'),
('hours_default',             'Seg a Sex 8h–18h · Sáb 8h–12h',             'text',  'contact'),
('avg_rating',                '4.9',                                       'text',  'general'),
('total_reviews',             '120',                                       'number','general');

-- Unidade única
INSERT IGNORE INTO `branches` (`name`,`address`,`city`,`state`,`zip_code`,`phone`,`whatsapp`,`hours_text`,`map_embed_url`,`is_active`,`sort_order`)
VALUES (
    'Multi Cell Assistência Técnica',
    'Av. Sen. Filinto Müller - Parque do Sabiá',
    'Várzea Grande',
    'MT',
    '78152-112',
    '',
    '',
    'Seg a Sex 8h–18h · Sáb 8h–12h',
    'https://www.google.com/maps?q=Av.%20Sen.%20Filinto%20M%C3%BCller%20V%C3%A1rzea%20Grande%20MT&output=embed',
    1,
    0
);

-- Slides demo
INSERT IGNORE INTO `hero_slides` (`title`,`subtitle`,`image_path`,`cta_label`,`cta_url`,`sort_order`,`is_active`) VALUES
('Seu celular em boas mãos','Reparo rápido, peças originais e garantia real.', '', 'Chamar no WhatsApp', '/go/whatsapp?src=home_hero_slide1', 1, 1),
('Troca de tela em até 1h','Orçamento gratuito e garantia de 90 dias.', '', 'Reservar atendimento', '/reservar', 2, 1),
('Acessórios premium','Capas, películas, carregadores e fones selecionados.', '', 'Ver catálogo', '/produtos', 3, 1);

-- Serviços demo
INSERT IGNORE INTO `services` (`name`,`slug`,`icon`,`short_description`,`description`,`price_from`,`is_featured`,`is_active`,`sort_order`) VALUES
('Troca de Tela',         'troca-de-tela',         'screen',   'Original ou OEM premium com garantia.',       'Troca de tela com peças selecionadas e testadas. Garantia de 90 dias.', 199.00, 1, 1, 1),
('Troca de Bateria',      'troca-de-bateria',      'battery',  'Bateria nova com capacidade real.',           'Substituição de bateria com teste de capacidade antes da entrega.',       129.00, 1, 1, 2),
('Conector de Carga',     'conector-de-carga',     'plug',     'Seu aparelho carregando como novo.',          'Limpeza ou substituição do conector de carga com teste completo.',         89.00, 1, 1, 3),
('Reparo de Placa',       'reparo-de-placa',       'chip',     'Diagnóstico microeletrônico preciso.',        'Reparos de placa-mãe com equipamento especializado e técnico sênior.',    249.00, 0, 1, 4),
('Software e Desbloqueio','software-e-desbloqueio','code',     'Sistema travado? A gente resolve.',           'Recuperação de sistema, atualização e remoção segura de travas.',          79.00, 0, 1, 5),
('Limpeza Interna',       'limpeza-interna',       'sparkle',  'Mais desempenho e vida útil.',                'Limpeza interna com dessoldagem de poeira e revisão de componentes.',      69.00, 0, 1, 6);

-- Produtos demo
INSERT IGNORE INTO `products` (`name`,`slug`,`category`,`short_description`,`price`,`promo_price`,`in_stock`,`is_featured`,`is_active`,`sort_order`) VALUES
('Capa Anti-impacto Premium',    'capa-anti-impacto-premium',    'Capas',          'Proteção militar com acabamento fosco.',      89.90,  69.90, 1, 1, 1, 1),
('Película 3D Cerâmica',         'pelicula-3d-ceramica',         'Películas',      'Flexível, anti-risco e anti-impacto.',        59.90,  39.90, 1, 1, 1, 2),
('Carregador Rápido 30W',        'carregador-rapido-30w',        'Carregadores',   'Carga rápida com proteção inteligente.',     129.90,  99.90, 1, 1, 1, 3),
('Fone Bluetooth Pro',           'fone-bluetooth-pro',           'Fones',          'Cancelamento de ruído e até 30h de bateria.',229.90, 179.90, 1, 1, 1, 4),
('Cabo USB-C Reforçado',         'cabo-usb-c-reforcado',         'Cabos',          'Malha em nylon e carga rápida até 100W.',     49.90,  34.90, 1, 0, 1, 5),
('Carregador Veicular 20W',      'carregador-veicular-20w',      'Carregadores',   'Duas saídas com proteção contra surto.',      79.90,  59.90, 1, 0, 1, 6),
('Smartwatch Multi Fit',         'smartwatch-multi-fit',         'Wearables',      'Monitor de saúde e notificações.',           349.90, 279.90, 1, 0, 1, 7),
('Suporte Magnético Veicular',   'suporte-magnetico-veicular',   'Acessórios',     'Fixação forte e instalação em segundos.',     69.90,  49.90, 1, 0, 1, 8);

-- Promoções demo
INSERT IGNORE INTO `promotions` (`title`,`slug`,`description`,`old_price`,`new_price`,`starts_at`,`ends_at`,`cta_label`,`cta_url`,`is_active`,`sort_order`) VALUES
('Combo Proteção Total',   'combo-protecao-total',    'Capa anti-impacto + película 3D cerâmica com instalação inclusa.', 149.80, 99.90,  CURDATE(), DATE_ADD(CURDATE(), INTERVAL 14 DAY), 'Aproveitar no WhatsApp', '/go/whatsapp?src=promo_combo', 1, 1),
('Troca de Tela + Bateria','troca-de-tela-mais-bateria','Dois serviços, um preço. Garantia de 90 dias em ambos.',          349.00, 279.00, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 21 DAY), 'Falar no WhatsApp',      '/go/whatsapp?src=promo_tela_bateria', 1, 2),
('Carregador Rápido 30W',  'promo-carregador-rapido', 'Carregador rápido com proteção inteligente. Estoque limitado.',     129.90, 79.90,  CURDATE(), DATE_ADD(CURDATE(), INTERVAL 7 DAY),  'Quero esse',             '/go/whatsapp?src=promo_carregador', 1, 3);

-- Depoimentos demo
INSERT IGNORE INTO `testimonials` (`author_name`,`rating`,`content`,`source`,`is_active`,`sort_order`) VALUES
('Ana Paula',   5, 'Atendimento excelente e troca de tela em menos de uma hora. Recomendo demais!', 'google',   1, 1),
('Ricardo M.',  5, 'Preço justo e o celular voltou melhor do que comprei. Equipe muito atenciosa.',  'whatsapp', 1, 2),
('Juliana S.',  5, 'Fui super bem atendida. Explicaram tudo com calma e cumpriram o prazo.',        'google',   1, 3),
('Diego A.',    5, 'Loja de confiança em Várzea Grande. Peças originais e garantia real.',         'whatsapp', 1, 4);

-- About blocks demo
INSERT IGNORE INTO `about_blocks` (`title`,`content`,`layout`,`sort_order`,`is_active`) VALUES
('Nossa história',  'A Multi Cell nasceu para oferecer assistência técnica séria em Várzea Grande. Unimos peças selecionadas, técnicos experientes e atendimento humano.', 'image-left',  1, 1),
('Garantia real',   'Todo serviço e produto tem garantia registrada. Transparência do orçamento ao pós-venda.', 'image-right', 2, 1),
('Equipe certificada', 'Nosso time recebe treinamento contínuo em novos modelos e técnicas de micro-solda para cuidar do seu aparelho.', 'image-left',  3, 1);
