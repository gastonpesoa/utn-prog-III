<?php
require_once CLASES . '/persona.php';

class Alumno extends Persona {

    //=====================  PROPIEDADES ===================== 

    public $legajo;
    
    //===================== CONSTRUCTOR ===================== 

    public function __construct($nombre, $apellido, $edad, $dni, $legajo)
    {
        parent::__construct($nombre, $apellido, $edad, $dni);
        $this->legajo = $legajo;
    }  

    //===================== METODOS PRIVADOS ========================

    //para archivos
    private function renombrar_archivo($file, &$nombreAlumno, &$extension)
    {   
        $nameimagen = explode('.', $file['imagen']['name']);
        $extension = "." . $nameimagen[count($nameimagen)-1];
        $nombreAlumno = "{$this->legajo}_{$this->apellido}";          
        return FOTOS . $nombreAlumno . $extension; 
    }

    private function renombrar_archivo_existente(&$nombreAlumno, &$extension)
    {   
        $hoy = date("_d-m-Y");
        return FOTOSBACKUP . $nombreAlumno . $hoy . $extension;
    }

    private function crear_backup($uploadfile, &$nombreAlumno, &$extension)
    {
        $returnAux = true;
        $nombreBackup = $this->renombrar_archivo_existente($nombreAlumno, $extension);

        if(!copy($uploadfile, $nombreBackup))
            $returnAux = false;   

        return $returnAux;
    }

    public function insertar_estampa($urlimagen, $urlestampa)
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
    public function guardar_archivo($file)
    {    
        $returnAux = "No se pudo guardar el archivo" . PHP_EOL;
        $returnAuxBackup = "";
        $nombreAlumno = "";
        $extension = "";     

        $uploadfile = $this->renombrar_archivo($file, $nombreAlumno, $extension);

        if(file_exists($uploadfile))
        {
            $returnAuxBackup = "Archivo existente. Se crea backup." . PHP_EOL;
            if(!$this->crear_backup($uploadfile, $nombreAlumno, $extension))
                $returnAuxBackup = "Archivo existente. No se pudo crear backup." . PHP_EOL;
        }     

        if(move_uploaded_file($file['imagen']['tmp_name'], $uploadfile))
            $this->insertar_estampa($uploadfile, ARCHIVOS . "/marca-de-agua.png");
            $returnAux = "Se guardo el archivo!" . PHP_EOL;            

        return $returnAuxBackup . $returnAux;
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