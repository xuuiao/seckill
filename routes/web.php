<?php

$router->get('/', ['uses' => 'IndexController@index']);
$router->get('doc/{name}', ['uses' => 'DocController@index']);
$router->get('seckill', ['uses' => 'IndexController@seckill']);
$router->get('initcache', ['uses' => 'IndexController@initcache']);

// 业务接口
$router->group(['prefix' => 'app', 'namespace' => 'App', 'middleware' => 'access'], function () use ($router) {
    $router->get('demo/list', ['uses' => 'Demo\ListController@index']);
});

// 服务接口
$router->group(['prefix' => 'service', 'namespace' => 'Service', 'middleware' => ['access', 'sign']], function () use ($router) {
    $router->get('demo/list', ['uses' => 'Demo\ListController@index']);
});
