<?php
namespace Clases;

use \stdClass;
use \Exception;

class VerificaPerfilMW
{
    public function __construct() { }

    public function VerificarPerfil($request, $response, $next){
    
        $status = 401;
        $params = $request->getParsedBody();        
        try
        {
            Token::VerifyToken($params['token']);
            $data = Token::GetData($params['token']);
            $user = UsuarioApi::GetUserByName($data->nombre);            
            if(strcasecmp($user->perfil, "admin") == 0){
                return $next($request, $response);                                
            } else {
                $respuesta = array("Estado" => "Ok", "Mensaje" => "hola.");
            }
        }
        catch(Exception $ex) {
            // var_dump($ex);
            // die();
            $respuesta = array("Estado" => "ERROR", "Mensaje" => "Token invalido.", "Excepcion" => $ex->getMessage());            
        }
        return $response->withJson($respuesta, $status);             
    }
}