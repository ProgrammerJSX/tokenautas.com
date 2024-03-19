<?php
// Funciones/automatizacionIMG.php
// Arrays globales que puedes modificar según necesites

$arrayusdt = [
    ['imagen' => 'Assets/src/(NUMERO4) USDT TK8PxYPYmmUUr4UnmQxmXAE8kDBUCsgp8D.png', 'palabraSecreta' => 'TK8PxYPYmmUUr4UnmQxmXAE8kDBUCsgp8D'],
    ['imagen' => 'Assets/src/NUMERO 2 USDT  TYpH9rHr1ve2kPySiCiofgmF7L9be2aD2d.png', 'palabraSecreta' => 'TYpH9rHr1ve2kPySiCiofgmF7L9be2aD2d'],
    ['imagen' => 'Assets/src/NUMERO6 USDT  THxCnBgTex9SB89SpNbRLLGREQhrxHb9RQ.png', 'palabraSecreta' => 'THxCnBgTex9SB89SpNbRLLGREQhrxHb9RQ'],
    ['imagen' => 'Assets/src/NUMERO8 USDT TNpumi7rGi9TZQypayi3b5kRCHsPxRVW2F.png', 'palabraSecreta' => 'TNpumi7rGi9TZQypayi3b5kRCHsPxRVW2F'],
    ['imagen' => 'Assets/src/NUMERO10 USDT TJPKZLzA8qTsPKuSNx4P5Pjpgz4ZicHYcB.png', 'palabraSecreta' => 'TJPKZLzA8qTsPKuSNx4P5Pjpgz4ZicHYcB'],
    ['imagen' => 'Assets/src/NUMERO12 USDT TX3AzKjaJGwHeYH4KYi5PQ1XFQTmcB4DsF.png', 'palabraSecreta' => 'TX3AzKjaJGwHeYH4KYi5PQ1XFQTmcB4DsF'],
    ['imagen' => 'Assets/src/NUMERO14 USDT   TPDiaR9tdQNAr8edfhwUXEDoRdo5qPEuuZ.png', 'palabraSecreta' => 'TPDiaR9tdQNAr8edfhwUXEDoRdo5qPEuuZ'],
    ['imagen' => 'Assets/src/NUMERO16 USDT TEBcKKR4Ne5e8ojEemkDkUsz8KpJSreae6.png', 'palabraSecreta' => 'TEBcKKR4Ne5e8ojEemkDkUsz8KpJSreae6'],
];

$arraybtc = [
    ['imagen' => 'Assets/src/(NUMERO1)BTC   1LLqNQoeFrQPJWDq9ctAF89nkFXtA5Nygq.png', 'palabraSecreta' => '1LLqNQoeFrQPJWDq9ctAF89nkFXtA5Nygq'],
    ['imagen' => 'Assets/src/NUMERO5  BTC 1JvBVVCgQH36jqDe9dTKkbrELyNzoPTBKy.png', 'palabraSecreta' => '1JvBVVCgQH36jqDe9dTKkbrELyNzoPTBKy'],
    ['imagen' => 'Assets/src/NUMERO7 BTC  12sYzrtr48rJaCq8EGR7diRtswwmVU3H5L.png', 'palabraSecreta' => '12sYzrtr48rJaCq8EGR7diRtswwmVU3H5L'],
    ['imagen' => 'Assets/src/NUMERO9 BTC   1DgZBBeVFLfPQmqXkgTvcbVEwFoZd9kcHT.png', 'palabraSecreta' => '1DgZBBeVFLfPQmqXkgTvcbVEwFoZd9kcHT'],
    ['imagen' => 'Assets/src/NUMERO11 BTC 1NPnQPvYZfQtSWBp3iZzD1ANeRRjgW9DyT.png', 'palabraSecreta' => '1NPnQPvYZfQtSWBp3iZzD1ANeRRjgW9DyT'],
    ['imagen' => 'Assets/src/NUMERO13 BTC 19xCA2hnc762SspR2UR7czu45dLNtxVhrs.png', 'palabraSecreta' => '19xCA2hnc762SspR2UR7czu45dLNtxVhrs'],
    ['imagen' => 'Assets/src/NUMERO15 BTC 15FruFyJv2DnbqrGkj9gA1o2sDXGKMUBmT.png', 'palabraSecreta' => '15FruFyJv2DnbqrGkj9gA1o2sDXGKMUBmT'],
    ['imagen' => 'Assets/src/NUMERO17 BTC  1HDDa5vMaTC4VSo2XUeyAK6mCpKNbwpv9Q.png', 'palabraSecreta' => '1HDDa5vMaTC4VSo2XUeyAK6mCpKNbwpv9Q'],
];


function seleccionarImagenYPalabra($tipo, $pdo) {
    global $arrayusdt, $arraybtc;

    // Selección del array correspondiente basada en el tipo
    $arraySeleccionado = ($tipo == 'usdt') ? $arrayusdt : $arraybtc;

    foreach ($arraySeleccionado as $item) {
        // Verificar si la imagen y palabraSecreta ya están asignadas
        if (!imagenOPalabraAsignada($item['imagen'], $item['palabraSecreta'], $pdo)) {
            // Si no están asignadas, retornar estas para su uso
            return [$item['imagen'], $item['palabraSecreta']];
        }
    }

    // En caso de no encontrar ninguna opción disponible, retornar valores nulos o manejar el error según convenga
    return [null, null];
}

function imagenOPalabraAsignada($imagen, $palabraSecreta, $pdo) {
    $sql = "SELECT COUNT(*) FROM usuarios WHERE imagenusdt = ? OR imagenbtc = ? OR walletUSDT = ? OR walletBTC = ?";
    $stmt = $pdo->prepare($sql);
    
    // Vincular parámetros usando bindValue() de PDO
    $stmt->bindValue(1, $imagen);
    $stmt->bindValue(2, $imagen);
    $stmt->bindValue(3, $palabraSecreta);
    $stmt->bindValue(4, $palabraSecreta);

    $stmt->execute();

    // PDOStatement::fetchColumn() devuelve el valor de una sola columna de la siguiente fila en el result set
    $count = $stmt->fetchColumn();

    return $count > 0;
}

?>
