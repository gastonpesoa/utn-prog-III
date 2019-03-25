<?php
    require 'persona.php';
    class Alumno extends Persona {

        public $legajo;
    
        function __construct($nombre, $edad, $dni, $legajo){
            parent::__construct($nombre, $edad, $dni);
            $this->legajo = $legajo;
        }   
        
        function Guardar($path){
            $data = "{$this->nombre};{$this->edad};{$this->dni};{$this->legajo};".PHP_EOL;
            var_dump($data);
            if(file_exists($path)){
                $file = fopen($path, "a");                
                fwrite($file,$data);
                fclose($file);
            }
            else{
                $file = fopen($path, "w");                
                fwrite($file,$data);
                fclose($file);
            }
        }
    }
?>