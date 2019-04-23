<?php
require_once 'settings.php';
require_once CLASES.'/Archivo.php';
require_once CLASES.'/Proveedor.php';
require_once CLASES.'/Pedido.php';

$method = $_SERVER['REQUEST_METHOD'];
$datos = file_get_contents("php://input");
$json = json_decode($datos);
$fileTxtProveedores = new Archivo(ARCHIVOS . "/proveedores.txt");
$fileTxtPedido = new Archivo(ARCHIVOS . "/pedidos.txt");

echo $method . PHP_EOL;
switch($method)
{
    case "POST":
        //Caso: POST by PARAMS
        if(isset($_POST['caso']))
        {
            $caso = $_POST['caso'];
            
            if($caso == 'cargarProveedor'){
                
                if(isset($_POST['id'])) {
                    $id = $_POST['id'];       
                }
                if(isset($_POST['nombre'])){
                    $nombre = $_POST['nombre'];   
                }            
                if(isset($_POST['email'])) {
                    $email = $_POST['email'];       
                }              
                
                //Con FILE
                if(!empty($_FILES['foto']))
                {  
                    $nombreArchivo = "{$id}_{$nombre}"; 
                    $fotosFile = new Archivo(FOTOS);
                    try
                    {
                        //Se guarda imagen en directorio Fotos y se hace Backup de ser necesario
                        $foto = $fotosFile->SaveFileDirectory($_FILES, $nombreArchivo, FOTOS_BACKUP, URL_ESTAMPA);
                        //$image_base64 = $fotosFile->SaveFileInBase64($foto);
                        
                        $proveedor = Proveedor::GetProveedor($id, $nombre, $email, $foto);
                        
                        //De no estar repetido se inserta en DB, JSON y TXT 
                        $lastInsertId = Proveedor::InsertProveedorInTxtFile($fileTxtProveedores, $proveedor);  
                        var_dump($lastInsertId);                              
                    }
                    catch(Exception $e)
                    {                    
                        print "Error en save file!: " . $e->getMessage(); 
                        die();   
                    }                 
                }    
            }            
            if($caso == 'hacerPedido')
            {
                if(isset($_POST['producto'])) {
                    $producto = $_POST['producto'];       
                }
                if(isset($_POST['cantidad'])){
                    $cantidad = $_POST['cantidad'];   
                }            
                if(isset($_POST['id'])) {
                    $id = $_POST['id'];       
                }              
                                    
                try
                {                        
                    $pedido = Pedido::GetPedido($id, $producto, $cantidad);
                    
                    //De no estar repetido se inserta en DB, JSON y TXT 
                    $lastInsertId = Pedido::InsertPedidoInTxtFile($fileTxtPedido, $pedido, $fileTxtProveedores);  
                    var_dump($lastInsertId);                              
                }
                catch(Exception $e)
                {                    
                    print "Error en save file!: " . $e->getMessage(); 
                    die();   
                }                                      
            } 
            if($caso == 'modificarProveedor'){
                if(isset($_POST['id'])) {
                    $id = $_POST['id'];
                    if(isset($_POST['nombre'])){
                        $nombre = $_POST['nombre'];   
                    }            
                    if(isset($_POST['email'])) {
                        $email = $_POST['email'];       
                    }        
                    $proveedor = Proveedor::GetProveedorByIdFromTxt($id, $fileTxtProveedores);
                    if($proveedor!=null){
                        echo Proveedor::UpdateProveedor($fileTxtProveedores, $id, $nombre, $email, $proveedor);                    
                    }                    
                }                 
            }                                                         
        }

        break;       

    case "GET":
        if(isset($_GET['caso']))
        {
            $caso = $_GET['caso'];

            if($caso == 'consultarProveedor')
            {     
                if(isset($_GET["nombre"]))
                {
                    $nombre = $_GET["nombre"];
                    $result = Proveedor::GetProveedorByNombreFromTxt($nombre, $fileTxtProveedores);
                    if($result!=null){
                        $result->ShowProveedorWithHeader();
                    } else {
                        echo "No existe proveedor ".$nombre.PHP_EOL;
                    }
                }
            }            
            if($caso == 'proveedores')
            {
                echo Proveedor::ShowTextList($fileTxtProveedores);
            }
            if($caso == 'listarPedidos')
            {
                echo Pedido::ShowTextList($fileTxtPedido, $fileTxtProveedores);
            }
            if($caso == 'listarPedidoProveedor')
            {
                if(isset($_GET['id']))
                {
                    $id = $_GET['id'];
                    echo Pedido::ShowPedidoProveedor($fileTxtPedido, $id);
                }                
            }                        
        }    
        break;
}         
?>   