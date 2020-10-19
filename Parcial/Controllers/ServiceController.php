<?php

class ServiceController
{

    public static function Create()
    {
        if (isset($_POST['id']) && isset($_POST['tipo']) && isset($_POST['precio']) && isset($_POST['demora'])) {

            if (($_POST['tipo'] == '10.000Km') || ($_POST['tipo'] == '20.000Km') || ($_POST['tipo'] == '50.000Km')) {
                $id = $_POST['id'];
                $tipo = $_POST['tipo'];
                $precio = $_POST['precio'];
                $demora = $_POST['demora'];

                $servicio = new Service($id, $tipo, $precio, $demora);
                $resultado = Service::save($servicio);
                echo GenericResponse::obtain($resultado, $resultado ? 'Service registrado' : 'El service ya se encuentra registrado.', $resultado ? $servicio : null);
            } else {
                echo GenericResponse::obtain(false, "Los tipos de servicios que se manejan son: 10.000Km, 20.000Km o 50.000Km.",  $_POST['tipo']);
            }
        }
    }

    public static function GetByPatente($patente)
    {
        $autos = Auto::getByPatente($patente);
        if (!is_null($autos)) {
            echo GenericResponse::obtain(true, '', $autos);
        } else {
            echo GenericResponse::obtain(false, "No existe la patente $patente",);
        }
    }

    public static function GetServicios()
    {
        $servicios = Service::getAll();
        echo GenericResponse::obtain(true, '', $servicios);
    }

    public static function GetTotalByTipo($tipo)
    {
        $filtro = Service::sumAllByType($tipo);
        echo GenericResponse::obtain(true, '', $filtro);
    }

}
