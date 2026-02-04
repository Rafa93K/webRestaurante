<?php
require "./conexion.php";

header("Content-Type: application/json; charset=utf-8");

try{
    $stmt = $pdo->query("SELECT valor FROM CONFIG WHERE clave='mostrar_specials' LIMIT 1");
    $valor = (int)($stmt->fetchColumn() ?? 0);

    echo json_encode(["mostrar" => $valor]);
    exit;

}catch(PDOException $e){
    echo json_encode(["mostrar" => 0]);
    exit;
}