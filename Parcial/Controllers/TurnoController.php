<?php

class TurnoController
{
    public static function Create()
    {

        if (isset($_POST['patente'])) {

            $patente = $_POST['patente'];
            $id = $_POST['id'];

            $auto = Auto::getByPatente($patente);
            $service = Service::getById($id);

            if (!is_null($auto)) {
                $turno = new Turno($auto->patente, date("g:i:a d-m-o"), $auto->marca, $auto->modelo, $auto->precio, $service->tipo);
                $resultado = Turno::Save($turno);
                echo GenericResponse::obtain($resultado, $resultado ? 'Se ha dado de alta el turno' : 'Turno previamente registrado.', $resultado ? $turno : null);
            } else {
                echo GenericResponse::obtain(false, "No existe la patente $patente",);
            }
        }
    }
}
