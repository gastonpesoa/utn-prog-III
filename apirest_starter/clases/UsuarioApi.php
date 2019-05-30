<?php
require_once 'Usuario.php';
require_once 'IApiUsable.php';

class UsuarioApi extends Usuario implements IApiUsable
{
 	public function TraerUno($request, $response, $args) {
     	$id=$args['id'];
    	$elUsuario=Usuario::TraerUnUsuario($id);
     	$newResponse = $response->withJson($elUsuario, 200);  
    	return $newResponse;
    }
     public function TraerTodos($request, $response, $args) {
      	$todosLosUsuarios=Usuario::TraerTodoLosUsuarios();
     	//$newResponse = $response->withJson($todosLosUsuarios, 200);  
    	return $newResponse;
    }
      public function CargarUno($request, $response, $args) {
     	 $ArrayDeParametros = $request->getParsedBody();
        //var_dump($ArrayDeParametros);
        $nombre= $ArrayDeParametros['nombre'];
        $password= $ArrayDeParametros['password'];
        
        $miUsuario = new Usuario();
        $miUsuario->nombre=$nombre;
        $miUsuario->password=$password;
        $miUsuario->InsertarElUsuarioParametros();

        // $archivos = $request->getUploadedFiles();
        // $destino="./fotos/";
        // var_dump($archivos);
        // var_dump($archivos['foto']);

        // $nombreAnterior=$archivos['foto']->getClientFilename();
        // $extension= explode(".", $nombreAnterior)  ;
        //var_dump($nombreAnterior);
        // $extension=array_reverse($extension);

        // $archivos['foto']->moveTo($destino.$nombre.".".$extension[0]);
        $response->getBody()->write("se guardo el Usuario");

        return $response;
    }
      public function BorrarUno($request, $response, $args) {
     	$ArrayDeParametros = $request->getParsedBody();
     	$id=$ArrayDeParametros['id'];
     	$Usuario= new Usuario();
     	$Usuario->id=$id;
     	$cantidadDeBorrados=$Usuario->BorrarUsuario();

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
}