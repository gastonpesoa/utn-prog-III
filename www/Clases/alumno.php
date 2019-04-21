<?php
require_once CLASES . '/Persona.php';
require_once CLASES . '/AccesoDatos.php';
require_once CLASES . '/AlumnoDatos.php';
require_once CLASES . '/Archivo.php';

class Alumno extends Persona {

    //=====================  PROPIEDADES =====================

    public $id;
    public $legajo;
    public $foto;

    //===================== CONSTRUCTOR =====================

    // public function __construct($nombre, $apellido, $edad, $dni, $legajo)
    // {
    //     parent::__construct($nombre, $apellido, $edad, $dni);
    //     $this->legajo = $legajo;
    // }

    public function CreateProperty($name, $value){
        $this->{$name} = $value;
    }

    public static function StdClassToAlumno($stdClass)
    {
        $alumno = new Alumno();
        $propertiesArray = get_object_vars($stdClass);                         
        foreach ($propertiesArray as $nombre => $valor) {
            if(isset($valor))
                $alumno->CreateProperty($nombre, $valor);
        }
        return $alumno;
    }

    public static function StdClassArrayToAlumnosArray($stdClassArray)
    {
        $alumnosArray = array(); 
        foreach($stdClassArray as $stdClass)
        {            
            $alumno = Alumno::StdClassToAlumno($stdClass);
            array_push($alumnosArray, $alumno);
        }
        return $alumnosArray;
    }

    public function GetAlumnoFromStdClass($stdClass)
    {
        $alumno = new Alumno();
        $propertiesArray = get_object_vars($stdClass);                         
        foreach ($propertiesArray as $nombre => $valor) {
            if($nombre == "id"){
                if (is_int($valor)) {
                    $alumno->$id = $valor;
                }
            } 
            if ($nombre == "nombre") {
                if (is_string($valor)) {
                    $alumno->$nombre = $valor;
                }
            } 
            if ($nombre == "apellido") {
                if (is_string($valor)) {
                    $alumno->$apellido = $valor;
                }
            } 
            if ($nombre == "edad") {
                if (is_int($valor)) {
                    $alumno->$edad = $valor;
                }
            } 
            if ($nombre == "dni") {
                if (is_int($valor)) {
                    $alumno->$dni = $valor;
                }
            } 
            if ($nombre == "legajo") {
                if (is_int($valor)) {
                    $alumno->$legajo = $valor;
                }
            }          
        }
        return $alumno;
    }    

    public function GetAlumnoCinco($nombre, $apellido, $edad, $dni, $legajo) {

        if (isset($nombre)) {
            $this->nombre = $nombre;
        } 
        if (isset($apellido)) {
            $this->apellido = $apellido;
        } 
        if (isset($edad)) {
            $this->edad = $edad;
        } 
        if (isset($dni)) {
            $this->dni = $dni;
        } 
        if (isset($legajo)) {
            $this->legajo = $legajo;
        }
        // if (is_string($nombre)) {
        //     $this->$nombre = $nombre;
        // } elseif (is_string($apellido)) {
        //     $this->apellido = $apellido;
        // } elseif (is_int($edad)) {
        //     $this->edad = $edad;
        // } elseif (is_int($dni)) {
        //     $this->dni = $dni;
        // } elseif (is_int($legajo)) {
        //     $this->legajo = $legajo;
        // }
    }

    public function GetAlumnoSeis($nombre, $apellido, $edad, $dni, $legajo, $foto) { 

        $this->GetAlumnoCinco($nombre, $apellido, $edad, $dni, $legajo);
        if (isset($foto)) {
            $this->foto = $foto;
        } 
    }

    public function GetAlumnoSeisId($id, $nombre, $apellido, $edad, $dni, $legajo) { 

        $this->GetAlumnoCinco($nombre, $apellido, $edad, $dni, $legajo);
        if (isset($id)) {
            $this->id = $id;
        } 
    }

    public function GetAlumnoSiete($id, $nombre, $apellido, $edad, $dni, $legajo, $foto) {
        
        $this->GetAlumnoSeis($nombre, $apellido, $edad, $dni, $legajo, $foto);
        if (isset($id)) {
            $this->id = $id;
        }
    }

    public static function GetObjectAlumno()
    {
        $alumno = new Alumno();       
        $numargs = func_num_args();
        // echo 'ctor alumn'.PHP_EOL;
        // var_dump($numargs);
        // var_dump(func_get_args());
        //$arg_list = func_get_args();
        switch($numargs)
        {
            case 1:                
                $arg = func_get_arg(0);
                if(is_object($arg))
                {
                    if(is_a($arg, 'stdClass'))
                        $alumno = Alumno::StdClassToAlumno($arg);
                }
                break;

            case 5: 
                $alumno->GetAlumnoCinco(func_get_arg(0),func_get_arg(1),func_get_arg(2),func_get_arg(3),func_get_arg(4));
                break;            
            case 6;                
                if((func_get_arg(0) == 'id'))
                {
                    $alumno->GetAlumnoSeisId(func_get_arg(0),func_get_arg(1),func_get_arg(2),func_get_arg(3),func_get_arg(4),func_get_arg(5));                    
                }
                else
                {
                    $alumno->GetAlumnoSeis(func_get_arg(0),func_get_arg(1),func_get_arg(2),func_get_arg(3),func_get_arg(4),func_get_arg(5));    
                    // $arrayParams = array("nombre", "apellido", "edad", "dni", "legajo", "foto");
                    // for ($i = 0; $i < $numargs; $i++) {
                    //     $alumno->CreateProperty($arrayParams[$i], func_get_arg($i));                   
                    // }   
                }                    
                break; 
            case 7:
                $alumno->GetAlumnoSiete(func_get_arg(0),func_get_arg(1),func_get_arg(2),func_get_arg(3),func_get_arg(4),func_get_arg(5),func_get_arg(6),func_get_arg(6));
                break;
            default:
                break;
        }        
        return $alumno;        
    }    

    //===================== ALTA =======================    

    public static function InsertArray($arrayAlumnos, $fileJsonAlumnos, $fileTxtAlumnos)
    {
        $arrayInsertedIds = Alumno::InsertAlumnosInDb($arrayAlumnos);                 
        //Se guarda copia en archivo .json        
        Alumno::InsertAlumnosInJsonFile($fileJsonAlumnos, $arrayAlumnos);  
        Alumno::InsertAlumnosInTxtFile($fileTxtAlumnos, $arrayAlumnos); 
        return $arrayInsertedIds; // retorna array con ids agregados a db 
    }

    public static function BackupArray($arrayAlumnos, $fileJsonAlumnos, $fileTxtAlumnos)
    {
        Alumno::InsertAlumnosInJsonFile($fileJsonAlumnos, $arrayAlumnos);
        Alumno::InsertAlumnosInTxtFile($fileTxtAlumnos, $arrayAlumnos); 
    }

    public static function InsertObject($alumno, $fileJsonAlumnos, $fileTxtAlumnos)
    {
        $lastInsertId = Alumno::InsertAlumnoInDb($alumno);               
        //Se guarda copia en archivo .json        
        Alumno::InsertAlumnoInJsonFile($fileJsonAlumnos, $alumno); 
        Alumno::InsertAlumnoInTxtFile($fileTxtAlumnos, $alumno);                              
        return $lastInsertId; // retorna el id del ultimo registro agregado a db  
    }

    public static function BackupObject($alumno, $fileJsonAlumnos, $fileTxtAlumnos)
    {
        Alumno::InsertAlumnoInJsonFile($fileJsonAlumnos, $alumno);
        Alumno::InsertAlumnoInTxtFile($fileTxtAlumnos, $alumno); 
    }

    //---------------------- DB

    public static function InsertAlumnoInDb($alumno)
    {
        $result = null;
        $alumnoExist = $alumno->GetAlumnoByLegajoOrDni($alumno->legajo, $alumno->dni);                         
        if(!$alumnoExist){
            $result = $alumno->InsertAlumno(); 
        }            
        return $result;
    }

    public static function InsertAlumnosInDb($arrayAlumnos)
    {        
        $arrayInsertedIds = array();
        foreach($arrayAlumnos as $alumno)
        {
            $alumnoExist = $alumno->GetAlumnoByLegajoOrDni($alumno->legajo, $alumno->dni);             
            if(!$alumnoExist){
                $lastInsertedId = $alumno->InsertAlumno();
                array_push($arrayInsertedIds, $lastInsertedId);  
            }
            else{
                continue;                           
            }  
        }
        return $arrayInsertedIds;
    }    

    //--------------------- JSON FILE
    
    public static function InsertAlumnoInJsonFile($fileJsonAlumnos, $alumno)
    {
        $result = null;
        $arrayJsonInFile = $fileJsonAlumnos->JsonFileToArray();
        $arrayAlumnosInFile = Alumno::StdClassArrayToAlumnosArray($arrayJsonInFile);
        if(!is_null($arrayJsonInFile))
        {       
               
            //$alumnoExist = $alumno->GetAlumnoByLegajoOrDniFromJsonFile($fileJsonAlumnos);
            $alumnoExist = $alumno->GetAlumnoByLegajoOrDniFromArray($arrayAlumnosInFile);  
            if($alumnoExist == null)
            {
                array_push($arrayAlumnosInFile, $alumno);
            }             
            $result = $fileJsonAlumnos->ArrayToJsonFile($arrayAlumnosInFile);
        }
        else
        {
            $result = $fileJsonAlumnos->ArrayToJsonFile($alumno);
        }        
        return $result;
    }

    public static function InsertAlumnosInJsonFile($fileJsonAlumnos, $arrayAlumnos)
    {
        $result;
        $arrayJsonInFile = $fileJsonAlumnos->JsonFileToArray();

        if(!is_null($arrayJsonInFile) && count($arrayJsonInFile) > 0)
        {
            $arrayAlumnosInFile = Alumno::StdClassArrayToAlumnosArray($arrayJsonInFile);             

            foreach($arrayAlumnos as $alumno)
            {                
                //$alumnoExist = $alumno->GetAlumnoByLegajoOrDniFromJsonFile($fileJsonAlumnos);
                $alumnoExist = $alumno->GetAlumnoByLegajoOrDniFromArray($arrayAlumnosInFile);
                if($alumnoExist == null)
                {
                    array_push($arrayAlumnosInFile, $alumno);
                }     
            }
            $result = $fileJsonAlumnos->ArrayToJsonFile($arrayAlumnosInFile);
        }
        else
        {
            $arrayAux = Alumno::FilterDuplicates($arrayAlumnos);            
            $result = $fileJsonAlumnos->ArrayToJsonFile($arrayAux);
        }        
        return $result;
    }

    //--------------------- TXT FILE

    public static function InsertAlumnoInTxtFile($fileTxtAlumnos, $alumno)
    {
        $result = null;
        $arrayTxtInFile = Alumno::TextToAlumnosArray($fileTxtAlumnos,';');
        
        if(!is_null($arrayTxtInFile) && count($arrayTxtInFile) > 0)
        {                     
            $alumnoExist = $alumno->GetAlumnoByLegajoOrDniFromArray($arrayTxtInFile);
            if($alumnoExist == null)
            {
                array_push($arrayTxtInFile, $alumno);
            }                 
            $result = Alumno::AlumnosArrayToTxtFile($fileTxtAlumnos, $arrayTxtInFile);
        }
        else
        {
            $result = Alumno::AlumnosArrayToTxtFile($fileTxtAlumnos, array($alumno));
        }        
        return $result;       
    }

    public static function InsertAlumnosInTxtFile($fileTxtAlumnos, $alumnosArray)
    {
        $result = null;
        $arrayTxtInFile = Alumno::TextToAlumnosArray($fileTxtAlumnos,';');
        
        if(!is_null($arrayTxtInFile) && count($arrayTxtInFile) > 0)
        {          
            foreach($alumnosArray as $alumno)
            {
                $alumnoExist = $alumno->GetAlumnoByLegajoOrDniFromArray($arrayTxtInFile);
                if($alumnoExist == null)
                {
                    array_push($arrayTxtInFile, $alumno);
                }     
            }
            $result = Alumno::AlumnosArrayToTxtFile($fileTxtAlumnos, $arrayTxtInFile);
        }
        else
        {
            $arrayAux = Alumno::FilterDuplicates($alumnosArray);   
            $result = Alumno::AlumnosArrayToTxtFile($fileTxtAlumnos, $arrayAux);
        }        
        return $result;
    }

    public function AlumnosArrayToTxtFile($fileTxtAlumnos, $array)
    {
        $string = 'Nombre;Apellido;Edad;Dni;Legajo'.PHP_EOL;
        $arrayInserts = array();
        foreach($array as $element)
        {
            $string .= $element->ToString();            
        }
        $result = $fileTxtAlumnos->WriteInTxtFile($string);
        return $result;
    } 

    //===================== GETERS ============================

    public static function FilterDuplicates($array)
    {
        $arrayAux = array();            
        for($i=0; $i < count($array); $i++)
        {
            if($i == 0)
            {
                array_push($arrayAux, $array[$i]);
            }
            else
            {
                $alumnoExist = $array[$i]->GetAlumnoByLegajoOrDniFromArray($arrayAux);
                if($alumnoExist == null)
                    array_push($arrayAux, $array[$i]);
            }                                    
        }
        return $arrayAux;
    }

    public function GetAlumnoByLegajoOrDniFromArray($array)
    {
        $returnAux = null;
        foreach($array as $alumno)
        {
            if($alumno->legajo == $this->legajo || $alumno->dni == $this->dni)
            {
                $returnAux = $alumno;
                break;
            }
        }
        return $returnAux;
    }

    //--------------------- TXT FILE 

    public static function TextToAlumnosArray($txtFile, $separador)
    {
        $arrayAlumnos = array();
        $arrayTxt = $txtFile->TextToArray();
        foreach($arrayTxt as $row)
        {
            $dataArray = explode($separador,$row);
            if(strtolower(trim($dataArray[0])) == 'nombre')
            {
                continue;
            }
            else
            {
                $nombreAux = trim($dataArray[0]);
                $apellidoAux = trim($dataArray[1]);
                $edadAux = trim($dataArray[2]);                
                $dniAux = trim($dataArray[3]);
                $legajoAux = trim($dataArray[4]);
                $alumno = Alumno::GetObjectAlumno($nombreAux, $apellidoAux, $edadAux, $dniAux, $legajoAux);
                array_push($arrayAlumnos, $alumno);
            }
        }        
        return $arrayAlumnos;
    }

    //--------------------- JSON LINES  
    public static function JsonLinesInRowToAlumnosArray($path)
    {
        $arrayAlumnos = array();  
        $array = Archivo::ReadByLinesToArray($path);
        foreach($array as $row)
        {
            $alumno = Alumno::GetObjectAlumno(json_decode($row));            
            array_push($arrayAlumnos, $alumno);                       
        }        
        return $arrayAlumnos;
    }

    public static function JsonLinesToAlumnosArray($txtFile)
    {                       
        $arrayAlumnos = array();        
        $arrayTxt = $txtFile->TextToArray();
        foreach($arrayTxt as $row)
        {
            $alumno = Alumno::GetObjectAlumno(json_decode($row));            
            array_push($arrayAlumnos, $alumno);                       
        }        
        return $arrayAlumnos;
    }
    //===================== METODOS DB ========================    

    public function InsertAlumno()
    {
        $ObjetoAlumnoDatos = AlumnoDatos::GetObjectAlumnoDatos(TABLA_ALUMNO);
        $query = "INSERT into Alumno(
                    Nombre,
                    Apellido,
                    Edad,
                    Dni,
                    Legajo)
                values(
                    :Nombre,
                    :Apellido,
                    :Edad,
                    :Dni,
                    :Legajo)";
        return $ObjetoAlumnoDatos->InsertAlumno($this, $query);
    }

    public static function GetAllAlumnos()
    {
        $ObjetoAlumnoDatos = AlumnoDatos::GetObjectAlumnoDatos(TABLA_ALUMNO);
        $query = 'SELECT
                    Id as id,
                    Nombre as nombre,
                    Apellido as apellido,
                    Edad as edad,
                    Dni as dni,
                    Legajo as legajo
                FROM
                    ' . TABLA_ALUMNO;
        return $ObjetoAlumnoDatos->GetAllAlumnos($query);
    }

    public static function GetAlumnoById($id)
    {
        $ObjetoAlumnoDatos = AlumnoDatos::GetObjectAlumnoDatos(TABLA_ALUMNO);
        $query = 'SELECT
                    Id as id,
                    Nombre as nombre,
                    Apellido as apellido,
                    Edad as edad,
                    Dni as dni,
                    Legajo as legajo
                FROM
                    ' . TABLA_ALUMNO . '
                WHERE
                    Id = ' . $id;
        return $ObjetoAlumnoDatos->GetAlumno($query);
    }

    public function GetAlumnoByLegajo() 
	{
        $ObjetoAlumnoDatos = AlumnoDatos::GetObjectAlumnoDatos(TABLA_ALUMNO);
        $query = 'SELECT
                    Id as id,
                    Nombre as nombre,
                    Apellido as apellido,
                    Edad as edad,
                    Dni as dni,
                    Legajo as legajo
                FROM
                    ' . TABLA_ALUMNO . '
                WHERE
                    Legajo = ' . $this->legajo;
        return $ObjetoAlumnoDatos->GetAlumno($query);
    }

    public function GetAlumnoByDni() 
	{
        $ObjetoAlumnoDatos = AlumnoDatos::GetObjectAlumnoDatos(TABLA_ALUMNO);
        $query = 'SELECT
                    Id as id,
                    Nombre as nombre,
                    Apellido as apellido,
                    Edad as edad,
                    Dni as dni,
                    Legajo as legajo
                FROM
                    ' . TABLA_ALUMNO . '
                WHERE
                    Dni = ' . $this->dni;
        return $ObjetoAlumnoDatos->GetAlumno($query);
    }

    public function GetAlumnoByLegajoOrDni() 
	{
        $ObjetoAlumnoDatos = AlumnoDatos::GetObjectAlumnoDatos(TABLA_ALUMNO);
        $query = 'SELECT
                    Id as id,
                    Nombre as nombre,
                    Apellido as apellido,
                    Edad as edad,
                    Dni as dni,
                    Legajo as legajo
                FROM
                    ' . TABLA_ALUMNO . '
                WHERE
                    Legajo = ' . $this->legajo .
                ' OR 
                    Dni = ' . $this->dni;
        return $ObjetoAlumnoDatos->GetAlumno($query);
    }    

    public function UpdateAlumno()
    {
        $ObjetoAlumnoDatos = AlumnoDatos::GetObjectAlumnoDatos(TABLA_ALUMNO);
        $query = "UPDATE "
                    . TABLA_ALUMNO .
                " SET
                    Nombre=:Nombre,
                    Apellido=:Apellido,
                    Edad=:Edad,
                    Dni=:Dni,
                    Legajo=:Legajo
                WHERE
                    Id=:Id";
        return $ObjetoAlumnoDatos->UpdateAlumno($this, $query);
    }

    public function DeleteAlumno()
    {
        $ObjetoAlumnoDatos = AlumnoDatos::GetObjectAlumnoDatos(TABLA_ALUMNO);
        $query = "DELETE from "
                    . TABLA_ALUMNO .
                " WHERE
                    Id=:Id";
        return $ObjetoAlumnoDatos->DeleteAlumno($this, $query);
    }

    //===================== METODOS PUBLICOS =======================    

    public function ShowAlumno()
    {
        $format = sprintf("%-18s;%-18s;%-9s;%-9s;%-9s;" . PHP_EOL,
            $this->nombre,$this->apellido,$this->edad,$this->dni,$this->legajo);
        return $format;
    }

    public function ShowAlumnoWithHeader()
    {        
        $format = sprintf("%-18s;%-18s;%-9s;%-9s;%-9s;" . PHP_EOL,
            "Nombre","Apellido","Edad","DNI","Legajo");
        echo $format;
        echo $this->ShowAlumno();
    }    

    //===================== GET ========================    

    // public static function GetAlumnoByLegajoFromText($legajo, $path)
    // {
    //     $returnAux = null;
    //     $array = Alumno::TextToArray($path);

    //     foreach($array as $alumno)
    //     {
    //         if($alumno->legajo == $legajo)
    //         {
    //             $returnAux = $alumno;
    //             break;
    //         }
    //     }        
    //     return json_encode($alumno);
    // }

    // public static function GetAlumnoByLegajoFromJsonLines($legajo, $path)
    // {
    //     $returnAux = null;
    //     $array = Alumno::JsonLinesToAlumnosArray($path);

    //     foreach($array as $alumno)
    //     {
    //         if($alumno->legajo == $legajo)
    //         {
    //             $returnAux = $alumno;
    //             break;
    //         }
    //     }
    //     return json_encode($alumno);
    // }

    //===================== POST ========================

    //para txt
    private function ToString()
    {
        return "{$this->nombre};{$this->apellido};{$this->edad};{$this->dni};{$this->legajo};" . PHP_EOL;
    }    

    //===================== PUT ========================

    public static function UpdateText($path, $alumno)
    {
        $mensaje = "Datos incorrectos" . PHP_EOL;
        $existe = false;
        $array = Alumno::TextToArray($path);
        //var_dump($array);
        foreach($array as $item)
        {
            //var_dump($item->nombre);
            if($item->legajo == $alumno->legajo)
            {
                $item->nombre = $alumno->nombre;
                $item->apellido = $alumno->apellido;
                $item->edad = $alumno->edad;
                $item->dni = $alumno->dni;
                $existe = true;
                break;
            }
        }
        if($existe)
        {
            var_dump($array);
            $file = fopen($path, "wt");
            // foreach($array as $alumno)
            // {
            //     var_dump($alumno);
            // }
            // fwrite($file, json_encode($array));
            // $mensaje = "Datos modificados" . PHP_EOL;
            // fclose($file);
        }
        return $mensaje;
    }

    public static function UpdateJson($path, $alumno)
    {
        $mensaje = "Datos incorrectos" . PHP_EOL;
        $existe = false;
        $array = Alumno::JsonFileToAlumnosArray($path);

        foreach($array as $key => $value)
        {
            if($value['legajo'] == $alumno->legajo)
            {
                $array[$key]['nombre'] = $alumno->nombre;
                $array[$key]['apellido'] = $alumno->apellido;
                $array[$key]['edad'] = $alumno->edad;
                $array[$key]['dni'] = $alumno->dni;
                $existe = true;
                break;
            }
        }
        if($existe)
        {
            $file = fopen($path, "wt");
            fwrite($file, json_encode($array));
            $mensaje = "Datos modificados" . PHP_EOL;
            fclose($file);
        }
        return $mensaje;
    }

     //===================== GETERS ============================

    //--------------------- JSON FILE

    // public function GetAlumnoByLegajoOrDniFromJsonFile($jsonFile)
    // {
    //     $returnAux = null;
    //     $arrayJsonInFile = $jsonFile->JsonFileToArray();
    //     $arrayAlumnosInFile = Alumno::StdClassArrayToAlumnosArray($arrayJsonInFile);
    //     foreach($arrayAlumnosInFile as $alumno)
    //     {
    //         if($alumno->legajo == $this->legajo || $alumno->dni == $this->dni)
    //         {
    //             $returnAux = $alumno;
    //             break;
    //         }
    //     }
    //     return $returnAux;
    // }

    // public function GetAlumnoByLegajoFromJsonFile($jsonFile)
    // {
    //     $returnAux = null;
    //     $arrayJsonInFile = $jsonFile->JsonFileToArray();
    //     $arrayAlumnosInFile = Alumno::StdClassArrayToAlumnosArray($arrayJsonInFile);

    //     foreach($arrayAlumnosInFile as $alumno)
    //     {
    //         if($alumno->legajo == $this->legajo)
    //         {
    //             $returnAux = $alumno;
    //             break;
    //         }
    //     }
    //     return $returnAux;
    // }

    // public function GetAlumnoByDniFromJsonFile($jsonFile)
    // {
    //     $returnAux = null;
    //     $arrayJsonInFile = $jsonFile->JsonFileToArray();
    //     $arrayAlumnosInFile = Alumno::StdClassArrayToAlumnosArray($arrayJsonInFile);

    //     foreach($arrayAlumnosInFile as $alumno)
    //     {
    //         if($alumno->dni == $this->dni)
    //         {
    //             $returnAux = $alumno;
    //             break;
    //         }
    //     }
    //     return $returnAux;
    // }
    //===================================================

    // public static function JsonToString($path)
    // {
    //     $datosLeidos = null;
    //     if(file_exists($path)){
    //         $myfile = fopen($path, "r");
    //         $datosLeidos = fread($myfile,filesize($path));
    //         fclose($myfile);
    //     }
    //     return $datosLeidos;
    // }

    // public static function JsonLinesToAlumnosArray($path)
    // {
    //     $arrayAlumnos = array();
    //     if(file_exists($path))
    //     {
    //         $myfile = fopen($path, "r");
    //         while(!feof($myfile))
    //         {
    //             $datos = fgets($myfile);
    //             if($datos == null)
    //                 break;

    //             $alumnoJson = json_decode($datos);
    //             $alumno = Alumno::StdClassToAlumno($alumnoJson);
    //             array_push($arrayAlumnos, $alumno);
    //         }
    //         fclose($myfile);
    //     }
    //     return $arrayAlumnos;
    // }
}
?>


