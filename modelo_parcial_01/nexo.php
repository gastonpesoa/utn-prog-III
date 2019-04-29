<?php
require_once 'settings.php';
require_once CLASES.'/Archivo.php';
require_once CLASES.'/Proveedor.php';
require_once CLASES.'/Pedido.php';

$fileTxtProveedores = new Archivo(ARCHIVOS . "/proveedores.txt");
$fileTxtPedidos = new Archivo(ARCHIVOS . "/pedidos.txt");
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
                case 'cargarProveedor':
                    $result = 'Son requeridos el nombre, email y foto del proveedor.'.PHP_EOL;
                    if(isset($_POST['nombre']) && isset($_POST['email']) && !empty($_FILES['foto']))
                    {
                        $nombre = strtolower($_POST['nombre']); 
                        $email = $_POST['email'];                                               
                        try
                        {
                            $result = Proveedor::InsertProveedor($fileTxtProveedores, $nombre, $email, $fileFotos, $_FILES);
                        }
                        catch(Exception $e)
                        {                    
                            print "Error al cargar proveedor: " . $e->getMessage(); 
                            die();   
                        }            
                    }                                 
                break;

                case 'modificarProveedor':
                    $result = 'Son requeridos el id, nombre, email y foto del proveedor.'.PHP_EOL;
                    if(isset($_POST['id']) && isset($_POST['nombre']) && isset($_POST['email']) && !empty($_FILES['foto'])) 
                    {
                        $id = $_POST['id'];                      
                        $nombre = $_POST['nombre'];                                                     
                        $email = $_POST['email'];
                        try
                        {
                            $result = Proveedor::UpdateProveedor($fileTxtProveedores, $id, $nombre, $email, $fileFotos, $fileFotosBackup, $_FILES);
                        }
                        catch(Exception $e)
                        {                    
                            print "Error al modificar proveedor: " . $e->getMessage(); 
                            die();   
                        }
                    } 
                break;

                case 'hacerPedido':
                    $result = 'Son requeridos el id del proveedor, nombre de producto y cantidad.'.PHP_EOL;
                    if(isset($_POST['producto']) && isset($_POST['cantidad']) && isset($_POST['idProveedor'])) 
                    {
                        $producto = $_POST['producto'];       
                        $cantidad = $_POST['cantidad'];   
                        $idProveedor = $_POST['idProveedor']; 
                        try
                        {
                            $result = Pedido::InsertPedido($fileTxtPedidos, $fileTxtProveedores, $idProveedor, $producto, $cantidad);
                        }
                        catch(Exception $ex)
                        {
                            print "Error al cargar pedido: " . $e->getMessage(); 
                            die(); 
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
                case 'consultarProveedor':
                    $result = 'Es requerido el nombre del proveedor.'.PHP_EOL;
                    if(isset($_GET["nombre"]))
                    {                        
                        $nombre = $_GET["nombre"];
                        $result = "No existe proveedor ".$nombre.PHP_EOL;
                        $proveedor = Proveedor::GetProveedorByNombreFromTxt($nombre, $fileTxtProveedores);
                        if(!is_null($proveedor))
                            $result = $proveedor->ShowProveedorWithHeader();
                    }
                break;

                case 'proveedores':
                    $result = Proveedor::ShowProveedores($fileTxtProveedores);                                       
                break;

                case 'listarPedidos':                    
                    $result = Pedido::ShowPedidos($fileTxtPedidos, $fileTxtProveedores);
                break;

                case 'listarPedidoProveedor': 
                    $result = 'Es requerido el id del proveedor.'.PHP_EOL;                   
                    if(isset($_GET['idProveedor']))
                    {
                        $idProveedor = $_GET['idProveedor'];
                        $result = "No existe proveedor con id ".$idProveedor.PHP_EOL;                        
                        $proveedor = Proveedor::GetProveedorByIdFromTxt($idProveedor, $fileTxtProveedores);
                        if(!is_null($proveedor))
                            $result = Pedido::ShowPedidosProveedor($fileTxtPedidos, $proveedor);
                    }
                break;

                case "fotosBack":
                    $result = Proveedor::ShowFotosBakup($fileTxtProveedores, $fileFotosBackup);                    
                break;
            }      
        }//if(isset($_GET['caso']))   
    break; //case "GET":
}//switch($method)  
echo $result;       
?>   