<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require_once './vendor/autoload.php';
require_once './clases/UsuarioApi.php';
require_once './clases/AccesoDatos.php';
require_once './clases/Archivo.php';

$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

/*
¡La primera línea es la más importante! A su vez en el modo de 
desarrollo para obtener información sobre los errores
 (sin él, Slim por lo menos registrar los errores por lo que si está utilizando
  el construido en PHP webserver, entonces usted verá en la salida de la consola 
  que es útil).

  La segunda línea permite al servidor web establecer el encabezado Content-Length, 
  lo que hace que Slim se comporte de manera más predecible.
*/

// $app = new \Slim\App(["settings" => $config]);


// $app->get('[/]', function (Request $request, Response $response) {    
//     $response->getBody()->write("GET => Bienvenido!!! a SlimFramework");
//     return $response;

// });

/*
COMPLETAR POST, PUT Y DELETE
*/


// $app->get('/saludar[/]', function (Request $request, Response $response) {    
//     $response->getBody()->write("Hola mundo SlimFramework!!!");
//     return $response;

// });


/*
MAS CODIGO AQUI...
*/


// $app->run();

$app = new \Slim\App(["settings" => $config]);

$VerificadorDeCredenciales = function ($request, $response, $next) {

  if($request->isGet())
  {
     $response->getBody()->write('<p>NO necesita credenciales para los get</p>');
     $response = $next($request, $response);
  }
  else
  {
    var_dump($request->getParsedBody());
    $response->getBody()->write('<p>verifico credenciales</p>');
    $ArrayDeParametros = $request->getParsedBody();
    $nombre=$ArrayDeParametros['nombre'];
    $tipo=$ArrayDeParametros['tipo'];
    if($tipo=="administrador")
    {
      $response->getBody()->write("<h3>Bienvenido $nombre </h3>");
      $response = $next($request, $response);
    }
    else
    {
      $response->getBody()->write('<p>no tenes habilitado el ingreso</p>');
    }  
  }  
  $response->getBody()->write('<p>vuelvo del verificador de credenciales</p>');
  return $response;  
};

/*LLAMADA A METODOS DE INSTANCIA DE UNA CLASE*/
$app->group('/Usuario', function () {
 
  $this->get('/', \UsuarioApi::class . ':TraerTodos');
 
  $this->get('/{id}', \UsuarioApi::class . ':TraerUno');

  $this->post('/', \UsuarioApi::class . ':CargarUno');

  $this->delete('/', \UsuarioApi::class . ':BorrarUno');
  
  $this->put('/', \UsuarioApi::class . ':ModificarUno');
     
})->add($VerificadorDeCredenciales);

$app->group('/login', function () {
 
    //se loguea
    $this->post('/', \UsuarioApi::class . ':CargarUno');
    //modifica clave
    $this->put('/', \UsuarioApi::class . ':ModificarUno');
       
  });


/* codifgo que se ejecuta antes que los llamados por la ruta*/
$app->add(function ($request, $response, $next) {
  $response->getBody()->write('<p>Antes de ejecutar UNO </p>');
  $response = $next($request, $response);
  $response->getBody()->write('<p>Despues de ejecutar UNO</p>');

  return $response;
});

$app->add(function ($request, $response, $next) {
  $response->getBody()->write('<p>Antes de ejecutar DOS </p>');
  $response = $next($request, $response);
  $response->getBody()->write('<p>Despues de ejecutar DOS</p>');

  return $response;
});
// despues de esto y llamando a la ruta cd/, el resultaso es este :
/*
Antes de ejecutar Dos ***
Antes de ejecutar UNO ***
TrearTodos
***Despues de ejecutar UNO
***Despues de ejecutar Dos
*/
/*habilito el CORS para todos*/
$app->add(function ($req, $res, $next) {    
    $response = $next($req, $res);
    return $response
            ->withHeader('Access-Control-Allow-Origin', 'http://localhost:4200')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
});



  
$app->run();