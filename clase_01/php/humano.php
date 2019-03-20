<?php
class Humano{

    public $nombre;
    public $edad;

    function __construct($nombre, $edad){
        $this->nombre = $nombre;
        $this->edad = $edad;
    }

    public function returnJSON(){
        return json_encode($this);
    }
}
?>