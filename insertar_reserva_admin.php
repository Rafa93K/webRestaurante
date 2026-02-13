<?php
require_once "./conexion.php";

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$fecha    = $data['fecha']    ?? '';
$hora     = $data['hora']     ?? '';
$nombre   = $data['nombre']   ?? '';
$personas = $data['personas'] ?? '';
$telefono = $data['telefono'] ?? '';
$mensaje  = $data['mensaje']  ?? '';

if ($fecha === '' || $hora === '' || $nombre === '' || $personas === '' || $telefono === '') {
    echo json_encode(['ok' => false, 'error' => 'Datos incompletos']);
    exit;
}

try {
    $sql = "INSERT INTO Reserva (fecha, hora, nombre_cliente, personas, telefono, mensaje)
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $fecha,
        $hora,
        $nombre,
        $personas,
        $telefono,
        $mensaje
    ]);

    echo json_encode(['ok' => true]);
    exit;

} catch (PDOException $e) {
    echo json_encode([
        'ok' => false,
        'error' => 'Error SQL'
    ]);
    exit;
}
