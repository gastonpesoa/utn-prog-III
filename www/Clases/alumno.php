<?php
require_once CLASES . '/persona.php';

class Alumno extends Persona {

    //=====================  PROPIEDADES ===================== 

    public $legajo;
    public $foto;
    
    //===================== CONSTRUCTORES ===================== 

    public function __construct($nombre, $apellido, $edad, $dni, $legajo)
    {
        parent::__construct($nombre, $apellido, $edad, $dni);
        $this->legajo = $legajo;
    }
    
    public static function con_foto($nombre, $apellido, $edad, $dni, $legajo, $foto)
    {
        $alumno = new self($nombre, $apellido, $edad, $dni, $legajo);
        $alumno->set_foto($foto);
        return $alumno;
    }

    //===================== SETERS ================================

    private function set_foto($foto)
    {
        if($foto != null)
            $this->foto = $foto;
    }

    //===================== METODOS PRIVADOS ========================

    //para archivos
    private static function get_extension($file)
    {
        $fileNameArray = explode('.', $file['imagen']['name']);
        return $extension = "." . $fileNameArray[count($fileNameArray)-1]; 
    }

    private static function renombrar_archivo($urlDestino, $nombreArchivo, $extension)
    {            
        return $urlDestino . $nombreArchivo . $extension; 
    }

    private static function renombrar_archivo_existente($urlBackup, $nombreArchivo, $extension)
    {   
        $hoy = date("_d-m-Y");
        return $urlBackup . $nombreArchivo . $hoy . $extension;
    }

    private static function crear_backup($urlBackup, $uploadfile, $nombreArchivo, $extension)
    {
        $returnAux = true;
        $backup = Alumno::renombrar_archivo_existente($urlBackup, $nombreArchivo, $extension);

        if(!copy($uploadfile, $backup))
            $returnAux = false;   

        return $returnAux;
    }

    public static function insertar_estampa($urlimagen, $urlestampa)
    {
        // Cargar la estampa y la foto para aplicarle la marca de agua
        $estampa = imagecreatefrompng($urlestampa);
        $im = imagecreatefromjpeg($urlimagen);

        // Establecer los márgenes para la estampa y obtener el alto/ancho de la imagen de la estampa
        $margen_dcho = 10;
        $margen_inf = 10;
        $sx = imagesx($estampa);
        $sy = imagesy($estampa);

        // Copiar la imagen de la estampa sobre nuestra foto usando los índices de márgen y el
        // ancho de la foto para calcular la posición de la estampa. 
        imagecopy($im, $estampa, imagesx($im) - $sx - $margen_dcho, imagesy($im) - $sy - $margen_inf, 0, 0, imagesx($estampa), imagesy($estampa));

        // Imprimir y liberar memoria
        imagejpeg($im, $urlimagen);
        imagedestroy($im);
    }

    //===================== POST ========================

    //para archivos
    public static function guardar_archivo($file, $urlDestino, $nuevoNombre, $urlBackup, $urlEstampa)
    {                    
        $extension = Alumno::get_extension($file); 
        $uploadfile = Alumno::renombrar_archivo($urlDestino, $nuevoNombre, $extension);
        if(file_exists($uploadfile))
            Alumno::crear_backup($urlBackup, $uploadfile, $nuevoNombre, $extension);

        if(move_uploaded_file($file['imagen']['tmp_name'], $uploadfile))
            Alumno::insertar_estampa($uploadfile, $urlEstampa);          

        return $uploadfile;
    }    
    
    //para txt
    public function guardar_txt($path)
    {    
        $returnAux = "Se escribió el dato en el archivo txt." . PHP_EOL;

        file_exists($path) ? $file = fopen($path, "at") : $file = fopen($path, "wt");   

        $data = "{$this->nombre};{$this->apellido};{$this->edad};{$this->dni};{$this->legajo};".PHP_EOL;
        if(!fwrite($file,$data))
            $returnAux = "No se pudo escribir el archivo txt.".PHP_EOL;         
            
        fclose($file);
        return $returnAux;
    }

    //para json
    public function guardar_json($path)
    {
        $returnAux = "Se escribió el dato en el archivo json." . PHP_EOL;

        file_exists($path) ? $file = fopen($path, "at") : $file = fopen($path, "wt"); 
        
        if(!fwrite($file, $this->objeto_a_json()))
            $returnAux = "No se pudo escribir el archivo json.".PHP_EOL; 

        fclose($file);
        return $returnAux;
    }

    public function guardar_json_array($path)
    {
        $arrayJson = Alumno::leer_json_array($path);
        array_push($this);
    }

    //===================== GET ========================

    //para txt
    public static function leer($path){
            
        $myfile = fopen($path, "r") or die("No se puede abrir el archivo!" . PHP_EOL);
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

    //para json
    public static function leer_json_array($path){

        $file = fopen($path, "r") or die("No se puede abrir el archivo json." . PHP_EOL);
        $datos = fread($file, filesize($path)) or die("No se puede leer el archivo json." . PHP_EOL);
        $array = json_decode($datos, true);
        return $array;
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
