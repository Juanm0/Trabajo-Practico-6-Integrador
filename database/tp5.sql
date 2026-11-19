DROP DATABASE IF EXISTS petcare;
CREATE DATABASE IF NOT EXISTS petcare;
USE petcare;

CREATE TABLE clientes (
    id_cliente INT AUTO_INCREMENT PRIMARY KEY,
    dni VARCHAR(15) NOT NULL UNIQUE,
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(50) NOT NULL,
    telefono VARCHAR(20)
);

INSERT INTO clientes (dni, nombre, apellido, telefono) VALUES
('30111222', 'Juan', 'Pérez', '3411234567'),
('28555444', 'Ana', 'Gómez', '3417654321');


CREATE TABLE veterinarios (
    id_veterinario INT AUTO_INCREMENT PRIMARY KEY,
    dni VARCHAR(15) NOT NULL UNIQUE,
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(50) NOT NULL,
    numero_registro VARCHAR(20) NOT NULL UNIQUE,
    telefono VARCHAR(20),
    especialidad VARCHAR(50)
);

CREATE TABLE mascotas (
    id_mascota INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    especie VARCHAR(30) NOT NULL,
    raza VARCHAR(50),
    fecha_nacimiento DATE,
    sexo VARCHAR(10),
    id_cliente INT NOT NULL,

    CONSTRAINT fk_mascotas_clientes
    FOREIGN KEY (id_cliente)
    REFERENCES clientes(id_cliente)
);
-- el motivo es por que no es lo mismo el diagnostico que el motivo por el cual llevaron al animal
CREATE TABLE consultas (
    id_consulta INT AUTO_INCREMENT PRIMARY KEY,
    fecha_consulta DATETIME NOT NULL,
    motivo VARCHAR(100) NOT NULL,
    diagnostico TEXT,
    precio DECIMAL(10,2) NOT NULL,
    id_mascota INT NOT NULL,
    id_veterinario INT NOT NULL,

    CONSTRAINT fk_consultas_mascotas
    FOREIGN KEY (id_mascota)
    REFERENCES mascotas(id_mascota),

    CONSTRAINT fk_consultas_veterinarios
    FOREIGN KEY (id_veterinario)
    REFERENCES veterinarios(id_veterinario)
);

INSERT INTO clientes (dni, nombre, apellido, telefono) VALUES
('32876543', 'Martin', 'Rodriguez', '1123456789'),
('35678901', 'Sofia', 'Martinez', '1145678901'),
('29456789', 'Galo', 'Profernandez', '1167890123');


INSERT INTO mascotas
(nombre, especie, raza, fecha_nacimiento, sexo, id_cliente)
VALUES
('Rocky', 'Perro', 'Ovejero aleman', '2022-04-15', 'Macho', 1),
('Luna', 'Gato', 'Siames', '2021-09-20', 'Hembra', 2),
('Iron', 'Perro', 'Callejero', '2020-10-31', 'macho', 3),
('Toby', 'Perro', 'Caniche', '2023-01-10', 'Macho', 4);


INSERT INTO veterinarios
(dni, nombre, apellido, numero_registro, telefono, especialidad)
VALUES
('30123456', 'Carlos', 'Gomez', 'VR-1001', '1122233344', 'Cardiologia'),
('28987654', 'Maria', 'Lopez', 'VR-1002', '1144455566', 'Dermatologia'),
('32123456', 'Pablo', 'Ramirez', 'VR-1003', '1166677788', 'Cirugia');


INSERT INTO consultas
(fecha_consulta, motivo, diagnostico, precio, id_mascota, id_veterinario)
VALUES
('2026-11-10 09:30:00', 'Control general', 'Mascota en buen estado de salud', 15000.00, 1, 1),
('2026-11-11 11:00:00', 'Picazon en la piel', 'Dermatitis leve', 20000.00, 2, 2),
('2026-11-12 16:15:00', 'Dolor en una pata', 'Lesion muscular', 18000.00, 3, 3);