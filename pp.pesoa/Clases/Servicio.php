<?php
class Servicio
{
    public $id;
    public $nombre;
    public $precio;
    public $tipo;
    public $demora;

    public static function GetServicio($id, $nombre, $tipo, $precio, $demora) 
    {
        $servicio = new Servicio();
        if (isset($id)) {
            $servicio->id = $id;
        }
        if (isset($nombre)) {
            $servicio->nombre = $nombre;
        }
        if (isset($precio)) {
            $servicio->precio = $precio;
        }
        if (isset($tipo)) {
            $servicio->tipo = strtolower($tipo);
        }          
        if (isset($demora)) {
            $servicio->demora = $demora;
        }         
        return $servicio;
    }

    public static function InsertServicio($fileTxtServicios, $id, $nombre, $tipo, $precio, $demora)
    {
        $servicioExist = Servicio::GetServicioByIdFromTxt($id, $fileTxtServicios);
        $result = "El servicio ya se encuentra registrado.".PHP_EOL;
        if(is_null($servicioExist))
        {                      
            $servicios = Servicio::TextToServiciosArray($fileTxtServicios,';');
            $servicio = Servicio::GetServicio($id, $nombre, $tipo, $precio, $demora); 
            array_push($servicios, $servicio);
            Servicio::ServiciosArrayToTxtFile($fileTxtServicios, $servicios) != false ? 
                $result = "Se cargo servicio con id " . "{$id}.".PHP_EOL : 
                $result = "Error al cargar.".PHP_EOL;                                                          
        }
        return $result;
    }

    public static function TextToServiciosArray($txtFile, $separador)
    {
        $arrayServicios = array();
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
                    $tipo = trim($dataArray[2]);
                    $precio = trim($dataArray[3]);                
                    $demora = trim($dataArray[4]);                
                    $servicio = Servicio::GetServicio($id, $nombre, $tipo, $precio, $demora);
                    array_push($arrayServicios, $servicio);
                }
            }
        }                
        return $arrayServicios;
    }

    public static function GetServicioByIdFromTxt($id, $txtFile)
    {        
        $result = null;
        $servicios = Servicio::TextToServiciosArray($txtFile, ';');
        if(!is_null($servicios) && count($servicios) > 0)
        {
            foreach($servicios as $servicio)
            {
                if($servicio->id == $id)
                {
                    $result = $servicio;
                    break;
                }
            }   
        }                                   
        return $result;
    }

    public static function GetServicioByTipoFromTxt($tipo, $txtFile)
    {      
        $result = null;
        $servicios = Servicio::TextToServiciosArray($txtFile, ';');
        if(!is_null($servicios) && count($servicios) > 0)
        {            
            foreach($servicios as $servicio)
            {
                if($servicio->tipo == $tipo)   
                    $result = $servicio;                          
            }
        }                              
        return $result;
    }    

    public function ServiciosArrayToTxtFile($fileTxtServicios, $array)
    {
        $string = 'Id;Nombre;Tipo;Precio;Demora;'.PHP_EOL;
        $arrayInserts = array();
        foreach($array as $element)
        {
            $string .= $element->ToString();            
        }
        $result = $fileTxtServicios->WriteInTxtFile($string);
        return $result;
    }

    private function ToString()
    {
        return "{$this->id};{$this->nombre};{$this->tipo};{$this->precio};{$this->demora};" . PHP_EOL;
    }
    
    public static function GetHeader()
    {
        $format = sprintf("|%-5s|%-30s|%-20s|%-15s|%-15s|" . PHP_EOL,
            "Id","Nombre","Tipo","Precio","Demora");
        return $format;
    }

    public function ShowServicio($prov)
    {
        $format = sprintf("|%-5s|%-30s|%-20s|%-15s|%-15s|" . PHP_EOL,
            $this->id,$this->nombre,$this->tipo,$this->precio,$this->demora);
        return $format;
    }

    public function ShowServicioWithHeader()
    {
        $header = Servicio::GetHeader();
        $servicio = $this->ShowServicio();
        return $header . $servicio;
    } 

    public static function ShowServicios($txtServicios, $txtVehiculos)
    {   
        $result = "Archivo vacío." . PHP_EOL;     
        $array = Servicio::TextToServiciosArray($txtServicios, ';');        
        if(!is_null($array) && count($array) > 0)
        {
            $result = Servicio::GetHeader();
            foreach($array as $servicio)
            {
                $prov = Vehiculo::GetVehiculoByIdFromTxt($servicio->idVehiculo, $txtVehiculos);
                $result .= $servicio->ShowServicio($prov);
            }
        }
        return $result;
    }

    public static function ShowServiciosVehiculo($fileTxtServicio, $vehiculo)
    {
        $result = "El vehiculo no tiene tipos cargados".PHP_EOL;
        $servicios = Servicio::GetServiciosByIdVehiculoFromTxt($vehiculo->id, $fileTxtServicio);
        if(!is_null($servicios) && count($servicios) > 0)
        {
            $result = Servicio::GetHeader();
            foreach($servicios as $servicio)
            {
                $result .= $servicio->ShowServicio($vehiculo);
            }
        }        
        return $result;
    }
}
?>