<?php
namespace Clases;

use \stdClass;
use \Exception;

class VerificaPerfilMW
{
    public function __construct() { }

    public function VerificarPerfil($request, $response, $next){
    
        $status = 401;
        // $params = $request->getParsedBody();     
        $headers = $request->getHeaders();
        $token = $headers["HTTP_TOKEN"][0];   
        try
        {
            Token::VerifyToken($token);
            $data = Token::GetData($token);
            $user = UsuarioApi::GetUserByName($data->nombre);            
            if(strcasecmp($user->perfil, "admin") == 0)
            {
                return $next($request, $response);                                
            } 
            else 
            {
                $respuesta = array("Estado" => "Ok", "Mensaje" => "hola.");
            }
        }
        catch(Exception $ex) 
        {
            // var_dump($ex);
            // die();
            $respuesta = array("Estado" => "ERROR", "Mensaje" => "Token invalido.", "Excepcion" => $ex->getMessage());            
        }
        return $response->withJson($respuesta, $status);             
    }
}