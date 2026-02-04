<?php
require "./conexion.php";
session_start();

/* Seguridad: si no hay admin logueado */
if (!isset($_SESSION["adminFogon"])) {
    header("Location: administrador.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_producto = $_POST["id_producto"] ?? null;

    if (!$id_producto) {
        header("Location: administrador.php?errorE=Producto no seleccionado");
        exit;
    }

    // Obtener la imagen actual para borrarla
    $stmt = $pdo->prepare("SELECT imagen FROM Producto WHERE id_producto = ?");
    $stmt->execute([$id_producto]);
    $producto = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$producto) {
        header("Location: administrador.php?errorE=Producto no encontrado");
        exit;
    }

    try {
        // Borrar producto de la BD
        $stmt = $pdo->prepare("DELETE FROM Producto WHERE id_producto = ?");
        $stmt->execute([$id_producto]);

        // Borrar imagen del servidor si no es default
        $carpeta = __DIR__ . "/Img/";
        if ($producto['imagen'] !== "default.jpg" && file_exists($carpeta . $producto['imagen'])) {
            unlink($carpeta . $producto['imagen']);
        }

        header("Location: administrador.php?okE=Producto eliminado correctamente");
        exit;

    } catch (PDOException $e) {
        header("Location: administrador.php?errorE=Error SQL: " . urlencode($e->getMessage()));
        exit;
    }
}