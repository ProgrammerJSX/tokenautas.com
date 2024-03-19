<?php
// Controladores/RegistroControlador.php
session_start();
require_once __DIR__ . '/../conexion.php';
require_once __DIR__ . '/../Funciones/automatizacionIMG.php';
require_once __DIR__ . '/../Modelos/Usuario.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    [$imagenusdt, $palabraSecretaUSDT] = seleccionarImagenYPalabra('usdt', $pdo);
    [$imagenbtc, $palabraSecretaBTC] = seleccionarImagenYPalabra('btc', $pdo);

    $usuarioModelo = new Usuario($pdo);

    $registroExitoso = $usuarioModelo->registrar($username, $password, $imagenusdt, $imagenbtc, $palabraSecretaUSDT, $palabraSecretaBTC);

    if ($registroExitoso) {
        header("Location: ../Vistas/login.php");
        exit;
    } else {
        echo "Error al registrar el usuario.";
    }
}
?>
