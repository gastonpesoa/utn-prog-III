<?php

class VerificaPerfilMW
{
    public function VerificarPerfil( $request,  $response, $next){
    
        $obj = new stdclass();
        $obj->respuesta = "";

        $params = $request->getQueryParams(''); 
                
        try{
            Token::VerifyToken($params['token']);
            $obj->esValido=true;
        }
        catch(Exception $ex) {
            $obj->exception = $ex->getMessage();
            $obj->esValido=false;            
        }
        if($obj->esValido){        
            if(strcasecmp($params['perfil'], "admin") == 0){
                $response = $next($request, $response);
            } else {
                $obj->respuesta = "hola";
            } 
        }else{
            $obj->respuesta = "Token invalido";
        }
        
        if($obj->respuesta!=""){
            $nueva = $response->withJson($obj, 401);
            return $nueva;
        }
        return $response;           
    }
}