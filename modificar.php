<?php
require "./conexion.php";
session_start();

/* Seguridad: si no hay admin logueado */
if (!isset($_SESSION["adminFogon"])) {
    header("Location: administrador.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $id_producto = $_POST["id_producto"] ?? null; // <--- importante
    $nombre = trim($_POST["nombre"] ?? "");
    $descripcion = trim($_POST["descripcion"] ?? "");
    $precio = $_POST["precio"] ?? "";
    $imagen = $_FILES["imagen"] ?? null;

    if (!$id_producto) {
        header("Location: administrador.php?errorM=Producto no seleccionado");
        exit;
    }

    // ---- OBTENER DATOS ACTUALES ----
    $stmt = $pdo->prepare("SELECT imagen FROM Producto WHERE id_producto = ?");
    $stmt->execute([$id_producto]);
    $producto = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$producto) {
        header("Location: administrador.php?errorM=Producto no encontrado");
        exit;
    }

    $nombreImagen = $producto['imagen']; // valor actual por defecto

    // ---- IMAGEN ----
    if ($imagen && $imagen["error"] !== UPLOAD_ERR_NO_FILE) {
        if ($imagen["error"] !== UPLOAD_ERR_OK) {
            header("Location: administrador.php?errorM=Error al subir imagen");
            exit;
        }

        $tmp = $imagen["tmp_name"];

        // Validar MIME real
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $tmp);
        finfo_close($finfo);

        $mimePermitidos = ["image/jpeg", "image/png", "image/webp"];
        if (!in_array($mime, $mimePermitidos)) {
            header("Location: administrador.php?errorM=Tipo de imagen no válido");
            exit;
        }

        $ext = match ($mime) {
            "image/jpeg" => "jpg",
            "image/png"  => "png",
            "image/webp" => "webp",
            default => "jpg"
        };

        $nombreImagen = uniqid("prod_", true) . "." . $ext;
        $carpeta = __DIR__ . "/Img/";
        if (!is_dir($carpeta)) mkdir($carpeta, 0755, true);
        $destino = $carpeta . $nombreImagen;

        if (!move_uploaded_file($tmp, $destino)) {
            header("Location: administrador.php?errorM=Error al guardar imagen");
            exit;
        }

        // borrar imagen anterior si no es default
        if ($producto['imagen'] !== "default.jpg" && file_exists($carpeta . $producto['imagen'])) {
            unlink($carpeta . $producto['imagen']);
        }
    }

    // ---- CONSTRUCCIÓN DINÁMICA DEL UPDATE ----
    $campos = [];
    $valores = [];

    if ($nombre !== "") { $campos[] = "nombre = ?"; $valores[] = $nombre; }
    if ($descripcion !== "") { $campos[] = "descripcion = ?"; $valores[] = $descripcion; }
    if ($precio !== "") { $campos[] = "precio = ?"; $valores[] = $precio; }

    // si se subió imagen nueva o se mantiene la actual
    if ($imagen && $imagen["error"] !== UPLOAD_ERR_NO_FILE) {
        $campos[] = "imagen = ?";
        $valores[] = $nombreImagen;
    }

    if (count($campos) === 0) {
        header("Location: administrador.php?errorM=No hay campos para modificar");
        exit;
    }

    $valores[] = $id_producto; // para WHERE

    $sql = "UPDATE Producto SET " . implode(", ", $campos) . " WHERE id_producto = ?";
    $stmt = $pdo->prepare($sql);

    try {
        $stmt->execute($valores);
        header("Location: administrador.php?okM=Producto modificado correctamente");
        exit;
    } catch (PDOException $e) {
        header("Location: administrador.php?errorM=Error SQL: " . urlencode($e->getMessage()));
        exit;
    }
}