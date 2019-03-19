<?php
    class Alumno{
        public $nombre;
        public $edad;
        // Alumno($nombre, )
    
        function __construct($nombre, $edad){
            $this->nombre = $nombre;
            $this->edad = $edad;
        }

        public function returnJSON(){
            return json_encode($this);
        }
    }
?>