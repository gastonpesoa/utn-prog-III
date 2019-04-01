<?php
require_once 'settings.php';

$dato = $_SERVER['REQUEST_METHOD'];
echo $dato .PHP_EOL;

if($dato == "POST")    
    require_once FUNCIONES.'/CrearAlumno.php';

if($dato == "GET")    
    require_once FUNCIONES.'/ListarAlumno.php';

if($dato == "DELETE")    
    require_once FUNCIONES.'/BorrarAlumno.php';    

if($dato == "PUSH")    
    require_once FUNCIONES.'/ModificarAlumno.php';        
    
?>   