<?php
class Proveedor{
    public $id;
    public $nombre;
    public $email;
    public $foto;

    public static function GetProveedor($id, $nombre, $email, $foto) {
        $proveedor = new Proveedor();
        if (isset($nombre)) {
            $proveedor->nombre = strtolower($nombre);
        } 
        if (isset($id)) {
            $proveedor->id = $id;
        } 
        if (isset($email)) {
            $proveedor->email = $email;
        } 
        if (isset($foto)) {
            $proveedor->foto = $foto;
        } 
        return $proveedor;
    }

    public function CreateProperty($name, $value){
        $this->{$name} = $value;
    }

    public static function StdClassToProveedor($stdClass)
    {
        $proveedor = new Proveedor();
        $propertiesArray = get_object_vars($stdClass);                         
        foreach ($propertiesArray as $nombre => $valor) {
            if(isset($valor))
                $proveedor->CreateProperty($nombre, $valor);
        }
        return $proveedor;
    }

    //--------------------- TXT FILE

    public static function InsertProveedorInTxtFile($fileTxtProveedores, $proveedor)
    {
        $result = null;
        $arrayTxtInFile = Proveedor::TextToProveedoresArray($fileTxtProveedores,';');
        
        if(!is_null($arrayTxtInFile) && count($arrayTxtInFile) > 0)
        {                     
            $ProveedorExist = $proveedor->GetProveedorById($arrayTxtInFile);
            if($ProveedorExist == null)
            {
                array_push($arrayTxtInFile, $proveedor);
            }                 
            $result = Proveedor::ProveedoresArrayToTxtFile($fileTxtProveedores, $arrayTxtInFile);
        }
        else
        {
            $result = Proveedor::ProveedoresArrayToTxtFile($fileTxtProveedores, array($proveedor));
        }        
        return $result;       
    }

    public static function UpdateProveedor($path, $id, $nombre, $email, $proveedor)
    {
        $mensaje = "Datos incorrectos" . PHP_EOL;
        $existe = false;
        $arrayTxtInFile = Proveedor::TextToProveedoresArray($path,';');
    
        foreach($arrayTxtInFile as $item)
        {        
            if($item->id == $id)
            {
                $item->nombre = $nombre;
                $item->email = $email;
                $existe = true;
                break;
            }
        }
        if($existe)
        {
            $result = Proveedor::ProveedoresArrayToTxtFile($path, $arrayTxtInFile);
        }
        return $result;
    }

    public static function TextToProveedoresArray($txtFile, $separador)
    {
        $arrayProveedores = array();
        $arrayTxt = $txtFile->TextToArray();
        foreach($arrayTxt as $row)
        {
            $dataArray = explode($separador,$row);
            if(strtolower(trim($dataArray[0])) == 'id')
            {
                continue;
            }
            else
            {
                $idAux = trim($dataArray[0]);
                $nombreAux = trim($dataArray[1]);
                $emailAux = trim($dataArray[2]);                
                $fotoAux = trim($dataArray[3]);
                $proveedor = Proveedor::GetProveedor($idAux, $nombreAux, $emailAux, $fotoAux);
                array_push($arrayProveedores, $proveedor);
            }
        }        
        return $arrayProveedores;
    }

    public function GetProveedorById($array)
    {
        $returnAux = null;
        foreach($array as $proveedor)
        {
            if($proveedor->id == $this->id)
            {
                $returnAux = $proveedor;
                break;
            }
        }
        return $returnAux;
    }

    public static function GetProveedorByNombreFromTxt($nombre, $txtFile)
    {
        $returnAux = null;
        $array = Proveedor::TextToProveedoresArray($txtFile, ';');

        foreach($array as $proveedor)
        {
            if($proveedor->nombre == $nombre)
            {
                $returnAux = $proveedor;
                break;
            }
        }
                      
        return $returnAux;

    }

    public static function GetProveedorByIdFromTxt($id, $txtFile)
    {        
        $returnAux = null;
        $array = Proveedor::TextToProveedoresArray($txtFile, ';');
        
        foreach($array as $proveedor)
        {
            if($proveedor->id == $id)
            {
                $returnAux = $proveedor;
                break;
            }
        }
                      
        return $returnAux;
    }    

    public function ProveedoresArrayToTxtFile($fileTxtProveedores, $array)
    {
        $string = 'Id;Nombre;Email;Foto'.PHP_EOL;
        $arrayInserts = array();
        foreach($array as $element)
        {
            $string .= $element->ToString();            
        }
        $result = $fileTxtProveedores->WriteInTxtFile($string);
        return $result;
    }

    private function ToString()
    {
        return "{$this->id};{$this->nombre};{$this->email};{$this->foto};" . PHP_EOL;
    } 

    public function ShowProveedor()
    {
        $format = sprintf("%-5s;%-18s;%-18s;%-20s;" . PHP_EOL,
            $this->id,$this->nombre,$this->email,$this->foto);
        return $format;
    }

    public function ShowProveedorWithHeader()
    {        
        $format = sprintf("%-5s;%-18s;%-18s;%-20s;" . PHP_EOL,
            "Id","Nombre","Email","Foto");
        echo $format;
        echo $this->ShowProveedor();
    } 

    public static function ShowTextList($path)
    {
        $format = sprintf("%-5s;%-18s;%-18s;%-20s;" . PHP_EOL,
        "Id","Nombre","Email","Foto");
        echo $format;

        $array = Proveedor::TextToProveedoresArray($path, ';');
        if($array != null)
        {
            foreach($array as $proveedor)
            {
                echo $proveedor->ShowProveedor();
            }
        }
        else
        {
            echo "Archivo vacío." . PHP_EOL;
        }
    }
}

?>