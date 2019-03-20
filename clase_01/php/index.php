<?php    
    require 'alumno.php';

    $nombre = "Gaston";
    var_dump($nombre);

    // $myArray = array("Nombre"=>$nombre,"Edad"=>30);
    $myArray = array();    
    $myArray['nombre'] = "Gaston";
    $myArray['edad'] = 30;    
    var_dump($myArray);
    // echo $myArray['nombre']; 

    $myObj = new stdClass();
    $myObj->nombre = "Gaston";
    $myObj->edad = 30;
    var_dump($myObj);
    
    echo "<h1>Hola $nombre $myObj->nombre</h1></br>";
    
    $myAlumno = new Alumno("Gaston", 30);
    var_dump($myAlumno);
    var_dump($myAlumno->returnJSON());
?>   