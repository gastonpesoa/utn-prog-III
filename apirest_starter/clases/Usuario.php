<?php
class Usuario
{
	public $id;
 	public $nombre;
  	public $password;


/* inicio  especiales para slimFramework*/

 	public function TraerUno($request, $response, $args) {
     	$id=$args['id'];
    	$elUsuario=Usuario::TraerUnUsuario($id);
     	$newResponse = $response->withJson($elUsuario, 200);  
    	return $newResponse;
    }
     public function TraerTodos($request, $response, $args) {
      	$todosLosUsuarios=Usuario::TraerTodoLosUsuarios();
     	$newResponse = $response->withJson($todosLosUsuarios, 200);  
    	return $newResponse;
    }
      public function CargarUno($request, $response, $args) {
		//   if(isset($_FILES['foto'])){
		// 	  Usuario::cargarFoto($_FILES['foto']);
		//   }
		var_dump($request);
     	$response->getBody()->write("<h1>Cargar uno nuevo</h1>");
      	return $response;
	}

	public static function cararFoto($foto){

	}

      public function BorrarUno($request, $response, $args) {
     	$ArrayDeParametros = $request->getParsedBody();
     	$id=$ArrayDeParametros['id'];
     	$usuario= new Usuario();
     	$usuario->id=$id;
     	$cantidadDeBorrados=$usuario->BorrarUsuario();

     	$objDelaRespuesta= new stdclass();
	    $objDelaRespuesta->cantidad=$cantidadDeBorrados;
	    if($cantidadDeBorrados>0)
	    	{
	    		 $objDelaRespuesta->resultado="algo borro!!!";
	    	}
	    	else
	    	{
	    		$objDelaRespuesta->resultado="no Borro nada!!!";
	    	}
	    $newResponse = $response->withJson($objDelaRespuesta, 200);  
      	return $newResponse;
    }
     public function ModificarUno($request, $response, $args) {
     	//$response->getBody()->write("<h1>Modificar  uno</h1>");
     	$ArrayDeParametros = $request->getParsedBody();
	    //var_dump($ArrayDeParametros);    	
	    $miUsuario = new Usuario();
	    $miUsuario->id=$ArrayDeParametros['id'];
	    $miUsuario->nombre=$ArrayDeParametros['nombre'];
	    $miUsuario->password=$ArrayDeParametros['password'];

	   	$resultado =$miUsuario->ModificarUsuarioParametros();
	   	$objDelaRespuesta= new stdclass();
		//var_dump($resultado);
		$objDelaRespuesta->resultado=$resultado;
		return $response->withJson($objDelaRespuesta, 200);		
    }

/* final especiales para slimFramework*/
  	public function BorrarUsuario()
	 {
	 		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("
				delete 
				from usuario 				
				WHERE id=:id");	
				$consulta->bindValue(':id',$this->id, PDO::PARAM_INT);		
				$consulta->execute();
				return $consulta->rowCount();
	 }

	// public static function BorrarUsuarioPorAnio($año)
	//  {

	// 		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
	// 		$consulta =$objetoAccesoDato->RetornarConsulta("
	// 			delete 
	// 			from usuario 				
	// 			WHERE jahr=:anio");	
	// 			$consulta->bindValue(':anio',$año, PDO::PARAM_INT);		
	// 			$consulta->execute();
	// 			return $consulta->rowCount();

	//  }
	public function ModificarUsuario()
	 {

			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("
				update usuario 
				set nombre='$this->nombre',
				password='$this->password'				
				WHERE id='$this->id'");
			return $consulta->execute();

	 }
	
  
	 public function InsertarElUsuario()
	 {
				$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
				$consulta =$objetoAccesoDato->RetornarConsulta("INSERT into usuario (nombre, password) values('$this->nombre','$this->password')");
				$consulta->execute();
				return $objetoAccesoDato->RetornarUltimoIdInsertado();
				

	 }

	  public function ModificarUsuarioParametros()
	 {
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("
				update usuario 
				set nombre=:nombre,
				password=:password
				WHERE id=:id");
			$consulta->bindValue(':id',$this->id, PDO::PARAM_INT);
			$consulta->bindValue(':nombre',$this->nombre, PDO::PARAM_STR);
			$consulta->bindValue(':password', $this->password, PDO::PARAM_STR);
			return $consulta->execute();
	 }

	 public function InsertarElUsuarioParametros()
	 {
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta("INSERT into usuario (nombre,password)values(:nombre,:password)");
		$consulta->bindValue(':nombre',$this->nombre, PDO::PARAM_STR);
		$consulta->bindValue(':password', $this->password, PDO::PARAM_STR);
		$consulta->execute();		
		return $objetoAccesoDato->RetornarUltimoIdInsertado();
	 }
	 public function GuardarCD()
	 {

	 	if($this->id>0)
		{
			$this->ModificarUsuarioParametros();
		}else {
			$this->InsertarElUsuarioParametros();
		}

	 }


  	public static function TraerTodoLosUsuarios()
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("select id,nombre as nombre, password as password from usuario");
			$consulta->execute();			
			return $consulta->fetchAll(PDO::FETCH_CLASS, "Usuario");		
	}

	public static function TraerUnUsuario($id) 
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("select id, nombre as nombre, password as password from usuario where id = $id");
			$consulta->execute();
			$usuarioBuscado= $consulta->fetchObject('Usuario');
			return $usuarioBuscado;				

			
	}

	// public static function TraerUnUsuarioAnio($id,$anio) 
	// {
	// 		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
	// 		$consulta =$objetoAccesoDato->RetornarConsulta("select  titel as nombre, interpret as password,jahr as año from usuario  WHERE id=? AND jahr=?");
	// 		$consulta->execute(array($id, $anio));
	// 		$usuarioBuscado= $consulta->fetchObject('Usuario');
    //   		return $usuarioBuscado;				

			
	// }

	// public static function TraerUnUsuarioNombreParamNombre($id,$nombre) 
	// {
	// 		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
	// 		$consulta =$objetoAccesoDato->RetornarConsulta("select nombre as nombre, password as password from usuario  WHERE id=:id AND nombre=:nombre");
	// 		$consulta->bindValue(':id', $id, PDO::PARAM_INT);
	// 		$consulta->bindValue(':nombre', $nombre, PDO::PARAM_STR);
	// 		$consulta->execute();
	// 		$usuarioBuscado= $consulta->fetchObject('Usuario');
    //   		return $usuarioBuscado;				

			
	// }
	
	// public static function TraerUnUsuarioAnioParamNombreArray($id,$anio) 
	// {
	// 		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
	// 		$consulta =$objetoAccesoDato->RetornarConsulta("select  titel as nombre, interpret as password,jahr as año from usuario  WHERE id=:id AND jahr=:anio");
	// 		$consulta->execute(array(':id'=> $id,':anio'=> $anio));
	// 		$consulta->execute();
	// 		$usuarioBuscado= $consulta->fetchObject('Usuario');
    //   		return $usuarioBuscado;				

			
	// }

	public function mostrarDatos()
	{
	  	return "Metodo mostar:".$this->nombre."  ".$this->password;
	}

}