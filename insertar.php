<?php
require "./conexion.php";
session_start();

/* Seguridad: si no hay admin logueado, fuera */
if (!isset($_SESSION["adminFogon"])) {
    header("Location: administrador.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nombre = $_POST["nombre"] ?? "";
    $descripcion = $_POST["descripcion"] ?? "";
    $precio = $_POST["precio"] ?? "";
    $tipo = $_POST["tipo"] ?? "";
    $subtipo = $_POST["subtipo"] ?? "";

    // imagen por defecto
    $nombreImagen = "default.jpg";

    // ---- VALIDACIONES BÁSICAS ----
    if ($nombre === "" || $descripcion === "" || $precio === "" || $tipo === "" || $subtipo === "") {
        header("Location: administrador.php?error=Campos incompletos");
        exit;
    }

    // ---- IMAGEN ----
    if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] !== UPLOAD_ERR_NO_FILE) {

        if ($_FILES["imagen"]["error"] !== UPLOAD_ERR_OK) {
            header("Location: administrador.php?error=imgerror");
            exit;
        }

        $tmp = $_FILES["imagen"]["tmp_name"];

        // Validar MIME REAL
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $tmp);
        finfo_close($finfo);

        $mimePermitidos = ["image/jpeg", "image/png", "image/webp"];
        if (!in_array($mime, $mimePermitidos)) {
            header("Location: administrador.php?error=imgmime");
            exit;
        }

        // extensión final según MIME
        $ext = match ($mime) {
            "image/jpeg" => "jpg",
            "image/png"  => "png",
            "image/webp" => "webp",
            default => "jpg"
        };

        // Nombre único seguro
        $nombreImagen = uniqid("prod_", true) . "." . $ext;

        // Carpeta destino
        $carpeta = __DIR__ . "/Img/$tipo/";
        if (!is_dir($carpeta)) {
            mkdir($carpeta, 0755, true);
        }

        $destino = $carpeta . $nombreImagen;

        if (!move_uploaded_file($tmp, $destino)) {
            header("Location: administrador.php?error=imgsubida");
            exit;
        }
    }

    // ---- INSERT BD ----
    try {
        $sql = "INSERT INTO Producto (nombre, descripcion, precio, tipo, subtipo, imagen)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nombre, $descripcion, $precio, $tipo, $subtipo, $nombreImagen]);

        header("Location: administrador.php?ok=Producto Registrado!");
        exit;

    } catch (PDOException $e) {
        die("Error SQL: " . $e->getMessage());
    }
}
