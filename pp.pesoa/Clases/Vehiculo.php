<?php
class Vehiculo
{
    public $patente;
    public $marca;
    public $modelo;
    public $precio;

    public static function GetVehiculo()
    {
        $vehiculo = new Vehiculo;        
        $numArgs = func_num_args();
        switch($numArgs)
        {
            case 3:
                $patente = func_get_arg(0);
                $marca = func_get_arg(1);
                $modelo = func_get_arg(2);
                $vehiculo->GetVehiculoUno($patente, $marca, $modelo);
            break;

            case 4:
                $patente = func_get_arg(0);
                $marca = func_get_arg(1);
                $modelo = func_get_arg(2);
                $precio = func_get_arg(3);
                $vehiculo->GetVehiculoDos($patente, $marca, $modelo, $precio);
            break;
        }
        return $vehiculo;
    }

    public function GetVehiculoUno($patente, $marca, $modelo) 
    {
        if (isset($patente)) {
            $this->patente = $patente;
        } 
        if (isset($marca)) {
            $this->marca = strtolower($marca);
        }
        if (isset($modelo)) {
            $this->modelo = $modelo;
        }         
    }

    public function GetVehiculoDos($patente, $marca, $modelo, $precio) 
    {
        if (isset($patente)) {
            $this->patente = $patente;
        } 
        if (isset($marca)) {
            $this->marca = strtolower($marca);
        }
        if (isset($modelo)) {
            $this->modelo = $modelo;
        } 
        if (isset($precio)) {
            $this->precio = $precio;
        } 
    }

    public static function InsertVehiculo($fileTxtVehiculos, $marca, $modelo, $patente, $precio)
    {
        $vehiculoExist = Vehiculo::GetVehiculoByPatenteFromTxt($patente, $fileTxtVehiculos);
        $result = "El vehiculo ya se encuentra registrado.".PHP_EOL;
        if(is_null($vehiculoExist))
        {                      
            $vehiculos = Vehiculo::TextToVehiculosArray($fileTxtVehiculos,';');
            $vehiculo = Vehiculo::GetVehiculo($patente, $marca, $modelo, $precio); 
            array_push($vehiculos, $vehiculo);
            Vehiculo::VehiculosArrayToTxtFile($fileTxtVehiculos, $vehiculos) != false ? 
                $result = "Se cargo vehiculo con patente " . "{$patente}.".PHP_EOL : 
                $result = "Error al cargar.".PHP_EOL;                                                          
        }
        return $result;
    }

    public static function TextToVehiculosArray($txtFile, $separador)
    {
        $arrayVehiculos = array();
        $arrayTxt = $txtFile->TextToArray();
        if(!is_null($arrayTxt) && count($arrayTxt) > 0)
        {
            foreach($arrayTxt as $row)
            {
                $dataArray = explode($separador, $row);
                if(strtolower(trim($dataArray[0])) == 'patente')
                {
                    continue;
                }
                else
                {
                    $patente = trim($dataArray[0]);
                    $marca = trim($dataArray[1]);
                    $modelo = trim($dataArray[2]);                
                    $precio = trim($dataArray[3]);
                    $vehiculo = Vehiculo::GetVehiculo($patente, $marca, $modelo, $precio);
                    array_push($arrayVehiculos, $vehiculo);
                }
            } 
        }               
        return $arrayVehiculos;
    }

    public static function GetVehiculoByMarcaFromTxt($marca, $txtFile)
    {
        $result = null;
        $vehiculos = Vehiculo::TextToVehiculosArray($txtFile, ';');
        if(!is_null($vehiculos) && count($vehiculos) > 0)
        {  
            foreach($vehiculos as $vehiculo)
            {
                if($vehiculo->marca == $marca)
                {
                    $result = $vehiculo;
                    break;
                }
            }
        }                              
        return $result;
    }

    public static function GetVehiculoByModeloFromTxt($modelo, $txtFile)
    {
        $result = null;
        $vehiculos = Vehiculo::TextToVehiculosArray($txtFile, ';');
        if(!is_null($vehiculos) && count($vehiculos) > 0)
        {  
            foreach($vehiculos as $vehiculo)
            {
                if($vehiculo->modelo == $modelo)
                {
                    $result = $vehiculo;
                    break;
                }
            }
        }                              
        return $result;
    }

    public static function GetVehiculosByDatoFromTxt($dato, $txtFile)
    {
        $result = null;
        $vehiculosDato = array();
        $vehiculos = Vehiculo::TextToVehiculosArray($txtFile, ';');        
        if(!is_null($vehiculos) && count($vehiculos) > 0)
        { 
            foreach($vehiculos as $vehiculo)
            {
                if($vehiculo->modelo == $dato || $vehiculo->marca == $dato || $vehiculo->patente == $dato)
                {          
                    array_push($vehiculosDato, $vehiculo);                    
                }
            }
            $result = $vehiculosDato;
        }                          
        return $result;
    }

    public static function GetVehiculoByPatenteFromTxt($patente, $txtFile)
    {        
        $result = null;
        $vehiculos = Vehiculo::TextToVehiculosArray($txtFile, ';');
        if(!is_null($vehiculos) && count($vehiculos) > 0)
        {
            foreach($vehiculos as $vehiculo)
            {
                if($vehiculo->patente == $patente)
                {
                    $result = $vehiculo;
                    break;
                }
            }   
        }                                   
        return $result;
    }    

    public function VehiculosArrayToTxtFile($fileTxtVehiculos, $array)
    {
        $string = 'patente;marca;modelo;precio;'.PHP_EOL;
        $arrayInserts = array();
        foreach($array as $element)
        {
            $string .= $element->ToString();            
        }
        return $fileTxtVehiculos->WriteInTxtFile($string);
    }

    private function ToString()
    {
        return "{$this->patente};{$this->marca};{$this->modelo};{$this->precio};" . PHP_EOL;
    }
    
    public static function GetHeader()
    {
        $format = sprintf("|%-15s|%-25s|%-30s|%-50s|" . PHP_EOL,
            "patente","marca","modelo","precio");
        return $format;
    }

    public function ShowVehiculo()
    {
        $format = sprintf("|%-15s|%-25s|%-30s|%-50s|" . PHP_EOL,
            $this->patente,$this->marca,$this->modelo,$this->precio);
        return $format;
    }

    public function ShowVehiculoWithHeader()
    {        
        $header = Vehiculo::GetHeader();
        $prov = $this->ShowVehiculo();
        return $header . $prov;
    } 

    public static function ShowVehiculosArray($array)
    {  
        $result = "Vacío." . PHP_EOL;      
        if(!is_null($array) && count($array) > 0)
        {
            $result = Vehiculo::GetHeader();
            foreach($array as $vehiculo)
            {
                
                $result .= $vehiculo->ShowVehiculo();
            }
        }
        return $result;
    }

    public static function ShowVehiculos($path)
    {  
        $result = "Archivo vacío." . PHP_EOL;      
        $array = Vehiculo::TextToVehiculosArray($path, ';');
        if(!is_null($array) && count($array) > 0)
        {
            $result = Vehiculo::GetHeader();
            foreach($array as $vehiculo)
            {
                $result .= $vehiculo->ShowVehiculo();
            }
        }
        return $result;
    }
}
?>