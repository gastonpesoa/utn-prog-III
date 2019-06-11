<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;
// use \Psr\Http\Message\ServerRequestInterface as Request;
// use \Psr\Http\Message\ResponseInterface as Response;

return function (App $app) {
    $container = $app->getContainer();

    $app->group('/usuario', function() use ($container) {                                
        $this->get('/welcome', \UsuarioApi::class . ':Welcome');
        $this->get('/', \UsuarioApi::class . ':GetAll')->add(\VerificaPerfilMW::class . ':VerificarPerfil');
       // $this->get('/{id}', \UsuarioApi::class . ':GetById');
        $this->post('/', \UsuarioApi::class . ':RegisterUser');
        $this->post('/login', \UsuarioApi::class . ':LoginUser');
    });  

    // $app->get('/[{name}]', function (Request $request, Response $response, array $args) use ($container) {
    //     // Sample log message
    //     $container->get('logger')->info("Slim-Skeleton '/' route");

    //     // Render index view
    //     return $container->get('renderer')->render($response, 'index.phtml', $args);
    // });
};
