<!doctype html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Fogón - Conócenos</title>
    <link rel="stylesheet" href="Includes/estilo.css" />
    <style>
      #api {
        display: flex;
        justify-content: center;
        margin-top: 40px;
      }


      .receta-card {
        width: 750px;
        background: rgba(107, 101, 101, 0.64);
        border-radius: 12px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        overflow: hidden;
        animation: fadeIn 0.4s ease-in-out;
      }

      .receta-card img {
        width: 100%;
        height: 220px;
        object-fit: cover;
      }

      .receta-card .contenido {
        padding: 15px;
      }

      .receta-card ul {
        padding-left: 18px;
      }
         .receta-card li{
          list-style: none;
         }

      .receta-card p {
        font-size: 14px;
        color: #f5e5c0;
      }

      @keyframes fadeIn {
        from {
          opacity: 0;
        }
        to {
          opacity: 1;
        }
      }
    </style>
  </head>
  <body class="conocenos">
    <?php include 'Includes/header.php'; ?>

    <main class="conocenos-main">
      <a
        href="administrador.php"
        class="admin-link"
        aria-label="Acceso administrador"
      ></a>
      <section class="info-conocenos">
        <h1>Gastro-Bar Fogón</h1>
        <p class="descripcion">
          Tradición, sabor y calidad desde 2019. Te invitamos a conocer nuestro
          espacio, donde la cocina casera y los productos frescos son los
          protagonistas.
        </p>

        <p class="horario">
          <strong>Horario:</strong><br />
          Lunes: 13:30 - 16:00<br />
          Martes: Cerrado <br />
          Miercoles: 13:30 - 16:00<br />
          Jueves: 13:30 - 16:00<br />
          Viernes y Sábados: 13:30-16:00 / 20:00-23:00 Domingo: 13:30 - 16:00<br />
        </p>

        <div class="mapa">
          <iframe
            src="https://www.google.com/maps/d/embed?mid=1cmzj9sj2tEzT3Rx8O0gMnGjH4AnpWzc&ehbc=2E312F&noprof=1"
          ></iframe>
        </div>
      </section> <br> <br>
      <div class="info-conocenos">
        <h1>Recestas del mundo</h1>
        <div id="api"></div>
      </div>
    </main>

    <?php include 'Includes/footer.php'; ?>
    <script>
      async function cargarReceta() {
        try {
          const response = await fetch("receta.php");
          const data = await response.json();

          if (data.error) {
            document.getElementById("api").innerHTML = data.error;
            return;
          }

          let ingredientesHTML = "";
          data.ingredientes.forEach((item) => {
            ingredientesHTML += `<li>${item}</li>`;
          });

          document.getElementById("api").innerHTML = `
      <div class="receta-card">
        <img src="${data.imagen}" alt="${data.titulo}">
        <div class="contenido">
          <h3>${data.titulo}</h3>
          <strong>Ingredientes:</strong>
          <ul>${ingredientesHTML}</ul>
          <p>${data.descripcion}</p>
        </div>
      </div>
    `;
        } catch (error) {
          console.error("Error:", error);
        }
      }

      cargarReceta();
    </script>
  </body>
</html>
