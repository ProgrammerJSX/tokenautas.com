<?php

function mostrarBancosUsuario($pdo, $userId) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM bancos_usuarios WHERE user_id = :userId");
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $bancos = $stmt->fetchAll();

        if (!$bancos) {
            return "<p>No tienes bancos registrados.</p>";
        }

        $output = "";
        foreach ($bancos as $banco) {
            $output .= "<div class='bank-card'>";
            $output .= "<p>Alias: " . $banco['alias'] . "</p>";
            $output .= "<p>Nombre del Banco: " . $banco['nombre_banco'] . "</p>";
            $output .= "<p>Tipo de Cuenta: " . $banco['tipo_cuenta'] . "</p>";
            $output .= "<p>Titular de la Cuenta: " . $banco['titular_cuenta'] . "</p>";
            $output .= "<p>Cédula del Titular: " . $banco['cedula_titular'] . "</p>";
            $output .= "<p>Número de Cuenta: " . $banco['numeroCuenta'] . "</p>";
            $output .= "</div>"; // Cierre del div bank-card
        }
        return $output;
    } catch (PDOException $e) {
        error_log('PDOException - ' . $e->getMessage());
        return "<p>Error al obtener los bancos: " . $e->getMessage() . "</p>";
    }
}
?>
