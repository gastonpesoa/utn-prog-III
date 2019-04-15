<?php
require_once CLASES . '/persona.php';

class Alumno extends Persona {

    //=====================  PROPIEDADES ===================== 

    public $legajo;
    public $foto;

    //===================== CONSTRUCTOR ===================== 

    public function __construct($nombre, $apellido, $edad, $dni, $legajo)
    {
        parent::__construct($nombre, $apellido, $edad, $dni);
        $this->legajo = $legajo;
    }
    
    public function con_foto($file, $urlFotos, $urlFotosBackup, $urlFotosEstampa)
    {
        $nombreArchivo = "{$this->legajo}_{$this->apellido}"; 
        $foto = $this->guardar_archivo($file, $urlFotos, $nombreArchivo, $urlFotosBackup, $urlFotosEstampa);  
        $this->foto = $foto;
    }

    //===================== METODOS PUBLICOS ========================

    

    //===================== METODOS PRIVADOS ========================
    
    private function a_string()
    {        
        $format = sprintf("%-18s;%-18s;%-9s;%-9s;%-9s;" . PHP_EOL,
            $this->nombre,$this->apellido,$this->edad,$this->dni,$this->legajo);
        return $format;
    }
        
    //para archivos
    private static function get_extension($file)
    {
        $fileNameArray = explode('.', $file['imagen']['name']);
        return $extension = "." . $fileNameArray[count($fileNameArray)-1]; 
    }

    private function renombrar_archivo($urlDestino, $nombreArchivo, $extension)
    {            
        return $urlDestino . $nombreArchivo . $extension; 
    }

    private function renombrar_archivo_existente($urlBackup, $nombreArchivo, $extension)
    {   
        $hoy = date("_d-m-Y");
        return $urlBackup . $nombreArchivo . $hoy . $extension;
    }

    private function crear_backup($urlBackup, $uploadfile, $nombreArchivo, $extension)
    {
        $returnAux = true;
        $backup = $this->renombrar_archivo_existente($urlBackup, $nombreArchivo, $extension);

        if(!copy($uploadfile, $backup))
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

    //===================== GET ========================

    //para txt
    public static function txt_a_array($path)
    {
        $arrayAlumnos = array();
        if(file_exists($path))
        {
            $myfile = fopen($path, "r");
            while(!feof($myfile))
            {                
                $datos = fgets($myfile);                
                if($datos == null)
                    break;

                $dataArray = explode(';',$datos);
                if(count($dataArray) == 6 && trim($dataArray[0]) != "")
                {
                    $nombreAux = trim($dataArray[0]);
                    $apellidoAux = trim($dataArray[1]);
                    $edadAux = trim($dataArray[2]);
                    $dniAux = trim($dataArray[3]);
                    $legajoAux = trim($dataArray[4]);
                    $alumno = new Alumno($nombreAux, $apellidoAux, $edadAux, $dniAux, $legajoAux);
                    array_push($arrayAlumnos, $alumno);
                }                
            }                
            fclose($myfile); 
        }                              
        return $arrayAlumnos;
    }

    public static function mostrar_listado_txt($path)
    {
        $format = sprintf("%-18s;%-18s;%-9s;%-9s;%-9s;" . PHP_EOL,
            "Nombre","Apellido","Edad","DNI","Legajo");
        echo $format;

        $arrayAlumnos = Alumno::txt_a_array($path);
        if($arrayAlumnos != null)
        {
            foreach($arrayAlumnos as $alumno)
            {
                echo $alumno->a_string();
            }
        }
        else
        {
            echo "Archivo vacío." . PHP_EOL;
        }
    }

    public static function obtener_por_legajo_de_txt($legajo, $path)
    {
        $returnAux = null;
        $array = Alumno::txt_a_array($path);
        
        foreach($array as $alumno)
        {        
            if($alumno->legajo == $legajo)
            {
                $returnAux = $alumno;
                break;
            }                
        }
        return json_encode($alumno);   
    }

    //para json   
    public static function json_a_array($path)
    {
        $array = array();
        if(file_exists($path))
        {
            $file = fopen($path, "r");
            $datos = fread($file, filesize($path));
            $array = json_decode($datos, true);
            fclose($file);
        }            
        return $array;
    }
    
    public static function json_a_string($path)
    {
        $datosLeidos = null;
        if(file_exists($path)){
            $myfile = fopen($path, "r");
            $datosLeidos = fread($myfile,filesize($path));
            fclose($myfile);               
        }
        return $datosLeidos;
    }

    public static function json_linea_a_array($path)
    {
        $arrayAlumnos = array();
        if(file_exists($path))
        {
            $myfile = fopen($path, "r");
            while(!feof($myfile))
            {                
                $datos = fgets($myfile);                
                if($datos == null)
                    break;

                $alumno = json_decode($datos);
                array_push($arrayAlumnos, $alumno);             
            }                
            fclose($myfile); 
        }                              
        return $arrayAlumnos;
    }

    public static function obtener_por_legajo_de_json($legajo, $path)
    {
        $returnAux = null;
        $array = Alumno::json_a_array($path);
        foreach($array as $alumno)
        {
            if($alumno["legajo"] == $legajo)
            {
                $returnAux = $alumno;
                break;
            }                
        }
        return json_encode($alumno);   
    }

    public static function obtener_por_legajo_de_json_linea($legajo, $path)
    {
        $returnAux = null;
        $array = Alumno::json_linea_a_array($path);

        foreach($array as $alumno)
        {
            if($alumno->legajo == $legajo)
            {
                $returnAux = $alumno;
                break;
            }              
        }
        return json_encode($alumno);   
    }

    //===================== POST ========================

    //para archivos
    public function guardar_archivo($file, $urlDestino, $nuevoNombre, $urlBackup, $urlEstampa)
    {    
        //inicializo variables de retorno de informacion
        $returnAux = "No se pudo guardar el archivo" . PHP_EOL;
        $returnAuxBackup = "";
        
        $extension = Alumno::get_extension($file); 

        $uploadfile = $this->renombrar_archivo($urlDestino, $nuevoNombre, $extension);

        if(file_exists($uploadfile))
        {
            $returnAuxBackup = "Archivo existente. Se crea backup." . PHP_EOL;
            if(!$this->crear_backup($urlBackup, $uploadfile, $nuevoNombre, $extension))
                $returnAuxBackup = "Archivo existente. No se pudo crear backup." . PHP_EOL;
        }     

        if(move_uploaded_file($file['imagen']['tmp_name'], $uploadfile))
            $this->insertar_estampa($uploadfile, $urlEstampa);
            $returnAux = "Se guardo el archivo!" . PHP_EOL;            

        //return $returnAuxBackup . $returnAux;
        return $uploadfile;
    }    
    
    //para txt
    private function escribir_linea_txt()
    {
        return "{$this->nombre};{$this->apellido};{$this->edad};{$this->dni};{$this->legajo};" . PHP_EOL;
    }    

    public function guardar_txt($path)
    {    
        $returnAux = "Se escribió el dato en el archivo txt." . PHP_EOL;

        file_exists($path) ? $file = fopen($path, "at") : $file = fopen($path, "wt");   
        //$data =  "{$this->nombre};{$this->apellido};{$this->edad};{$this->dni};{$this->legajo};".PHP_EOL;
        $data = $this->escribir_linea_txt();
        if(!fwrite($file,$data))
            $returnAux = "No se pudo escribir el archivo txt.".PHP_EOL;         
            
        fclose($file);
        return $returnAux;
    }

    //para json
    
    public function guardar_json_linea($path)
    {
        $returnAux = "Se escribió el dato en el archivo json." . PHP_EOL;

        file_exists($path) ? $file = fopen($path, "at") : $file = fopen($path, "wt"); 
        
        if(!fwrite($file, $this->objeto_a_json()))
            $returnAux = "No se pudo escribir el archivo json.".PHP_EOL; 

        fclose($file);
        return $returnAux;
    }

    public function guardar_json($path)
    {
        $arrayAlumnos = Alumno::json_a_array($path);   
        array_push($arrayAlumnos, $this);
        $file = fopen($path, "wt"); 
        fwrite($file, json_encode($arrayAlumnos));
        fclose($file);
    }

    //===================== PUT ========================

    public static function modificar_txt($path, $alumno)
    {
        $mensaje = "Datos incorrectos" . PHP_EOL;
        $existe = false;
        $array = Alumno::txt_a_array($path);
        //var_dump($array);
        foreach($array as $item)
        {
            //var_dump($item->nombre);
            if($item->legajo == $alumno->legajo)
            {                
                $item->nombre = $alumno->nombre;
                $item->apellido = $alumno->apellido;
                $item->edad = $alumno->edad;
                $item->dni = $alumno->dni;
                $existe = true;
                break;
            }              
        }
        if($existe)
        {
            var_dump($array);
            $file = fopen($path, "wt"); 
            // foreach($array as $alumno)
            // {
            //     var_dump($alumno);
            // }
            // fwrite($file, json_encode($array));
            // $mensaje = "Datos modificados" . PHP_EOL;
            // fclose($file);     
        }
        return $mensaje; 
    }

    public static function modificar_json($path, $alumno)
    {
        $mensaje = "Datos incorrectos" . PHP_EOL;
        $existe = false;
        $array = Alumno::json_a_array($path);

        foreach($array as $key => $value)
        {
            if($value['legajo'] == $alumno->legajo)
            {                
                $array[$key]['nombre'] = $alumno->nombre;
                $array[$key]['apellido'] = $alumno->apellido;
                $array[$key]['edad'] = $alumno->edad;
                $array[$key]['dni'] = $alumno->dni;
                $existe = true;
                break;
            }              
        }
        if($existe)
        {
            $file = fopen($path, "wt"); 
            fwrite($file, json_encode($array));
            $mensaje = "Datos modificados" . PHP_EOL;
            fclose($file);     
        }
        return $mensaje; 
    }
}
?>


