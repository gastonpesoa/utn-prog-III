<?php
class Archivo{

    public $path;

    public function __construct($path){        
        $this->path = $path;
    }
    
    //====================== GETTERS
    //JSON
    public function JsonFileToArray()
    {
        $arrayStdClass = array();

        if(file_exists($this->path))
        {
            $file = file_get_contents($this->path);
            $arrayStdClass = json_decode($file);
        }
        return $arrayStdClass;
    }
    //TXT
    public function TextToArray()
    {
        $array = array();        
        if(file_exists($this->path))
        {
            $array = Archivo::ReadByLinesToArray($this->path);
        }                         
        return $array;
    }  
    
    public static function ReadByLinesToArray($path)
    {
        $array = array();
        $myfile = fopen($path, "r");            
        while(!feof($myfile))
        {
            $datos = fgets($myfile);
            if($datos == null)
                break;
            array_push($array, trim($datos));                
        }
        fclose($myfile);
        return $array;
    }
    //====================== SETS
    //JSON
    public function ArrayToJsonFile($array)
    {           
        return file_put_contents($this->path, json_encode($array));
    }    
    //TXT
    public function WriteInTxtFile($string)
    {
        return file_put_contents($this->path, $string);        
    }
    //================================================================

    //para imagenes o documentos
    public function GetExtension($file)
    {
        $fileNameArray = explode('.', $file['imagen']['name']);
        return $extension = "." . $fileNameArray[count($fileNameArray)-1];
    }

    private function RenameFile($urlDestino, $nombreArchivo, $extension)
    {
        return $urlDestino . $nombreArchivo . $extension;
    }

    private function RenameExistingFile($urlBackup, $nombreArchivo, $extension)
    {
        $hoy = date("_d-m-Y");
        return $urlBackup . $nombreArchivo . $hoy . $extension;
    }

    private function CreateBackup($urlBackup, $uploadfile, $nombreArchivo, $extension)
    {
        $returnAux = true;
        $backup = $this->RenameExistingFile($urlBackup, $nombreArchivo, $extension);

        if(!copy($uploadfile, $backup))
            $returnAux = false;

        return $returnAux;
    }

    public function InsertWatermark($urlimagen, $urlestampa)
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

    public function SaveFile($file, $nuevoNombre, $urlBackup, $urlEstampa)
    {
        //inicializo variables de retorno de informacion
        $returnAux = "No se pudo guardar el archivo" . PHP_EOL;
        $returnAuxBackup = "";

        $extension = $this->GetExtension($file);

        $uploadfile = $this->RenameFile($this->path, $nuevoNombre, $extension);

        if(file_exists($uploadfile))
        {
            $returnAuxBackup = "Archivo existente. Se crea backup." . PHP_EOL;
            if(!$this->CreateBackup($urlBackup, $this->path, $nuevoNombre, $extension))
                $returnAuxBackup = "Archivo existente. No se pudo crear backup." . PHP_EOL;
        }

        if(move_uploaded_file($file['imagen']['tmp_name'], $uploadfile))
            $this->InsertWatermark($uploadfile, $urlEstampa);
            $returnAux = "Se guardo el archivo!" . PHP_EOL;

        //return $returnAuxBackup . $returnAux;
        return $uploadfile;
    }

    // public function SaveFile($file, $urlDestino, $nuevoNombre, $urlBackup, $urlEstampa)
    // {
    //     //inicializo variables de retorno de informacion
    //     $returnAux = "No se pudo guardar el archivo" . PHP_EOL;
    //     $returnAuxBackup = "";

    //     $extension = Archivo::GetExtension($file);

    //     $uploadfile = $this->RenameFile($urlDestino, $nuevoNombre, $extension);

    //     if(file_exists($uploadfile))
    //     {
    //         $returnAuxBackup = "Archivo existente. Se crea backup." . PHP_EOL;
    //         if(!$this->CreateBackup($urlBackup, $uploadfile, $nuevoNombre, $extension))
    //             $returnAuxBackup = "Archivo existente. No se pudo crear backup." . PHP_EOL;
    //     }

    //     if(move_uploaded_file($file['imagen']['tmp_name'], $uploadfile))
    //         $this->InsertWatermark($uploadfile, $urlEstampa);
    //         $returnAux = "Se guardo el archivo!" . PHP_EOL;

    //     //return $returnAuxBackup . $returnAux;
    //     return $uploadfile;
    // }        

    public static function ShowTextList($path)
    {
        $format = sprintf("%-18s;%-18s;%-9s;%-9s;%-9s;" . PHP_EOL,
            "Nombre","Apellido","Edad","DNI","Legajo");
        echo $format;

        $arrayAlumnos = Archivo::TextToArray($path);
        if($arrayAlumnos != null)
        {
            foreach($arrayAlumnos as $alumno)
            {
                echo $alumno->ShowAlumno();
            }
        }
        else
        {
            echo "Archivo vacío." . PHP_EOL;
        }
    }

    public function SaveText($path)
    {
        $returnAux = "Se escribió el dato en el archivo txt." . PHP_EOL;
        file_exists($path) ? $file = fopen($path, "at") : $file = fopen($path, "wt");
        $data = $this->ToString();
        if(!fwrite($file,$data))
            $returnAux = "No se pudo escribir el archivo txt.".PHP_EOL;

        fclose($file);
        return $returnAux;
    }

    //para json

    public function SaveJsonLinea($path)
    {
        $returnAux = "Se escribió el dato en el archivo json." . PHP_EOL;

        file_exists($path) ? $file = fopen($path, "at") : $file = fopen($path, "wt");

        if(!fwrite($file, $this->ObjectToJson()))
            $returnAux = "No se pudo escribir el archivo json.".PHP_EOL;

        fclose($file);
        return $returnAux;
    }

    
}
?>