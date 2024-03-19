<?php
//verWalletBTC.php
function obtenerValorWalletBTC($idUsuario, $pdo) {
    $stmt = $pdo->prepare("SELECT walletBTC FROM usuarios WHERE user_id = ?");
    $stmt->execute([$idUsuario]);
    return $stmt->fetch(PDO::FETCH_ASSOC)['walletBTC'] ?? null;
}
?>
