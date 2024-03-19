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

    <title>Proyecto 01</title>
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


   
<style>
      /* Menu hamburguesa*/
/* Estilo del botón de hamburguesa */
.boton-hamburguesa {
    cursor: pointer;
    font-size: 30px;
    z-index: 20; /* Asegura que el botón esté por encima de la modal */
    position: relative; /* Asegura que el z-index funcione correctamente */
}

/* Estilos para la ventana modal del menú */
.enlaces-menu {
    display: none;
    position: fixed; /* Posición fija en la pantalla */
    top: 0; /* Alineado a la parte superior */
    left: 0; /* Alineado a la izquierda */
    width: 100%; /* Ancho completo */
    height: 100vh; /* Alto completo de la ventana del navegador */
    background-color: rgba(0,0,0,0.9); /* Fondo semi-transparente */
    z-index: 10; /* Asegura que el menú esté por encima del contenido */
    justify-content: center; /* Centra los enlaces verticalmente */
    align-items: center; /* Centra los enlaces horizontalmente */
    flex-direction: column; /* Organiza los enlaces en columna */
    text-align: center; /* Alinea el texto de los enlaces al centro */
}

/* Estilo para los enlaces dentro del menú modal */
.enlaces-menu a {
    display: block; /* Hace que cada enlace sea un bloque */
    margin: 10px 0; /* Espaciado entre enlaces */
    font-size: 1.2em; /* Tamaño de fuente mayor */
    color: white; /* Color de texto */
    text-decoration: none; /* Sin subrayado en los enlaces */
}

/* Cuando el menú está activo, se muestra */
.enlaces-menu.activado {
    display: flex; /* Usamos flex para centrar el contenido fácilmente */
}

</style>
    
<body class="grid-container">
    <header class="header" style="margin: 0; padding: 0; margin-bottom:50px">


        
        <div class="logo" style="z-index: 15;"><img src="./AssetsVistaC/imagenesAll/logos/tokenautasBlanco-02.png" width="300" alt=""></div>
       
        <div class="menu-hamburguesa" style="z-index: 666; background-color:red; display:flex; justify-content:flex-start; position:">
    <div class="boton-hamburguesa" id="botonMenu" style="background-color: #eee; width:50px; position: absolute; top:20px; border-radius: 5px; ">
        &#9776;
    </div>
    <div class="enlaces-menu" id="enlacesMenu">
        <a href="./Vistas/dashboardVistaCelular.php" style="color: white !important;">Inicio</a>
        <a href="./Vistas/dashboardVistaCelular.php" style="color: white !important;">Retirar</a>
        <a href="./Vistas/depositar.php" style="color: white !important;">Depositar</a>
        <a href="./Vistas/misbancos.php" style="color: white !important;">Mis Bancos</a>
        <a href="./Vistas/registrarBancos.php" style="color: white !important;">Registrar Bancos</a>
        <a href="./Vistas/historialderetiro.php" style="color: white !important;">Historial de Retiro</a>
        <a href="./logout.php">Salir</a>
    </div>
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
                </span>
                <span class="usuarioNombre nombre-usuario-con-icono">
                    <i class="fa-regular fa-user"></i>
                    <p class="mile email-usuario"><?php echo $_SESSION['username']; ?></p>
                </span>
                <span class="usuarioNombre cantidad-usuario">$<?php echo $miBilletera1 ?? '0.00'; ?></span>
                <span class="usuarioNombre moneda-usuario">COP: Pesos Colombianos</span>
            </div>
            
            <!-- Aquí tus otros divs permanecen sin cambios, solo asegúrate de añadir las nuevas clases donde sea necesario -->
            
            <div class="gradient-text detailUser">
                
                 <span class="usuarioNombre miestudio">MI ESTUDIO</span>
           
                </div>
            <div class="gradient-text detailUser">
                <div class="imagenCliente">
                    <span class="usuarioNombre" style="display: flex; justify-content: center; align-items: center; gap: 10px; padding-bottom: 20px;"><img src="./AssetsVistaC/imagenesAll/logos/spaceModels.png" width="50" alt=""> <span style="font-size: 16px;">Space Models</span></span>
                </div>

                
              

                 
        
                </div>
            </aside>

    </header>
    



      
    <article class="main">

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

    <div class="footer" style="position: relative;">
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

        
            
        <div class="cardsxxxx" style="padding-bottom: 200px; position: relative;">
        
        <div class="cajaxxx">
            <div class="cajachaturb chat1">Chaturbate</div>
            <div class="cajachaturb chat2">www.chaturbate.com</div>
            <div class="cajachaturb chat3"><img src="./AssetsVistaC/imagenesAll/marcas/chaturbate.png" width="100" alt="">
            
            </div>

            


        </div>
        <div class="cajaxxx">
            <div class="cajachaturb chat1">StripChat</div>
            <div class="cajachaturb chat2">www.stripchat.com</div>
            <div class="cajachaturb chat3"><img src="./AssetsVistaC/imagenesAll/marcas/stripchat.png" width="100" alt=""></div>

            


        </div>
        <div class="cajaxxx">
            <div class="cajachaturb chat1">XvideosCams</div>
            <div class="cajachaturb chat2">www.xvideoscams.com</div>
            <div class="cajachaturb chat3"><img src="./AssetsVistaC/imagenesAll/marcas/xvideosCams.png" width="100" alt=""></div>

            


        </div>
        <div><button class="buttonInicio">ver mas</button></div>
        <span style="padding-top: 20px;">...</span>
        </div>

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
  
</body>
</html>