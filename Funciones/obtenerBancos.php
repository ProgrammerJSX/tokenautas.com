<?php

function obtenerBancosUsuario($pdo, $userId) {
    $bancos = [];
    try {
        $sql = "SELECT banco_id, nombre_banco, tipo_cuenta FROM bancos_usuarios WHERE user_id = :userId";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $bancos = $stmt->fetchAll();
    } catch (PDOException $e) {
        // Aquí puedes manejar el error como prefieras, por ahora lo escribiremos en el log
        error_log('PDOException - ' . $e->getMessage(), 0);
        // Si quieres puedes descomentar la línea siguiente para mostrar el error en el HTML
        // echo 'Error al obtener los bancos: ' . $e->getMessage();
    }
    return $bancos;
}
?>
