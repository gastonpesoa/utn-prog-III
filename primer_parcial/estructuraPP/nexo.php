<?php

require_once 'settings.php';
require_once CLASES.'/Archivo.php';
require_once CLASES.'/ObjectA.php';
require_once CLASES.'/ObjectB.php';
require_once CLASES.'/ObjectC.php';

$fileTxtObjectA = new Archivo(ARCHIVOS . "/ObjectA.txt");
$fileTxtObjectB = new Archivo(ARCHIVOS . "/ObjectB.txt");
$fileTxtObjectC = new Archivo(ARCHIVOS . "/ObjectC.txt");
$fileFotos = new Archivo(FOTOS);

$result = "Es requerido el parámetro caso.".PHP_EOL;
$method = $_SERVER['REQUEST_METHOD'];
//$json = json_decode(file_get_contents("php://input"));

switch($method)
{
    case "POST":
        if(isset($_POST['caso'])) 
        {
            switch($_POST['caso'])
            {
                case 'cargarObjectA':                               
                    $result = ObjectA::InsertObjectA($fileTxtObjectA, $_POST, $_FILES);                 
                break;

                case 'cargarObjectB':
                    $result = ObjectB::InsertObjectB($fileTxtObjectB, $_POST);
                break;

                case 'modificarObjectA':   
                    $result = ObjectA::UpdateObjectA($fileTxtObjectA, $fileFotos, FOTOS_BACKUP, $_POST, $_FILES);       
                break;
            }
        }        
    break;    
                       
    case "GET":
        if(isset($_GET['caso']))
        {
            switch($_GET['caso'])
            {
                case 'consultarObjectA':
                    $result = ObjectA::ConsultObjectA($_GET, $fileTxtObjectA);                    
                break;                
                
                case 'consultarObjectB':                       
                break;
                
                case 'cargarObjectC':
                    $result = ObjectC::InsertObjectC($fileTxtObjectC, $fileTxtObjectA, $fileTxtObjectB, $_GET);
                break;
                
                case 'obectAs':                       
                    $result = ObjectA::ShowObjectAsWithFotos($fileTxtObjectA);
                break;
                
                case 'obectCs':                       
                    $result = ObjectC::ShowObjectCs($fileTxtObjectC);
                break;
                
                case 'consultarObjectCs':
                    $result = ObjectC::ConsultObjectC($_GET, $fileTxtObjectC);                    
                break;                
            }        
        }
    break;
        
    default:
    break;
}
echo $result;
?>