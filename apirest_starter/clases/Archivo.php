<?php

class Archivo
{
    public $path;

    public function __construct($path)
    {
        $this->path = $path;
    }

    public function TextToArray()
    {
        $array = array();
        if(file_exists($this->path))
        {            
            $myfile = fopen($this->path, "r");            
            while(!feof($myfile))
            {
                $datos = fgets($myfile);
                if($datos == null)
                    break;
                array_push($array, trim($datos));                
            }
            fclose($myfile);
        }
        return $array;
    }

    public function WriteInTxtFile($string)
    {
        $result = true;
        if(!file_put_contents($this->path, $string))
            $result = null;
        return $result;        
    }
    
    public function GetExtension($fileName)
    {
        $fileNameArray = explode('.', $fileName);
        return "." . $fileNameArray[count($fileNameArray)-1];
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

    private function CreateBackup($urlBackup, $uploadfile, $nombreArchivo, $extension)
    {
        $returnAux = true;
        $backup = $this->RenameFile($urlBackup, $nombreArchivo, $extension);
        if(!copy($uploadfile, $backup))
            $returnAux = false;
        return $returnAux;
    }

    public function SaveFileInFolder($file, $urlBackup, $object)
    {     
        $result = null;   
        $nombreArchivo = "{$object->propA}";
        $extension = $this->GetExtension($file['name']);
        $uploadfile = $this->NameFile($nombreArchivo, $extension);   
        if(file_exists($uploadfile))
            $this->CreateBackup($urlBackup, $uploadfile, $nombreArchivo, $extension);                        
        if(move_uploaded_file($file['tmp_name'], $uploadfile))
            $result = $uploadfile;
        return $result;
    }      
}

?>