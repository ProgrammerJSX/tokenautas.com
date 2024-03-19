<?php
session_start();

// Verifica si el usuario está logueado y tiene un ID de sesión.
if (!isset($_SESSION['loggedin']) || !isset($_SESSION['user_id'])) {
    exit('Usuario no autorizado.');
}

require_once __DIR__ . '/../conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && $conn) {
    $userId = $_SESSION['user_id'];
    $numeroWallet = $_POST['numeroWallet'] ?? '';
    $monto = $_POST['montoDepositar'] ?? 0;
    $estado = 'pendiente'; // Estado por defecto

     // Preparar y vincular
     $stmt = $conn->prepare("INSERT INTO depositos (user_id, numero_wallet, monto, estado) VALUES (?, ?, ?, ?)");
     $stmt->bind_param("isds", $userId, $numeroWallet, $monto, $estado);
 

    if ($stmt = $conn->prepare("INSERT INTO depositos (user_id, numero_wallet, monto) VALUES (?, ?, ?)")) {
        $stmt->bind_param("isd", $userId, $numeroWallet, $monto);

        if ($stmt->execute()) {
            echo "<script>console.log('Depósito realizado con éxito.');</script>";
            echo "<p>Depósito realizado con éxito.</p>";
        } else {
            echo "<script>console.log('Error al realizar el depósito: " . $stmt->error . "');</script>";
            echo "<p>Error al realizar el depósito: " . $stmt->error . "</p>";
        }
        $stmt->close();
    } else {
        echo "<script>console.log('Error al preparar la consulta: " . $conn->error . "');</script>";
        echo "<p>Error al preparar la consulta: " . $conn->error . "</p>";
    }
    $conn->close();
} else {
    echo "<script>console.log('Error en el método de solicitud o conexión a la base de datos.');</script>";
    echo "<p>Error en el método de solicitud o conexión a la base de datos.</p>";
}
?>
