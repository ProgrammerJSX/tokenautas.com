<?php
//verImagenesWallets.php
function getUserImages($userId, $pdo) {
    $sql = "SELECT imagenusdt, imagenbtc FROM usuarios WHERE user_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$userId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
