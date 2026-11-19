<?php
/**
 * Funciones CRUD para todas las entidades - PetCare
 */
require_once __DIR__ . '/db.php';

// ==================== CLIENTES ====================

function getAllClientes($search = '') {
    $db = getDB();
    $sql = "SELECT * FROM clientes WHERE 1=1";
    $params = [];
    if ($search) {
        $sql .= " AND (nombre LIKE ? OR apellido LIKE ? OR dni LIKE ?)";
        $params = ["%$search%", "%$search%", "%$search%"];
    }
    $sql .= " ORDER BY apellido, nombre";
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function getClienteById($id) {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM clientes WHERE id_cliente = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function createCliente($dni, $nombre, $apellido, $telefono) {
    $db = getDB();
    $stmt = $db->prepare("INSERT INTO clientes (dni, nombre, apellido, telefono) VALUES (?, ?, ?, ?)");
    return $stmt->execute([$dni, $nombre, $apellido, $telefono]);
}

function updateCliente($id, $dni, $nombre, $apellido, $telefono) {
    $db = getDB();
    $stmt = $db->prepare("UPDATE clientes SET dni = ?, nombre = ?, apellido = ?, telefono = ? WHERE id_cliente = ?");
    return $stmt->execute([$dni, $nombre, $apellido, $telefono, $id]);
}

function deleteCliente($id) {
    $db = getDB();
    $stmt = $db->prepare("DELETE FROM clientes WHERE id_cliente = ?");
    return $stmt->execute([$id]);
}

// ==================== MASCOTAS ====================

function getAllMascotas($search = '') {
    $db = getDB();
    $sql = "SELECT m.*, c.nombre as cliente_nombre, c.apellido as cliente_apellido 
            FROM mascotas m 
            JOIN clientes c ON m.id_cliente = c.id_cliente 
            WHERE 1=1";
    $params = [];
    if ($search) {
        $sql .= " AND (m.nombre LIKE ? OR m.especie LIKE ? OR m.raza LIKE ? OR c.nombre LIKE ? OR c.apellido LIKE ?)";
        $params = ["%$search%", "%$search%", "%$search%", "%$search%", "%$search%"];
    }
    $sql .= " ORDER BY m.nombre";
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function getMascotasByCliente($idCliente) {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM mascotas WHERE id_cliente = ? ORDER BY nombre");
    $stmt->execute([$idCliente]);
    return $stmt->fetchAll();
}

function getMascotaById($id) {
    $db = getDB();
    $stmt = $db->prepare("SELECT m.*, c.nombre as cliente_nombre, c.apellido as cliente_apellido 
                          FROM mascotas m JOIN clientes c ON m.id_cliente = c.id_cliente 
                          WHERE m.id_mascota = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function createMascota($nombre, $especie, $raza, $fechaNacimiento, $sexo, $idCliente) {
    $db = getDB();
    $stmt = $db->prepare("INSERT INTO mascotas (nombre, especie, raza, fecha_nacimiento, sexo, id_cliente) VALUES (?, ?, ?, ?, ?, ?)");
    return $stmt->execute([$nombre, $especie, $raza, $fechaNacimiento, $sexo, $idCliente]);
}

function updateMascota($id, $nombre, $especie, $raza, $fechaNacimiento, $sexo, $idCliente) {
    $db = getDB();
    $stmt = $db->prepare("UPDATE mascotas SET nombre = ?, especie = ?, raza = ?, fecha_nacimiento = ?, sexo = ?, id_cliente = ? WHERE id_mascota = ?");
    return $stmt->execute([$nombre, $especie, $raza, $fechaNacimiento, $sexo, $idCliente, $id]);
}

function deleteMascota($id) {
    $db = getDB();
    $stmt = $db->prepare("DELETE FROM mascotas WHERE id_mascota = ?");
    return $stmt->execute([$id]);
}

// ==================== VETERINARIOS ====================

function getAllVeterinarios($search = '') {
    $db = getDB();
    $sql = "SELECT * FROM veterinarios WHERE 1=1";
    $params = [];
    if ($search) {
        $sql .= " AND (nombre LIKE ? OR apellido LIKE ? OR especialidad LIKE ? OR numero_registro LIKE ?)";
        $params = ["%$search%", "%$search%", "%$search%", "%$search%"];
    }
    $sql .= " ORDER BY apellido, nombre";
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function getVeterinarioById($id) {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM veterinarios WHERE id_veterinario = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function createVeterinario($dni, $nombre, $apellido, $numeroRegistro, $telefono, $especialidad) {
    $db = getDB();
    $stmt = $db->prepare("INSERT INTO veterinarios (dni, nombre, apellido, numero_registro, telefono, especialidad) VALUES (?, ?, ?, ?, ?, ?)");
    return $stmt->execute([$dni, $nombre, $apellido, $numeroRegistro, $telefono, $especialidad]);
}

function updateVeterinario($id, $dni, $nombre, $apellido, $numeroRegistro, $telefono, $especialidad) {
    $db = getDB();
    $stmt = $db->prepare("UPDATE veterinarios SET dni = ?, nombre = ?, apellido = ?, numero_registro = ?, telefono = ?, especialidad = ? WHERE id_veterinario = ?");
    return $stmt->execute([$dni, $nombre, $apellido, $numeroRegistro, $telefono, $especialidad, $id]);
}

function deleteVeterinario($id) {
    $db = getDB();
    $stmt = $db->prepare("DELETE FROM veterinarios WHERE id_veterinario = ?");
    return $stmt->execute([$id]);
}

// ==================== CONSULTAS ====================

function getAllConsultas($search = '') {
    $db = getDB();
    $sql = "SELECT c.*, m.nombre as mascota_nombre, m.especie, m.raza, 
                   cli.nombre as cliente_nombre, cli.apellido as cliente_apellido,
                   v.nombre as vet_nombre, v.apellido as vet_apellido, v.especialidad
            FROM consultas c
            JOIN mascotas m ON c.id_mascota = m.id_mascota
            JOIN clientes cli ON m.id_cliente = cli.id_cliente
            JOIN veterinarios v ON c.id_veterinario = v.id_veterinario
            WHERE 1=1";
    $params = [];
    if ($search) {
        $sql .= " AND (c.motivo LIKE ? OR c.diagnostico LIKE ? OR m.nombre LIKE ? OR cli.nombre LIKE ? OR cli.apellido LIKE ?)";
        $params = ["%$search%", "%$search%", "%$search%", "%$search%", "%$search%"];
    }
    $sql .= " ORDER BY c.fecha_consulta DESC";
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function getConsultasByMascota($idMascota) {
    $db = getDB();
    $stmt = $db->prepare("SELECT c.*, v.nombre as vet_nombre, v.apellido as vet_apellido, v.especialidad
                          FROM consultas c
                          JOIN veterinarios v ON c.id_veterinario = v.id_veterinario
                          WHERE c.id_mascota = ?
                          ORDER BY c.fecha_consulta DESC");
    $stmt->execute([$idMascota]);
    return $stmt->fetchAll();
}

function getConsultaById($id) {
    $db = getDB();
    $stmt = $db->prepare("SELECT c.*, m.nombre as mascota_nombre, m.especie, m.raza, 
                                   cli.nombre as cliente_nombre, cli.apellido as cliente_apellido,
                                   v.nombre as vet_nombre, v.apellido as vet_apellido, v.especialidad
                            FROM consultas c
                            JOIN mascotas m ON c.id_mascota = m.id_mascota
                            JOIN clientes cli ON m.id_cliente = cli.id_cliente
                            JOIN veterinarios v ON c.id_veterinario = v.id_veterinario
                            WHERE c.id_consulta = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function createConsulta($fechaConsulta, $motivo, $diagnostico, $precio, $idMascota, $idVeterinario) {
    $db = getDB();
    $stmt = $db->prepare("INSERT INTO consultas (fecha_consulta, motivo, diagnostico, precio, id_mascota, id_veterinario) VALUES (?, ?, ?, ?, ?, ?)");
    return $stmt->execute([$fechaConsulta, $motivo, $diagnostico, $precio, $idMascota, $idVeterinario]);
}

function updateConsulta($id, $fechaConsulta, $motivo, $diagnostico, $precio, $idMascota, $idVeterinario) {
    $db = getDB();
    $stmt = $db->prepare("UPDATE consultas SET fecha_consulta = ?, motivo = ?, diagnostico = ?, precio = ?, id_mascota = ?, id_veterinario = ? WHERE id_consulta = ?");
    return $stmt->execute([$fechaConsulta, $motivo, $diagnostico, $precio, $idMascota, $idVeterinario, $id]);
}

function deleteConsulta($id) {
    $db = getDB();
    $stmt = $db->prepare("DELETE FROM consultas WHERE id_consulta = ?");
    return $stmt->execute([$id]);
}

// ==================== USUARIOS ====================

function getAllUsuarios() {
    $db = getDB();
    $stmt = $db->query("SELECT u.*, r.nombre as rol_nombre FROM usuarios u LEFT JOIN roles r ON u.id_rol = r.id_rol ORDER BY u.nombre");
    return $stmt->fetchAll();
}

function getUsuarioById($id) {
    $db = getDB();
    $stmt = $db->prepare("SELECT u.*, r.nombre as rol_nombre FROM usuarios u LEFT JOIN roles r ON u.id_rol = r.id_rol WHERE u.id_usuario = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function createUsuario($nombre, $apellido, $email, $password, $telefono, $idRol) {
    $db = getDB();
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $db->prepare("INSERT INTO usuarios (nombre, apellido, email, password, telefono, id_rol) VALUES (?, ?, ?, ?, ?, ?)");
    return $stmt->execute([$nombre, $apellido, $email, $hash, $telefono, $idRol]);
}

function updateUsuario($id, $nombre, $apellido, $email, $telefono, $idRol, $activo) {
    $db = getDB();
    $stmt = $db->prepare("UPDATE usuarios SET nombre = ?, apellido = ?, email = ?, telefono = ?, id_rol = ?, activo = ? WHERE id_usuario = ?");
    return $stmt->execute([$nombre, $apellido, $email, $telefono, $idRol, $activo, $id]);
}

function deleteUsuario($id) {
    $db = getDB();
    $stmt = $db->prepare("DELETE FROM usuarios WHERE id_usuario = ?");
    return $stmt->execute([$id]);
}
