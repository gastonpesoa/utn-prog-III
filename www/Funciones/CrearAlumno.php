<?php
require_once CLASES.'/Alumno.php';
require_once CLASES.'/Archivo.php';

$fileJsonAlumnos = new Archivo(ARCHIVOS . "/ListadoAlumno.json");
$fileTxtAlumnos = new Archivo(ARCHIVOS . "/ListadoAlumno.txt");

$datos = file_get_contents("php://input");

//Caso: POST from BODY ROW
if($_POST == null && $datos != null && $_SERVER["CONTENT_TYPE"] == "application/json")
{
    $json = json_decode($datos);
    //Si el contenido no es array u objeto, son lineas de JSON
    if((!is_array($json) && !is_object($json)) && $datos != null)
    {
        $arrayAlumnos = Alumno::JsonLinesInRowToAlumnosArray("php://input");     
        $arrayInsertedIds = array();
        try
        { 
            //Se insertan los registros no repetidos en DB, JSON y TXT  
            $arrayInsertedIds = Alumno::InsertArray($arrayAlumnos, $fileJsonAlumnos, $fileTxtAlumnos);
            var_dump(count($arrayInsertedIds));               
        }
        catch(Exception $e)
        {
            //En caso de error con db igual se guarda en JSON y TXT
            Alumno::BackupArray($arrayAlumnos, $fileJsonAlumnos, $fileTxtAlumnos);
            print "Error en crear!: " . $e->getMessage(); 
            die();   
        }
    } 
    else
    {
        //Si el contenido es un array de JSON
        $json = json_decode($datos);  
        if(is_array($json))
        {
            $arrayAlumnos = Alumno::StdClassArrayToAlumnosArray($json);           
            $arrayInsertedIds = array();
            try
            {
                //Se insertan los registros no repetidos en DB, JSON y TXT 
                $arrayInsertedIds = Alumno::InsertArray($arrayAlumnos, $fileJsonAlumnos, $fileTxtAlumnos);
                var_dump(count($arrayInsertedIds));     
            }
            catch(Exception $e)
            {
                //En caso de error con db igual se guarda en JSON y TXT
                Alumno::BackupArray($arrayAlumnos, $fileJsonAlumnos, $fileTxtAlumnos);
                print "Error en crear!: " . $e->getMessage(); 
                die();   
            }
        }
        else
        {
            //Si el contenido es un objeto JSON
            if(is_object($json))
            {
                $alumno = Alumno::GetObjectAlumno($json);
                try
                {
                    //De no estar repetido se inserta en DB, JSON y TXT 
                    $lastInsertId = Alumno::InsertObject($alumno, $fileJsonAlumnos, $fileTxtAlumnos);
                    var_dump($lastInsertId);
                }
                catch(Exception $e)
                {
                    //En caso de error con db igual se guarda en JSON y TXT
                    Alumno::BackupObject($alumno, $fileJsonAlumnos, $fileTxtAlumnos); 
                    print "Error en crear!: " . $e->getMessage(); 
                    die();   
                }        
            }

        } 
    }            
}
else
{
    //Caso: POST by PARAMS
    if(isset($_POST['nombre'])){

        $nombre = $_POST['nombre'];   
        if(isset($_POST['apellido'])) {
            $apellido = $_POST['apellido'];       
        } 
        if(isset($_POST['edad'])) {
            $edad = $_POST['edad'];       
        } 
        if(isset($_POST['dni'])) {
            $dni = $_POST['dni'];        
        } 
        if(isset($_POST['legajo'])) {
            $legajo = $_POST['legajo'];      
        } 
        if(isset($_POST['id'])) {
            $id = $_POST['id'];       
        } 
        //Si viene con imagen
        if(!empty($_FILES['imagen'])){  
            $nombreArchivo = "{$legajo}_{$apellido}"; 
            $fotosFile = new Archivo(FOTOS);
            try
            {
                $fotosFile->SaveFile($_FILES, $nombreArchivo, FOTOS_BACKUP, URL_ESTAMPA);
            }
            catch(Exception $e)
            {
                print "Error en save file!: " . $e->getMessage(); 
                die();   
            }                 
        }                   
    }
    else
    {
        //Caso: POST by FILE CSV
        if(!empty($_FILES['csv']) && $_FILES['csv']["type"] == "text/csv")
        {            
            $csvFile = new Archivo($_FILES['csv']['tmp_name']);
            $arrayAlumnos = Alumno::TextToAlumnosArray($csvFile, ';');
            $arrayInsertedIds = array();
            try
            {
                //Se insertan los registros no repetidos en DB, JSON y TXT    
                $arrayInsertedIds = Alumno::InsertArray($arrayAlumnos, $fileJsonAlumnos, $fileTxtAlumnos);
                var_dump(count($arrayInsertedIds));               
            }
            catch(Exception $e)
            {
                //En caso de error con db igual se guarda en JSON y TXT
                Alumno::BackupArray($arrayAlumnos, $fileJsonAlumnos, $fileTxtAlumnos);
                print "Error en crear!: " . $e->getMessage(); 
                die();   
            }                        
        }
        //Caso: POST by FILE JSON        
        if(!empty($_FILES['json']) && $_FILES['json']["type"]  == "application/json")
        {    
            echo 'file json'.PHP_EOL;                             
            //Si el contenido no es array u objeto, son lineas de JSON
            $fileContent = file_get_contents($_FILES['json']['tmp_name']);             
            $json = json_decode(file_get_contents($_FILES['json']['tmp_name']));                       
            if((!is_array($json) && !is_object($json)) && $fileContent != null)
            {
                echo 'lineas json'.PHP_EOL; 
                $jsonFile = new Archivo($_FILES['json']['tmp_name']);  
                $arrayAlumnos = Alumno::JsonLinesToAlumnosArray($jsonFile);
                $arrayInsertedIds = array();
                try
                {   
                    //Se insertan los registros no repetidos en DB, JSON y TXT 
                    $arrayInsertedIds = Alumno::InsertArray($arrayAlumnos, $fileJsonAlumnos, $fileTxtAlumnos);
                    var_dump(count($arrayInsertedIds));               
                }
                catch(Exception $e)
                {
                    //En caso de error con db igual se guarda en JSON y TXT
                    Alumno::BackupArray($arrayAlumnos, $fileJsonAlumnos, $fileTxtAlumnos);
                    print "Error en crear!: " . $e->getMessage(); 
                    die();   
                }
            }
            else
            {
                //Si el contenido es un array de JSON                                                                
                if(is_array($json))
                {
                    echo "array file json".PHP_EOL;
                    $arrayAlumnos = Alumno::StdClassArrayToAlumnosArray($json);           
                    $arrayInsertedIds = array();
                    try
                    {
                        //Se insertan los registros no repetidos en DB, JSON y TXT 
                        $arrayInsertedIds = Alumno::InsertArray($arrayAlumnos, $fileJsonAlumnos, $fileTxtAlumnos);
                        var_dump(count($arrayInsertedIds));     
                    }
                    catch(Exception $e)
                    {
                        //En caso de error con db igual se guarda en JSON y TXT
                        Alumno::BackupArray($arrayAlumnos, $fileJsonAlumnos, $fileTxtAlumnos);
                        print "Error en crear!: " . $e->getMessage(); 
                        die();   
                    }
                }
                else
                {
                    //Si el contenido es un objeto JSON
                    if(is_object($json))
                    {
                        echo "object file json".PHP_EOL;
                        $alumno = Alumno::GetObjectAlumno($json);                        
                        try
                        {
                            //De no estar repetido se inserta en DB, JSON y TXT 
                            $lastInsertId = Alumno::InsertObject($alumno, $fileJsonAlumnos, $fileTxtAlumnos);
                            var_dump($lastInsertId);
                        }
                        catch(Exception $e)
                        {
                            //En caso de error con db igual se guarda en JSON y TXT
                            Alumno::BackupObject($alumno, $fileJsonAlumnos, $fileTxtAlumnos); 
                            print "Error en crear!: " . $e->getMessage(); 
                            die();   
                        }        
                    }                
                } 
            }
                 
        } 
    } 
    //$alumno = Alumno::GetObjectAlumno();
}


// $alumnoJSON = json_decode($datos);
// $miAlumno = Alumno::StdClassToAlumno($alumnoJSON);
// var_dump($miAlumno->InsertAlumno());
//echo Alumno::objeto_json_a_objeto_alumno($alumnoJSON);


//==========================================================================================

// $nombre = $_POST['nombre'];
// $apellido = $_POST['apellido'];
// $edad = $_POST['edad'];
// $dni = $_POST['dni'];
// $legajo = $_POST['legajo'];

// $alumno = Alumno::GetObjectAlumno($nombre, $apellido, $edad, $dni, $legajo);
//$myAlumno = new Alumno($nombre, $apellido, $edad, $dni, $legajo);

//=================================== TXT ==========================================

//echo $myAlumno->SaveText(ARCHIVOS . "/ListadoAlumno.txt");

//=================================== JSON ==========================================

//echo $myAlumno->SaveJsonLinea(ARCHIVOS . "/ListadoAlumnoLinea.json");
//echo $myAlumno->InsertAlumnoInJsonArrayFile(ARCHIVOS . "/ListadoAlumno.json");

//============================ MANEJO DE ARCHIVOS ===================================

// var_dump($_FILES);
// if(!empty($_FILES['imagen']))
// {    
//     $nombreArchivo = "{$myAlumno->legajo}_{$myAlumno->apellido}"; 
//     $myAlumno->SaveFile($_FILES, FOTOS, $nombreArchivo, FOTOS_BACKUP, URL_ESTAMPA);    
// } 


//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> Ignorar
// if (!empty($_FILES['imagen'])) 
//     if(is_uploaded_file($_FILES['imagen']['tmp_name']) || file_exists($_FILES['imagen']['tmp_name']))
//         echo $myAlumno->SaveFile($_FILES);  
//$arrayAlumnos = array($myAlumno);
//
//var_dump($myAlumno->ObjectToJson());
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
?>