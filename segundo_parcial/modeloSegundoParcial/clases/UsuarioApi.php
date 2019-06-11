<?php

class UsuarioApi
{ 
    protected $logger;
    
    public function __construct(\Monolog\Logger $logger) {
        $this->logger = $logger;
    }

    public function LoginUser($request, $response, $args)
    {
        $params = $request->getParsedBody();
        $nombre = $params["nombre"];
        $clave = $params["clave"];
        $sexo = $params["sexo"];

        $userORM = new \App\Models\Usuario();        
        $respuesta = $userORM->where('nombre', "=", $nombre)->first();      
        
        if($respuesta){
            if (password_verify(trim($clave), $respuesta->clave)) 
            {
                if(strcasecmp($respuesta["sexo"],$sexo)==0){
                    if ($respuesta["perfil"] != "") 
                    {
                        $token = Token::CreateToken($respuestaPass);
                        $this->logger->addInfo('User login'.$respuestaPass);             
                        $respuesta = array("Estado" => "OK", "Mensaje" => "Logueado exitosamente.", "Token" => $token, "Nombre_Empleado" => $retorno["nombre_empleado"]);            
                    } else {
                        $respuesta = array("Estado" => "ERROR", "Mensaje" => "Perfil invalido.");
                    }
                }else{

                    $respuesta = array("Estado" => "ERROR", "Mensaje" => "Sexo invalido.");
                }
            } else {
                $respuesta = array("Estado" => "ERROR", "Mensaje" => "Clave invalida.");
            }            
        }else{
            $respuesta = array("Estado" => "ERROR", "Mensaje" => "Usuario invalido.");
        }
        

        $newResponse = $response->withJson($respuesta, 200);
        return $newResponse;
    }

    public function GetAll($request, $response, $args)
    {     
        $this->logger->addInfo('User list'); 
        $userORM = new \App\Models\Usuario();        
        $params = $request->getQueryParams();   
        $badRequest = true;     

        if (!isset($params['sort']) && !isset($params['order']))
        {
            $users = $userORM::all();        
            $result = $response->withStatus(200)->getBody()->write($users->toJson());  
            $badRequest = false;  
        } 
        else 
        {
            if (isset($params['sort']) && isset($params['order']))
            {
                if (strcasecmp($params['sort'],'nombre') == 0)
                {
                    if (strcasecmp($params['order'],'desc') == 0)
                    {
                        $users = $userORM::orderBy('nombre', 'desc')->get();
                        $result = $response->withStatus(200)->getBody()->write($users->toJson());  
                        $badRequest = false;  
                    } 
                    else 
                    {
                        if (strcasecmp($params['order'],'asc') == 0)
                        {
                            $users = $userORM::orderBy('nombre', 'asc')->get();
                            $result = $response->withStatus(200)->getBody()->write($users->toJson());    
                            $badRequest = false;
                        }                     
                    }
                }
            }    
        }  

        if($badRequest)
            $result = $response->withStatus(400)->getBody()->write('Bad Request');

        return $result;
    }

    public function GetById($request, $response, $args)
    {
        $this->logger->addInfo('User by id'); 
        $userId = (int)$args['id'];
        $userORM = new \App\Models\Usuario();    
        $user = $userORM->find($userId);
        return $response->withStatus(200)->getBody()->write($user->toJson());    
    }

    public function RegisterUser($request, $response)
    {
        $this->logger->addInfo('New user'); 
        $data = $request->getParsedBody();
        $badRequest = true;   

        if(isset($data['nombre']) && isset($data['clave']) && isset($data['sexo']) && isset($data['perfil']))
        {
            $nombre = filter_var(trim($data['nombre']), FILTER_SANITIZE_STRING);
            $pass = filter_var(trim($data['clave']), FILTER_SANITIZE_STRING);     
            $sexo = filter_var(trim($data['sexo']), FILTER_SANITIZE_STRING);     
            $tipo = filter_var(trim($data['perfil']), FILTER_SANITIZE_STRING);     
            
            if($nombre && $pass && $tipo && $sexo)
            {
                $user = new \App\Models\Usuario();        
                $user->nombre = $nombre;
                $user->clave = password_hash($pass, PASSWORD_DEFAULT);
                $user->sexo = $sexo;
                $user->perfil = $tipo;
                $user->save();
                $result = $response->withStatus(200)->getBody()->write($user->toJson());   
                $badRequest = false; 
            }               
        }

        if($badRequest)
            $result = $response->withStatus(400)->getBody()->write('Bad Request');

        return $result;
    }
}

?>