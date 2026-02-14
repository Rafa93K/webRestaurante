<?php
require "./conexion.php";
session_start();

$error = "";
/*Comprobar estado de Specials */
$mostrarSpecials = 0;
$stmt = $pdo->prepare("SELECT valor FROM CONFIG WHERE clave='mostrar_specials' LIMIT 1");
$stmt->execute();
$mostrarSpecials = (int)($stmt->fetchColumn() ?? 0);


/* Si viene el POST (ha intentado loguearse) */
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST["nombre"] ?? "";
    $contrasena = $_POST["contrasena"] ?? "";
try {
    // Consulta a la tabla ADMINISTRADOR

        $sql = "SELECT id_admin FROM `Administrador` WHERE nombre=? AND contrasena=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nombre, $contrasena]);
     

        if ($stmt->rowCount() === 1) {
            $r = $stmt->fetch(PDO::FETCH_ASSOC);
            $_SESSION["adminFogon"] = $r["id_admin"];
            header("Location: administrador.php");
            exit;
        } else {
            $error = "Nombre o contraseña incorrectos";
        }
    } catch (PDOException $e) {
        die("Error de conexión: " . $e->getMessage());

    }
   
}

/* ---------- LOGIN SI NO HAY SESIÓN ---------- */
if (!isset($_SESSION["adminFogon"])) 
{
    ?>
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <title>Login Administrador</title>
            <link rel="stylesheet" href="Includes/estilo.css">
        </head>
        <body align="center">
            <h1>Acceso Administrador</h1><br><br>
            <form action="#" method="post">
                <label>Nombre</label><br><br>
                <input type="text" name="nombre" required> <br><br>

                <label>Contraseña</label><br><br>
                <input type="password" name="contrasena" required> <br><br>

                <button type="submit">Entrar</button>
                <?php if ($error != "") echo "<p style='color:red;'>$error</p>"; ?>
            </form>
        </body>
        </html>
    <?php
}else{
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Administrador - Fogón</title>
        <link rel="stylesheet" href="Includes/estilo.css" />
    </head>
    <body>
        <header>
        <div class="logo">
            <a href="index.php"><img src="Img/fogone_clarito.png" /></a>
        </div>
        <nav>
            <a href="verReservas.php">Ver Reservas</a>
        </nav>
        </header>

        <main>
        <h1>Panel de Administrador</h1>
        <p>Añade, modifica o elimina productos de Carta, Vinos y Especiales.</p>

        <!-- ------------------ AÑADIR PRODUCTO ------------------ -->

        <section class="admin-section">
            <h2 class="seccion-titulo">Añadir Producto</h2>

            <form action="insertar.php" method="post" enctype="multipart/form-data" class="form-admin">
                <label>Nombre</label>
                <input type="text" name="nombre" required />

                <label>Descripción</label>
                <input type="text" name="descripcion" required />

                <label>Precio</label>
                <input type="number" step="0.01" name="precio" required />

                <label>Tipo</label>
                <select name="tipo" id="tipo_insert">
                    <option value="">Selecciona...</option>
                    <option value="carta">Carta</option>
                    <option value="vinos">Vinos</option>
                    <option value="especial">Especial</option>
                </select>                
                <label>SubTipo</label>
                <select name="subtipo" id="subtipo_insert">
                    <option value="">Selecciona...</option>
                </select>
                <label>Imagen</label>
                <input type="file" name="imagen"/>
                <button type="submit">Añadir producto</button>
                <p style="color:red; margin-top:10px;">
                    <?= isset($_GET['error']) ? htmlspecialchars($_GET['error']) : '' ?>
                </p>
                <p style="color:green; margin-top:10px;">
                <?= isset($_GET['ok']) ? htmlspecialchars($_GET['ok']) : '' ?>
                </p>
            </form>
        </section>

        <!-- ------------------ MODIFICAR PRODUCTO ------------------ -->
        <section class="admin-section">
            <h2 class="seccion-titulo">Modificar Producto</h2>

            <form action="modificar.php" method="post" enctype="multipart/form-data" class="form-admin">
            <label>Seleccionar producto</label>
            <select name="id_producto">
            <option value="">Selecciona...</option>
            <?php
            $productos = $pdo->query("SELECT id_producto, nombre FROM Producto")->fetchAll(PDO::FETCH_ASSOC);
            foreach ($productos as $p) {
                echo '<option value="'.$p['id_producto'].'">'.htmlspecialchars($p['nombre']).'</option>';
            }
            ?>
            </select>

            <label>Nombre</label>
            <input type="text" name="nombre"/>

            <label>Descripción</label>
            <input type="text" name="descripcion"/>

            <label>Precio</label>
            <input type="number" step="0.01" name="precio"/>

            <label>Nueva imagen</label>
            <input type="file" name="imagen"/>

            <button type="submit">Guardar cambios</button>
            <p style="color:red; margin-top:10px;">
                    <?= isset($_GET['errorM']) ? htmlspecialchars($_GET['errorM']) : '' ?>
                </p>
                <p style="color:green; margin-top:10px;">
                <?= isset($_GET['okM']) ? htmlspecialchars($_GET['okM']) : '' ?>
                </p>
            </form>
        </section>

        <!-- ------------------ ELIMINAR PRODUCTO ------------------ -->
        <section class="admin-section">
            <h2 class="seccion-titulo">Eliminar Producto</h2>

            <form action="eliminar.php" method="post" class="form-admin">
                
                <label>Tipo</label>
                <select name="tipo_filtro" id="tipo_eliminar" required>
                    <option value="">Selecciona...</option>
                    <option value="carta">Carta</option>
                    <option value="vinos">Vinos</option>
                    <option value="especial">Especial</option>
                </select>

                <label>Subtipo</label>
                <select name="subtipo_filtro" id="subtipo_eliminar" required>
                    <option value="">Selecciona tipo primero...</option>
                </select>

                <label>Producto</label>
                <select name="id_producto" id="producto_eliminar" required>
                    <option value="">Selecciona tipo y subtipo...</option>
                </select>

                <button class="btn-eliminar" type="submit">Eliminar producto</button>

                <!-- Mensajes -->
                <?php if(isset($_GET['errorE'])): ?>
                    <p style="color:red; margin-top:10px;"><?= htmlspecialchars($_GET['errorE']) ?></p>
                <?php endif; ?>
                <?php if(isset($_GET['okE'])): ?>
                    <p style="color:green; margin-top:10px;"><?= htmlspecialchars($_GET['okE']) ?></p>
                <?php endif; ?>
            </form>
        </section>
        <!-- MOSTRAR ESPECIALS-->
        <section class="admin-section">
        <h2 class="seccion-titulo">Mostrar Specials</h2>

        <form class="form-adminEspecial">
            <label><b>Mostrar</b></label>
            <input type="checkbox" id="mostrarEspecial" <?= $mostrarSpecials ? "checked" : "" ?> />
        </form>
        </section>

        </main>
        <script>
            document.addEventListener("DOMContentLoaded", function(){

            const chk = document.getElementById("mostrarEspecial");
    

            if(!chk) return;

            chk.addEventListener("change", async function(){
                const valor = chk.checked ? 1 : 0;

                try{
                const res = await fetch("guardar_specials.php", {
                    method: "POST",
                    headers: {"Content-Type":"application/x-www-form-urlencoded"},
                    body: "valor=" + encodeURIComponent(valor)
                });

                const data = await res.json();

                }
            });

            });
        </script>
                    
        <script>
        document.addEventListener("DOMContentLoaded", function () {

            const opcionesCarta = ["Entrantes","Carnes","Pescados","Arroces y Pastas","Postres"];
            const opcionesVinos = ["Tinto","Blanco","Rosado","Espumoso"];

            function activarSubtipos(idTipo, idSubtipo) {
                const tipo = document.getElementById(idTipo);
                const subtipo = document.getElementById(idSubtipo);

                // si no existen en la página, no hace nada (para evitar errores)
                if (!tipo || !subtipo) return;

                tipo.addEventListener("change", function () {
                    subtipo.innerHTML = "";

                    if (tipo.value === "carta") {
                        subtipo.disabled = false;
                        subtipo.innerHTML = `<option value="">Selecciona...</option>`;
                        opcionesCarta.forEach(opcion => {
                            let option = document.createElement("option");
                            option.value = opcion;
                            option.textContent = opcion;
                            subtipo.appendChild(option);
                        });

                    } else if (tipo.value === "vinos") {
                        subtipo.disabled = false;
                        subtipo.innerHTML = `<option value="">Selecciona...</option>`;
                        opcionesVinos.forEach(opcion => {
                            let option = document.createElement("option");
                            option.value = opcion;
                            option.textContent = opcion;
                            subtipo.appendChild(option);
                        });

                    } else if (tipo.value === "especial") {
                        subtipo.disabled = true;
                        const option = document.createElement("option");
                        option.value = "especial";
                        option.textContent = "No aplica";
                        subtipo.appendChild(option);

                    } else {
                        subtipo.disabled = true;
                        subtipo.innerHTML = `<option value="">Selecciona tipo primero...</option>`;
                    }
                });
            }

            // INSERTAR
            activarSubtipos("tipo_insert", "subtipo_insert");

            // ELIMINAR (si tienes estos IDs)
            activarSubtipos("tipo_eliminar", "subtipo_eliminar");

            // MODIFICAR (si tienes estos IDs)
            activarSubtipos("tipo_modificar", "subtipo_modificar");

        });
        </script>
        <script>
        document.addEventListener("DOMContentLoaded", function () {

            async function cargarProductos(tipoId, subtipoId, productoId){
                const tipo = document.getElementById(tipoId);
                const subtipo = document.getElementById(subtipoId);
                const productos = document.getElementById(productoId);

                if(!tipo || !subtipo || !productos) return;

                productos.innerHTML = `<option value="">Cargando...</option>`;

                // Si el subtipo está disabled (especial) lo forzamos a "especial"
                const t = tipo.value;
                const s = subtipo.disabled ? "especial" : subtipo.value;

                if(!t || !s){
                    productos.innerHTML = `<option value="">Selecciona tipo y subtipo...</option>`;
                    return;
                }

                try{
                    const res = await fetch(`productos_filtrados.php?tipo=${encodeURIComponent(t)}&subtipo=${encodeURIComponent(s)}`);
                    const data = await res.json();

                    productos.innerHTML = `<option value="">Selecciona...</option>`;

                    if(data.length === 0){
                        productos.innerHTML = `<option value="">No hay productos</option>`;
                        return;
                    }

                    data.forEach(p=>{
                        const opt = document.createElement("option");
                        opt.value = p.id_producto;
                        opt.textContent = p.nombre;
                        productos.appendChild(opt);
                    });

                }catch(e){
                    productos.innerHTML = `<option value="">Error cargando productos</option>`;
                }
            }

            // ==============================
            // ELIMINAR
            // ==============================
            const tipoEliminar = document.getElementById("tipo_eliminar");
            const subtipoEliminar = document.getElementById("subtipo_eliminar");

            if(tipoEliminar && subtipoEliminar){

                // Cuando cambie el tipo: si es especial, cargamos directo productos
                tipoEliminar.addEventListener("change", function(){
                    if(tipoEliminar.value === "especial"){
                        cargarProductos("tipo_eliminar", "subtipo_eliminar", "producto_eliminar");
                    }else{
                        // resetea productos hasta elegir subtipo
                        document.getElementById("producto_eliminar").innerHTML = `<option value="">Selecciona tipo y subtipo...</option>`;
                    }
                });

                // Cuando cambie el subtipo: cargar productos
                subtipoEliminar.addEventListener("change", function(){
                    cargarProductos("tipo_eliminar", "subtipo_eliminar", "producto_eliminar");
                });
            }

        });
        </script>
            </body>
            </html>
        <?php
        }
        ?>
            
