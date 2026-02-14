<?php

  session_start();
  require_once "./conexion.php";

  // Datos por defecto (vacíos)
  $nombreUsuario = '';
  $telefonoUsuario = '';

  // Si hay sesión, SIEMPRE rellenamos
  if (isset($_SESSION['usuarioFogon'])) {
      $nombreUsuario  = $_SESSION['usuarioFogon']['nombre'];
      $telefonoUsuario = $_SESSION['usuarioFogon']['telefono'];
  }
  // Si hay sesión, rellenamos
  if($_SERVER['REQUEST_METHOD']=="POST"){

      // Si el usuario NO está logueado, usar lo que envía el formulario
      if (!isset($_SESSION['usuarioFogon'])) {
          $nombreUsuario=$_POST['nombreUsuario']??'';
          $telefonoUsuario=$_POST['telefonoUsuario']??'';
      }

      $fechaUsuario=$_POST['fechaUsuario']??'';
      $horaUsuario=$_POST['horaUsuario']??'';
      $comensalesUsuario=$_POST['comensalesUsuario']??'';
      $mensajeUsuario=$_POST['mensajeUsuario']??'';

      /*validaciones de campo sino retornar mensaje de rellenar campos */
      if($nombreUsuario=='' || $telefonoUsuario=='' || $fechaUsuario=='' || $horaUsuario=='' || $comensalesUsuario==''){
        header("Location: reservar.php?error=Rellene todos los campos");
        exit;
      }
      /**
       * 
       * CONTROLAR CAPACIDAD
       */
      $capacidadMaxima = 50;

      //dia de la semana insertado por el usuario
      $diaSemana = date('N', strtotime($fechaUsuario)); 

      // Detectar turno según hora
        if ($horaUsuario >= '13:30' && $horaUsuario <= '15:30') {
            $inicioTurno = '13:30';
            $finTurno    = '15:30';
            $esMediodia = true;
            $esNoche = false;

        } elseif ($horaUsuario >= '20:30' && $horaUsuario <= '22:30') {
            $inicioTurno = '20:30';
            $finTurno    = '22:30';
            $esMediodia = false;
            $esNoche = true;

        } else {
            header("Location: reservar.php?error=Horario fuera de servicio");
            exit;
        }


        // Martes cerrado
        if ($diaSemana == 2) {
            header("Location: reservar.php?error=El martes estamos cerrados");
            exit;
        }

        //Lunes, Miércoles y Jueves → solo mediodía
        if (in_array($diaSemana, [1,3,4])) {
            if ($esNoche) {
                header("Location: reservar.php?error=Entre semana solo abrimos al mediodía");
                exit;
            }
        }


        // Domingo → solo mediodía
        if ($diaSemana == 7) {
            if ($esNoche) {
                header("Location: reservar.php?error=Domingo solo servicio de mediodía");
                exit;
            }
        }


      
      // ---- INSERT BD ----
       try {

        // 🔹 Comprobar aforo del turno
        $sqlCapacidad = "
            SELECT COALESCE(SUM(personas),0) as total
            FROM Reserva
            WHERE fecha = ?
            AND hora BETWEEN ? AND ?
        ";

        $stmtCapacidad = $pdo->prepare($sqlCapacidad);
        $stmtCapacidad->execute([$fechaUsuario, $inicioTurno, $finTurno]);

        $totalActual = $stmtCapacidad->fetch(PDO::FETCH_ASSOC)['total'];

        if (($totalActual + $comensalesUsuario) > $capacidadMaxima) {
            header("Location: reservar.php?error=No hay disponibilidad. Aforo completo!!");
            exit;
        }
          $sql = "INSERT INTO Reserva (nombre_cliente, telefono, fecha, hora, personas, mensaje)
                  VALUES (?, ?, ?, ?, ?, ?)";
          $stmt = $pdo->prepare($sql);
          $stmt->execute([$nombreUsuario, $telefonoUsuario, $fechaUsuario, $horaUsuario, $comensalesUsuario, $mensajeUsuario]);

          header("Location: reservar.php?ok=Reserva Realizada!");
          exit;

      } catch (PDOException $e) {
          die("Error SQL: " . $e->getMessage());
      }
      
      
    }

?>


<!doctype html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Fogón - Reservar</title>
    <link rel="stylesheet" href="Includes/estilo.css" />
  </head>
  <body>
    <header>
      <div class="logo">
        <a href="index.php"><img src="Img/fogone_clarito.png" /></a>
      </div>
      <nav>
        <nav>
          <a href="carta.php">Carta</a>
          <a href="reservar.php">Reservar</a>
          <a href="conocenos.php">Conócenos</a>
        </nav>
      </nav>
    </header>
    <main class="reserva-main">
      <h1>Reserva Mesa</h1>
      <p class="descripcion-reserva">
      <?php if (!isset($_SESSION['usuarioFogon'])): ?>
        <b>
          <a href="loginUsuario.php">Accede a tu cuenta</a>
          o
          <a href="registrarUsuario.php">Regístrate</a>
          para agilizar tus reservas
        </b>
      <?php else: ?>
        <b>Bienvenido, <?= htmlspecialchars($nombreUsuario) ?></b>
      <?php endif; ?>
      </p>
      <section class="reserva-container">
        <form class="reserva-form" method="post">  <!-- FORMULARIO RESERVA-->
          <label for="nombreUsuario">Nombre completo</label>
          <input type="text" name="nombreUsuario" value="<?= htmlspecialchars($nombreUsuario) ?>" required 
          <?php if(isset($_SESSION['usuarioFogon'])) echo 'readonly'; ?>>

          <label for="telefono">Teléfono</label>
          <input type="tel" name="telefonoUsuario" value="<?= htmlspecialchars($telefonoUsuario) ?>" required
          <?php if(isset($_SESSION['usuarioFogon'])) echo 'readonly'; ?>>

          <label for="fecha">Fecha</label>
          <input type="date" id="fecha" name="fechaUsuario" required min="<?= date('Y-m-d') ?>">


          <label for="hora">Hora</label>
          <select id="hora" name="horaUsuario" required>
            <option value="">Seleccione Hora</option>
          </select>
         

          <label for="personas">Nº de comensales</label>
          <input
            type="number"
            id="personas" 
            name="comensalesUsuario"
            min="1"
            max="50"
            value="2"           
            required
          />

          <label for="mensaje">Mensaje (opcional)</label>
          <textarea
            id="mensaje"
            placeholder="Alergias, preferencias, eventos…"
            name="mensajeUsuario"
          ></textarea>

          <button type="submit">Enviar Reserva</button> 
          <p>
            <?php if(isset($_GET['error'])): ?>
                    <p style="color:red;font-weight: bold;"><?= htmlspecialchars($_GET['error']) ?></p>
                <?php endif; ?>
                <?php if(isset($_GET['ok'])): ?>
                    <p style="color:green;font-weight: bold;"><?= htmlspecialchars($_GET['ok']) ?></p>
                <?php endif; ?>
          </p>

        </form>
      </section>
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
  <script>
    document.getElementById('fecha').addEventListener('change', function() {
        const fecha = this.value;
        const selectHora = document.getElementById('hora');
        selectHora.innerHTML = '<option>Cargando...</option>';

        fetch('get_horas.php?fecha=' + fecha)
            .then(response => response.json())
            .then(data => {
                selectHora.innerHTML = '<option value="">Seleccione Hora</option>';

                if (data.length === 0) {
                    selectHora.innerHTML = '<option>No disponible</option>';
                    return;
                }

                data.forEach(hora => {
                    const opt = document.createElement('option');
                    opt.value = hora;
                    opt.textContent = hora;
                    selectHora.appendChild(opt);
                });
            });
    });
</script>

</html>

