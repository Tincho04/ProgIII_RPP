<?php

class AutoController
{

    public static function Create()
    {
        if (isset($_POST['patente']) && isset($_POST['marca']) && isset($_POST['modelo']) && isset($_POST['precio'])) {

            $patente = $_POST['patente'];
            $marca = $_POST['marca'];
            $modelo = $_POST['modelo'];
            $precio = $_POST['precio'];

            $auto = new Auto($patente, $marca, $modelo, $precio);
            $resultado = Auto::save($auto);
            echo GenericResponse::obtain($resultado, $resultado ? 'Auto almacenado' : 'El auto ya se encuentra registrado.', $resultado ? $auto : null);
        }
    }

    public static function GetByPatente($patente)
    {
        $autos = Auto::getByPatente($patente);
        if(!is_null($autos))
        {
            echo GenericResponse::obtain(true, '', $autos);
        }
        else{
            echo GenericResponse::obtain(false, "No existe la patente $patente", );
        }

    }

    public static function GetEstacionados()
    {
        $autos = Auto::getAll();
        echo GenericResponse::obtain(true, '', $autos);
    }

    public static function GetTotalByTipo($tipo)
    {
        $sum = Auto::sumAllByType($tipo);
        echo GenericResponse::obtain(true, '', $sum);
    }

}
