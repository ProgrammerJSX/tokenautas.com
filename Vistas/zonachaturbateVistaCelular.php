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
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./AssetsVistaC/style.css">
    <link rel="stylesheet" href="./AssetsVistaC/css/navbar.css">
    <link rel="stylesheet" href="./AssetsVistaC/css/footer.css">
    <link rel="stylesheet" href="./AssetsVistaC/css/componenteTitulo.css">
    <link rel="stylesheet" href="./AssetsVistaC/css/footerReal.css">
    <link rel="stylesheet" href="./AssetsVistaC/css/main.css">
    <link rel="stylesheet" href="./AssetsVistaC/css/header.css">
    <link rel="stylesheet" href="./AssetsVistaC/css/cardsxxx2025.css">

    <title>Zona USDT</title>
    <!-- FONTAWESOME -->
    <script src="https://kit.fontawesome.com/b036b95ef5.js" crossorigin="anonymous"></script>
    <!--FONT AWESOME-->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">



</head>

    
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


   

    
<body class="grid-container">
    <header class="header" style="margin: 0; padding: 0; margin-bottom:50px">

        <a href="./dashboardVistaCelular.php">
        <div class="back" style="position: absolute;  cursor: pointer; padding:10px; display: flex; justify-content: center">
        <img src="./AssetsVistaC/imagenesAll/asaasa.png" style="position:;" width="30" alt=""><span style="font-weight: bold; color: white; font-size:14px" >Regresar</span>
        </div></a>
        <div class="logo" style="z-index: 15;"><img src="./AssetsVistaC/imagenesAll/logos/tokenautasBlanco-02.png" width="300" alt=""></div>
    
</div>


        <style>
            .detailUser{
                display: flex;
                justify-content: center;
                gap: 20px;
            }
            .detailUser1{
                display: flex;
                flex-direction: column;
                justify-content: center;
                gap: 20px;
            }
            
        </style>
        


<style>


    /* Añade esto a tu archivo CSS existente sin borrar tus estilos previos */

.imagen-usuario img {
    border-radius: 50%;
}

.nombre-usuario-con-icono {
    display: flex;
    align-items: center;
    gap: 10px;
}

.icono-circular {
    display: inline-block;
    width: 15px;
    height: 15px;
    border-radius: 50%;
    background-color: black;
}

.email-usuario,.cantidad-usuario,.moneda-usuario,.miestudio {
    background: -webkit-linear-gradient(#eee, #333);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.cantidad-usuario {
    font-size: 36px;
    font-weight: bold;
}

.moneda-usuario {
    font-size: 18px;
}

.miestudio{
    font-size: 35px;
    padding: 40px;
}

/* Asegúrate de que las nuevas clases no estén sobreescribiendo propiedades de las clases antiguas si no es intencional */

</style>


          <aside class="sidebar">
            
            
            <div class="gradient-text detailUser detalle-usuario" style="display: flex; flex-direction: column; justify-content: center; align-items: center;"> 
            <div>
      <h2 class="main__heading"><span style="background: linear-gradient(to bottom, hsl(70, 6%, 21%), hsl(300, 3%, 6%)); box-shadow: 0 2px 12px hsla(247, 88%, 70%, .3)">
        
        </span> Hola <?php echo $_SESSION['username']; ?> a continuacion encontraras las instrucciones para que puedas vender tus tokens y recibir tu pago en cuestion de minutos. ¡Esa es nuestra promesa!</h2>
      <p class="main__desc" style="font-size: 50px; display:none">Tether</p>
      <p class="main__sub" style="font-size: 25px; display:none " ><span>RED:</span>TRC20<span></span></p>
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
            <p> <span style="opacity: 0;"></span> <img width="400" src="./AssetsVistaC/imagenesAll/marcas/chaturbate.png" alt=""></p>
            </div>
   
          </div>
</div>

<ul class="main__list">

<li class="main__list-item">
<div class="main__list-item-image" style="cursor: pointer; padding-bottom: 10px ;" onclick="copiarAlPortapapeles(this)">
  <img src="../img/copiar.png" width="40px" alt="">
</div>
<div class="main__list-content-wrap">
  <p class="main__list-content" style="padding-bottom: 50px ; color:white"><a href="https://chaturbate.com/tokenauta/" style="color: white !important;">https://chaturbate.com/tokenauta/</a></p>
  <p class="main__list-sub" style="  padding-bottom: 50px ;color:white !important"><a href="https://chaturbate.com/tokenauta/" style="color: white !important;" >Ir a nuestro perfil</a></p>
</div>

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


<div style="background-color: white; width: 100%; padding: 20px; height:auto;display:flex; flex-direction:column;justify-content: center;  background-color: hsl(270, 50%, 10%);"> 

<div id="dis" class="disclamerMobile1" style="background-color: white; box-shadow: 10px 15px 10px black; border-radius:5px; width:100%; text-align: center; align-items:center; justify-content: center; display: flex; flex-direction:column"> <p style="color: #333; padding:20px" class="original"> Solamente tienes que ir a nuestro perfil y enviarnos los tokens, una vez lo hagas toma un "pantallazo" y envialo a nuestro whatsapp para desembolsarte tu dinero de inmediato

<span class="dots">...</span>

<p style="color: #333; padding:20px" class="extra" id="hideText">
Recomendamos encarecidamente verificar la cantidad de tokens a transferir, gracias.</p>

</p>


<a href="" id="hideText_btn">Leer mas</a>


<script>
  let hideText = document.getElementById('hideText');
  let hideText_btn = document.getElementById('hideText_btn');
 

  hideText_btn.addEventListener('click', function(event) {
    event.preventDefault(); // Esto evita el comportamiento predeterminado.
    toggleText();
  });

  function toggleText(){
    hideText.classList.toggle('show');
    if(hideText.classList.contains('show')){
      hideText_btn.innerHTML = "Leer Menos";
    }else{
      hideText_btn.innerHTML = "Leer Mas";
    }
  }
</script>

</div>



<style>
  /* estilos de ver mas */

  .extra{
    display: none;
  }

  .show{
    display: block;
  }

</style>

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

    </div>

            </div>
            
            <!-- Aquí tus otros divs permanecen sin cambios, solo asegúrate de añadir las nuevas clases donde sea necesario -->
            <div class="ocultador" style="display: none;">
            <div class="gradient-text detailUser">
                
                 <span class="usuarioNombre miestudio">MI ESTUDIO</span>
           
                </div>
            <div class="gradient-text detailUser">
                <div class="imagenCliente">
                    <span class="usuarioNombre" style="display: flex; justify-content: center; align-items: center; gap: 10px; padding-bottom: 20px;"><img src="./AssetsVistaC/imagenesAll/logos/spaceModels.png" width="50" alt=""> <span style="font-size: 16px;">Space Models</span></span>
                </div>

                </div>
           

                 
        
                </div>
            </aside>

    </header>
    



      
    <article class="main" style="display:none">

        <div class="card-container">
            <div class="card bitcoin">
                <h2>Bitcoin</h2>
                <p class="wallet-address"><?php echo $valorWalletBTC; ?></p>
               <a href=""><button class="copy-btn">Abrir</button></a> 
            </div>
            <div class="card tether">
                <h2>Tether</h2>
                <p class="wallet-address"><?php echo $valorWalletUSDT; ?></p>
                <a href=""><button class="copy-btn">Abrir</button></a> 
            </div>
        </div>

    </article>

    <div class="footer" style="position: relative; display: none;">
        <div class="tituloDestacado" style="position: relative;">
            <style>
                .main__crossing-container {
                    background-color: hsl(270, 50%, 11%);
                    background-image: linear-gradient(120deg, hsla(26, 80%, 50%, .5), 10%, hsl(270, 50%, 11%) 60%), url(https://images.unsplash.com/photo-1506197603052-3cc9c3a201bd?crop=entropy&cs=tinysrgb&fit=crop&fm=jpg&h=100&ixid=eyJhcHBfaWQiOjF9&ixlib=rb-1.2.1&q=80&w=600);
                    box-shadow: 0 6px 16px 0 hsla(270, 30%, 3%, .4);
                    border-radius: 8px;
                    padding: 2rem 3.6rem;
                    display: flex;
                    align-items: center;
                    transform: translate(-2rem, 2.4rem);
                    position: relative;
                    z-index: 2;
                    margin-top: -50px;
                    width: 100%; /* Ajuste para ocupar el ancho completo */
                }
    
                /* Media query para dispositivos con un ancho mínimo de 768px */
                @media (min-width: 768px) {
                    .main__crossing-container {
                        width: 88%; /* Ajuste para dispositivos más grandes */
                        max-width: 420px; /* Máximo ancho para mantener el diseño */
                        margin: auto; /* Centrar el contenedor si es más estrecho que el viewport */
                        transform: none;
                    }
                }
            </style>
    
            <div class="main__crossing-container">
                <div class="main__crossing-current">
                    <p class="main__crossing-upper" style="font-size: 13px;">
                        PAGO EN MENOS DE 24 HORAS
                    </p>
                    <h3 class="main__crossing-heading" style="font-size:25px; font-weight: bold;">
                        VENDE TUS TOKENS
                    </h3>
                </div>
            </div>
        </div>

        
            
        <div class="cardsxxxx" style="padding-bottom: 200px; position: relative; max-width:450px ">
        



            <!-- DISCOVER -->
            <div class="main__discover" style="height:90vh !important">

         

              <ul class="main__discover-places">

                <style>

                  /* Estilos para la nueva sección de retiro de bancos */
                  .withdraw-section {
                  background-color: #2c3e50 !important; /* Fondo oscuro para el formulario */
                  padding: 20px !important;
                  
                  border-radius: 10px !important; /* Esquinas redondeadas */
                  color: #ecf0f1 !important; /* Color de texto claro */
                  margin: 10px 140px!important;
                  max-width: 500px !important; /* Ajusta esto según el ancho deseado */
                  }
                  
                  .withdraw-section .withdraw-heading {
                  padding: 20px !important;
                  text-align: center !important;
                  font-size: 1.5rem !important; /* Tamaño de la fuente para el encabezado */
                  }
                  
                  .withdraw-section .input-estilo-retiro {
                  width: 100% !important; /* Ocupar el ancho total */
                  padding: 12px !important; /* Espaciado interno */
                  margin-bottom: 15px !important; /* Espaciado inferior */
                  border-radius: 5px !important; /* Esquinas redondeadas para los inputs */
                  border: 1px solid #34495e !important; /* Borde de los inputs */
                  background-color: #3a506b !important; /* Fondo de los inputs */
                  color: #ecf0f1 !important; /* Color de texto de los inputs */
                  }
                  
                  .withdraw-section .boton-estilo-retiro {
                  width: 100% !important; /* Ocupar el ancho total */
                  padding: 15px !important; /* Espaciado interno */
                  margin-top: 15px !important; /* Espaciado superior */
                  border-radius: 5px !important; /* Esquinas redondeadas para el botón */
                  background-color: #2980b9 !important; /* Fondo del botón */
                  color: #ecf0f1 !important; /* Color de texto del botón */
                  border: none !important; /* Sin bordes para el botón */
                  cursor: pointer !important; /* Estilo del cursor como puntero */
                  font-size: 1rem !important; /* Tamaño de la fuente del botón */
                  }
                  
                  .withdraw-section .boton-estilo-retiro:hover {
                  background-color: #3498db !important; /* Color de fondo al pasar el ratón por encima */
                  }
                  
                  
                      </style>
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  <style>
                    body {
                      font-family: 'Arial', sans-serif;
                      background-color: #f4f4f4;
                      margin: 0;
                      padding: 0;
                    }
                  
                    .withdraw-section {
                      background-color: #333;
                      padding: 20px;
                      color: white;
                      border-radius: 5px;
                      max-width: 600px;
                      margin: 50px auto;
                    }
                  
                    .withdraw-heading {
                      font-size: 1.5em;
                      margin-bottom: 20px;
                    }
                  
                    .input-estilo-retiro, #boton_retirar {
                      width: 100%;
                      padding: 10px;
                      margin-bottom: 10px;
                      border-radius: 5px;
                      border: 1px solid #ddd;
                    }
                  
                    .input-estilo-retiro:focus, #boton_retirar:focus {
                      outline: none;
                      border-color: #666;
                    }
                  
                    #boton_retirar {
                      background-color: #5cb85c;
                      color: white;
                      border: none;
                      cursor: pointer;
                      font-size: 1em;
                    }
                  
                    #boton_retirar:hover {
                      background-color: #4cae4c;
                    }
                  
                    .heading-visible {
                      color: white;
                      text-align: center;
                      margin-top: 20px;
                    }
                  </style>
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  <!-- DISCOVER -->
                  
                  
                  
                  <?php
                  // Suponiendo que ya iniciaste la sesión y conectaste a la base de datos
                  
                  if ($_SERVER["REQUEST_METHOD"] == "POST") {
                      $bancoId = $_POST['banco_seleccionado'];
                      $valorRetirar = $_POST['valor_retirar'];
                      $identificadorTransaccion = $_POST['identificador_transaccion'];
                      $estado = $_POST['estado']; // Asegúrate de que este valor sea 'pendiente' o cualquier otro valor válido en tu lógica de negocio
                  
                      // Preparar y ejecutar la consulta para insertar el retiro
                      $query = "INSERT INTO retiros (user_id, banco_id, valor_retirar, identificador_transaccion, estado) VALUES (?, ?, ?, ?, ?)";
                      $stmt = $pdo->prepare($query);
                      $stmt->execute([$_SESSION['user_id'], $bancoId, $valorRetirar, $identificadorTransaccion, $estado]);
                  
                      // Redireccionar o mostrar un mensaje de éxito/error según corresponda
                  }
                  ?>
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  <div class="withdraw-section">
                    <div class="main__discover-heading-container">
                      <h3 class="main__discover-heading withdraw-heading ss-heading">SELECCIONA EL BANCO QUE YA REGISTRASTE</h3>
                      <a href="#" class="ss-show"></a>
                    </div>
                    <form id="formRetiro" method="post" action="../Funciones/procesarRetiro.php">
                    <select id="banco_seleccionado" name="banco_seleccionado" class="input-estilo-retiro">
                    <?php
                    // Asegúrate de que estás llamando a la función correcta y que $userId está definido
                    $bancosUsuario = obtenerBancosUsuarioOptimizada($pdo, $userId);
                  
                    if (empty($bancosUsuario)) {
                        echo "<option>No se encontraron bancos registrados.</option>";
                    } else {
                        foreach ($bancosUsuario as $banco) {
                            echo "<option value='" . $banco['id'] . "'>" . $banco['nombre_banco'] . " - " . $banco['tipo_cuenta'] . "</option>";
                        }
                    }
                    ?>
                  </select>
                  
                  
                      <input type="number" id="cantidad_retirar" name="valor_retirar" class="input-estilo-retiro" placeholder="Cantidad a retirar" required>
                      <input type="hidden" id="identificador_transaccion" name="identificador_transaccion" value="<?php echo uniqid(); ?>">
                      <input type="hidden" name="estado" value="pendiente">
                      <button type="submit" id="boton_retirar" style="font-size: 20px;">Retirar!</button>
                    </form>
                    <h1 class="heading-visible" style="font-weight: 600; font-size:15px ">Puedes monitorear el estado de tu retiro en la seccion <br> <a style="color: #fff ;" href="./historialderetiro.php">"Historial de retiro" </a></h1>
                  </div>
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  <script>
                  function generarRetiro() {
                    document.getElementById('identificador_transaccion').value = 'tx-' + Date.now();
                    document.getElementById('formRetiro').submit();
                  }
                  
                  document.getElementById('formRetiro').addEventListener('submit', function(event) {
                    generarRetiro();
                    // event.preventDefault();
                  });
                  </script>
                  
              </ul>

            </div>

            <!-- FOOTER -->




















    </div>
    
</div>

<style>
    .buttonInicio {
  background-color: #0201039f; /* Un morado similar al de los botones en la imagen */
  color: #ffffff; /* Texto blanco para mayor contraste */
  padding: 10px 20px; /* Espaciado interno para hacerlo más grande */
  border: none; /* Sin borde */
  border-radius: 10px; /* Esquinas redondeadas */
  font-size: 16px; /* Tamaño de texto adecuado */
  cursor: pointer; /* Cursor tipo puntero para indicar que es clickeable */
  transition: background-color 0.3s; /* Transición suave para efectos de hover */
  outline: none; /* Quitar el contorno que aparece al hacer click */
  margin-top: 10px;
}

.buttonInicio:hover {
  background-color: #7E57C2; /* Un morado más claro para el efecto hover */
}

</style>































<script>

    // Espera a que el documento se cargue completamente
document.addEventListener('DOMContentLoaded', (event) => {
    // Selecciona el elemento
    var footer = document.querySelector('.footer');

    // Asegúrate de que el elemento exista para evitar errores
    if (footer) {
        // Establece el fondo a transparente
        footer.style.backgroundColor = 'transparent';
        // Elimina cualquier sombra de caja que pueda estar causando la "marca"
        footer.style.boxShadow = 'none';
        // Elimina cualquier otro estilo que pueda estar interfiriendo
        footer.style.border = 'none';
    }
});

</script>



<script>
    //menu hamburguesa

    document.getElementById('botonMenu').addEventListener('click', function() {
    var enlaces = document.getElementById('enlacesMenu');
    if (enlaces.classList.contains('activado')) {
        enlaces.classList.remove('activado');
    } else {
        enlaces.classList.add('activado');
    }
});

</script>
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