CREATE DATABASE IF NOT EXISTS pinatas_mel
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE pinatas_mel;

CREATE TABLE usuarios_admin (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    usuario         VARCHAR(50)  NOT NULL UNIQUE,
    password_hash   VARCHAR(255) NOT NULL,
    nombre          VARCHAR(100) NOT NULL,
    activo          TINYINT(1)   NOT NULL DEFAULT 1,
    ultimo_login    TIMESTAMP    NULL,
    creado_en       TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE categorias (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    slug        VARCHAR(50)  NOT NULL UNIQUE,
    titulo      VARCHAR(100) NOT NULL,
    icono       VARCHAR(100) NOT NULL,
    banner      VARCHAR(100) NOT NULL DEFAULT 'franja.png',
    activa      TINYINT(1)   NOT NULL DEFAULT 1,
    creado_en   TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE productos (
    id                  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    categoria_id        INT UNSIGNED NOT NULL,
    slug                VARCHAR(80)  NOT NULL UNIQUE,
    nombre              VARCHAR(120) NOT NULL,
    imagen              VARCHAR(120) NOT NULL,
    precio              DECIMAL(10,2) NOT NULL,
    descripcion_corta   VARCHAR(255) NULL,
    capacidad           VARCHAR(255) NULL,
    rendimiento         VARCHAR(100) NULL,
    estructura          VARCHAR(255) NULL,
    decoracion          TEXT NULL,
    activo              TINYINT(1)   NOT NULL DEFAULT 1,
    creado_en           TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    actualizado_en      TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_producto_categoria
        FOREIGN KEY (categoria_id) REFERENCES categorias(id)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE INDEX idx_productos_categoria ON productos(categoria_id);

CREATE TABLE pedidos (
    id                INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    folio             VARCHAR(20)  NOT NULL UNIQUE,
    cliente_nombre    VARCHAR(120) NOT NULL,
    cliente_telefono  VARCHAR(20)  NOT NULL,
    cliente_direccion VARCHAR(255) NOT NULL,
    total             DECIMAL(10,2) NOT NULL DEFAULT 0,
    estado            ENUM('pendiente','pagado','enviado','entregado','cancelado')
                      NOT NULL DEFAULT 'pendiente',
    creado_en         TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE pedido_items (
    id               INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    pedido_id        INT UNSIGNED NOT NULL,
    producto_id      INT UNSIGNED NOT NULL,
    cantidad         INT UNSIGNED NOT NULL DEFAULT 1,
    precio_unitario  DECIMAL(10,2) NOT NULL,
    CONSTRAINT fk_item_pedido
        FOREIGN KEY (pedido_id) REFERENCES pedidos(id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_item_producto
        FOREIGN KEY (producto_id) REFERENCES productos(id)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE INDEX idx_items_pedido ON pedido_items(pedido_id);

CREATE TABLE pedidos_personalizados (
    id                   INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    cliente_nombre       VARCHAR(120) NOT NULL,
    cliente_telefono     VARCHAR(20)  NOT NULL,
    personaje            VARCHAR(150) NOT NULL,
    texto_personalizado  VARCHAR(150) NULL,
    tamano_cm            INT UNSIGNED NULL,
    tipo_apertura        ENUM('tradicional','cintas','ambas') NULL,
    imagen_referencia    VARCHAR(255) NULL,
    estado               ENUM('pendiente','en_proceso','terminado','cancelado')
                         NOT NULL DEFAULT 'pendiente',
    creado_en            TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- DATOS INICIALES DE PRUEBA
INSERT INTO usuarios_admin (usuario, password_hash, nombre) VALUES
('admin', '$2y$12$cwXLRw0jYbIG6BnTHVGZ.epRWQEV0aHM4RCUkBLVtei4cFifiMEdm', 'Administrador');


INSERT INTO categorias (slug, titulo, icono, banner) VALUES
('estrellas',    'Tradicionales de Estrellas', 'estrella.png',    'franja.png'),
('personajes',   'Personajes y Cultura Pop',   'bob.png',         'franja.png'),
('numeros',      'Números y Letras',           'numero1.png',     'franja.png'),
('animales',     'Animales',                   'caballo.png',     'franja.png'),
('geometricas',  'Formas Geométricas',         'espejo.png',      'franja.png'),
('comida',       'Objetos, Comida',            'hamburguesa.png', 'franja.png'),
('jalon',        'Piñatas de "Jalón"',         'stitch.png',      'franja.png'),
('humoristicas', 'Humorísticas',               'perro.png',       'franja.png');


INSERT INTO productos
(categoria_id, slug, nombre, imagen, precio, descripcion_corta, capacidad, rendimiento, estructura, decoracion)
VALUES
((SELECT id FROM categorias WHERE slug='comida'),
 'hamburguesa', 'Piñata de Hamburguesa', 'hamburguesa.png', 500.00,
 'Hamburguesa clásica con queso y lechuga',
 'Capacidad estimada: 3 a 5 kg de dulces surtidos (o juguetes pequeños y confeti).',
 '5 a 25 niños',
 'Estructura: Cartón de alta resistencia y papel periódico/cartonería tradicional.',
 'Decoración: Papel china y crepé picado/plisado en capas de colores vivos con detalles en foamy/goma EVA.'),

((SELECT id FROM categorias WHERE slug='comida'),
 'fresa', 'Piñata de Fresa', 'fresa.png', 450.00,
 'Diseñada con un rojo brillante de papel china',
 'Capacidad estimada: 2 a 4 kg de dulces surtidos.',
 '5 a 20 niños',
 'Estructura: Cartón y papel periódico.',
 'Decoración: Papel china rojo brillante con detalles verdes para las hojas.'),

((SELECT id FROM categorias WHERE slug='comida'),
 'buble-tea', 'Piñata de Buble Tea', 'buble-tea.png', 480.00,
 'El famoso vaso de bubble tea en versión piñata',
 'Capacidad estimada: 3 kg de dulces.',
 '5 a 20 niños',
 'Estructura: Cartón y papel periódico.',
 'Decoración: Representa el famoso vaso transparente con detalles en foamy.'),

((SELECT id FROM categorias WHERE slug='comida'),
 'palomitas', 'Piñata de Palomitas', 'palomitas.png', 400.00,
 'Dulce y divertida, en tonos rosa, blanco y amarillo',
 'Capacidad estimada: 2 a 4 kg de dulces.',
 '5 a 15 niños',
 'Estructura: Cartón y papel periódico.',
 'Decoración: Papel china en tonos rosa, blanco y amarillo.'),

((SELECT id FROM categorias WHERE slug='comida'),
 'pozole', 'Piñata de Pozole', 'pozole.png', 520.00,
 'El platillo mexicano favorito, ahora en piñata',
 'Capacidad estimada: 3 a 5 kg de dulces.',
 '5 a 25 niños',
 'Estructura: Cartón y papel periódico.',
 'Decoración: Detalles de lechuga, rábanos y maíz en papel china.'),

((SELECT id FROM categorias WHERE slug='comida'),
 'taco', 'Piñata de Taco', 'taco.png', 450.00,
 'Tortilla, carne y verduras en una piñata única',
 'Capacidad estimada: 2 a 4 kg de dulces.',
 '5 a 20 niños',
 'Estructura: Cartón y papel periódico.',
 'Decoración: Papel china simulando tortilla, carne y verduras.');