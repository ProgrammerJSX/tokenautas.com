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


if (isset($_SESSION['mensaje_retiro'])) {
  echo "<script>alert('" . $_SESSION['mensaje_retiro'] . "');</script>";
  unset($_SESSION['mensaje_retiro']);
}


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

          <!-- COL-1 -->
          <div class="main__col-1">

     <!-- HEADING -->
     <div>
              <h2 class="main__heading"><span style="background: linear-gradient(to bottom, hsl(247, 88%, 70%), hsl(282, 82%, 51%)); box-shadow: 0 2px 12px hsla(247, 88%, 70%, .3)">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#fff">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                  </svg>
                </span>  <?= htmlspecialchars(isset($_SESSION['username']) ? $_SESSION['username'] : "Error: Username no está establecido en la sesión.") ?></h2>
              <p class="main__desc" style="display: none;">Bienvenido! Aqui podras vender tus tokens o recibir tus criptomonedas  rapido y seguro^_^</p>
              <p class="main__sub" style="padding-top: 20px !important;">
    <span style="font-size: 50px;">Saldo:</span> 
    <span style="font-size: 50px;">$
    <?= htmlspecialchars($miBilletera1 ?? "Error: Saldo no disponible.") ?> COP</span>
</p>
            </div>

            <!-- LIST -->
            <div class="main__list-heading-wrap">
              <h2 class="main__list-heading ss-heading" style="font-size: 30px;">Mi Estudio</h2>
              <a href="#" class="ss-show"></a>
            </div>

            <ul class="main__list">


              <li class="main__list-item">
                <div class="main__list-item-image">
                  <img src="./logos/spaceModels.png" width="60" alt="">
                </div>
                <div class="main__list-content-wrap">
                  <p class="main__list-content" style="padding-left: 20px; font-size: 20px;">Space Models</p>
                  <p class="main__list-sub" style="padding-left: 20px;padding-top: 5px; font-size: 14px;">Cucuta - Bucaramanga - Medellin</p>
                </div>
              </li>

              <li class="main__list-item" style="opacity: 0;">
                <div class="main__list-content-wrap">
                  <p class="main__list-content">Wadi Hanifah</p>
                  <p class="main__list-sub">At Last</p>
                </div>
              </li>

              <li class="main__list-item" style="opacity: 0;">
                <div class="main__list-content-wrap">
                  <p class="main__list-content">Heet Cave</p>
                  <p class="main__list-sub">Loved</p>
                </div>
              </li>

              <li class="main__list-item">
                <div class="main__list-item-image" style="opacity: 0;">
                  <img src="https://images.unsplash.com/photo-1575279146056-963c4a35627b?crop=entropy&cs=tinysrgb&fit=crop&fm=jpg&h=40&ixid=eyJhcHBfaWQiOjF9&ixlib=rb-1.2.1&q=80&w=40" alt="">
                </div>
                <div class="main__list-content-wrap">
                <p class="main__desc" style="font-weight: bold; color: white !important; font-size:20px ">Bienvenido! Aqui podras vender tus tokens o recibir tus criptomonedas  rapido y seguro, Si tienes preguntas no dudes en presionar el boton "WhatsApp" para ayudarte de inmmediato.</p>
                </div>
              </li>
                <p class="main__list-sub"></p>

            </ul>

          </div>

          <!-- COL-2 -->
          <div class="main__col-2">

            <!-- CARDS -->
            <div class="main__cards-container" style="display: none;">

              <div class="main__cards-container-heading-wrap">
                <h2 class="main__cards-container-heading ss-heading">MIS BILLETERAS</h2>
                <a href="#" class="ss-show"></a>
              </div>

              <ul class="main__cards">
                <li class="main__card" style="--hue: 25; opacity: 0;">
                  <div class="main__card-image-container">
                    <img src="https://images.unsplash.com/photo-1542037104857-ffbb0b9155fb?crop=entropy&cs=tinysrgb&fit=crop&fm=jpg&h=140&ixid=eyJhcHBfaWQiOjF9&ixlib=rb-1.2.1&q=80&w=220" alt="" class="main__card-image">
                  </div>
                  <h3 class="main__card-heading">BITCOIN</h3>
                  <p class="main__card-heading-sub">BTC</p>
                  <p class="main__card-heading-type">Ver</p>
                </li>

                <li class="main__card" style="--hue: 25;">
                  <div class="main__card-image-container">
                    <img src="./fondos/bt.jpeg" width="220" alt="" class="main__card-image">
                  </div>
                  <h3 class="main__card-heading">BITCOIN</h3>
                  <p class="main__card-heading-sub">BTC</p>
                  <p class="main__card-heading-type">Ver</p>
                </li>

                <li class="main__card" style="--hue: 231;">
                  <div class="main__card-image-container">
                    <img src="./fondos/tete.jpeg" width="250" alt="" class="main__card-image">
                  </div>
                  <h3 class="main__card-heading">TETHER</h3>
                  <p class="main__card-heading-sub">USDT</p>
                  <p class="main__card-heading-type">Ver</p>
                </li>
              </ul>

              <div class="main__cards-pagination" style="opacity: 0;">
                <span class="ss-dots">
                  <span></span>
                  <span></span>
                  <span></span>
                </span>
                <div class="main__cards-buttons">
                  <button style="opacity: .4">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                  </button>
                  <button>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                  </button>
                </div>
              </div>

            </div>

            <!-- CROSSING -->
            <div class="main__crossing-container">
              <div class="main__crossing-image">
                <img src="./fondos/666.webp" width="500" alt="">
              </div>
              <div class="main__crossing-current">
                <p class="main__crossing-upper">
                  MONITOREA TUS RETIROS
                </p>
                <h3 class="main__crossing-heading" style="font-weight: bold; font-size: 30px;">
                HISTORIAL DE RETIRO

                </h3>
              </div>
            </div>

            <!-- DISCOVER -->
            <div class="main__discover" >

              <div class="main__discover-heading-container" >
                <h3 class="main__discover-heading ss-heading">Historial</h3>
                <a href="#" class="ss-show"></a>
              </div>

              <ul class="main__discover-places">
   




              <style>



.historial-container {
  display: flex;
  flex-wrap: wrap;
  gap: 20px; /* Puedes ajustar el espacio entre cartas */
  overflow-y: auto;
  max-height: 200px; /* Ajusta esto según necesites */
  padding: 10px;
}

.historial-card {
  background: #2c3e50; /* Un color oscuro para combinar con tu tema */
  color: #ecf0f1; /* Un color claro para el texto */
  border-radius: 10px; /* Esquinas redondeadas */
  padding: 20px;
  box-shadow: 0 4px 8px rgba(0,0,0,0.1); /* Sombra sutil para profundidad */
  min-width: 250px; /* Ancho mínimo de cada tarjeta */
  max-width: calc(50% - 20px); /* Ancho máximo para 2 cartas por fila, ajusta el gap si cambias esto */
}

.historial-card p {
  margin: 5px 0; /* Asegúrate de que los párrafos no estén muy juntos */
}


/* Estiliza la barra de desplazamiento para todo el contenedor */
.historial-container::-webkit-scrollbar {
  width: 12px; /* Ancho de la barra de desplazamiento */
}

/* Estilo para la "track" (la parte por donde se desliza el "thumb") */
.historial-container::-webkit-scrollbar-track {
  background: #34495e; /* Un tono ligeramente más claro que el fondo del contenedor */
  border-radius: 10px; /* Bordes redondeados para la track */
}

/* Estilo para el "thumb" (la parte que se mueve de la barra de desplazamiento) */
.historial-container::-webkit-scrollbar-thumb {
  background-color: #ecf0f1; /* Color de tu elección para el thumb */
  border-radius: 10px; /* Bordes redondeados para el thumb */
  border: 3px solid #34495e; /* Borde sólido con el color del fondo de la track */
}

/* Estilo para el estado "hover" del thumb */
.historial-container::-webkit-scrollbar-thumb:hover {
  background: #bdc3c7; /* Un color un poco más claro cuando se pasa el ratón por encima */
}



</style>



            <!-- DISCOVER -->
            <div class="main__discover" >

              <div class="main__discover-heading-container" >
                <h3 class="main__discover-heading ss-heading">Historial</h3>
                <a href="#" class="ss-show"></a>
              </div>
              <div style="overflow-y: scroll; height: 200px;" class="historial-container">
              <?php
// Asegúrate de que $pdo es tu objeto de conexión PDO
$query = "SELECT retiros.valor_retirar, retiros.identificador_transaccion, retiros.fecha_hora, retiros.estado, bancos_usuarios.nombre_banco, bancos_usuarios.tipo_cuenta FROM retiros INNER JOIN bancos_usuarios ON retiros.banco_id = bancos_usuarios.id WHERE retiros.user_id = :userId ORDER BY retiros.fecha_hora DESC";
$stmt = $pdo->prepare($query);
$stmt->execute(['userId' => $userId]);
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "<div class='historial-card'>";
    echo "<p>Banco: " . htmlspecialchars($row['nombre_banco']) . "</p>";
    echo "<p>Tipo de Cuenta: " . htmlspecialchars($row['tipo_cuenta']) . "</p>";
    echo "<p>Monto: " . htmlspecialchars($row['valor_retirar']) . "</p>";
    echo "<p>Identificador Transacción: " . htmlspecialchars($row['identificador_transaccion']) . "</p>";
    echo "<p>Estado: " . htmlspecialchars($row['estado']) . "</p>";
    echo "<p>Fecha: " . htmlspecialchars($row['fecha_hora']) . "</p>";
    echo "</div>";
}
?>

              </div>


            </div>



              </ul>

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

  </body>


</html>
