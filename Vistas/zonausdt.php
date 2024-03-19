<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Verifica si el usuario está logueado
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.php");
    exit;
}

// Genera un token de formulario si no existe
if (!isset($_SESSION['form_token'])) {
    $_SESSION['form_token'] = bin2hex(random_bytes(32));
}

// Incluye los archivos necesarios
require_once __DIR__ . '/../conexion.php';
require_once __DIR__ . '/../Funciones/automatizacionIMG.php';
require_once __DIR__ . '/../Funciones/verImagenesWallets.php';
require_once __DIR__ . '/../Funciones/verWalletBTC.php';
require_once __DIR__ . '/../Funciones/verWalletUSDT.php';
require_once __DIR__ . '/../Funciones/obtenerMiBilletera1.php';

// Recupera el ID del usuario de la sesión
$userId = $_SESSION['user_id']; // Asegúrate de tener la sesión iniciada y el user_id disponible
$miBilletera1 = obtenerMiBilletera1($pdo, $userId);
$miBilletera1 = $miBilletera1 === null ? '0.00' : $miBilletera1;

// Usar la función obtenerMiBilletera1 para obtener el valor de la billetera del usuario actual
$userId = $_SESSION['user_id']; // Asegúrate de tener la sesión iniciada y el user_id disponible
$miBilletera1 = obtenerMiBilletera1($pdo, $userId);


// Obtiene la información del usuario usando las funciones
$userImages = getUserImages($userId, $pdo);
$valorWalletBTC = obtenerValorWalletBTC($userId, $pdo);
$valorWalletUSDT = obtenerValorWalletUSDT($userId, $pdo);

// Función para generar un ID de transacción
function generateTransactionId() {
    return uniqid() . bin2hex(random_bytes(8));
}

// Manejo del formulario POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['alias'], $_POST['token'])) {
    if (!hash_equals($_SESSION['form_token'], $_POST['token'])) {
        die("Error de validación del token.");
    }
    unset($_SESSION['form_token']);

    $alias = $_POST['alias'];
    $nombre_banco = $_POST['nombre_banco'];
    $tipo_cuenta = $_POST['tipo_cuenta'];
    $titular_cuenta = $_POST['titular_cuenta'];
    $cedula_titular = $_POST['cedula_titular'];
    $numeroCuenta = $_POST['numeroCuenta'];

    // Preparar y ejecutar la consulta para insertar el alias del banco
    $stmt = $pdo->prepare("INSERT INTO bancos_usuarios (user_id, alias, nombre_banco, tipo_cuenta, titular_cuenta, cedula_titular, numeroCuenta) SELECT ?, ?, ?, ?, ?, ?, ? FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM bancos_usuarios WHERE user_id = ? AND alias = ?)");
    if ($stmt->execute([$userId, $alias, $nombre_banco, $tipo_cuenta, $titular_cuenta, $cedula_titular, $numeroCuenta, $userId, $alias])) {
        if ($stmt->rowCount() > 0) {
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } else {
            echo "El banco ya está registrado o no se pudo registrar.";
        }
    } else {
        echo "Error: " . $stmt->error;
    }
}



require_once __DIR__ . '/../Funciones/mostrarBancosUsuario.php';

// Recupera el ID del usuario de la sesión
$userId = $_SESSION['user_id'];

// Llamamos a la función para obtener la salida de los bancos
$htmlBancos = mostrarBancosUsuario($pdo, $userId);

// Ahora puedes usar $htmlBancos donde necesites mostrar los bancos.
?>

<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tokenautas</title>
    <link rel="stylesheet" href="./style.css">
  </head>

  <body>

    <div class="dash">

      <!-- HEADER -->
      <header class="header">
        <h1 class="header__heading"><a href="#" target="_blank" rel="noreferrer noopener"><img src="./logos/tokenautasBlanco-02.png" width="40" alt=""></a></h1>
        <div class="header__search">
          <div class="header__search-icon" style="opacity: 0;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
          </div>
        </div>
        <div class="header__options">
          <button class="header__pro">WhatsApp</button>
          <a href="#" class="header__link">Soporte</a>
          <a href="./logout.php" class="header__link">Salir</a>

        </div>
      </header>

              <!-- BODY -->
      <div class="body">

<!-- SIDEBAR -->
<div class="sidebar">

<a href="../Vistas/dashboard.php" >
  <div class="sidebar__icon" style="display: flex; justify-content: center; align-items: center; text-align: center; color:aliceblue; "><h3>Inicio</h3>
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
    </svg>
  </div>
  </a>



  <a href="../Vistas/retirar.php">
  <div class="sidebar__icon" style="display: flex; justify-content: center; align-items: center; text-align: center; color:aliceblue; margin-top: 50px !important "><h3>Retirar</h3>
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
    </svg>
  </div></a>

  <a href="../Vistas/depositar.php">
  <div class="sidebar__icon" style="display: flex; justify-content: center; align-items: center; text-align: center; color:aliceblue; margin-top: 50px !important "><h3>Depositar</h3>
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 4v12l-4-2-4 2V4M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
    </svg>
  </div>
  </a>
  
  <a href="../Vistas/misbancos.php">
  <div class="sidebar__icon" style="display: flex; justify-content: center; align-items: center; text-align: center; color:aliceblue; margin-top: 50px !important ""><h3>Mis Bancos</h3>
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
    </svg>
  </div>
  </a>

  <a href="../Vistas/registrarBancos.php">
  <div class="sidebar__icon" style="display: flex; justify-content: center; align-items: center; text-align: center; color:aliceblue; margin-top: 50px !important ""><h3>Registrar Bancos</h3>
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
    </svg>
  </div>
  </a>


<!-- HAMBURGUESA -->
<a href="../Vistas/historialderetiro.php">
  <div class="sidebar__icon" style="display: flex; justify-content: center; align-items: center; text-align: center; color:aliceblue; margin-top: 50px !important ""><h3>Historial de Retiro</h3>
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
    </svg>
  </div>
  </a>
</div>

<!-- MAIN -->
        <main class="main">

        <div class="main__col-1">

    <!-- HEADING -->
    <div>
      <h2 class="main__heading"><span style="background: linear-gradient(to bottom, hsl(70, 6%, 21%), hsl(300, 3%, 6%)); box-shadow: 0 2px 12px hsla(247, 88%, 70%, .3)">
          <img src="../img/coheteLogoBlanco.png" alt="" height="50rem">
        </span><?php echo $_SESSION['username']; ?></h2>
      <p class="main__desc" style="font-size: 50px;">TETHER</p>
      <p class="main__sub" style="font-size: 25px;"><span>RED:</span> <span>TRC20</span></p>
    </div>

    <?php

    // Ejecuta las funciones y almacena los resultados
$imagenesUsuario = getUserImages($userId, $pdo);
$valorWalletBTC = obtenerValorWalletBTC($userId, $pdo);
$valorWalletUSDT = obtenerValorWalletUSDT($userId, $pdo);

// Verifica si las claves existen en el array antes de intentar acceder a ellas
$imagenUSD = isset($imagenesUsuario['imagenusdt']) ? $imagenesUsuario['imagenusdt'] : '';
$imagenBTC = isset($imagenesUsuario['imagenbtc']) ? $imagenesUsuario['imagenbtc'] : '';
?>


    <!-- LIST -->
    <div class="main__list-heading-wrap">
             <div id="johan" class="modal-bitcoin" >  <!-- OJO -->
                <div class="image-container" onmouseover="showModal('btcModal')" onmouseout="hideModal('btcModal')">
                <p><span style="opacity: 0;">Imagen USD: </span>  <img width="400" src="<?php echo '../' . htmlspecialchars($imagenUSD); ?>" alt="Imagen USD"></p>
                </div>
       
              </div>
    </div>

    <ul class="main__list">

    <li class="main__list-item">
    <div class="main__list-item-image" style="cursor: pointer;" onclick="copiarAlPortapapeles(this)">
      <img src="../img/copiar.png" width="40px" alt="">
    </div>
    <div class="main__list-content-wrap">
      <p class="main__list-content"><?php echo $valorWalletUSDT; ?></p>
      <p class="main__list-sub">Tu billetera USDT</p>
    </div>
</li>

<script>
function copiarAlPortapapeles(elemento) {
    // Encuentra el elemento <p> que contiene el valor a copiar
    var contenido = elemento.closest('.main__list-item').querySelector('.main__list-content').textContent;

    // Crea un elemento input temporal, necesario para el proceso de copiado
    var inputTemporal = document.createElement("input");
    document.body.appendChild(inputTemporal);
    inputTemporal.value = contenido;
    inputTemporal.select();
    document.execCommand("copy");
    document.body.removeChild(inputTemporal);

    // Muestra un mensaje de alerta con el contenido copiado
    alert("Copiado al portapapeles: " + contenido);
}
</script>



      <div class="main__list-heading-wrap" style="display: none;">
        <h1>Para desplegar las instrucciones selecciona la plataforma o criptomoneda que deseas vendernos.</h1>
        </li>













    </ul>

  </div>

  <!-- COL-2 -->
  <div class="main__col-2">


    <!-- CROSSING -->
    <div class="main__crossing-container" style="padding: 35px;">
   
      <div class="main__crossing-current">
        <p class="main__crossing-upper">
        
        </p>
        <h3 class="main__crossing-heading" style="font-size:21px;  font-weight: bold;">
          ¡INFORMACION IMPORTANTE!
        </h3>
      </div>
    </div>



    <style>
      .cajaDisclamer1{
        background-color: white;
        padding: 30px;
        border-radius: 15px;
        font-size: 14px;
        margin-bottom: 20px;
      }
    </style>
    <!-- DISCOVER -->
    <div class="main__discover" style="">

      <div class="main__discover-heading-container">
        <h3 class="main__discover-heading ss-heading" style="padding-bottom:20px">DESCARGO DE RESPONSABILIDAD: ANTES DE RECIBIR TUS BITCOIN NUNCA DEBES OLVIDAR TENER EN CUENTA LAS SIGUIENTES RECOMENDACIONES:</h3>
        <a href="#" class="ss-show"></a>
      </div>
   <div class="disclamerBitcoin1">
   <div class="cajaDisclamer1">
   <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Illum earum quae eligendi praesentium quis voluptates iusto quibusdam, voluptas impedit tempora non? Quas, harum. Facilis provident ullam nobis. Libero, earum nam!</p>
   </div>
   <div class="cajaDisclamer1">
   <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Illum earum quae eligendi praesentium quis voluptates iusto quibusdam, voluptas impedit tempora non? Quas, harum. Facilis provident ullam nobis. Libero, earum nam!</p>
   </div>
   </div>

    </div>

            <!-- FOOTER -->
            <footer class="main__footer">

              <a href="#" class="main__footer-more ss-show">...Leer Normativa Legal<span>
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                  </svg>
                </span></a>

              <div class="main__info">
                <a href="" target="_blank" rel="noreferrer noopener" class="main__info-link">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-twitter">
                    <path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path>
                  </svg>
                </a>
                <a href="" target="_blank" rel="noreferrer noopener" class="main__info-link">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-github">
                    <path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22"></path>
                  </svg>
                </a>
                <a href="" target="_blank" rel="noreferrer noopener" class="main__info-link">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-dribbble">
                    <circle cx="12" cy="12" r="10"></circle>
                    <path d="M8.56 2.75c4.37 6.03 6.02 9.42 8.03 17.72m2.54-15.38c-3.72 4.35-8.94 5.66-16.88 5.85m19.5 1.9c-3.5-.93-6.63-.82-8.94 0-2.58.92-5.01 2.86-7.44 6.32"></path>
                  </svg>
                </a>

                <p class="main__cp" style="font-size: 1.4rem; text-align: center; ">
                  Copyright &copy; 2024 <a href="" target="_blank" rel="noreferrer noopener" class="main__info-link" style="border-bottom: 1px solid  hsla(270, 10%, 50%, .4);">Tokenautas.com.</a>
                </p>

                <p class="main__cr">
                  <a href="https://colorpick.vercel.app" target="_blank" rel="noreferrer noopener">
                    <img src="./logos/tokenautasBlanco-02.png" alt="" height="50rem">
                  </a>
                </p>
              </div>

            </footer>

          </div>

        </main>

      </div>

    </div>



    <script>
      // Deshabilitar el zoom en el sitio web
document.addEventListener('keydown', function (event) {
    if (event.ctrlKey && (event.key === '+' || event.key === '-')) {
        event.preventDefault(); // Evita el zoom con Ctrl + / Ctrl -
    }
});

// También puedes agregar esto en tu hoja de estilos CSS:
// html, body {
//     zoom: reset; /* Esto funciona en Chrome */
// }


// Mantener la posición del sitio web al girar el celular
window.addEventListener('resize', function () {
    // Obtén la posición actual de desplazamiento vertical (scroll) de la página
    const currentPosition = window.scrollY;

    // Restaura la posición de desplazamiento vertical después de que se complete la rotación
    window.scrollTo(0, currentPosition);
});


//funcion menu hamburguesa
/* Update the toggleMenu function */
function toggleMenu() {
  const sidebar = document.querySelector('.sidebar');
  sidebar.classList.toggle('sidebar-expanded');

  const iconTexts = document.querySelectorAll('.sidebar__icon-text');
  iconTexts.forEach(text => {
    text.style.display = (text.style.display === 'none' ? 'block' : 'none');
  });
}

    </script>


<script>
    function mostrarSeccion(seccionId) {
      // Ocultar todas las secciones
      document.querySelectorAll('.section').forEach(function(section) {
        section.style.display = 'none';
      });

      // Mostrar la sección solicitada
      document.getElementById(seccionId).style.display = 'block';
    }
  </script>





  <script>
    /*=============== SHOW SIDEBAR ===============*/
    const showSidebar = (toggleId, sidebarId, mainId) => {
      const toggle = document.getElementById(toggleId),
        sidebar = document.getElementById(sidebarId),
        main = document.getElementById(mainId)

      if (toggle && sidebar && main) {
        toggle.addEventListener('click', () => {
          /* Show sidebar */
          sidebar.classList.toggle('show-sidebar')
          /* Add padding main */
          main.classList.toggle('main-pd')
        })
      }
    }
    showSidebar('header-toggle', 'sidebar', 'main')

    /*=============== LINK ACTIVE ===============*/
    const sidebarLink = document.querySelectorAll('.sidebar__link')

    function linkColor() {
      sidebarLink.forEach(l => l.classList.remove('active-link'))
      this.classList.add('active-link')
    }

    sidebarLink.forEach(l => l.addEventListener('click', linkColor))
  </script>


  <!-- JavaScript para cambiar el color del botón y generar retiro -->
  <script>
    function cambiarBotonRetirar(select) {
      if (select.value !== "") {
        document.getElementById('boton_retirar').style.backgroundColor = 'green';
      } else {
        document.getElementById('boton_retirar').style.backgroundColor = 'red';
      }
    }

    function generarRetiro() {
      var bancoSeleccionado = document.getElementById('banco_seleccionado').value;
      var cantidadRetirar = document.getElementById('cantidad_retirar').value;
      if (bancoSeleccionado === "" || cantidadRetirar === "") {
        alert("Por favor, selecciona un banco y especifica la cantidad a retirar.");
        return;
      }
      var identificador = 'TKT-' + Math.random().toString(36).substr(2, 9) + '#' + Math.random().toString(36).substr(2, 9);
      document.getElementById('identificador_transaccion').value = identificador;

      // Muestra un alerta con el identificador único
      alert("Tu orden fue generada con el ticket#: " + identificador);

      // Envía el formulario
      document.getElementById('formRetiro').submit();
    }
  </script>












  </body>


</html>
