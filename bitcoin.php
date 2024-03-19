<?php
/*
// Inicio de sesión y verificación (asegúrate de iniciar sesión al comienzo del archivo)
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.php");
    exit;
}

// Incluye el archivo de conexión y cualquier otro necesario
require_once __DIR__ . '/conexion.php';
// Incluye el archivo que contiene el array con los datos de las imágenes
require_once __DIR__ . '/walletData.php';

// Obtiene los datos de las imágenes
$imagesData = require __DIR__ . '/walletData.php';

// Aquí tu lógica para determinar qué imagen mostrar basándote en algún criterio, por ejemplo, una selección aleatoria o basada en la sesión del usuario
// Por simplificación, voy a mostrar solo cómo incluirías esto en tu HTML sin una lógica específica de selección
*/
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Información Bitcoin</title>
</head>
<body>
<div id="imagenes">
    <?php foreach ($imagesData as $imageData): ?>
        <div class="image-container">
            <img src="ruta/a/imagenes/<?php echo htmlspecialchars($imageData['image']); ?>" alt="Imagen">
            <p><?php echo htmlspecialchars($imageData['text']); ?></p>
        </div>
    <?php endforeach; ?>
</div>
</body>
</html>
