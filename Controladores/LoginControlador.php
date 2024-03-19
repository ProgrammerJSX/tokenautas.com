<?php
// Controladores/LoginControlador.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once __DIR__ . '/../conexion.php';
require_once __DIR__ . '/../Controladores/UsuarioControlador.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $usuarioControlador = new UsuarioControlador($pdo);

    // Validar credenciales del usuario
    if ($usuarioControlador->validarCredenciales($username, $password)) {
        // Obtén los detalles del usuario de la base de datos
        $user = $usuarioControlador->obtenerUsuarioPorNombreUsuario($username);

        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;

        // Asegúrate de que el user_id se está estableciendo correctamente en la sesión
        if ($user['user_id'] !== NULL) {
            $_SESSION['user_id'] = $user['user_id'];
        } else {
            // maneja el caso en que user_id es NULL
            // puedes redirigir al usuario a una página de error o mostrar un mensaje de error
        }

        header("Location: ../Vistas/dashboard.php");
        exit;
    } else {
        $_SESSION['mensaje'] = "Nombre de usuario o contraseña incorrecta.";
        header("Location: ../Vistas/login.php");
        exit;
    }
}
?>
