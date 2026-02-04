<?php
require "./conexion.php";
session_start();

header("Content-Type: application/json; charset=utf-8");

if (!isset($_SESSION["adminFogon"])) {
    echo json_encode(["ok"=>false, "error"=>"No autorizado"]);
    exit;
}

$valor = isset($_POST["valor"]) ? (int)$_POST["valor"] : 0;
$valor = ($valor === 1) ? 1 : 0;

try{
    $sql = "UPDATE CONFIG SET valor=? WHERE clave='mostrar_specials'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$valor]);

    echo json_encode(["ok"=>true]);
    exit;

}catch(PDOException $e){
    echo json_encode(["ok"=>false, "error"=>"SQL"]);
    exit;
}