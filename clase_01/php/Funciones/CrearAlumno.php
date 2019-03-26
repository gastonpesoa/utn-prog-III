<?php
    require 'alumno.php';

    // echo "GET: ";
    // var_dump($_GET);
    
    // echo "POST: ";
    // var_dump($_POST);

    $nombre = $_POST['nombre'];
    $edad = $_POST['edad'];
    $dni = $_POST['dni'];
    $legajo = $_POST['legajo'];

    $myAlumno = new Alumno($nombre, $edad, $dni, $legajo);
    $myAlumno->Guardar("../Archivos/ListadoAlumno.txt");
    // $myAlumno->GuardarJSON("../Archivos/ListadoAlumno.json");
    // var_dump($myAlumno);
    var_dump($myAlumno->returnJSON());
?>