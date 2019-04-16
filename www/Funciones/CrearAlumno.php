<?php
require_once CLASES.'/alumno.php';

$datosPOST = fopen("php://input", "r");
$datos = fread($datosPOST, 1024);
$alumnoJSON = json_decode($datos);

//echo Alumno::dame_un_objeto_alumno($alumnoJSON);
echo Alumno::guardar($alumnoJSON);

//==========================================================================================

// $nombre = $_POST['nombre'];
// $apellido = $_POST['apellido'];
// $edad = $_POST['edad'];
// $dni = $_POST['dni'];
// $legajo = $_POST['legajo'];

// $myAlumno = new Alumno($nombre, $apellido, $edad, $dni, $legajo);

//=================================== TXT ==========================================

//echo $myAlumno->guardar_txt(ARCHIVOS . "/ListadoAlumno.txt");

//=================================== JSON ==========================================

//echo $myAlumno->guardar_json_linea(ARCHIVOS . "/ListadoAlumnoLinea.json");
//echo $myAlumno->guardar_json(ARCHIVOS . "/ListadoAlumno.json");

//============================ MANEJO DE ARCHIVOS ===================================

//var_dump($_FILES);
// if(!empty($_FILES['imagen']))
//     $myAlumno->con_foto($_FILES, FOTOS, FOTOS_BACKUP, URL_ESTAMPA);  


//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> Ignorar
// if (!empty($_FILES['imagen'])) 
//     if(is_uploaded_file($_FILES['imagen']['tmp_name']) || file_exists($_FILES['imagen']['tmp_name']))
//         echo $myAlumno->guardar_archivo($_FILES);  
//$arrayAlumnos = array($myAlumno);
//
//var_dump($myAlumno->objeto_a_json());
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
?>