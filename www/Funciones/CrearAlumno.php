<?php
require_once CLASES.'/alumno.php';

$nombre = $_POST['nombre'];
$edad = $_POST['edad'];
$dni = $_POST['dni'];
$legajo = $_POST['legajo'];

$myAlumno = new Alumno($nombre, $edad, $dni, $legajo);
$myAlumno->guardar(ARCHIVOS."/ListadoAlumno.txt");

if(isset($_FILES))
{
    echo $myAlumno->guardar_archivo($_FILES);        
}

//$arrayAlumnos = array($myAlumno);
//$myAlumno->guardar_json(ARCHIVOS."/ListadoAlumno.json");
//var_dump($myAlumno->objeto_a_json());
?>