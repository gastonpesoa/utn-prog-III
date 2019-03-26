<?php

$dato = $_SERVER['REQUEST_METHOD'];
echo $dato;

if($dato == "POST")    
    require_once 'Funciones/CrearAlumno.php';

if($dato == "GET")    
    require_once 'Funciones/ListarAlumno.php';

if($dato == "DELETE")    
    require_once 'Funciones/BorrarAlumno.php';    

if($dato == "PUSH")    
    require_once 'Funciones/ModificarAlumno.php';        
    
?>   