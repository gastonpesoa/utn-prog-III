<?php
    class Turno
    {
        public $fecha;
        public $vehiculo;
        public $servicio;

        public static function GetTurno($fecha, $vehiculo, $servicio) 
        {
            $turno = new Turno();
            if (isset($fecha)) {
                $turno->fecha = $fecha;
            }
            if (isset($vehiculo)) {
                $turno->vehiculo = $vehiculo;
            }    
            if (isset($servicio)) {
                $turno->servicio = $servicio;
            }        
            return $turno;
        }

        public static function InsertTurno($fileTxtTurnos, $fileTxtVehiculos, $fileTxtServicios, $fecha, $vehiculo, $servicio)
        {           
            $turnos = Turno::TextToTurnosArray($fileTxtTurnos, $fileTxtVehiculos, $fileTxtServicios,';');
            $turno = Turno::GetTurno($fecha, $vehiculo, $servicio); 
            array_push($turnos, $turno);
            Turno::TurnosArrayToTxtFile($fileTxtTurnos, $turnos) != false ? 
                $result = "Se cargo turno con patente " . "{$patente}.".PHP_EOL : 
                $result = "Error al cargar.".PHP_EOL;   
            //var_dump($turnos);
            return $result;
        }

        public static function TextToTurnosArray($txtFile, $fileTxtVehiculos, $fileTxtServicios, $separador)
    {
        $arrayTurnos = array();
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
                    $fecha = trim($dataArray[0]);
                    $patente = trim($dataArray[1]);
                    $marca = trim($dataArray[2]);
                    $modelo = trim($dataArray[3]);                
                    $precio = trim($dataArray[4]);
                    $tipo = trim($dataArray[5]);
                    $vehiculo = Vehiculo::GetVehiculoByPatenteFromTxt($patente, $fileTxtVehiculos);
                    $servicio = Servicio::GetServicioByTipoFromTxt($tipo, $fileTxtServicios);
                    $vehiculo = Turno::GetTurno($fecha, $vehiculo, $servicio);
                    array_push($arrayTurnos, $vehiculo);
                }
            } 
        }               
        return $arrayTurnos;
    }

        public function TurnosArrayToTxtFile($fileTxtTurnos, $array)
        {
            $string = 'fecha;patente;marca;modelo;precio;tipo;'.PHP_EOL;
            foreach($array as $element)
            {                
                $string .= $element->ToString();            
            }
            return $fileTxtTurnos->WriteInTxtFile($string);
        }

        private function ToString()
        {                                 
            return "{$this->fecha};{$this->vehiculo->patente};{$this->vehiculo->marca};{$this->vehiculo->modelo};{$this->servicio->precio};{$this->servicio->tipo};" . PHP_EOL;
        }
        
    }
?>