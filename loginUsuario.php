<?php
session_start();
require 'conexion.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $telefono   = trim($_POST['telefono'] ?? '');
    $contrasena = $_POST['contrasena'] ?? '';

    if ($telefono === '' || $contrasena === '') {
        $error = "Rellena todos los campos";
    } else {

        $sql = "SELECT id_usuario, nombre, telefono, contrasena
                FROM Usuario
                WHERE telefono = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$telefono]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$usuario || !password_verify($contrasena, $usuario['contrasena'])) {
            $error = "Teléfono o contraseña incorrectos";
        } else {

            // Crear sesión
            $_SESSION['usuarioFogon'] = [
                'id_usuario' => $usuario['id_usuario'],
                'nombre'     => $usuario['nombre'],
                'telefono'   => $usuario['telefono']
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
  <title>Iniciar sesión | Fogón</title>
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
  <h1>Iniciar sesión</h1>

  <?php if ($error): ?>
    <p class="error"><?= htmlspecialchars($error) ?></p>
  <?php endif; ?>

  <form method="post" class="reserva-form">

    <label>Teléfono</label> <br>
    <input type="tel" name="telefono" required> <br>

    <label>Contraseña</label><b></b> <br>
    <input type="password" name="contrasena" required><br> <br>


    <button type="submit">Entrar</button>
  </form>

  <p>
    ¿No tienes cuenta?
    <a href="registrarUsuario.php">Regístrate</a>
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
