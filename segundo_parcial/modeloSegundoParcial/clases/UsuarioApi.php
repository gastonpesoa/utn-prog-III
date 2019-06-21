<?php

namespace Clases;
use App\Models\Usuario;

class UsuarioApi
{ 
    protected $logger;
    
    public function __construct(\Monolog\Logger $logger) 
    {
        $this->logger = $logger;
    }

    public function Welcome($request, $response, $args)
    {
        $result = $response->withStatus(200)->getBody()->write("Hola Mundo!!");
    }

    public static function GetUserById($userId)
    {        
        $userORM = new Usuario();    
        return $userORM->find($userId);        
    }

    public static function GetIdUserByName($name)
    {                
        $userORM = new Usuario();    
        $user = $userORM->where('nombre', "=", $name)->first();            
        return $user->id;
    }  

    public static function GetUserByName($nombre)
    {
        $userORM = new Usuario();       
        return $userORM->where('nombre', "=", $nombre)->first();                
    }

    public function VerifyPerfil($tipo)
    {
        $result = "user";
        if(isset($tipo))
        {
            if(strcasecmp($tipo, "user")==0 || strcasecmp($tipo, "admin")==0)
            {
                $result = $tipo;                            
            }
        }                        
        return $result;
    }

    public function VerifySexo($sexo)
    {
        $result = "";
        if(isset($sexo))
        {
            if(strcasecmp($sexo, "femenino")==0 || strcasecmp($sexo, "masculino")==0)
            {
                $result = $sexo;                 
            }
        }                        
        return $result;
    }   

    public function RegisterUser($request, $response)
    {        
        $this->logger->addInfo('New user'); 
        $data = $request->getParsedBody();
        $status = 400;
        $respuesta = array("Estado" => "ERROR", "Mensaje" => "Son requeridos nombre, clave y sexo del usuario.");                            
        if(isset($data['nombre']) && isset($data['clave']) && isset($data['sexo']))
        {            
            $nombre = filter_var(trim($data['nombre']), FILTER_SANITIZE_STRING);
            $pass = filter_var(trim($data['clave']), FILTER_SANITIZE_STRING);     
            $sexoFilter = filter_var(trim($data['sexo']), FILTER_SANITIZE_STRING);     
            $sexo = $this->VerifySexo($sexoFilter);
            $tipo = filter_var(trim($data['perfil']), FILTER_SANITIZE_STRING);               
            $respuesta = array("Estado" => "ERROR", "Mensaje" => "Sexo debe ser 'femenino' o 'masculino'.");                            
            if($sexo != "")
            {
                $respuesta = array("Estado" => "ERROR", "Mensaje" => "Nombre o clave inocrrectos.");
                if($nombre && $pass)
                {
                    $respuesta = array("Estado" => "ERROR", "Mensaje" => "Ya existe usuario registrado con nombre ".$nombre);                            
                    if(UsuarioApi::GetUserByName($nombre) == null)
                    {                    
                        $user = new Usuario();                            
                        $user->nombre = $nombre;
                        $user->clave = password_hash($pass, PASSWORD_DEFAULT);
                        $user->sexo = $sexo;
                        $user->perfil = $this->VerifyPerfil($tipo);
                        $user->save();
                        $respuesta = array("Estado" => "OK", "Mensaje" => "Usuario, ".$nombre." registrado correctamente.");
                        $status = 200;
                    }
                }
            }                   
        }        
        return $response->withJson($respuesta, $status);        
    }    

    public function LoginUser($request, $response, $args)
    {        
        $data = $request->getParsedBody();
        $status = 400;
        $respuesta = array("Estado" => "ERROR", "Mensaje" => "Son requeridos nombre, clave y sexo del usuario.");                            
        if(isset($data['nombre']) && isset($data['clave']) && isset($data['sexo']))
        {            
            $nombre = filter_var(trim($data['nombre']), FILTER_SANITIZE_STRING);
            $pass = filter_var(trim($data['clave']), FILTER_SANITIZE_STRING);     
            $sexoFilter = filter_var(trim($data['sexo']), FILTER_SANITIZE_STRING);     
            $sexo = $this->VerifySexo($sexoFilter);               
            $respuesta = array("Estado" => "ERROR", "Mensaje" => "Sexo debe ser 'femenino' o 'masculino'.");    
            if($sexo != "")
            {
                $respuesta = array("Estado" => "ERROR", "Mensaje" => "Nombre o clave inocrrectos.");
                if($nombre && $pass)
                {
                    $respuesta = array("Estado" => "ERROR", "Mensaje" => "No existe usuario registrado con nombre ".$nombre);
                    $usuario = UsuarioApi::GetUserByName($nombre);
                    if($usuario)
                    {  
                        $respuesta = array("Estado" => "ERROR", "Mensaje" => "Clave incorrecta.");
                        if (password_verify(trim($pass), $usuario->clave)) 
                        {
                            $respuesta = array("Estado" => "ERROR", "Mensaje" => "Sexo incorrecto.");
                            if(strcasecmp($usuario->sexo, $sexo) == 0)
                            {
                                $token = Token::CreateToken($data);
                                $this->logger->addInfo('User login'.$data);             
                                $respuesta = array("Estado" => "OK", "Mensaje" => "Logueado exitosamente.", "Token" => $token, "Nombre" => $nombre);                               
                                $status = 200;
                            }
                        }
                    }
                }
            }
        }
        return $response->withJson($respuesta, $status); 
    }

    public function GetAll($request, $response, $args)
    {    
        $this->logger->addInfo('User list'); 
        $userORM = new Usuario();        
        $params = $request->getParsedBody();   

        if (!isset($params['sort']) && !isset($params['order']))
            $users = UsuarioApi::ShowUsuariosArray($userORM::all());
        else 
        {
            if (isset($params['sort']) && isset($params['order']))
            {
                if (strcasecmp($params['sort'],'nombre') == 0)
                {
                    if (strcasecmp($params['order'],'desc') == 0)
                        $users = UsuarioApi::ShowUsuariosArray($userORM::orderBy('nombre', 'desc')->get());
                    else 
                        if (strcasecmp($params['order'],'asc') == 0)
                            $users = UsuarioApi::ShowUsuariosArray($userORM::orderBy('nombre', 'asc')->get());                    
                }
            }    
        }  
        return $response->withJson($users, 200);
    }

    public function GetById($request, $response, $args)
    {
        $this->logger->addInfo('User by id'); 
        $userId = (int)$args['id'];
        $userORM = new Usuario();    
        $user = $userORM->find($userId);
        return $response->withStatus(200)->getBody()->write($user->toJson());    
    }   
    
    public static function ShowUsuariosArray($array)
    {  
        $result = array();     
        if(!is_null($array) && count($array) > 0)
        {
            foreach($array as $user)
            {  
                $element = array(
                    "nombre:" => $user->nombre,
                    "sexo:" => $user->sexo,
                    "perfil:" => $user->perfil
                );  
                array_push($result, $element);                            
            }
        }
        return $result;
    }
}

?>