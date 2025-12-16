-- Crear base de datos
CREATE DATABASE IF NOT EXISTS asistencia_db;
USE asistencia_db;

-- Tabla: cargo
CREATE TABLE cargo (
    id_cargo INT AUTO_INCREMENT PRIMARY KEY,
    nombre_cargo VARCHAR(100) NOT NULL,
    descripcion TEXT
);

-- Tabla: empleado
CREATE TABLE empleado (
    id_empleado INT AUTO_INCREMENT PRIMARY KEY,
    nombres VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    dni CHAR(8) NOT NULL UNIQUE,
    telefono VARCHAR(15),
    correo VARCHAR(100),
    id_cargo INT,
    fecha_ingreso DATE,
    estado ENUM('Activo', 'Inactivo') DEFAULT 'Activo',
    FOREIGN KEY (id_cargo) REFERENCES cargo(id_cargo)
);

-- Tabla: usuario
CREATE TABLE usuario (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    rol ENUM('Administrador', 'Empleado') DEFAULT 'Empleado',
    activo BOOLEAN DEFAULT TRUE,
    id_empleado INT,
    FOREIGN KEY (id_empleado) REFERENCES empleado(id_empleado)
);

-- Tabla: asistencia
CREATE TABLE asistencia (
    id_asistencia INT AUTO_INCREMENT PRIMARY KEY,
    id_empleado INT NOT NULL,
    fecha DATE NOT NULL,
    hora_entrada TIME,
    hora_salida TIME,
    estado ENUM('Presente', 'Tarde', 'Falta', 'Justificado') DEFAULT 'Presente',
    observaciones TEXT,
    FOREIGN KEY (id_empleado) REFERENCES empleado(id_empleado)
);

-- Datos de ejemplo
INSERT INTO cargo (nombre_cargo, descripcion) VALUES
('Administrador', 'Encargado del sistema y la gestión general'),
('Docente', 'Profesor responsable de clases'),
('Personal de apoyo', 'Asistente o personal administrativo');

INSERT INTO empleado (nombres, apellidos, dni, telefono, correo, id_cargo, fecha_ingreso)
VALUES
('Juan', 'Pérez López', '12345678', '987654321', 'juanperez@example.com', 1, '2024-01-10'),
('Ana', 'García Torres', '87654321', '912345678', 'anagarcia@example.com', 2, '2024-03-15');

INSERT INTO usuario (username, password_hash, rol, activo, id_empleado)
VALUES
('admin', SHA2('admin123', 256), 'Administrador', TRUE, 1),
('ana', SHA2('123456', 256), 'Empleado', TRUE, 2);

-- Tabla: huellas
CREATE TABLE huellas (
    id_huella INT AUTO_INCREMENT PRIMARY KEY,
    id_empleado INT NOT NULL,
    huella_template LONGBLOB NOT NULL, -- aquí se guarda la plantilla cifrada
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_empleado) REFERENCES empleado(id_empleado) ON DELETE CASCADE
);
