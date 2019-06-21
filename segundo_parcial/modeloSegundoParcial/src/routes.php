<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

return function (App $app) 
{
    $container = $app->getContainer();

    $app->group('/usuario', function() use ($container) 
    {                                
        $this->get('/welcome', \UsuarioApi::class . ':Welcome');
        $this->get('/', \UsuarioApi::class . ':GetAll')->add(\VerificaPerfilMW::class . ':VerificarPerfil');        
        $this->get('/{id}', \UsuarioApi::class . ':GetById');
        $this->post('/', \UsuarioApi::class . ':RegisterUser');
        $this->post('/login', \UsuarioApi::class . ':LoginUser');
    });

    $app->group('/compra', function() use ($container) 
    {                                        
        $this->get('/', \CompraApi::class . ':GetAll');    
        $this->get('/{id}', \CompraApi::class . ':GetById');
        $this->post('/', \CompraApi::class . ':RegisterCompra');
        $this->post('/login', \CompraApi::class . ':LoginUser');
    })->add(\VerificaPerfilMW::class . ':VerificarPerfil');
    
};
