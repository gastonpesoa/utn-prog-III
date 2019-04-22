<?php

if($datos != null && $_SERVER["CONTENT_TYPE"] == "application/json"){
    echo 'eo'.PHP_EOL;
    //Si el contenido no es array u objeto, son lineas de JSON
    if((!is_array($json) && !is_object($json)) && $datos != null)
    {

    }
    else
    {
        if(is_array($json))
        {

        }
        else
        {
            //Si el contenido es un objeto JSON
            if(is_object($json))
            {

            }
        }

    }
}
else
{
    //Caso: POST by PARAMS
    if(isset($_POST['id']))
    {

    }
}
// $datosPUT = fopen("php://input", "r");
// $datos = fread($datosPUT, 1024);

// $alumnoJSON = json_decode($datos);
// $miAlumno = Alumno::StdClassToAlumno($alumnoJSON);
// var_dump($miAlumno);
// var_dump($miAlumno->UpdateAlumno());

//echo Alumno::UpdateJson(ARCHIVOS . "/ListadoAlumno.json", $alumno);
//echo Alumno::modificar_txt(ARCHIVOS . "/ListadoAlumno.txt", $alumno);
//var_dump(Alumno::UpdateJson(ARCHIVOS . "/ListadoAlumno.json", $alumno));

?>