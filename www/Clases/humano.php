<?php
class Humano{

    public $nombre;
    public $apellido;
    public $edad;

    function __construct($nombre, $apellido, $edad){
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->edad = $edad;
    }

    public function objeto_a_json(){
        return json_encode($this) . PHP_EOL;;
    }
}
?>