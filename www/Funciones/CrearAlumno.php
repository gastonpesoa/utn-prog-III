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
        // if(isset($_POST['id'])) {
        //     $id = $_POST['id'];       
        // } 
        //Con FILE
        if(!empty($_FILES['imagen'])){  
            $nombreArchivo = "{$legajo}_{$apellido}"; 
            $fotosFile = new Archivo(FOTOS);
            try
            {
                //Se guarda imagen en directorio Fotos y se hace Backup de ser necesario
                $foto = $fotosFile->SaveFileDirectory($_FILES, $nombreArchivo, FOTOS_BACKUP, URL_ESTAMPA);
                //$image_base64 = $fotosFile->SaveFileInBase64($foto);

                $alumno = Alumno::GetObjectAlumno($nombre, $apellido, $edad, $dni, $legajo, $foto);
                
                //De no estar repetido se inserta en DB, JSON y TXT 
                $lastInsertId = Alumno::InsertObject($alumno, $fileJsonAlumnos, $fileTxtAlumnos);
                var_dump($lastInsertId);                              
            }
            catch(Exception $e)
            {
                //En caso de error con db igual se guarda en JSON y TXT
                Alumno::BackupObject($alumno, $fileJsonAlumnos, $fileTxtAlumnos); 
                print "Error en save file!: " . $e->getMessage(); 
                die();   
            }                 
        } 
        else
        {
            //Sin FILE
            try
            {
                $alumno = Alumno::GetObjectAlumno($nombre, $apellido, $edad, $dni, $legajo);
                
                //De no estar repetido se inserta en DB, JSON y TXT 
                $lastInsertId = Alumno::InsertObject($alumno, $fileJsonAlumnos, $fileTxtAlumnos);
                var_dump($lastInsertId);                              
            }
            catch(Exception $e)
            {
                //En caso de error con db igual se guarda en JSON y TXT
                Alumno::BackupObject($alumno, $fileJsonAlumnos, $fileTxtAlumnos); 
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
            //Si el contenido no es array u objeto, son lineas de JSON
            $fileContent = file_get_contents($_FILES['json']['tmp_name']);             
            $json = json_decode(file_get_contents($_FILES['json']['tmp_name']));                       
            if((!is_array($json) && !is_object($json)) && $fileContent != null)
            {
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
    } 
}
?>