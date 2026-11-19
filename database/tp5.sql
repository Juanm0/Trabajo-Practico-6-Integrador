SET NAMES utf8mb4;

CREATE TABLE roles (
    id_rol INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(30) NOT NULL UNIQUE,
    descripcion TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO roles (nombre, descripcion) VALUES
('Admin', 'Administrador del sistema con acceso total'),
('Veterinario', 'Veterinario con acceso a consultas y mascotas'),
('Usuario común', 'Cliente/usuario estándar con acceso limitado');

CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    telefono VARCHAR(20),
    id_rol INT NOT NULL DEFAULT 3,
    activo TINYINT(1) NOT NULL DEFAULT 1,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_usuarios_roles FOREIGN KEY (id_rol) REFERENCES roles(id_rol)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO usuarios (nombre, apellido, email, password, telefono, id_rol) VALUES
('Admin', 'Sistema', 'admin@petcare.com', '$2y$10$.O0eGxYHwfXzn2E8cIUz..zL5KhlDlM/es/FzlBX.ZuD9xdxZ.AcW', '1111111111', 1);

CREATE TABLE clientes (
    id_cliente INT AUTO_INCREMENT PRIMARY KEY,
    dni VARCHAR(15) NOT NULL UNIQUE,
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(50) NOT NULL,
    telefono VARCHAR(20)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO clientes (dni, nombre, apellido, telefono) VALUES
('30111222', 'Juan', 'Pérez', '3411234567'),
('28555444', 'Ana', 'Gómez', '3417654321'),
('32876543', 'Martin', 'Rodriguez', '1123456789'),
('35678901', 'Sofia', 'Martinez', '1145678901'),
('29456789', 'Galo', 'Profernandez', '1167890123');

CREATE TABLE veterinarios (
    id_veterinario INT AUTO_INCREMENT PRIMARY KEY,
    dni VARCHAR(15) NOT NULL UNIQUE,
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(50) NOT NULL,
    numero_registro VARCHAR(20) NOT NULL UNIQUE,
    telefono VARCHAR(20),
    especialidad VARCHAR(50)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO veterinarios (dni, nombre, apellido, numero_registro, telefono, especialidad) VALUES
('30123456', 'Carlos', 'Gomez', 'VR-1001', '1122233344', 'Cardiología'),
('28987654', 'Maria', 'Lopez', 'VR-1002', '1144455566', 'Dermatología'),
('32123456', 'Pablo', 'Ramirez', 'VR-1003', '1166677788', 'Cirugía');

CREATE TABLE mascotas (
    id_mascota INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    especie VARCHAR(30) NOT NULL,
    raza VARCHAR(50),
    fecha_nacimiento DATE,
    sexo VARCHAR(10),
    id_cliente INT NOT NULL,
    CONSTRAINT fk_mascotas_clientes FOREIGN KEY (id_cliente) REFERENCES clientes(id_cliente) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO mascotas (nombre, especie, raza, fecha_nacimiento, sexo, id_cliente) VALUES
('Rocky', 'Perro', 'Ovejero alemán', '2022-04-15', 'Macho', 1),
('Luna', 'Gato', 'Siames', '2021-09-20', 'Hembra', 2),
('Iron', 'Perro', 'Callejero', '2020-10-31', 'Macho', 3),
('Toby', 'Perro', 'Caniche', '2023-01-10', 'Macho', 4);

CREATE TABLE consultas (
    id_consulta INT AUTO_INCREMENT PRIMARY KEY,
    fecha_consulta DATETIME NOT NULL,
    motivo VARCHAR(100) NOT NULL,
    diagnostico TEXT,
    precio DECIMAL(10,2) NOT NULL,
    id_mascota INT NOT NULL,
    id_veterinario INT NOT NULL,
    CONSTRAINT fk_consultas_mascotas FOREIGN KEY (id_mascota) REFERENCES mascotas(id_mascota) ON DELETE CASCADE,
    CONSTRAINT fk_consultas_veterinarios FOREIGN KEY (id_veterinario) REFERENCES veterinarios(id_veterinario) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO consultas (fecha_consulta, motivo, diagnostico, precio, id_mascota, id_veterinario) VALUES
('2026-11-10 09:30:00', 'Control general', 'Mascota en buen estado de salud', 15000.00, 1, 1),
('2026-11-11 11:00:00', 'Picazón en la piel', 'Dermatitis leve', 20000.00, 2, 2),
('2026-11-12 16:15:00', 'Dolor en una pata', 'Lesión muscular', 18000.00, 3, 3);

CREATE INDEX idx_consultas_fecha ON consultas(fecha_consulta);

CREATE VIEW vista_mascotas_completa AS
SELECT m.*, c.nombre AS cliente_nombre, c.apellido AS cliente_apellido, c.dni AS cliente_dni, c.telefono AS cliente_telefono
FROM mascotas m
JOIN clientes c ON m.id_cliente = c.id_cliente;

CREATE VIEW vista_consultas_completa AS
SELECT c.*,
       m.nombre AS mascota_nombre, m.especie, m.raza, m.sexo,
       cli.nombre AS cliente_nombre, cli.apellido AS cliente_apellido, cli.telefono AS cliente_telefono,
       v.nombre AS vet_nombre, v.apellido AS vet_apellido, v.especialidad, v.numero_registro
FROM consultas c
JOIN mascotas m ON c.id_mascota = m.id_mascota
JOIN clientes cli ON m.id_cliente = cli.id_cliente
JOIN veterinarios v ON c.id_veterinario = v.id_veterinario;
