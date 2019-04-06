<?php
require_once CLASES.'/alumno.php';

$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$edad = $_POST['edad'];
$dni = $_POST['dni'];
$legajo = $_POST['legajo'];
if(!empty($_FILES['imagen']))
{
    $nombreArchivo = "{$legajo}_{$apellido}"; 
    $foto = Alumno::guardar_archivo($_FILES, FOTOS, $nombreArchivo, FOTOS_BACKUP, URL_ESTAMPA);  
    $myAlumno = Alumno::con_foto($nombre, $apellido, $edad, $dni, $legajo, $foto);
}
else
{   
    $myAlumno = new Alumno($nombre, $apellido, $edad, $dni, $legajo);
}
echo $myAlumno->guardar_txt(ARCHIVOS . "/ListadoAlumno.txt");
 
// $myAlumno = new Alumno($nombre, $apellido, $edad, $dni, $legajo);
// echo $myAlumno->guardar_txt(ARCHIVOS . "/ListadoAlumno.txt");
//echo $myAlumno->guardar_json(ARCHIVOS . "/ListadoAlumno.json");

//var_dump($_FILES);
// if(!empty($_FILES['imagen'])) 
//     $nombreArchivo = "{$myAlumno->legajo}_{$myAlumno->apellido}"; 
//     echo $myAlumno->guardar_archivo($_FILES, FOTOS, $nombreArchivo, FOTOS_BACKUP, URL_ESTAMPA);  

// if (!empty($_FILES['imagen'])) 
//     if(is_uploaded_file($_FILES['imagen']['tmp_name']) || file_exists($_FILES['imagen']['tmp_name']))
//         echo $myAlumno->guardar_archivo($_FILES);  

//$arrayAlumnos = array($myAlumno);
//
//var_dump($myAlumno->objeto_a_json());
?>