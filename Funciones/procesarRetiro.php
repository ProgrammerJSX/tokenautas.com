<?php



//Funciones/procesarRetiro.php










































session_start();

// Verifica que el usuario esté logueado y que el user_id esté establecido en la sesión
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || !isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Incluye la conexión a la base de datos
require_once __DIR__ . '/../conexion.php'; // Asegúrate de que la ruta es correcta

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_SESSION['user_id'];
    $bancoId = $_POST['banco_seleccionado'] ?? '';
    $valorRetirar = $_POST['valor_retirar'] ?? '';
    $identificadorTransaccion = $_POST['identificador_transaccion'] ?? '';
    $estado = $_POST['estado'] ?? 'pendiente';

    // Comprobar si el banco_id existe en bancos_usuarios para el usuario
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM bancos_usuarios WHERE id = :bancoId AND user_id = :userId");
    $stmt->execute([':bancoId' => $bancoId, ':userId' => $userId]);
    $count = $stmt->fetchColumn();

    if ($count == 0) {
        echo "Error: El banco seleccionado no existe.";
        exit;
    }

    // Preparar la consulta SQL para insertar el retiro
    $stmt = $pdo->prepare("INSERT INTO retiros (user_id, banco_id, valor_retirar, identificador_transaccion, estado) VALUES (:userId, :bancoId, :valorRetirar, :identificadorTransaccion, :estado)");
    $result = $stmt->execute([
        ':userId' => $userId, 
        ':bancoId' => $bancoId, 
        ':valorRetirar' => $valorRetirar, 
        ':identificadorTransaccion' => $identificadorTransaccion, 
        ':estado' => $estado
    ]);

    if ($result) {
        header("Location: ../Vistas/dashboard.php"); // Asumiendo que el archivo dashboard.php está en el directorio Vistas
        exit;
    } else {
        echo "Error al intentar registrar el retiro.";
    }
}

?>


