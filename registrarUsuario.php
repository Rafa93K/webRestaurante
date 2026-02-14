<?php
session_start();
require 'conexion.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombre     = trim($_POST['nombre'] ?? '');
    $telefono   = trim($_POST['telefono'] ?? '');
    $contrasena = $_POST['contrasena'] ?? '';

    // Validaciones básicas
    if ($nombre === '' || $telefono === '' || $contrasena === '') {
        $error = "Rellena todos los campos";
    } else {

        // ¿Teléfono ya registrado?
        $sql = "SELECT id_usuario FROM Usuario WHERE telefono = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$telefono]);

        if ($stmt->fetch()) {
            $error = "Este teléfono ya está registrado";
        } else {

            // Insertar usuario
            $hash = password_hash($contrasena, PASSWORD_DEFAULT);

            $sql = "INSERT INTO Usuario (nombre, telefono, contrasena)
                    VALUES (?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nombre, $telefono, $hash]);

            // Crear sesión
            $_SESSION['usuarioFogon'] = [
                'id_usuario' => $pdo->lastInsertId(),
                'nombre'     => $nombre,
                'telefono'   => $telefono
            ];

            header("Location: reservar.php");
            exit;
        }
    }
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registro | Fogón</title>
  <link rel="stylesheet" href="Includes/estilo.css">
</head>
<body align="center">
<header>
      <div class="logo">
        <a href="index.php"><img src="Img/fogone_clarito.png" /></a>
      </div>
      <nav>
        <nav>
          <a href="carta.php">Carta</a>
          <a href="reservar.php">Reservar</a>
          <a href="conocenos.html">Conócenos</a>
        </nav>
      </nav>
    </header>
<main class="reserva-container">
  <h1>Crear cuenta</h1>

  <?php if ($error): ?>
    <p class="error"><?= htmlspecialchars($error) ?></p>
  <?php endif; ?>

  <form method="post" class="reserva-form">

    <label>Nombre completo</label> <br>
    <input type="text" name="nombre" required> <br>

    <label>Teléfono</label> <br>
    <input type="tel" name="telefono" required> <br>

    <label>Contraseña</label> <br>
    <input type="password" name="contrasena" required> <br> <br>

    <button type="submit">Registrarse</button>
  </form>

  <p>
    ¿Ya tienes cuenta?
    <a href="loginUsuario.php">Inicia sesión</a>
  </p>
</main>
<footer>
      <nav>
        <a href="privacidad.html">Política de Privacidad</a>
        <a href="cookies.html">Política de Cookies</a>
        <a href="terminos.html">Términos y Condiciones</a>
      </nav>
      <a id="insta" href="https://www.instagram.com/fogonpya/">
        <img src="Img/insta_1.png" /> &copy;Gastro-Bar Fogón
      </a>
    </footer>
</body>
</html>
