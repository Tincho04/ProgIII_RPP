<?php

require_once "./Components/JsonHandler.php";

class Turno
{
    public $patente;
    public $fecha;
    public $marca;
    public $modelo;
    public $precio;
    public $tipoServicio;

    function __construct($patente, $fecha, $marca, $modelo, $precio, $tipoServicio)
    {
        $this->patente = $patente;
        $this->fecha = $fecha;
        $this->marca = $marca;
        $this->modelo = $modelo;
        $this->precio = $precio;
        $this->tipoServicio = $tipoServicio;
    }

    public static function save($object)
    {
        return JsonHandler::saveUnique($object, 'turnos.json');
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
