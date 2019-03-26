<?php
    require_once 'persona.php';
    
    class Alumno extends Persona {

        public $legajo;
    
        function __construct($nombre, $edad, $dni, $legajo){
            parent::__construct($nombre, $edad, $dni);
            $this->legajo = $legajo;
        }   
        
        function Guardar($path){
            $data = "{$this->nombre};{$this->edad};{$this->dni};{$this->legajo};"."\n";
            // var_dump($data);
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

        function GuardarJSON($path){
            if(file_exists($path)){
                $file = fopen($path, "a");                
                fwrite($file,$this->returnJSON());
                fclose($file);
            }
            else{
                $file = fopen($path, "w");                
                fwrite($file,$this->returnJSON());
                fclose($file);
            }
        }

        public static function LeerAlumno($path){
            
            if(file_exists($path)){
                $myfile = fopen($path, "r");
                // $datos = fread($myfile,filesize($path));
                while(!feof($path)){
                    $datos = fgets($myfile, filesize($path));
                    $data_array = explode(';',$datos);
                    $alumno = new Alumno($data_array[0],$data_array[1],$data_array[2],$data_array[3]);
                    $array_alumnos = array();
                    array_push($array_alumnos, $alumno);
                }                
                fclose($myfile);               
            }
            return $array_alumnos;
        }

        public static function LeerAlumnoJSON($path){
            if(file_exists($path)){
                $myfile = fopen($path, "r");
                $datos = fread($myfile,filesize($path));
                fclose($myfile);               
            }
            return json_decode($datos);
        }

        public static function MostrarAlumno($alumno){
            implode()
            $data_array = explode(';',$data);
        }
    }
?>