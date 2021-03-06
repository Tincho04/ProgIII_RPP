<?php

class UserController
{
    public static function Create()
    {
        if (isset($_POST['email']) && isset($_POST['password']) && isset($_POST['tipo'])) {

            $email = $_POST['email'];
            $pass = PassManager::Hash($_POST['password']);
            $tipo = $_POST['tipo'] ?? 'user';

            if (Usuario::existsByEmail($email)) {
                echo GenericResponse::obtain(false, 'CORREO REPETIDO.');
            } else {
                if ($tipo != 'admin' && $tipo != 'user')
                    $tipo = 'user';

                $usuario = new Usuario($email, $pass, $tipo);
                $resultado = Usuario::guardarUsuario($usuario);
                echo GenericResponse::obtain($resultado, "$tipo registrado", $usuario->id);
            }
        } else {
            echo GenericResponse::obtain(false, 'El usuario y la pass son obligatorios.');
        }
    }

    public static function GetAll()
    {
        $users = Usuario::getAll();
        echo GenericResponse::obtain(true, '', $users);
    }
}
