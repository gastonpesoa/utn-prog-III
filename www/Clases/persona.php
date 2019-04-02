<?php
require_once CLASES.'/humano.php';

class Persona extends Humano 
{
    public $dni;

    function __construct($nombre, $apellido, $edad, $dni){
        parent::__construct($nombre, $apellido, $edad);
        $this->dni = $dni;
    }    
}
?>