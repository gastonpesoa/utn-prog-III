<?php
require_once CLASES."/alumno.php";

// var_dump(Alumno::read(ARCHIVOS . "/ListadoAlumno.txt"));
//var_dump(Alumno::leer_json_array(ARCHIVOS . "/ListadoAlumno.json"));
echo Alumno::mostrar_alumnos(ARCHIVOS . "/ListadoAlumno.txt");
?>