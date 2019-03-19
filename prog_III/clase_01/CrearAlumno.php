<?php
    require 'alumno.php';

    echo "GET: ";
    var_dump($_GET);
    
    echo "POST: ";
    var_dump($_POST);

    $nombre = $_POST['nombre'];
    $edad = $_POST['edad'];

    $myAlumno = new Alumno($nombre, $edad);
    // var_dump($myAlumno);
    var_dump($myAlumno->returnJSON());
?>