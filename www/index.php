<?php
require_once 'settings.php';

$method = $_SERVER['REQUEST_METHOD'];
echo $method . PHP_EOL;
$datos = file_get_contents("php://input");

switch($method){
    
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