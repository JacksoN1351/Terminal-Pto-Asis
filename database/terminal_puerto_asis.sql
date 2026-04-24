-- 1. Crear la Base de Datos
CREATE DATABASE IF NOT EXISTS terminal_puerto_asis;
USE terminal_puerto_asis;

-- --------------------------------------------------------
-- 2. Tabla `usuarios` (Administradores del Panel)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol ENUM('admin', 'empleado') DEFAULT 'admin'
) ENGINE=InnoDB;

-- Admin por defecto (Pass: 123)
INSERT INTO usuarios (usuario, password, rol) VALUES 
('admin', '$2y$10$v0A0x2f64U/g1/iY7Q/CjOeB0VzE5oD8k7Z1iB.6E8Uu2S6i3S6.', 'admin');

-- --------------------------------------------------------
-- 3. Tabla `clientes` (Pasajeros)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS clientes (
    id_cliente INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    telefono VARCHAR(20),
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- --------------------------------------------------------
-- 4. Tabla `rutas` (Incluye la columna imagen para la galería)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS rutas (
    id_ruta INT AUTO_INCREMENT PRIMARY KEY,
    destino VARCHAR(100) NOT NULL,
    horario VARCHAR(20) NOT NULL,
    precio INT NOT NULL,
    vehiculo VARCHAR(50),
    empresa VARCHAR(50),
    cupos INT NOT NULL,
    imagen VARCHAR(255) DEFAULT 'logo.png'
) ENGINE=InnoDB;

INSERT INTO rutas (destino, horario, precio, vehiculo, empresa, cupos, imagen) VALUES 
('BOGOTÁ', '05:00 AM', 150000, 'Bus Heliconia', 'Cootransmayo', 32, 'fotobogota.jpg'),
('CALI', '08:00 AM', 133000, 'Bus Heliconia', 'Coomotor', 20, 'fotocali.jpg'),
('PASTO', '05:15 PM', 85000, 'Bus Heliconia', 'Transipiales', 15, 'fotopasto.jpg'),
('MOCOA', '07:15 AM', 30000, 'Aerovan Sprinter', 'Transipiales', 8, 'fotomocoa.jpeg'),
('NEIVA', '09:30 AM', 100000, 'Aerovan Sprinter', 'Coomotor', 12, 'fotoneiva.jpg'),
('PITALITO', '10:00 AM', 80000, 'Buseta', 'Cootranshuila', 18, 'fotopitalito.jpg'),
('FLORENCIA', '02:00 PM', 111000, 'Aerovan Sprinter', 'Cootransmayo', 5, 'fotoflorencia.jpg'),
('IBAGUE', '06:30 PM', 160000, 'Bus Heliconia', 'Coomotor', 25, 'fotoibague.jpg'),
('ORITO', '06:00 AM', 21000, 'Camioneta', 'Transipiales', 4, 'fotoorito.jpg'),
('SIBUNDOY', '06:30 PM', 65000, 'Buseta', 'Cootransmayo', 14, 'fotosibundoy.jpg'),
('VILLAGARZON', '11:00 AM', 25000, 'Aerovan sprinter', 'Transipiales', 9, 'fotovillagarzon.jpeg'),
('HORMIGA', '08:30 AM', 21000, 'Buseta', 'Transipiales', 10, 'fotohormiga.jpg');

-- --------------------------------------------------------
-- 5. Tabla `empresas` (Corregida con la columna LOGO)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS empresas (
    id_empresa INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    logo VARCHAR(255) NOT NULL,
    nit VARCHAR(20),
    telefono VARCHAR(20)
) ENGINE=InnoDB;

INSERT INTO empresas (nombre, logo) VALUES 
('COOTRANSMAYO', 'cootransmayo.jpg'),
('COOMOTOR', 'coomotor.jpg'),
('COOTRANSHUILA', 'cootranshuila.jpg'),
('TRANSIPIALES', 'transipiales.jpeg');

-- --------------------------------------------------------
-- 6. Tabla `vehiculos` (Corregida con la columna IMAGEN)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS vehiculos (
    id_vehiculo INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    imagen VARCHAR(255) NOT NULL,
    placa VARCHAR(10) UNIQUE
) ENGINE=InnoDB;

INSERT INTO vehiculos (nombre, imagen) VALUES 
('Bus Heliconia', 'busheliconia.jpeg'),
('Buseta', 'buseta.jpg'),
('Aerovan Sprinter', 'aerobanssprinter.jpeg'),
('Camioneta', 'camioneta.jpg'),
('Taxi Intermunicipal', 'taxi.jpg'),
('Gacela', 'gacela.png');

-- --------------------------------------------------------
-- 7. Tabla `reservas` (Cotizaciones)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS reservas (
    id_reserva INT AUTO_INCREMENT PRIMARY KEY,
    id_ruta INT,
    id_cliente INT,
    cantidad_pasajes INT NOT NULL,
    total_pago INT NOT NULL,
    estado ENUM('pendiente', 'confirmado', 'cancelado') DEFAULT 'pendiente',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_ruta) REFERENCES rutas(id_ruta) ON DELETE CASCADE,
    FOREIGN KEY (id_cliente) REFERENCES clientes(id_cliente) ON DELETE CASCADE
) ENGINE=InnoDB;

-- --------------------------------------------------------
-- 8. Contenido Dinámico (Nosotros, Contacto)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS contenido_paginas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    seccion VARCHAR(50) UNIQUE, 
    contenido TEXT NOT NULL,
    ultima_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;