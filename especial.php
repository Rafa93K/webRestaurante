<?php
require "./conexion.php";

/* 1) Comprobar si specials están activados */
$stmt = $pdo->prepare("SELECT valor FROM CONFIG WHERE clave='mostrar_specials' LIMIT 1");
$stmt->execute();
$mostrar = (int)($stmt->fetchColumn() ?? 0);

/* 2) Si no se debe mostrar -> redirigir */
if ($mostrar !== 1) {
    header("Location: carta.php");
    exit;
}

/* 3) Si está activado -> cargar productos tipo especial */
$sql = "SELECT id_producto, nombre, descripcion, precio, imagen
        FROM Producto
        WHERE tipo='especial'
        ORDER BY nombre ASC";
$stmt = $pdo->query($sql);
$especiales = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Especiales - Fogón</title>
  <link rel="stylesheet" href="Includes/estilo.css" />
</head>

<body>
<header>
  <div class="logo">
    <a href="index.html"><img src="Img/fogone_clarito.png" /></a>
  </div>
  <nav>
    <a href="carta.php">Carta</a>
    <a href="vinos.php">Vinos</a>
    <a href="especial.php" id="linkEspecials">Especiales</a>
  </nav>
</header>

<main><br>
  <h1>Especiales del día</h1>
  <p class="descripcion-especial">
    Platos únicos del día, preparados por nuestro chef y disponibles solo por tiempo limitado.
  </p>

  <div class="grid-platos">
    <?php if (count($especiales) === 0): ?>
      <p style="opacity:.7;">Hoy no hay especiales disponibles.</p>
    <?php else: ?>
      <?php foreach($especiales as $p): ?>
        <?php
          $img = $p["imagen"];
          if ($img === null || trim($img) === "") $img = "default.jpg";
        ?>
        <div class="plato-card">
          <img src="Img/<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($p["nombre"]) ?>" />
          <h3><?= htmlspecialchars($p["nombre"]) ?></h3>
          <p><?= htmlspecialchars($p["descripcion"]) ?></p>
          <p class="precio"><?= number_format((float)$p["precio"], 2, ",", ".") ?>€</p>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</main>

<footer>
  <nav>
    <a href="#privacidad">Política de Privacidad</a>
    <a href="#cookies">Política de Cookies</a>
    <a href="#terminos">Términos y Condiciones</a>
    <a id="insta" href="https://www.instagram.com/fogonpya/">
      <img src="Img/insta_1.png" /> &copy;Gastro-Bar Fogón 2019
    </a>
  </nav>
</footer>

<!-- ocultar enlace especiales si config lo desactiva -->
<script>
document.addEventListener("DOMContentLoaded", async function () {
  const link = document.getElementById("linkEspecials");
  if (!link) return;

  try {
    const res = await fetch("config_specials.php");
    const data = await res.json();

    if (!data.mostrar) link.style.display = "none";
  } catch (e) {
    link.style.display = "none";
  }
});
</script>

</body>
</html>