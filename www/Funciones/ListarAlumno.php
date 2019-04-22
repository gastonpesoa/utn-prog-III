<?php
require_once CLASES . "/Alumno.php";
//require_once CLASES.'/Archivo.php';

//Caso: GET sin PARAMS - Devuelve todos los alumnos
if($_GET == null)
{
    echo Alumno::GetJsonArrayAlumnosInDbSortedByKey('legajo', 1);
    // echo Alumno::GetJsonArrayAlumnosInDb();
    echo PHP_EOL;
    echo Alumno::ShowAlumnosListSortedByKey('legajo', -1);
}
else
{   
    //Caso: GET by PARAMS - Devuelve el alumno con el parametro seteado
    if(isset($_GET["id"]))
    {
        $id = $_GET["id"];
        echo Alumno::GetJsonAlumnoInDbById($id);
    }
    else
    {
        if(isset($_GET["legajo"]))
        {
            $legajo = $_GET["legajo"];
            echo Alumno::GetJsonAlumnoInDbByLegajo($legajo);
        }
        else
        {
            if(isset($_GET["dni"]))
            {
                $dni = $_GET["dni"];
                echo Alumno::GetJsonAlumnoInDbByDni($dni);
            }
        }        
    }    
}
?>