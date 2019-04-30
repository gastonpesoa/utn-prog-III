<?php
require_once 'settings.php';
require_once CLASES.'/Archivo.php';
require_once CLASES.'/Vehiculo.php';
require_once CLASES.'/Servicio.php';
require_once CLASES.'/Turno.php';

$fileTxtVehiculos = new Archivo(ARCHIVOS . "/vehiculos.txt");
$fileTxtServicios = new Archivo(ARCHIVOS . "/tiposServicio.txt");
$fileTxtTurnos = new Archivo(ARCHIVOS . "/turnos.txt");
$fileFotos = new Archivo(FOTOS);
$fileFotosBackup = new Archivo(FOTOS_BACKUP);
$method = $_SERVER['REQUEST_METHOD'];
$result = "Es requerido el parámetro caso.".PHP_EOL;

switch($method)
{
    case "POST":        
        if(isset($_POST['caso']))
        {
            $caso = $_POST['caso'];            
            switch($caso)
            {
                case 'cargarVehiculo':
                    $result = 'Son requeridos el nombre, email y foto del vehiculo.'.PHP_EOL;
                    if(isset($_POST['marca']) && isset($_POST['modelo']) && isset($_POST['patente']) && isset($_POST['precio']))
                    {
                        $marca = strtolower($_POST['marca']); 
                        $modelo = strtolower($_POST['modelo']); 
                        $patente = strtolower($_POST['patente']);  
                        $precio = strtolower($_POST['precio']);                                               
                        try
                        {
                            $result = Vehiculo::InsertVehiculo($fileTxtVehiculos, $marca, $modelo, $patente, $precio);
                        }
                        catch(Exception $e)
                        {                    
                            print "Error al cargar vehiculo: " . $e->getMessage(); 
                            die();   
                        }            
                    }                                 
                break;
              
                case 'cargarTipoServicio':                
                    $result = 'Son requeridos el id del vehiculo, nombre de tipo y demora.'.PHP_EOL;
                    if(isset($_POST['id']) && isset($_POST['nombre']) && isset($_POST['tipo']) && isset($_POST['demora']) && isset($_POST['precio'])) 
                    {   
                        $result = "El tipo debe ser 10.000km, 20.000km, 50.000km".PHP_EOL;                        
                        $tipo = $_POST['tipo'];
                        if($tipo == "10000" || $tipo == "20000" || $tipo == "30000")
                        {
                            $id = $_POST['id'];
                            $nombre = $_POST['nombre'];                               
                            $demora = $_POST['demora'];   
                            $precio = $_POST['precio']; 
                            try
                            {
                                $result = Servicio::InsertServicio($fileTxtServicios, $id, $nombre, $tipo, $precio, $demora);
                            }
                            catch(Exception $ex)
                            {
                                print "Error al cargar servicio: " . $e->getMessage(); 
                                die(); 
                            }
                        }                                                                                            
                    }
                break;                        
            }//switch($caso)                                                     
        }//if(isset($_POST['caso']))        
    break; //case "POST":      

    case "GET":
        if(isset($_GET['caso']))
        {
            $caso = $_GET['caso'];
            switch($caso)
            {
                case 'consultarVehiculo':
                    $result = 'Es requerido al menos un dato del vehiculo.'.PHP_EOL;
                    if(isset($_GET["dato"]))
                    {   
                        $dato = strtolower($_GET["dato"]);
                        $result = "No existe vehiculo ".$dato.PHP_EOL;
                        $vehiculos = Vehiculo::GetVehiculosByDatoFromTxt($dato, $fileTxtVehiculos);                        
                                                
                        if(!is_null($vehiculos)){
                            
                            $result = Vehiculo::ShowVehiculosArray($vehiculos); 
                        }                    
                    }
                break;

                case "sacarTurno":
                    $result = 'Son requeridos fecha, patente y tipo.'.PHP_EOL;
                    if(isset($_GET['fecha']) && isset($_GET['patente'])  && isset($_GET['tipo'])) 
                    {   
                        $fecha = $_GET['fecha'];
                        $patente = $_GET['patente'];
                        $tipo = $_GET['tipo'];   

                        $result = "No existe vehiculo con patente".$patente.PHP_EOL;
                        $vehiculo = Vehiculo::GetVehiculoByPatenteFromTxt($patente, $fileTxtVehiculos);
                        $servicio = Servicio::GetServicioByTipoFromTxt($tipo, $fileTxtServicios);
                        if(!is_null($vehiculo) && !is_null($servicio)){                                            
                            try
                            {                                
                                $result = Turno::InsertTurno($fileTxtTurnos, $fileTxtVehiculos, $fileTxtServicios, $fecha, $vehiculo, $servicio);
                            }
                            catch(Exception $ex)
                            {
                                print "Error al cargar servicio: " . $e->getMessage(); 
                                die(); 
                            }                             
                        }                                                                                                                                                          
                    }
                break;
            }      
        }//if(isset($_GET['caso']))   
    break; //case "GET":
}//switch($method)  
echo $result;       
?>   