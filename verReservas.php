<?php
  require_once "./conexion.php";

?>

<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Administrador - Reservas</title>
    <link rel="stylesheet" href="Includes/estilo.css" />
  </head>

  <body class="admin-reservas">
    <header>
      <div class="logo"><a href="index.php"><img src="Img/fogone_clarito.png" /></a></div>
      <nav>
        <a href="administrador.php">Panel Administrador</a>
      </nav>
    </header>

    <main class="reservas-main">

      <h1>Reservas del Día</h1>

      <div class="selector-fecha">
        <label for="fecha">Seleccionar fecha:</label>
        <input type="date" id="fecha" name="fecha" min="<?= date('Y-m-d') ?>"/>
        <button id="buscar">Buscar</button>
      </div>

      <section class="contenedor-reservas">
        <h2 id="titulo-dia">Reservas para el día seleccionado</h2>

        <!-- Tabla de reservas -->
        <table class="tabla-reservas">
          <thead>
            <tr>
              <th>Hora</th>
              <th>Nombre</th>
              <th>Personas</th>
              <th>Teléfono</th>
              <th>Mensaje</th>
              <th></th>
            </tr>
          </thead>

          <tbody id="lista-reservas"></tbody>
        </table>
        

      </section>


      <!-- FORMULARIO AÑADIR RESERVA DESDE ADMIN -->
      <section class="formulario-reserva-admin">
        <h2>Registrar Nueva Reserva</h2>

        <form id="formReservaAdmin" method="post">

          <label for="fechaNueva">Fecha</label>
          <input type="date" id="fechaNueva" required min="<?= date('Y-m-d') ?>">

          <label for="horaNueva">Hora</label>
          <select id="horaNueva" required>
            <option value="">Seleccione hora</option>
          </select>

          <label for="nombreNueva">Nombre</label>
          <input type="text" id="nombreNueva" placeholder="Nombre del cliente" required>

          <label for="personasNueva">Personas</label>
          <input type="number" id="personasNueva" min="1" required>

          <label for="telefonoNuevo">Teléfono</label>
          <input type="text" id="telefonoNuevo" placeholder="Ej: 612345678" required>

          <label for="mensajeNuevo">Mensaje</label>
          <textarea id="mensajeNuevo" placeholder="Observaciones (opcional)"></textarea>

          <button type="submit">Añadir Reserva</button>

        </form>
      </section>
    </main>
    <script>
      document.getElementById('buscar').addEventListener('click', () => {
          const fecha = document.getElementById('fecha').value;
          const tbody = document.getElementById('lista-reservas');
          const titulo = document.getElementById('titulo-dia');

          if (!fecha) {
              alert('Selecciona una fecha');
              return;
          }

          titulo.textContent = `Reservas para el ${fecha}`;
          tbody.innerHTML = '<tr><td colspan="6">Cargando...</td></tr>';

          fetch('get_reservas.php?fecha=' + fecha)
              .then(res => res.json())
              .then(data => {

                  tbody.innerHTML = '';

                  if (data.length === 0) {
                      tbody.innerHTML = `
                          <tr>
                              <td colspan="6">No hay reservas para este día</td>
                          </tr>`;
                      return;
                  }

                  data.forEach(reserva => {
                      const tr = document.createElement('tr');

                      tr.innerHTML = `
                          <td>${reserva.hora.substring(0, 5)}</td>
                          <td>${reserva.nombre_cliente}</td>
                          <td>${reserva.personas}</td>
                          <td>${reserva.telefono}</td>
                          <td>${reserva.mensaje ?? ''}</td>
                          <td>
                              <button class="btn-cancelar" data-id="${reserva.id_reserva ?? ''}">X</button>
                          </td>
                      `;

                      tbody.appendChild(tr);
                  });
              })
              .catch(err => {
                  tbody.innerHTML = '<tr><td colspan="6">Error al cargar reservas</td></tr>';
                  console.error(err);
              });
      });
      // CANCELAR RESERVA
      document.getElementById('lista-reservas').addEventListener('click', e => {

          if (!e.target.classList.contains('btn-cancelar')) return;

          const idReserva = e.target.dataset.id;

          if (!confirm('¿Cancelar esta reserva?')) return;

          fetch('cancelar_reserva.php', {
              method: 'POST',
              headers: {
                  'Content-Type': 'application/json'
              },
              body: JSON.stringify({ id: idReserva })
          })
          .then(res => res.json())
          .then(data => {
              if (data.ok) {
                  e.target.closest('tr').remove();
              } else {
                  alert(data.error || 'Error al cancelar');
              }
          })
          .catch(() => alert('Error de conexión'));
      });
    </script>
    <script> /*admin registra reserva */
    document.getElementById('fechaNueva').addEventListener('change', function() {
        const fecha = this.value;
        const selectHora = document.getElementById('horaNueva');
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

    document.getElementById('formReservaAdmin').addEventListener('submit', e => {
        e.preventDefault();

        const datos = {
            fecha: document.getElementById('fechaNueva').value,
            hora: document.getElementById('horaNueva').value,
            nombre: document.getElementById('nombreNueva').value,
            personas: document.getElementById('personasNueva').value,
            telefono: document.getElementById('telefonoNuevo').value,
            mensaje: document.getElementById('mensajeNuevo').value
        };

        if (!datos.fecha || !datos.hora || !datos.nombre || !datos.personas || !datos.telefono) {
            alert('Rellena todos los campos obligatorios');
            return;
        }

        fetch('insertar_reserva_admin.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(datos)
        })
        .then(res => res.json())
        .then(data => {
            if (data.ok) {
                alert('Reserva añadida correctamente');

                // limpiar formulario
                document.getElementById('formReservaAdmin').reset();

                // refrescar listado si la fecha coincide
                const fechaBuscada = document.getElementById('fecha').value;
                if (fechaBuscada === datos.fecha) {
                    document.getElementById('buscar').click();
                }
            } else {
                alert(data.error || 'Error al guardar');
            }
        })
        .catch(() => alert('Error de conexión'));
    });
</script>

  </body>
</html>
