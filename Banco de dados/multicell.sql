-- Multi Cell • SQL inicial compatível com MySQL/MariaDB
-- Importar este arquivo antes de testar o admin
SET NAMES utf8mb4;
SET time_zone = '+00:00';

CREATE TABLE IF NOT EXISTS admins (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(190) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME NULL,
    updated_at DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS settings (
    setting_key VARCHAR(120) PRIMARY KEY,
    setting_value TEXT NULL,
    updated_at DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS hero_slides (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(190) NOT NULL,
    subtitle TEXT NULL,
    cta_label VARCHAR(120) NULL,
    cta_url VARCHAR(255) NULL,
    image_path VARCHAR(255) NULL,
    sort_order INT NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME NULL,
    updated_at DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS services (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(190) NOT NULL,
    slug VARCHAR(190) NULL,
    summary TEXT NULL,
    details TEXT NULL,
    icon VARCHAR(20) NULL,
    featured TINYINT(1) NOT NULL DEFAULT 0,
    sort_order INT NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME NULL,
    updated_at DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS products (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(190) NOT NULL,
    category VARCHAR(100) NULL,
    summary TEXT NULL,
    price_label VARCHAR(120) NULL,
    condition_label VARCHAR(100) NULL,
    cta_label VARCHAR(120) NULL,
    cta_url VARCHAR(255) NULL,
    image_path VARCHAR(255) NULL,
    featured TINYINT(1) NOT NULL DEFAULT 0,
    sort_order INT NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME NULL,
    updated_at DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS promotions (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(190) NOT NULL,
    summary TEXT NULL,
    price_label VARCHAR(120) NULL,
    old_price_label VARCHAR(120) NULL,
    badge VARCHAR(100) NULL,
    valid_until DATE NULL,
    cta_label VARCHAR(120) NULL,
    cta_url VARCHAR(255) NULL,
    image_path VARCHAR(255) NULL,
    featured TINYINT(1) NOT NULL DEFAULT 0,
    sort_order INT NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME NULL,
    updated_at DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS testimonials (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    author_name VARCHAR(190) NOT NULL,
    content TEXT NULL,
    rating INT NOT NULL DEFAULT 5,
    source_label VARCHAR(120) NULL,
    sort_order INT NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME NULL,
    updated_at DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS about_blocks (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(190) NOT NULL,
    content TEXT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME NULL,
    updated_at DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS branches (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(190) NOT NULL,
    address TEXT NULL,
    business_hours VARCHAR(255) NULL,
    map_embed MEDIUMTEXT NULL,
    phone VARCHAR(50) NULL,
    whatsapp_number VARCHAR(50) NULL,
    sort_order INT NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME NULL,
    updated_at DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS lead_reservations (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(190) NOT NULL,
    customer_phone VARCHAR(50) NOT NULL,
    device_brand VARCHAR(120) NULL,
    device_model VARCHAR(120) NULL,
    service_type VARCHAR(120) NULL,
    desired_date DATE NULL,
    desired_period VARCHAR(50) NULL,
    issue_description TEXT NULL,
    notes TEXT NULL,
    status_label VARCHAR(50) NOT NULL DEFAULT 'novo',
    created_at DATETIME NULL,
    updated_at DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS analytics_events (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    event_name VARCHAR(120) NOT NULL,
    page_path VARCHAR(255) NULL,
    source_label VARCHAR(255) NULL,
    entity_type VARCHAR(100) NULL,
    entity_id INT NULL,
    entity_label VARCHAR(190) NULL,
    cta_label VARCHAR(190) NULL,
    unit_label VARCHAR(120) NULL,
    metadata_json JSON NULL,
    ip_address VARCHAR(45) NULL,
    user_agent VARCHAR(255) NULL,
    referer_url VARCHAR(255) NULL,
    created_at DATETIME NULL,
    INDEX idx_event_name (event_name),
    INDEX idx_page_path (page_path),
    INDEX idx_entity_type (entity_type),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

REPLACE INTO admins (id, name, email, password_hash, is_active, created_at, updated_at) VALUES
(1, 'Administrador Multi Cell', 'admin@multicell.local', '$2y$12$MSD705myaZWa5nW71kDanuWBzRkVfH9hlUjO8tYfkljdEmmehMNna', 1, NOW(), NOW());

REPLACE INTO settings (setting_key, setting_value, updated_at) VALUES
('brand_name', 'Multi Cell', NOW()),
('tagline', 'Assistência técnica especializada e ofertas reais para seu celular.', NOW()),
('hero_title', 'Assistência técnica especializada, promoções e atendimento rápido.', NOW()),
('hero_subtitle', 'Conserto, acessórios, aparelhos novos e seminovos com atendimento local em Várzea Grande.', NOW()),
('primary_cta_label', 'Chamar no WhatsApp', NOW()),
('primary_cta_url', 'https://wa.me/5500000000000', NOW()),
('secondary_cta_label', 'Reservar atendimento', NOW()),
('secondary_cta_url', '/reservar', NOW()),
('whatsapp_number', '5500000000000', NOW()),
('instagram_url', 'https://www.instagram.com/multicell_assistencia_/', NOW()),
('facebook_url', 'https://www.facebook.com/p/Multicell-Assistência-Técnica-em-Celulares-100083173240110/', NOW()),
('address', 'Av. Sen. Filinto Müller - Parque do Sabia, Várzea Grande - MT, 78152-112', NOW()),
('business_hours', 'Seg a Sex: 07:30 às 18:00 | Sáb: 07:30 às 12:00', NOW()),
('seo_title', 'Multi Cell | Assistência Técnica em Celulares', NOW()),
('seo_description', 'Assistência técnica, venda de celulares, acessórios e promoções em Várzea Grande.', NOW()),
('announcement_bar', 'Atendimento rápido, promoções e orçamento via WhatsApp.', NOW()),
('footer_note', 'Multi Cell • Assistência Técnica • Várzea Grande/MT', NOW());

INSERT INTO hero_slides (title, subtitle, cta_label, cta_url, image_path, sort_order, is_active, created_at, updated_at) VALUES
('Seu celular precisa de reparo rápido?', 'Troca de tela, bateria, conector e manutenção com atendimento ágil.', 'Falar no WhatsApp', 'https://wa.me/5500000000000', 'assets/img/logo.png', 1, 1, NOW(), NOW()),
('Produtos, acessórios e promoções', 'Aparelhos novos, seminovos e ofertas atualizadas pela loja.', 'Ver promoções', '/promocoes', 'assets/img/logo.png', 2, 1, NOW(), NOW());

INSERT INTO services (name, slug, summary, details, icon, featured, sort_order, is_active, created_at, updated_at) VALUES
('Troca de Tela', 'troca-de-tela', 'Substituição de telas quebradas com avaliação rápida.', 'Ideal para aparelhos com vidro quebrado, display falhando ou toque comprometido.', '📱', 1, 1, 1, NOW(), NOW()),
('Troca de Bateria', 'troca-de-bateria', 'Melhore autonomia e desempenho do aparelho.', 'Atendimento para falhas de carga, drenagem rápida e desligamentos.', '🔋', 1, 2, 1, NOW(), NOW()),
('Conector e Carga', 'conector-e-carga', 'Correção de falhas de carga, cabos e conectores.', 'Verificação de entrada de carga, limpeza técnica e substituição.', '🔌', 0, 3, 1, NOW(), NOW()),
('Software e Atualização', 'software-e-atualizacao', 'Formatação, atualização e otimização do sistema.', 'Para lentidão, travamentos, reinstalações e recuperação geral.', '⚙️', 0, 4, 1, NOW(), NOW());

INSERT INTO products (name, category, summary, price_label, condition_label, cta_label, cta_url, image_path, featured, sort_order, is_active, created_at, updated_at) VALUES
('Celulares Novos', 'Novo', 'Modelos disponíveis sob consulta.', 'Consulte', 'Novo', 'Consultar disponibilidade', 'https://wa.me/5500000000000', 'assets/img/logo.png', 1, 1, 1, NOW(), NOW()),
('Seminovos', 'Seminovo', 'Aparelhos revisados e selecionados.', 'Consulte', 'Seminovo', 'Consultar disponibilidade', 'https://wa.me/5500000000000', 'assets/img/logo.png', 1, 2, 1, NOW(), NOW()),
('Acessórios', 'Acessório', 'Capas, películas, carregadores e mais.', 'Consulte', 'Acessório', 'Consultar disponibilidade', 'https://wa.me/5500000000000', 'assets/img/logo.png', 1, 3, 1, NOW(), NOW());

INSERT INTO promotions (title, summary, price_label, old_price_label, badge, valid_until, cta_label, cta_url, image_path, featured, sort_order, is_active, created_at, updated_at) VALUES
('Promoções da Semana', 'Confira ofertas em acessórios e aparelhos.', 'Consulte', '', 'Oferta', NULL, 'Ver promoção', 'https://wa.me/5500000000000', 'assets/img/logo.png', 1, 1, 1, NOW(), NOW());

INSERT INTO testimonials (author_name, content, rating, source_label, sort_order, is_active, created_at, updated_at) VALUES
('Cliente Multi Cell', 'Atendimento rápido e objetivo.', 5, 'Loja', 1, 1, NOW(), NOW());

INSERT INTO about_blocks (title, content, sort_order, is_active, created_at, updated_at) VALUES
('Atendimento local forte', 'Presença local com foco em agilidade, confiança e atendimento prático.', 1, 1, NOW(), NOW()),
('Vendas e assistência', 'Estrutura pensada para captar quem quer conserto e quem quer comprar.', 2, 1, NOW(), NOW()),
('WhatsApp como conversão', 'O site encaminha rápido para atendimento com mensagem pronta.', 3, 1, NOW(), NOW());

INSERT INTO branches (name, address, business_hours, map_embed, phone, whatsapp_number, sort_order, is_active, created_at, updated_at) VALUES
('Loja principal', 'Av. Sen. Filinto Müller - Parque do Sabia, Várzea Grande - MT, 78152-112', 'Seg a Sex: 07:30 às 18:00 | Sáb: 07:30 às 12:00', '', '', '5500000000000', 1, 1, NOW(), NOW());
