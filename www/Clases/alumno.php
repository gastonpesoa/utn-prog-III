<?php
require_once CLASES.'/persona.php';

class Alumno extends Persona {

    public $legajo;

    function __construct($nombre, $edad, $dni, $legajo){
        parent::__construct($nombre, $edad, $dni);
        $this->legajo = $legajo;
    }   
    
    function guardar($path){
        $data = "{$this->nombre};{$this->edad};{$this->dni};{$this->legajo};".PHP_EOL;
        // var_dump($data);
        if(file_exists($path)){
            $file = fopen($path, "at");                
            fwrite($file,$data);
            fclose($file);
        }
        else{
            $file = fopen($path, "wt");                
            fwrite($file,$data);
            fclose($file);
        }
    }

    function guardar_json($path){
        if(file_exists($path)){
            $file = fopen($path, "a");                
            fwrite($file,$this->objeto_a_json());
            fclose($file);
        }
        else{
            $file = fopen($path, "w");                
            fwrite($file,$this->objeto_a_json());   
            fclose($file);
        }
    }

    function guardar_archivo($file)
    {    
        $returnAux = "No se pudo guardar el archivo";

        $nameimagen = explode('.', $file['imagen']['name']);
        $extension = "." . $nameimagen[count($nameimagen)-1];
        $name = "{$this->legajo}-{$this->nombre}";
        $namenuevo = $name . $extension;                
        $uploadfile = FOTOS . $namenuevo;

        $this->existe_archivo($uploadfile, $name, $extension);        

        if(move_uploaded_file($file['imagen']['tmp_name'], $uploadfile))
            $returnAux = "Se guardo el archivo!";            

        return $returnAux;
    }

    function existe_archivo($uploadfile, $name, $extension)
    {
        if(file_exists($uploadfile))
        {
            $hoy = date("-d-m-Y");
            $namebackup = FOTOSBACKUP . $name . $hoy . $extension; 

            if(!copy($uploadfile, $namebackup))
                return "No se pudo crear backup";                         
        }        
    }

    public static function leer_json($path){
        $file = fopen($path, "r") or die("No se puede leer el archivo!");
        $array = array(json_decode($path, true));
        return $array;
    }

    public static function leer($path){
            
        $myfile = fopen($path, "r") or die("No se puede leer el archivo!");
        // $datos = fread($myfile,filesize($path));
        while(!feof($path)){
            $datos = fgets($myfile);
            $dataArray = explode(';',$datos);
            $alumno = new Alumno($dataArray[0],$dataArray[1],$dataArray[2],$dataArray[3]);
            $arrayAlumnos = array();
            array_push($arrayAlumnos, $alumno);
        }                
        fclose($myfile);                       
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

    // public static function MostrarAlumno($alumno){
    //     implode()
    //     $data_array = explode(';',$data);
    // }
}
?>