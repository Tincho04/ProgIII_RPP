<?php

require_once "./Components/JsonHandler.php";

class Auto
{
    public $patente;
    public $marca;
    public $modelo;
    public $precio;

    function __construct($patente, $marca, $modelo, $precio = 0)
    {
        $this->patente = $patente;
        $this->marca = $marca;
        $this->modelo = $modelo;
        $this->precio = $precio;
    }

    public static function save($object)
    {
        // Validación de patente repetida.
        if (Auto::isValidUnique($object->patente)) {
            return JsonHandler::saveJson($object, 'vehiculos.json') != null;
        }
        return false;
    }

    public static function update($object)
    {
        if (!Auto::isValidUnique($object->patente)) {
            $archivoArray = (array) JsonHandler::readJson('vehiculos.json');
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
            JsonHandler::saveAllJson($listaAutos, 'vehiculos.json');
        }

        return false;
    }

    public static function isValidUnique($unique)
    {
        $autos = Auto::getAll();
        foreach ($autos as $auto) {
            if ($auto->patente == $unique) {
                return false;
            }
        }

        return true;
    }

    public static function getAll()
    {
        $archivoArray = (array) JsonHandler::readJson('vehiculos.json');
        $listaAutos = [];

        foreach ($archivoArray as $datos) {
            $nuevoAuto = new Auto($datos->patente, $datos->marca, $datos->modelo, $datos->precio);
            
                array_push($listaAutos, $nuevoAuto);
        }

        return $listaAutos;
    }

    public static function sumAllByType($tipo)
    {
        $archivoArray = (array) JsonHandler::readJson('vehiculos.json');
        $suma = 0;

        foreach ($archivoArray as $datos) {
            $nuevoAuto = new Auto($datos->patente, $datos->tipo, $datos->date, $datos->email, $datos->fecha_egreso, $datos->importe);

            // Sólo los estacionados, no los retirados.
            if ($nuevoAuto->tipo == $tipo)
                $suma = $suma + $nuevoAuto->importe;
        }

        return $suma;
    }

    public static function getByPatente($patente)
    {
        $archivoArray = (array) JsonHandler::readJson('vehiculos.json');

        foreach ($archivoArray as $datos) {
            $nuevoAuto = new Auto($datos->patente, $datos->marca, $datos->modelo, $datos->precio);

            if ($nuevoAuto->patente == $patente) {
                return $nuevoAuto;
            }
        }

        return null;
    }

    public static function exists($patente)
    {
        $archivoArray = (array) JsonHandler::readJson('vehiculos.json');

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
