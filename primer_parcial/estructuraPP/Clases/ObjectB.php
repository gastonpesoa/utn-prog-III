<?php

class ObjectB
{
    public $propA;
    public $propB;
    public $propC;
    public $propD;

    public function GetObjectB($propA, $propB, $propC, $propD)    
    {
        $objectB = new ObjectB();
        if (isset($propA)) {
            $objectB->propA = $propA;
        } 
        if (isset($propB)) {
            $objectB->propB = $propB;
        }
        if (isset($propC)) {
            $objectB->propC = $propC;
        }
        if (isset($propD)) {
            $objectB->propD = $propD;
        } 
        return $objectB;
    }
    
    public static function TextToObjectBsArray($fileTxtObjectB, $separador)
    {
        $arrayObjectBs = array();
        $arrayTxt = $fileTxtObjectB->TextToArray();
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
                    $objectB = ObjectB::GetObjectB($propA, $propB, $propC, $propD);
                    array_push($arrayObjectBs, $objectB);
                }
            } 
        }               
        return $arrayObjectBs;
    }

    public static function GetObjectBsArrayByDatoFromTxt($dato, $fileTxtObjectB)
    {                
        $objectBsDato = array();
        $objectBs = ObjectB::TextToObjectBsArray($fileTxtObjectB, ';');        
        if(!is_null($objectBs) && count($objectBs) > 0)
        { 
            foreach($objectBs as $objectB)
            {
                if( strcasecmp($objectB->propA, $dato) == 0 || 
                    strcasecmp($objectB->propB, $dato) == 0 || 
                    strcasecmp($objectB->propC, $dato) == 0)
                {          
                    array_push($objectBsDato, $objectB);                    
                }
            }               
        }                          
        return $objectBsDato;                                                                                           
    }

    public static function GetObjectBByPropAFromTxt($propA, $fileTxtObjectB)
    {        
        $result = null;        
        $objectBs = ObjectB::TextToObjectBsArray($fileTxtObjectB, ';');
        if(!is_null($objectBs) && count($objectBs) > 0)
        {
            foreach($objectBs as $objectB)
            {
                if(strcasecmp($objectB->propA, $propA) == 0)
                {
                    $result = $objectB;
                    break;
                }
            }   
        }                                   
        return $result;
    }    

    public static function GetObjectBByPropBFromTxt($propB, $fileTxtObjectB)
    {        
        $result = null;        
        $objectBs = ObjectB::TextToObjectBsArray($fileTxtObjectB, ';');
        if(!is_null($objectBs) && count($objectBs) > 0)
        {
            foreach($objectBs as $objectB)
            {
                if(strcasecmp($objectB->propB, $propB) == 0)
                {
                    $result = $objectB;
                    break;
                }
            }   
        }                                   
        return $result;
    }  
    
    private function ToString()
    {
        return "{$this->propA};{$this->propB};{$this->propC};{$this->propD};" . PHP_EOL;
    }

    public function ObjectBsArrayToTxtFile($fileTxtObjectB, $array)
    {
        $string = 'propA;propB;propC;propD;' . PHP_EOL;
        foreach($array as $element)
        {
            $string .= $element->ToString();            
        }
        return $fileTxtObjectB->WriteInTxtFile($string);
    }    
    
    public static function GetHeader()
    {
        $format = sprintf("|%-15s|%-25s|%-30s|%-50s|" . PHP_EOL,
            "propA","propB","propC","propD");
        return $format;
    }

    public function ShowObjectB()
    {
        $format = sprintf("|%-15s|%-25s|%-30s|%-50s|" . PHP_EOL,
            $this->propA,$this->propB,$this->propC,$this->propD);
        return $format;
    }

    public function ShowObjectBWithHeader()
    {        
        $header = ObjectB::GetHeader();
        $prov = $this->ShowObjectB();
        return $header . $prov;
    } 

    public static function ShowObjectBsArray($array)
    {  
        $result = "Vacío." . PHP_EOL;      
        if(!is_null($array) && count($array) > 0)
        {
            $result = ObjectB::GetHeader();
            foreach($array as $objectB)
            {                
                $result .= $objectB->ShowObjectB();
            }
        }
        return $result;
    }

    public static function ShowObjectBs($path)
    {  
        $result = "Archivo vacío." . PHP_EOL;      
        $array = ObjectB::TextToObjectBsArray($path, ';');
        if(!is_null($array) && count($array) > 0)
        {
            $result = ObjectB::GetHeader();
            foreach($array as $objectB)
            {
                $result .= $objectB->ShowObjectB();
            }
        }
        return $result;
    }

    public static function InsertObjectB($fileTxtObjectB, $post)
    {
        $result = 'Son requeridos propA, propB, propC y propD del objectB.'.PHP_EOL;
        if(isset($post['propA']) && isset($post['propB']) && isset($post['propC']) && isset($post['propD']))
        {  
            $result = "El tipo debe ser 10.000km, 20.000km, 50.000km".PHP_EOL;
            if(strcmp($post['propB'], "10000") == 0 || strcmp($post['propB'], "20000") == 0 || strcmp($post['propB'], "50000") == 0)
            {
                $objectBExist = ObjectB::GetObjectBByPropAFromTxt($post['propA'], $fileTxtObjectB);
                $result = "El objectB ya se encuentra registrado.".PHP_EOL;
                if(is_null($objectBExist))
                {                      
                    $objectBs = ObjectB::TextToObjectBsArray($fileTxtObjectB,';');            
                    $objectB = ObjectB::GetObjectB($post['propA'], $post['propB'], $post['propC'], $post['propD']);
                    array_push($objectBs, $objectB);
                    is_null(ObjectB::ObjectBsArrayToTxtFile($fileTxtObjectB, $objectBs)) ? 
                        $result = "Error al cargar." . PHP_EOL :                                                           
                        $result = "Se cargo objectB con propA " . "{$post['propA']}." . PHP_EOL;
                }
            }
        }        
        return $result;
    }
    
    public static function ConsultObjectB($get, $fileTxtObjectB)
    {
        $result = 'Es requerido al menos un dato del objectB.'.PHP_EOL;
        if(isset($get['dato']))
        {  
            $result = "No existe vehiculo ". $get['dato'] . PHP_EOL;    
            $objectBsDato = ObjectB::GetObjectBsArrayByDatoFromTxt($get['dato'], $fileTxtObjectB);
            if(!is_null($objectBsDato))                
                $result = ObjectB::ShowObjectBsArray($objectBsDato); 
        }
        return $result;
    }
}

?>