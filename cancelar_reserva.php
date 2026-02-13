<?php
require_once "./conexion.php";

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$id = $data['id'] ?? null;

if (!$id) {
    echo json_encode(['ok' => false, 'error' => 'ID inválido']);
    exit;
}

try {
    $sql = "DELETE FROM Reserva WHERE id_reserva = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);

    echo json_encode(['ok' => true]);
    exit;

} catch (PDOException $e) {
    echo json_encode([
        'ok' => false,
        'error' => 'Error SQL'
    ]);
    exit;
}
