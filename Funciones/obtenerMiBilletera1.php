<?php
// Funciones/obtenerMiBilletera1.php

function obtenerMiBilletera1($pdo, $userId) {
    // Asegúrate de que $userId es el identificador del usuario cuyo valor de mi_billetera1 quieres obtener
    $stmt = $pdo->prepare("SELECT mi_billetera1 FROM usuarios WHERE user_id = ?");
    $stmt->execute([$userId]);
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    return $resultado ? $resultado['mi_billetera1'] : '0.00'; // Retorna '0.00' si no hay resultados
}
