<?php

function obtenerBancosUsuarioOptimizada($pdo, $userId) {
    $stmt = $pdo->prepare("SELECT id, nombre_banco, tipo_cuenta FROM bancos_usuarios WHERE user_id = :userId");
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>
