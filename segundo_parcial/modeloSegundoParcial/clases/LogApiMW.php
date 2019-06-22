<?php

namespace Clases;
use App\Models\Log;
use \DateTime;

class LogApiMW
{           
    public function RegisterLog($request, $response, $next)
    {                
        $ahora = date("H:i:s");
        $path = $request->getUri()->getPath();
        $data = $request->getParsedBody();
        $routeObj = $request->getAttribute('route');
           
        if(!empty($routeObj))
        {  
            $methods = $routeObj->getMethods();     
            try
            {
                if(($path == 'usuario/' && $methods[0] == 'POST') || $path == 'usuario/login')
                {            
                    $user = $data['nombre'];
                } 
                else 
                {
                    $headers = $request->getHeaders();
                    $token = $headers["HTTP_TOKEN"][0];
                    $dataToken = Token::GetData($token); 
                    $user = $dataToken->nombre;
                }  
    
                $log = new Log();
                $log->usuario = $user;
                $log->metodo = $methods[0];
                $log->ruta = $path;
                $log->hora = $ahora;
                $log->save();
                return $next($request, $response);             
            }   
            catch(\Exception $ex)
            {
                $respuesta = array("Estado" => "ERROR", "Mensaje" => "Token invalido.", "Excepcion" => $ex->getMessage());
            }                                     
        }     
        else{
            $respuesta = array("Estado" => "ERROR", "Mensaje" => "Ruta invalida.");
        }  
        return $response->withJson($respuesta, 401);           
    }       
}

?>