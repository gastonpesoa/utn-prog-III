<?php
require_once CLASES . "/Alumno.php";

//=================================== TXT ==========================================

// var_dump(Alumno::read(ARCHIVOS . "/ListadoAlumno.txt"));
//echo Alumno::ShowTextList(ARCHIVOS . "/ListadoAlumno.txt");

//=================================== JSON ==========================================

//var_dump(Alumno::JsonFileToAlumnosArray(ARCHIVOS . "/ListadoAlumno.json"));
//var_dump(Alumno::json_a_string(ARCHIVOS . "/ListadoAlumno.json"));
//var_dump(Alumno::json_a_string(ARCHIVOS . "/ListadoAlumnoLinea.json"));
var_dump(Alumno::JsonLinesToAlumnosArray(ARCHIVOS . "/ListadoAlumnoLinea.json"));

//================================= BY LEGAJO ==========================================
//var_dump(Alumno::GetAllAlumnos()); 
//var_dump(Alumno::GetAlumnoById(1)); 
// $legajo = $_GET['legajo'];
// if(!empty($_GET['legajo']))
// {
//     echo "leo del txt" . PHP_EOL;
//     var_dump(Alumno::GetAlumnoByLegajoFromText($legajo, ARCHIVOS . "/ListadoAlumno.txt"));
    
//     echo "leo del json" . PHP_EOL;
//     var_dump(Alumno::GetAlumnoByLegajoFromJson($legajo, ARCHIVOS . "/ListadoAlumno.json"));
    
//     echo "leo del json linea" . PHP_EOL;
//     var_dump(Alumno::GetAlumnoByLegajoFromJsonLines($legajo, ARCHIVOS . "/ListadoAlumnoLinea.json"));
// }
?>