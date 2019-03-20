<?php
    require 'persona.php';
    class Alumno extends Persona {

        public $legajo;
    
        function __construct($nombre, $edad, $dni, $legajo){
            parent::__construct($nombre, $edad, $dni);
            $this->legajo = $legajo;
        }        
    }
?>