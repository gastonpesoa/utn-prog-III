<?php
require_once CLASES . "/alumno.php";

//=================================== TXT ==========================================

// var_dump(Alumno::read(ARCHIVOS . "/ListadoAlumno.txt"));
//echo Alumno::mostrar_listado_txt(ARCHIVOS . "/ListadoAlumno.txt");

//=================================== JSON ==========================================

//var_dump(Alumno::json_a_array(ARCHIVOS . "/ListadoAlumno.json"));
//var_dump(Alumno::json_a_string(ARCHIVOS . "/ListadoAlumno.json"));
//var_dump(Alumno::json_a_string(ARCHIVOS . "/ListadoAlumnoLinea.json"));
//var_dump(Alumno::json_linea_a_array(ARCHIVOS . "/ListadoAlumnoLinea.json"));

//================================= BY LEGAJO ==========================================

$legajo = $_GET['legajo'];
if(!empty($_GET['legajo']))
{
    echo "leo del txt" . PHP_EOL;
    var_dump(Alumno::obtener_por_legajo_de_txt($legajo, ARCHIVOS . "/ListadoAlumno.txt"));
    
    echo "leo del json" . PHP_EOL;
    var_dump(Alumno::obtener_por_legajo_de_json($legajo, ARCHIVOS . "/ListadoAlumno.json"));
    
    echo "leo del json linea" . PHP_EOL;
    var_dump(Alumno::obtener_por_legajo_de_json_linea($legajo, ARCHIVOS . "/ListadoAlumnoLinea.json"));
}
?>