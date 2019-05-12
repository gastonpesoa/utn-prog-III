<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require_once './vendor/autoload.php';
require_once './clases/UsuarioApi.php';
require_once './clases/AccesoDatos.php';

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

/*LLAMADA A METODOS DE INSTANCIA DE UNA CLASE*/
$app->group('/Usuario', function () {
 
  $this->get('/', \UsuarioApi::class . ':TraerTodos');
 
  $this->get('/{id}', \UsuarioApi::class . ':TraerUno');

  $this->post('/', \UsuarioApi::class . ':CargarUno');

  $this->delete('/', \UsuarioApi::class . ':BorrarUno');
  
  $this->put('/', \UsuarioApi::class . ':ModificarUno');
     
});

$app->group('/login', function () {
 
    //se loguea
    $this->post('/', \UsuarioApi::class . ':CargarUno');
    //modifica clave
    $this->put('/', \UsuarioApi::class . ':ModificarUno');
       
  });

  
$app->run();