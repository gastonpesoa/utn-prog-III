<?php
require_once CLASES . '/Alumno.php';

$datosPUT = fopen("php://input", "r");
$datos = fread($datosPUT, 1024);

$alumnoJSON = json_decode($datos);
$miAlumno = Alumno::StdClassToAlumno($alumnoJSON);
var_dump($miAlumno->DeleteAlumno());

//echo Alumno::modificar_json(ARCHIVOS . "/ListadoAlumno.json", $alumno);
//echo Alumno::UpdateText(ARCHIVOS . "/ListadoAlumno.txt", $alumno);
//var_dump(Alumno::modificar_json(ARCHIVOS . "/ListadoAlumno.json", $alumno));

?>