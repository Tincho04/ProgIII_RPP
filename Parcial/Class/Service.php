<?php

require_once "./Components/JsonHandler.php";

class Service
{
    public $id;
    public $tipo;
    public $precio;
    public $demora;

    function __construct($id, $tipo, $precio, $demora)
    {
        $this->id = $id;
        $this->tipo = $tipo;
        $this->precio = $precio;
        $this->demora = $demora;
    }

    public static function save($object)
    {
        if (Service::isValidUnique($object->id)) {
            return JsonHandler::saveJson($object, 'tiposServicio.json') != null;
        }
        return false;
    }

    public static function update($object)
    {
        if (!Auto::isValidUnique($object->patente)) {
            $archivoArray = (array) JsonHandler::readJson('tiposServicio.json');
            $listaAutos = [];

            foreach ($archivoArray as $datos) {
                $nuevoAuto = new Auto($datos->patente, $datos->tipo, $datos->date, $datos->email, $datos->fecha_egreso, $datos->importe);

                if ($nuevoAuto->patente == $object->patente) {
                    array_push($listaAutos, $object);
                } else {
                    array_push($listaAutos, $nuevoAuto);
                }
            }

            // Update.
            JsonHandler::saveAllJson($listaAutos, 'tiposServicio.json');
        }

        return false;
    }

    public static function isValidUnique($unique)
    {
        $service = Service::getAll();
        foreach ($service as $serv) {
            if ($serv->id == $unique) {
                return false;
            }
        }

        return true;
    }

    public static function getAll()
    {
        $archivoArray = (array) JsonHandler::readJson('tiposServicio.json');
        $listaServices = [];

        foreach ($archivoArray as $datos) {
            $nuevoServ = new Service($datos->id, $datos->tipo, $datos->precio, $datos->demora);
            array_push($listaServices, $nuevoServ);
        }

        return $listaServices;
    }

    public static function sumAllByType($tipo)
    {
        $archivoArray = (array) JsonHandler::readJson('tiposServicio.json');
        $filtroService = [];

        foreach ($archivoArray as $datos) {
            $nuevoService = new Service($datos->id, $datos->tipo, $datos->precio, $datos->demora);

            // Sólo los estacionados, no los retirados.
            if ($nuevoService->tipo == $tipo)
                array_push($filtroService, $nuevoService);
        }

        return $filtroService;
    }

    public static function getById($id)
    {
        $archivoArray = (array) JsonHandler::readJson('tiposServicio.json');

        foreach ($archivoArray as $datos) {
            $nuevoService = new Service($datos->id, $datos->tipo, $datos->precio, $datos->demora);

            if ($nuevoService->id == $id) {
                return $nuevoService;
            }
        }

        return null;
    }

    public static function exists($patente)
    {
        $archivoArray = (array) JsonHandler::readJson('tiposServicio.json');

        foreach ($archivoArray as $datos) {
            $nuevoAuto = new Auto($datos->patente, $datos->tipo, $datos->date, $datos->email);

            if ($nuevoAuto->patente == $patente) {
                return true;
            }
        }

        return false;
    }

    /* Métodos mágicos */
    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    public function __get($name)
    {
        return $this->$name;
    }
}
