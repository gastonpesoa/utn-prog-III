<?php

use Slim\App;
use Clases\UsuarioApi;
use Clases\CompraApi;
use Clases\VerificaPerfilMW;

return function (App $app) {
    $container = $app->getContainer();

    // view renderer
    $container['renderer'] = function ($c) {
        $settings = $c->get('settings')['renderer'];
        return new \Slim\Views\PhpRenderer($settings['template_path']);
    };

    // monolog
    $container['logger'] = function ($c) {
        $settings = $c->get('settings')['logger'];
        $logger = new \Monolog\Logger($settings['name']);
        $logger->pushProcessor(new \Monolog\Processor\UidProcessor());
        $logger->pushHandler(new \Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
        return $logger;
    };

    // ORM
    $capsule = new \Illuminate\Database\Capsule\Manager;
    $capsule->addConnection($container->get('settings')['db']);
    $capsule->setAsGlobal();
    $capsule->bootEloquent();
    //pass the connection to global container
    $container['db'] = function ($container) use ($capsule){
        return $capsule;
    };
    
    // Registration Controller
    $container['UsuarioApi'] = function($c) {
        return new UsuarioApi($c->get('logger'));
    };

    $container['CompraApi'] = function($c) {
        return new CompraApi($c->get('logger'));
    };

    // Registration MW
    $container['VerificaPerfilMW'] = function() {
        return new VerificaPerfilMW();
    };
};
