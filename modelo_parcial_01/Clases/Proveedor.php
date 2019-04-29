<?php
class Proveedor
{
    public $id;
    public $nombre;
    public $email;
    public $foto;

    public static function GetProveedor()
    {
        $proveedor = new Proveedor;        
        $numArgs = func_num_args();
        switch($numArgs)
        {
            case 3:
                $id = func_get_arg(0);
                $nombre = func_get_arg(1);
                $email = func_get_arg(2);
                $proveedor->GetProveedorWithoutFoto($id, $nombre, $email);
            break;

            case 4:
                $id = func_get_arg(0);
                $nombre = func_get_arg(1);
                $email = func_get_arg(2);
                $foto = func_get_arg(3);
                $proveedor->GetProveedorWithFoto($id, $nombre, $email, $foto);
            break;
        }
        return $proveedor;
    }

    public function GetProveedorWithoutFoto($id, $nombre, $email) 
    {
        if (isset($id)) {
            $this->id = $id;
        } 
        if (isset($nombre)) {
            $this->nombre = strtolower($nombre);
        }
        if (isset($email)) {
            $this->email = $email;
        }         
    }

    public function GetProveedorWithFoto($id, $nombre, $email, $foto) 
    {
        if (isset($id)) {
            $this->id = $id;
        } 
        if (isset($nombre)) {
            $this->nombre = strtolower($nombre);
        }
        if (isset($email)) {
            $this->email = $email;
        } 
        if (isset($foto)) {
            $this->foto = $foto;
        } 
    }

    public static function GetLastIdInArray($array)
    {
        return $array[count($array) - 1]->id;
    }

    public static function AssignId($array)
    {
        $id = 1;
        if(!is_null($array) && count($array) > 0)
        {   
            $lastId = Proveedor::GetLastIdInArray($array);
            $id = $lastId + 1;                                                                                                                                                                                                                     
        }
        return $id;
    }

    public static function InsertProveedor($fileTxtProveedores, $nombre, $email, $fileFotos, $file)
    {
        $proveedorExist = Proveedor::GetProveedorByNameOrEmailFromTxt($nombre, $email, $fileTxtProveedores);
        $result = "El proveedor ya se encuentra registrado.".PHP_EOL;
        if(is_null($proveedorExist))
        {                      
            $proveedores = Proveedor::TextToProveedoresArray($fileTxtProveedores,';');
            $id = Proveedor::AssignId($proveedores);
            $proveedor = Proveedor::GetProveedor($id, $nombre, $email); 
            $result = "Error al guardar foto".PHP_EOL;
            if(!is_null($file))
            {                                                                     
                $foto = $fileFotos->SaveFileInFolder($file, $proveedor);                          
                if(!is_null($foto))
                {
                    $proveedor->foto = $foto;
                    array_push($proveedores, $proveedor);
                    Proveedor::ProveedoresArrayToTxtFile($fileTxtProveedores, $proveedores) != false ? 
                        $result = "Se cargó proveedor con id " . "{$id}.".PHP_EOL : 
                        $result = "Error al cargar.".PHP_EOL; 
                }                    
            }                                                           
        }
        return $result;
    }

    public static function UpdateProveedor($fileTxtProveedores, $id, $nombre, $email, $fileFotos, $fileFotosBackup, $file)
    {
        $result = "No existe registro con id " . $id . PHP_EOL;
        $proveedor = Proveedor::GetProveedorByIdFromTxt($id, $fileTxtProveedores);
        if(!is_null($proveedor))
        {
            $proveedores = Proveedor::TextToProveedoresArray($fileTxtProveedores,';');
            $result = "Error al actualizar foto".PHP_EOL;
            if(!is_null($file))
            {                              
                $fotoBack = $fileFotosBackup->UpdateFileInFolder($file, $fileFotos, $proveedor);                
                if(!is_null($fotoBack))
                {
                    foreach($proveedores as $item)
                    {        
                        if($item->id == $id)
                        {
                            $item->nombre = $nombre;
                            $item->email = $email;
                            $foto = $fileFotos->SaveFileInFolder($file, $item);
                            if(!is_null($foto))
                                $item->foto = $foto;
                            break;
                        }
                    } 
                    Proveedor::ProveedoresArrayToTxtFile($fileTxtProveedores, $proveedores) != false ? 
                        $result = "Se modificó proveedor con id " . "{$id}.".PHP_EOL : 
                        $result = "Error al modificar.".PHP_EOL; 
                }
            }                                   
        }
        return $result;
    }

    public static function TextToProveedoresArray($txtFile, $separador)
    {
        $arrayProveedores = array();
        $arrayTxt = $txtFile->TextToArray();
        if(!is_null($arrayTxt) && count($arrayTxt) > 0)
        {
            foreach($arrayTxt as $row)
            {
                $dataArray = explode($separador, $row);
                if(strtolower(trim($dataArray[0])) == 'id')
                {
                    continue;
                }
                else
                {
                    $id = trim($dataArray[0]);
                    $nombre = trim($dataArray[1]);
                    $email = trim($dataArray[2]);                
                    $foto = trim($dataArray[3]);
                    $proveedor = Proveedor::GetProveedor($id, $nombre, $email, $foto);
                    array_push($arrayProveedores, $proveedor);
                }
            } 
        }               
        return $arrayProveedores;
    }

    public static function GetProveedorByNombreFromTxt($nombre, $txtFile)
    {
        $result = null;
        $proveedores = Proveedor::TextToProveedoresArray($txtFile, ';');
        if(!is_null($proveedores) && count($proveedores) > 0)
        {  
            foreach($proveedores as $proveedor)
            {
                if($proveedor->nombre == $nombre)
                {
                    $result = $proveedor;
                    break;
                }
            }
        }                              
        return $result;
    }

    public static function GetProveedorByNameOrEmailFromTxt($nombre, $email, $txtFile)
    {
        $result = null;
        $proveedores = Proveedor::TextToProveedoresArray($txtFile, ';');
        if(!is_null($proveedores) && count($proveedores) > 0)
        { 
            foreach($proveedores as $proveedor)
            {
                if($proveedor->email == $email || $proveedor->nombre == $nombre)
                {
                    $result = $proveedor;
                    break;
                }
            }
        }                              
        return $result;
    }

    public static function GetProveedorByIdFromTxt($id, $txtFile)
    {        
        $result = null;
        $proveedores = Proveedor::TextToProveedoresArray($txtFile, ';');
        if(!is_null($proveedores) && count($proveedores) > 0)
        {
            foreach($proveedores as $proveedor)
            {
                if($proveedor->id == $id)
                {
                    $result = $proveedor;
                    break;
                }
            }   
        }                                   
        return $result;
    }    

    public function ProveedoresArrayToTxtFile($fileTxtProveedores, $array)
    {
        $string = 'Id;Nombre;Email;Foto'.PHP_EOL;
        $arrayInserts = array();
        foreach($array as $element)
        {
            $string .= $element->ToString();            
        }
        return $fileTxtProveedores->WriteInTxtFile($string);
    }

    private function ToString()
    {
        return "{$this->id};{$this->nombre};{$this->email};{$this->foto};" . PHP_EOL;
    }
    
    public static function GetHeader()
    {
        $format = sprintf("|%-5s|%-25s|%-30s|%-50s|" . PHP_EOL,
            "Id","Nombre","Email","Foto");
        return $format;
    }

    public function ShowProveedor()
    {
        $format = sprintf("|%-5s|%-25s|%-30s|%-50s|" . PHP_EOL,
            $this->id,$this->nombre,$this->email,$this->foto);
        return $format;
    }

    public function ShowProveedorWithHeader()
    {        
        $header = Proveedor::GetHeader();
        $prov = $this->ShowProveedor();
        return $header . $prov;
    } 

    public static function ShowProveedores($path)
    {  
        $result = "Archivo vacío." . PHP_EOL;      
        $array = Proveedor::TextToProveedoresArray($path, ';');
        if(!is_null($array) && count($array) > 0)
        {
            $result = Proveedor::GetHeader();
            foreach($array as $proveedor)
            {
                $result .= $proveedor->ShowProveedor();
            }
        }
        return $result;
    }

    public static function GetArrayOfFotosBackup($fileFotosBackup)
    {
        $fotosBack = array();
        $arrayBack = scandir($fileFotosBackup->path);        
        foreach($arrayBack as $item)
        {
            if(!is_dir($item) && substr($item,0,1) != '.')
                array_push($fotosBack, $item);
        }
        return $fotosBack;
    }

    public static function ShowFotosBakup($fileTxtProveedores, $fileFotosBackup)
    {        
        $result = "Directorio vacio.".PHP_EOL;
        $fotosBack = Proveedor::GetArrayOfFotosBackup($fileFotosBackup);
        if(!is_null($fotosBack) && count($fotosBack) > 0)
        {
            $result = sprintf("|%-50s|%-30s|%-30s|" . PHP_EOL,
                "Foto Backup","Proveedor","Fecha de creacion");
            foreach($fotosBack as $foto)
            {
                $nameArray = explode('_', $foto);
                $id = $nameArray[0];
                $fechaYExtension = $nameArray[2];
                $fechaYExtensionArray = explode('.', $fechaYExtension);
                $fecha = $fechaYExtensionArray[0];
                $proveedor = Proveedor::GetProveedorByIdFromTxt($id, $fileTxtProveedores);
                $format = sprintf("|%-50s|%-30s|%-30s|" . PHP_EOL,
                    $foto,$proveedor->nombre,$fecha);
                $result .= $format;
            }
        }        
        return $result;
    }
}
?>