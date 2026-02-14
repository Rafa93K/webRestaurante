<!doctype html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Política de Cookies - Fogón</title>
    <link rel="stylesheet" href="Includes/estilo.css" />
    <style>
      .politica-contenido {
        max-width: 900px;
        margin: 40px auto;
        padding: 30px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
      }

      .politica-contenido h2 {
        color: #30312e;
        margin-top: 30px;
        margin-bottom: 15px;
      }

      .politica-contenido p {
        line-height: 1.8;
        color: #333;
        margin-bottom: 15px;
        font-family: "Patrick Hand", cursive;
        font-size: 16px;
      }

      .politica-contenido ul {
        margin-left: 20px;
        margin-bottom: 15px;
      }

      .politica-contenido li {
        margin-bottom: 10px;
        line-height: 1.6;
        color: #333;
        font-family: "Patrick Hand", cursive;
      }

      .tabla-cookies {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
        font-family: "Patrick Hand", cursive;
      }

      .tabla-cookies th,
      .tabla-cookies td {
        border: 1px solid #ddd;
        padding: 12px;
        text-align: left;
      }

      .tabla-cookies th {
        background: #30312e;
        color: white;
      }

      .tabla-cookies tr:nth-child(even) {
        background: #f9f9f9;
      }

      .actualizado {
        text-align: center;
        color: #888;
        font-style: italic;
        margin-top: 40px;
        font-size: 14px;
      }
    </style>
  </head>
  <body>
    <?php include 'Includes/header.php'; ?>

    <main>
      <div class="politica-contenido">
        <h1>Política de Cookies</h1>

        <h2>1. ¿Qué son las Cookies?</h2>
        <p>
          Las cookies son pequeños archivos de texto que se guardan en tu
          dispositivo cuando visitas nuestro sitio web. Estos archivos permiten
          que el sitio recuerde información sobre ti, como tus preferencias de
          navegación, mejorando así tu experiencia en el sitio.
        </p>

        <h2>2. Tipos de Cookies que Utilizamos</h2>

        <h3>2.1 Cookies Esenciales</h3>
        <p>
          Estas cookies son necesarias para el funcionamiento básico de nuestro
          sitio web. Sin ellas, no podrías navegar correctamente ni realizar
          acciones como hacer reservas.
        </p>
        <table class="tabla-cookies">
          <thead>
            <tr>
              <th>Nombre</th>
              <th>Propósito</th>
              <th>Duración</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>PHPSESSID</td>
              <td>Gestiónamos la sesión del usuario</td>
              <td>Hasta cerrar el navegador</td>
            </tr>
            <tr>
              <td>usuario_id</td>
              <td>Identificar usuarios registrados</td>
              <td>30 días</td>
            </tr>
          </tbody>
        </table>

        <h3>2.2 Cookies de Análisis</h3>
        <p>
          Utilizamos estas cookies para entender cómo los usuarios interactúan
          con nuestro sitio, permitiéndonos mejorar la experiencia y optimizar
          nuestros servicios.
        </p>

        <h3>2.3 Cookies de Marketing</h3>
        <p>
          Estas cookies se utilizan para rastrear tu actividad y mostrarte
          publicidad relevante. Solo se usan si has consentido a ellas.
        </p>

        <h2>3. Control de Cookies</h2>
        <p>Puedes controlar las cookies de la siguiente manera:</p>
        <ul>
          <li>
            <strong>Navegador:</strong> La mayoría de navegadores permiten
            rechazar cookies o avisar antes de aceptarlas. Consulta la ayuda de
            tu navegador para más información.
          </li>
          <li>
            <strong>Deshabilitar Cookies:</strong> Si deshabilitas las cookies,
            algunos servicios del sitio pueden no funcionar correctamente.
          </li>
          <li>
            <strong>Eliminar Cookies:</strong> Puedes eliminar las cookies
            existentes desde la configuración de tu navegador.
          </li>
        </ul>

        <h2>4. Cookies de Terceros</h2>
        <p>
          Nuestro sitio contiene enlaces a redes sociales (Instagram) cuyos
          servicios pueden depositar cookies propias. No controlamos estas
          cookies. Te recomendamos revisar las políticas de privacidad de estos
          servicios.
        </p>

        <h2>5. Consentimiento de Cookies</h2>
        <p>
          Al continuar navegando en nuestro sitio, consientes el uso de cookies
          según se describe en esta política. Si no deseas aceptar cookies,
          puedes cambiar la configuración de tu navegador.
        </p>

        <h2>6. Cambios en esta Política</h2>
        <p>
          Podemos actualizar esta Política de Cookies en cualquier momento para
          reflejar cambios en nuestras prácticas. Te recomendamos revisar esta
          página periódicamente.
        </p>

        <h2>7. Contacto</h2>
        <p>
          Si tienes preguntas sobre esta Política de Cookies, contáctanos a
          través de Instagram:
          <a href="https://www.instagram.com/fogonpya/">@fogonpya</a>
        </p>

        <div class="actualizado">Última actualización: Febrero 2026</div>
      </div>
    </main>

    <?php include 'Includes/footer.php'; ?>
  </body>
</html>
