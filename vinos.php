<?php
require "./conexion.php";

// Secciones de vinos (orden fijo)
$opcionesVinos = ["tinto","blanco","rosado","espumoso"];


// Traemos todos los productos tipo vinos
$sql = "SELECT id_producto, nombre, descripcion, precio, subtipo, imagen
        FROM Producto
        WHERE tipo='vinos'
        ORDER BY subtipo ASC, nombre ASC";
$stmt = $pdo->query($sql);
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Agrupar por subtipo
$porSubtipo = [];
foreach ($productos as $p) {
    $st = $p["subtipo"] ?? "Otros";
    if (!isset($porSubtipo[$st])) $porSubtipo[$st] = [];
    $porSubtipo[$st][] = $p;
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Vinos - Fogón</title>
  <link rel="stylesheet" href="Includes/estilo.css" />
</head>

<body>
<header>
  <div class="logo">
    <a href="index.php"><img src="Img/fogone_clarito.png" /></a>
  </div>
  <nav>
    <a href="carta.php">Carta</a>
    <a href="vinos.php">Vinos</a>
    <a href="especial.php" id="linkEspecials">Especiales</a>
  </nav>
</header>

<main>
  <h1>Nuestra Selección de Vinos</h1>

  <?php foreach($opcionesVinos as $subtipo): ?>
    <h2 class="seccion-titulo"><?= ucfirst($subtipo) ?></h2>

    <div class="grid-platos">

      <?php if (!isset($porSubtipo[$subtipo]) || count($porSubtipo[$subtipo]) === 0): ?>
        <p style="opacity:.7;">No hay vinos en esta sección.</p>
      <?php else: ?>
        <?php foreach($porSubtipo[$subtipo] as $p): ?>
          <div class="plato-card">
            <?php
              // imagen por defecto si viene vacía
              $img = $p["imagen"];
              if ($img === null || trim($img) === "") $img = "default.jpg";
            ?>
            <img src="Img/vinos/<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($p["nombre"]) ?>" />

            <h3><?= htmlspecialchars($p["nombre"]) ?></h3>
            <p><?= htmlspecialchars($p["descripcion"]) ?></p>
            <p class="precio"><?= number_format((float)$p["precio"], 2, ",", ".") ?>€</p>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>

    </div>
  <?php endforeach; ?>

</main>

   <?php include 'Includes/footer.php'; ?>

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