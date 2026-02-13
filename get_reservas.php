<?php
require_once "./conexion.php";

header('Content-Type: application/json');

$fecha = $_GET['fecha'] ?? '';

if ($fecha === '') {
    echo json_encode([]);
    exit;
}

$sql = "SELECT id_reserva,hora, nombre_cliente, personas, telefono, mensaje
        FROM Reserva
        WHERE fecha = ?
        ORDER BY hora ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute([$fecha]);

$reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($reservas);
?>