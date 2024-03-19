<?php
//verWalletUSDT.php
function obtenerValorWalletUSDT($idUsuario, $pdo) {
    $stmt = $pdo->prepare("SELECT walletUSDT FROM usuarios WHERE user_id = ?");
    $stmt->execute([$idUsuario]);
    return $stmt->fetch(PDO::FETCH_ASSOC)['walletUSDT'] ?? null;
}
?>
