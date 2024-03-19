<?php
// Controladores/UsuarioControlador.php

require_once __DIR__ . '/../Modelos/Usuario.php';

class UsuarioControlador {
    private $usuarioModelo;

    public function __construct($pdo) {
        $this->usuarioModelo = new Usuario($pdo);
    }

    public function obtenerUsuarioPorNombreUsuario($username) {
        return $this->usuarioModelo->obtenerUsuarioPorNombreUsuario($username);
    }

    public function validarCredenciales($username, $password) {
        return $this->usuarioModelo->validarCredenciales($username, $password);
    }
}
