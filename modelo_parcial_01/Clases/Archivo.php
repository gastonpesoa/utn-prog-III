<?php
class Archivo
{
    public $path;

    public function __construct($path){        
        $this->path = $path;
    }    
    //====================== GETTERS
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
    public function WriteInTxtFile($string)
    {
        return file_put_contents($this->path, $string);        
    }
    //===================== SAVE FILES
    public function GetNameAndExtension($fileName)
    {
        $fileNameArray = explode('/', $fileName);
        return $fileNameArray[count($fileNameArray)-1];
    }
    
    public function GetExtension($fileName)
    {
        $fileNameArray = explode('.', $fileName);
        return "." . $fileNameArray[count($fileNameArray)-1];
    }    

    public function GetName($fileName)
    {
        $nameAndExtension = $this->GetNameAndExtension($fileName);
        $nameAndExtensionArray = explode('.', $nameAndExtension);
        return $nameAndExtensionArray[count($nameAndExtensionArray)-2];
    }

    private function NameFile($nombreArchivo, $extension)
    {
        return $this->path . $nombreArchivo . $extension;
    }

    private function RenameFile($urlBackup, $nombreArchivo, $extension)
    {
        $hoy = date("_d-m-Y");
        return $urlBackup . $nombreArchivo . $hoy . $extension;
    }

    public function SaveFileInFolder($file, $object)
    {     
        $result = null;   
        $nombreArchivo = "{$object->id}_{$object->nombre}";
        $extension = $this->GetExtension($file['foto']['name']);
        $uploadfile = $this->NameFile($nombreArchivo, $extension);                
        if(move_uploaded_file($file['foto']['tmp_name'], $uploadfile))
            $result = $uploadfile;
        return $result;
    }
    
    public function UpdateFileInFolder($file, $fileFotos, $object)
    {
        $result = null;
        $extension = $this->GetExtension($object->foto);
        $nombreAnterior = $this->GetName($object->foto);
        $nombreNuevo = $this->RenameFile($this->path, $nombreAnterior, $extension);
        if(rename($object->foto, $nombreNuevo))
            $result = $nombreNuevo;            
        return $result;
    }
}
?>