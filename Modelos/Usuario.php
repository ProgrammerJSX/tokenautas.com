<?php
// Modelos/Usuario.php

class Usuario {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function registrar($username, $password, $imagenusdt, $imagenbtc, $palabraSecretaUSDT, $palabraSecretaBTC) {
        // Hash de la contraseña para almacenamiento seguro
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Preparar la consulta SQL para insertar el nuevo usuario
        $sql = "INSERT INTO usuarios (username, password, imagenusdt, imagenbtc, walletUSDT, walletBTC) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);

        // Ejecutar la inserción con los valores proporcionados
        if ($stmt->execute([$username, $passwordHash, $imagenusdt, $imagenbtc, $palabraSecretaUSDT, $palabraSecretaBTC])) {
            return true; // Registro exitoso
        } else {
            return false; // Fallo el registro
        }
    }

    public function validarCredenciales($username, $password) {
        // Preparar la consulta SQL para buscar el usuario
        $sql = "SELECT password FROM usuarios WHERE username = ?";
        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            return true; // Las credenciales son correctas
        } else {
            return false; // Las credenciales son incorrectas
        }
    }

    public function obtenerUsuarioPorNombreUsuario($username) {
        // Preparar la consulta SQL para buscar el usuario
        $sql = "SELECT * FROM usuarios WHERE username = ?";
        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([$username]);
        $user = $stmt->fetch();

        return $user; // Devuelve todos los detalles del usuario
    }
}
