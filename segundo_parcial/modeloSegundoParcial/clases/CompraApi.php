<?php

namespace Clases;
use App\Models\Compra;
use \DateTime;

class CompraApi
{ 
    protected $logger;
    
    public function __construct(\Monolog\Logger $logger) 
    {
        $this->logger = $logger;
    }

    public static function GetFormatedDate($external, $format = "Y/m/d")
    {
        // $dateObj = DateTime::createFromFormat($format, $external);
        $fecha = DateTime::createFromFormat($format, $external, new \DateTimeZone("America/Argentina/Buenos_Aires"));        
        return $fecha->format($format);
    }
   
    public function RegisterCompra($request, $response)
    {        
        $this->logger->addInfo('New compra'); 
        $data = $request->getParsedBody();
        $status = 400;
        $respuesta = array("Estado" => "ERROR", "Mensaje" => "Son requeridos articulo, fecha y precio de lo compra.");                            
        if(isset($data['articulo']) && isset($data['fecha']) && isset($data['precio']))
        {            
            $articulo = filter_var(trim($data['articulo']), FILTER_SANITIZE_STRING);               
            $fecha = CompraApi::GetFormatedDate($data['fecha']);            
            $precio = filter_var(trim($data['precio']), FILTER_VALIDATE_FLOAT);                                                                   
            $respuesta = array("Estado" => "ERROR", "Mensaje" => "Articulo o precio inocrrectos.");
            if($articulo && $precio)
            {
                $data = Token::GetData($data['token']);                      
                $compra = new Compra();                            
                $compra->idUsuario = UsuarioApi::GetIdUserByName($data->nombre);
                $compra->articulo = $articulo;
                $compra->fecha = $fecha;
                $compra->precio = $precio;                
                $compra->save();
                $respuesta = array("Estado" => "OK", "Mensaje" => "Compra de, ".$articulo." registrada correctamente.");
                $status = 200;                
            }                           
        }        
        return $response->withJson($respuesta, $status);        
    }   

    public function GetAll($request, $response, $args)
    {    
        $this->logger->addInfo('Compras list'); 
        $compraORM = new Compra();        
        $params = $request->getParsedBody();   

        $data = Token::GetData($data['token']);  
        $user = UsuarioApi::GetUserByName($data->nombre);  

        if (strcasecmp($user->perfil, 'admin') == 0)
            $compras = CompraApi::ShowComprasArray($compraORM::all());
        else 
        {
            if (isset($params['sort']) && isset($params['order']))
            {
                if (strcasecmp($params['sort'],'articulo') == 0)
                {
                    if (strcasecmp($params['order'],'desc') == 0)
                        $compras = CompraApi::ShowComprasArray($compraORM::orderBy('articulo', 'desc')->get());
                    else 
                        if (strcasecmp($params['order'],'asc') == 0)
                            $compras = CompraApi::ShowComprasArray($compraORM::orderBy('articulo', 'asc')->get());                    
                }
            }    
        }  
        return $response->withJson($compras, 200);
    }

    public static function ShowComprasArray($array)
    {  
        $result = array();     
        if(!is_null($array) && count($array) > 0)
        {
            foreach($array as $compra)
            {  
                $user = GetUserById($compra->idUsuario);
                $element = array(
                    "articulo:" => $compra->articulo,
                    "fecha:" => $compra->fecha,
                    "precio:" => $compra->precio,
                    "usuario:" => $user->nombre
                );  
                array_push($result, $element);                            
            }
        }
        return $result;
    }
}

?>