<?php
require_once CLASES . '/alumno.php';

$datosPUT = fopen("php://input", "r");
$datos = fread($datosPUT, 1024);
$alumno = json_decode($datos);

//echo Alumno::modificar_json(ARCHIVOS . "/ListadoAlumno.json", $alumno);
echo Alumno::modificar_txt(ARCHIVOS . "/ListadoAlumno.txt", $alumno);
//var_dump(Alumno::modificar_json(ARCHIVOS . "/ListadoAlumno.json", $alumno));

// (parse_str(file_get_contents('php://input'), $_PUT));
// var_dump($_PUT); //$_PUT contains put fields 
// var_dump($_PUT['1']);
?>