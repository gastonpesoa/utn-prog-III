<?php
require_once 'settings.php';

$dato = $_SERVER['REQUEST_METHOD'];
echo $dato . PHP_EOL;

switch($dato)
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