<?php

class ObjectA
{
    public $propA;
    public $propB;
    public $propC;
    public $propD;
    public $propE;

    public function GetObjectA($propA, $propB, $propC, $propD, $propE)    
    {
        $objectA = new ObjectA();
        if (isset($propA)) {
            $objectA->propA = $propA;
        } 
        if (isset($propB)) {
            $objectA->propB = $propB;
        }
        if (isset($propC)) {
            $objectA->propC = $propC;
        }
        if (isset($propD)) {
            $objectA->propD = $propD;
        } 
        if (isset($propE)) {
            $objectA->propE = $propE;
        } 
        return $objectA;
    }
    
    public static function TextToObjectAsArray($fileTxtObjectA, $separador)
    {
        $arrayObjectAs = array();
        $arrayTxt = $fileTxtObjectA->TextToArray();
        if(!is_null($arrayTxt) && count($arrayTxt) > 0)
        {
            foreach($arrayTxt as $row)
            {
                $dataArray = explode($separador, $row);
                if(strcmp((trim($dataArray[0])), 'propA') == 0)
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
                    $objectA = ObjectA::GetObjectA($propA, $propB, $propC, $propD, $propE);
                    array_push($arrayObjectAs, $objectA);
                }
            } 
        }               
        return $arrayObjectAs;
    }

    public static function GetObjectAsArrayByDatoFromTxt($dato, $fileTxtObjectA)
    {                
        $objectAsDato = array();
        $objectAs = ObjectA::TextToObjectAsArray($fileTxtObjectA, ';');        
        if(!is_null($objectAs) && count($objectAs) > 0)
        { 
            foreach($objectAs as $objectA)
            {
                if( strcasecmp($objectA->propA, $dato) == 0 || 
                    strcasecmp($objectA->propB, $dato) == 0 || 
                    strcasecmp($objectA->propC, $dato) == 0)
                {          
                    array_push($objectAsDato, $objectA);                    
                }
            }               
        }                          
        return $objectAsDato;                                                                                           
    }

    public static function GetObjectAByPropAFromTxt($propA, $fileTxtObjectA)
    {        
        $result = null;        
        $objectAs = ObjectA::TextToObjectAsArray($fileTxtObjectA, ';');
        if(!is_null($objectAs) && count($objectAs) > 0)
        {
            foreach($objectAs as $objectA)
            {
                if(strcasecmp($objectA->propA, $propA) == 0)
                {
                    $result = $objectA;
                    break;
                }
            }   
        }                                   
        return $result;
    }    
    
    private function ToString()
    {
        return "{$this->propA};{$this->propB};{$this->propC};{$this->propD};{$this->propE};" . PHP_EOL;
    }

    public function ObjectAsArrayToTxtFile($fileTxtObjectA, $array)
    {
        $string = 'propA;propB;propC;propD;propE;' . PHP_EOL;
        foreach($array as $element)
        {
            $string .= $element->ToString();            
        }
        return $fileTxtObjectA->WriteInTxtFile($string);
    }    
    
    public static function GetHeader()
    {
        $format = sprintf("|%-15s|%-25s|%-30s|%-50s|" . PHP_EOL,
            "propA","propB","propC","propD");
        return $format;
    }

    public function ShowObjectA()
    {
        $format = sprintf("|%-15s|%-25s|%-30s|%-50s|" . PHP_EOL,
            $this->propA,$this->propB,$this->propC,$this->propD);
        return $format;
    }

    public function ShowObjectAWithHeader()
    {        
        $header = ObjectA::GetHeader();
        $prov = $this->ShowObjectA();
        return $header . $prov;
    } 

    public static function ShowObjectAsArray($array)
    {  
        $result = "Vacío." . PHP_EOL;      
        if(!is_null($array) && count($array) > 0)
        {
            $result = ObjectA::GetHeader();
            foreach($array as $objectA)
            {                
                $result .= $objectA->ShowObjectA();
            }
        }
        return $result;
    }

    public static function ShowObjectAs($path)
    {  
        $result = "Archivo vacío." . PHP_EOL;      
        $array = ObjectA::TextToObjectAsArray($path, ';');
        if(!is_null($array) && count($array) > 0)
        {
            $result = ObjectA::GetHeader();
            foreach($array as $objectA)
            {
                $result .= $objectA->ShowObjectA();
            }
        }
        return $result;
    }
    
    public static function ShowObjectAsWithFotos($path)
    {  
        $result = "Archivo vacío." . PHP_EOL;      
        $array = ObjectA::TextToObjectAsArray($path, ';');
        if(!is_null($array) && count($array) > 0)
        {
            $result = "<style>table,th,tr,td{border:1px solid black;border-collapse: collapse;}</style>";
            $result .= sprintf("<table><tr><th>propA</th><th>propB</th><th>propC</th><th>propD</th><th>propE</th></tr>");
            foreach($array as $objectA)
            {
                if($objectA->propE != null)
                {
                    $result .= sprintf("<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>",
                        $objectA->propA,$objectA->propB,$objectA->propC,$objectA->propD,
                            "<img src='$objectA->propE' style='width:100px;height:100px;'>");
                }
                else
                {
                    $result .= sprintf("<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>",
                        $objectA->propA,$objectA->propB,$objectA->propC,$objectA->propD,"-");
                }
            }
            $result .= "</table>";
        }
        return $result;
    }

    public static function InsertObjectA($fileTxtObjectA, $post, $file)
    {
        $result = 'Son requeridos propA, propB, propC y propD del objectA.'.PHP_EOL;
        if(isset($post['propA']) && isset($post['propB']) && isset($post['propC']) && isset($post['propD']))
        {             
            $objectAExist = ObjectA::GetObjectAByPropAFromTxt($post['propA'], $fileTxtObjectA);
            $result = "El objectA ya se encuentra registrado.".PHP_EOL;
            if(is_null($objectAExist))
            {                      
                $objectAs = ObjectA::TextToObjectAsArray($fileTxtObjectA,';');     
                $objectA = ObjectA::GetObjectA($post['propA'], $post['propB'], $post['propC'], $post['propD'], null);
                if(isset($file['foto']))
                {
                    $fileFotos = new Archivo(FOTOS);
                    $fotoObjectA = $fileFotos->SaveFileInFolder($file['foto'], FOTOS_BACKUP, $objectA);
                    if(isset($fotoObjectA))
                        $objectA->propE = $fotoObjectA;
                }
                array_push($objectAs, $objectA);
                is_null(ObjectA::ObjectAsArrayToTxtFile($fileTxtObjectA, $objectAs)) ? 
                    $result = "Error al cargar." . PHP_EOL :                                                           
                    $result = "Se cargo objectA con propA " . "{$post['propA']}." . PHP_EOL;
            }
        }        
        return $result;
    }
    
    public static function ConsultObjectA($get, $fileTxtObjectA)
    {
        $result = 'Es requerido al menos un dato del objectA.'.PHP_EOL;
        if(isset($get['dato']))
        {  
            $result = "No existe objectA ". $get['dato'] . PHP_EOL;    
            $objectAsDato = ObjectA::GetObjectAsArrayByDatoFromTxt($get['dato'], $fileTxtObjectA);
            if(!is_null($objectAsDato))                
                $result = ObjectA::ShowObjectAsArray($objectAsDato); 
        }
        return $result;
    }

    public static function UpdateObjectA($fileTxtObjectA, $fileFotos, $urlBackup, $post, $file)
    {        
        $result = "Es requerida una propA" . PHP_EOL;
        if(isset($post['propA']))
        {                                    
            $existe = false;
            $result = "No existe objectA " . $post['propA'] . PHP_EOL;    
            $objectA = ObjectA::GetObjectAByPropAFromTxt($post['propA'], $fileTxtObjectA);
            if(!is_null($objectA))   
            {
                if(isset($file['foto']))
                    $fotoObjectA = $fileFotos->SaveFileInFolder($file['foto'], $urlBackup, $objectA);
                $objectAs = ObjectA::TextToObjectAsArray($fileTxtObjectA,';');  
                foreach($objectAs as $objectA)
                {        
                    if(strcasecmp($objectA->propA, $post['propA']) == 0)
                    {
                        if(isset($post['propB']))
                            $objectA->propB = $post['propB'];
                        if(isset($post['propC']))
                            $objectA->propC = $post['propC'];
                        if(isset($post['propD']))
                            $objectA->propD = $post['propD'];
                        if(isset($fotoObjectA))
                            $objectA->propE = $fotoObjectA; 
                            
                        is_null(ObjectA::ObjectAsArrayToTxtFile($fileTxtObjectA, $objectAs)) ? 
                            $result = "Error al cargar." . PHP_EOL :                                                           
                            $result = "Se modifico objectA con propA " . "{$post['propA']}." . PHP_EOL;

                        $existe = true;
                        break;                        
                    }
                }
            }            
        }
        return $result;
    }
}

?>