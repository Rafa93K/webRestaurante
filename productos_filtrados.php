<?php
require "./conexion.php";
session_start();

header("Content-Type: application/json; charset=utf-8");

if (!isset($_SESSION["adminFogon"])) {
    echo json_encode([]);
    exit;
}

$tipo = $_GET["tipo"] ?? "";
$subtipo = $_GET["subtipo"] ?? "";

if ($tipo === "" || $subtipo === "") {
    echo json_encode([]);
    exit;
}

try {
    $sql = "SELECT id_producto, nombre 
            FROM Producto 
            WHERE tipo = ? AND subtipo = ?
            ORDER BY nombre ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$tipo, $subtipo]);

    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    exit;

} catch (PDOException $e) {
    echo json_encode([]);
    exit;
}