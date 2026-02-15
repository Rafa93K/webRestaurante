<!doctype html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Fogón - Restaurante</title>
    <link rel="stylesheet" href="Includes/estilo.css" />
  </head>
  <body>
    <?php include 'Includes/header.php'; ?>
    <main>
      <div class="galeria">
        <div class="foto f1"><img src="Img/puerta.jpg" /></div>
        <div class="foto f2"><img src="Img/patio2.jpg" /></div>
        <div class="foto f3"><img src="Img/patio.jpg" /></div>
        <div class="foto f4"><img src="Img/barraF.jpg" /></div>
      </div>
      <?php 
        $visitas = $_COOKIE['visitas'] ?? 0;
        $visitas++;
        setcookie("visitas", $visitas, time()+3600*24*365, "/");
        if($visitas==1){
          echo "<p class='cookie'>Has visitado esta página por primera vez</p>";
        }else{
          echo "<p class='cookie'>Has visitado esta página $visitas veces</p>";
        }
        
      ?>
    </main>
    <?php include 'Includes/footer.php'; ?>
  </body>
</html>
