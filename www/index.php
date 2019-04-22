<?php
require_once 'settings.php';
require_once CLASES.'/Alumno.php';
require_once CLASES.'/Archivo.php';

$method = $_SERVER['REQUEST_METHOD'];
$datos = file_get_contents("php://input");
$json = json_decode($datos);
$fileJsonAlumnos = new Archivo(ARCHIVOS . "/ListadoAlumno.json");
$fileTxtAlumnos = new Archivo(ARCHIVOS . "/ListadoAlumno.txt");

echo $method . PHP_EOL;
switch($method)
{
    case "POST":
        require_once FUNCIONES.'/CrearAlumno.php';
        break;       

    case "GET":
        require_once FUNCIONES.'/ListarAlumno.php';
        break;

    case "PUT":
        require_once FUNCIONES.'/ModificarAlumno.php';
        break;

    case "DELETE":
        require_once FUNCIONES.'/BorrarAlumno.php';
        break;
}         
?>   