<?php

class GenericResponse
{
    public $status;
    public $message;
    public $content;

    function __construct($status, $message = '', $content = '')
    {
        $this->status = $status;
        $this->message = $message;
        $this->content = $content;
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    public function __get($name)
    {
        return $this->$name;
    }

    /* Used to return a new Generic response for the client */
    public static function obtain($status, $message = '', $content = '')
    {
        if($status == true)
        {
           $msg = 'SUCCESS';
        }
        else
        {
            $msg = 'FATAL ERROR';
        }
        return json_encode(new GenericResponse($msg, $message, $content), JSON_PRETTY_PRINT);
    }
}
