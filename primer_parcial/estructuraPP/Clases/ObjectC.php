<?php

class ObjectC
{
    public $propA;
    public $propB;
    public $propC;
    public $propD;
    public $propE;
    public $propF;    

    public function GetObjectC($propA, $propB, $propC, $propD, $propE, $propF)    
    {
        $objectC = new ObjectC();
        if (isset($propA)) {
            $objectC->propA = $propA;
        } 
        if (isset($propB)) {
            $objectC->propB = $propB;
        }
        if (isset($propC)) {
            $objectC->propC = $propC;
        }
        if (isset($propD)) {
            $objectC->propD = $propD;
        } 
        if (isset($propE)) {
            $objectC->propE = $propE;
        } 
        if (isset($propF)) {
            $objectC->propF = $propF;
        } 
        return $objectC;
    }
    
    public static function TextToObjectCsArray($fileTxtObjectC, $separador)
    {
        $arrayObjectCs = array();
        $arrayTxt = $fileTxtObjectC->TextToArray();
        if(!is_null($arrayTxt) && count($arrayTxt) > 0)
        {
            foreach($arrayTxt as $row)
            {
                $dataArray = explode($separador, $row);
                if(strcmp(trim($dataArray[0]), 'propA') == 0)
                {
                    continue;
                }
                else
                {
                    $propA = trim($dataArray[0]);
                    $propB = trim($dataArray[1]);
                    $propC = trim($dataArray[2]);                
                    $propD = trim($dataArray[3]);
                    $propE = trim($dataArray[4]);
                    $propF = trim($dataArray[5]);
                    $objectC = ObjectC::GetObjectC($propA, $propB, $propC, $propD, $propE, $propF);
                    array_push($arrayObjectCs, $objectC);
                }
            } 
        }               
        return $arrayObjectCs;
    }

    public static function GetObjectCsArrayByDatoFromTxt($dato, $fileTxtObjectC)
    {                
        $objectCsDato = array();
        $objectCs = ObjectC::TextToObjectCsArray($fileTxtObjectC, ';');        
        if(!is_null($objectCs) && count($objectCs) > 0)
        { 
            foreach($objectCs as $objectC)
            {
                if( strcasecmp($objectC->propB, $dato) == 0 || 
                    strcasecmp($objectC->propF, $dato) == 0)
                {          
                    array_push($objectCsDato, $objectC);                    
                }
            }               
        }                          
        return $objectCsDato;                                                                                           
    }

    public static function GetObjectCByPropAFromTxt($propA, $fileTxtObjectC)
    {        
        $result = null;        
        $objectCs = ObjectC::TextToObjectCsArray($fileTxtObjectC, ';');
        if(!is_null($objectCs) && count($objectCs) > 0)
        {
            foreach($objectCs as $objectC)
            {
                if(strcasecmp($objectC->propA, $propA) == 0)
                {
                    $result = $objectC;
                    break;
                }
            }   
        }                                   
        return $result;
    }    
    
    private function ToString()
    {
        return "{$this->propA};{$this->propB};{$this->propC};{$this->propD};{$this->propE};{$this->propF};" . PHP_EOL;
    }

    public function ObjectCsArrayToTxtFile($fileTxtObjectC, $array)
    {
        $string = 'propA;propB;propC;propD;' . PHP_EOL;
        foreach($array as $element)
        {
            $string .= $element->ToString();            
        }
        return $fileTxtObjectC->WriteInTxtFile($string);
    }    
    
    public static function GetHeader()
    {
        $format = sprintf("|%-15s|%-25s|%-30s|%-25s|%-25s|%-25s|" . PHP_EOL,
            "propA","propB","propC","propD","propE","propF");
        return $format;
    }

    public function ShowObjectC()
    {
        $format = sprintf("|%-15s|%-25s|%-30s|%-25s|%-25s|%-25s|" . PHP_EOL,
            $this->propA,$this->propB,$this->propC,$this->propD,$this->propE,$this->propF);
        return $format;
    }

    public function ShowObjectCWithHeader()
    {        
        $header = ObjectC::GetHeader();
        $prov = $this->ShowObjectC();
        return $header . $prov;
    } 

    public static function ShowObjectCsArray($array)
    {  
        $result = "Vacío." . PHP_EOL;      
        if(!is_null($array) && count($array) > 0)
        {
            $result = ObjectC::GetHeader();
            foreach($array as $objectC)
            {                
                $result .= $objectC->ShowObjectC();
            }
        }
        return $result;
    }

    public static function ShowObjectCs($path)
    {  
        $result = "Archivo vacío." . PHP_EOL;      
        $array = ObjectC::TextToObjectCsArray($path, ';');
        if(!is_null($array) && count($array) > 0)
        {
            $result = ObjectC::GetHeader();
            foreach($array as $objectC)
            {
                $result .= $objectC->ShowObjectC();
            }
        }
        return $result;
    }

    public static function InsertObjectC($fileTxtObjectC, $fileTxtObjectA, $fileTxtObjectB, $post)
    {
        $result = 'Son requeridos propA, propB del objectC.'.PHP_EOL;
        if(isset($post['propA']) && isset($post['propB']))
        {                     
            $objectAExist = ObjectA::GetObjectAByPropAFromTxt($post['propA'], $fileTxtObjectA);
            $result = "No existe objectA registrado con propA.".PHP_EOL;
            if(!is_null($objectAExist))
            {                      
                $result = "La propB es invalida.".PHP_EOL;
                if($post['propB'] > 0 && $post['propB'] <= 31)
                {
                    if($post['propB'] <= 10)
                    {
                        $propF = "10000";
                    }
                    else
                    {
                        if($post['propB'] > 10 && $post['propB'] <= 20)
                        {
                            $propF = "20000";
                        }
                        else
                        {
                            $propF = "50000";
                        }                            
                    }

                    $objectB = ObjectB::GetObjectBByPropBFromTxt($propF, $fileTxtObjectB);

                    $propC = $objectAExist->propB;
                    $propD = $objectAExist->propC;
                    $propE = $objectB->propC;
                    
                    $objectCs = ObjectC::TextToObjectCsArray($fileTxtObjectC,';');            
                    $objectC = ObjectC::GetObjectC($post['propA'], $post['propB'], $propC, $propD, $propE, $propF);

                    array_push($objectCs, $objectC);
                    is_null(ObjectC::ObjectCsArrayToTxtFile($fileTxtObjectC, $objectCs)) ? 
                        $result = "Error al cargar." . PHP_EOL :                                                           
                        $result = "Se cargo objectC con propA " . "{$post['propA']}." . PHP_EOL;
                }                
            }
            
        }        
        return $result;
    }
    
    public static function ConsultObjectC($dato, $fileTxtObjectC)
    {
        $result = 'Es requerido al menos un dato del objectC.'.PHP_EOL;
        if(isset($dato))
        {  
            $result = "No existe vehiculo ".$dato.PHP_EOL;    
            $objectCsDato = ObjectC::GetObjectCsArrayByDatoFromTxt($dato, $fileTxtObjectC);
            if(!is_null($objectCsDato))                
                $result = ObjectC::ShowObjectCsArray($objectCsDato); 
        }
        return $result;
    }
}

?>