<?php
/**
 * Created by PhpStorm.
 * User: Robbie
 * Date: 11/29/2016
 * Time: 9:13 AM
 */
use VGBacklog\Http\Methods; 

require_once 'config.php';
require_once 'vendor/autoload.php';

$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) use ($baseURI) {

    //Backlog Handles
    $handlePostBacklog = function(){
        return (new VGBacklog\Controllers\BacklogController)->addGameToBacklog();
    };

    $handleGetBacklog = function($args){
        return (new VGBacklog\Controllers\BacklogController)->getEntireBacklog($args);
    };

    $handleGetLastBacklog = function ($args) {
        return (new VGBacklog\Controllers\BacklogController)->getLastGameAdded($args);
    };

    $handleGetTopOfBacklog = function ($args) {
        return (new VGBacklog\Controllers\BacklogController)->getTopOfBacklog($args);
    };

    $putGameFromBacklog = function () {
        return (new VGBacklog\Controllers\BacklogController)->putGameFromBacklog();
    };

    $deleteGameFromBacklog = function ($args) {
        return (new VGBacklog\Controllers\BacklogController)->deleteGameFromBacklog($args);
    };

    $handleGetBacklogById = function($args){
        return (new VGBacklog\Controllers\BacklogController)->getGameById($args);
    };

    //Backlog Routes
    $r->addRoute(Methods::POST, $baseURI . '/backlog', $handlePostBacklog);
    $r->addRoute(Methods::PUT, $baseURI . '/backlog', $putGameFromBacklog);
    $r->addRoute(Methods::DELETE, $baseURI . '/backlog/game/{id:\d+}', $deleteGameFromBacklog);
    $r->addRoute(Methods::GET, $baseURI . '/backlog/game/{id:\d+}', $handleGetBacklogById);
    $r->addRoute(Methods::GET, $baseURI . '/backlog/{id:\d+}/all', $handleGetBacklog);
    $r->addRoute(Methods::GET, $baseURI . '/backlog/{id:\d+}/last', $handleGetLastBacklog);
    $r->addRoute(Methods::GET, $baseURI . '/backlog/{id:\d+}/top', $handleGetTopOfBacklog);

    //Collection Handles
    $handlePostCollection = function(){
        return (new VGBacklog\Controllers\CollectionController)->addGameToCollection();
    };

    $handlePutGameFromCollection = function(){
        return (new VGBacklog\Controllers\CollectionController)->putGameFromCollection();
    };

    $handleDeleteGameFromCollection = function($args){
        return (new VGBacklog\Controllers\CollectionController)->deleteGameFromCollection($args);
    };

    $handleGetCollection = function($args){
        return (new VGBacklog\Controllers\CollectionController)->getCollection($args);
    };

    $handleGetCollectionByPlatform = function($args){
        return (new VGBacklog\Controllers\CollectionController)->getByPlatform($args);
    };

    $handleGetCollectionByPublisher = function($args){
        return (new VGBacklog\Controllers\CollectionController)->getByPublisher($args);
    };

    $handleGetCollectionByGenre = function($args){
        return (new VGBacklog\Controllers\CollectionController)->getByGenre($args);
    };

    $handleGetCollectionById = function($args){
        return (new VGBacklog\Controllers\CollectionController)->getGameById($args);
    };

    //Collection Routes
    $r->addRoute(Methods::POST, $baseURI . '/collection', $handlePostCollection);
    $r->addRoute(Methods::PUT, $baseURI . '/collection', $handlePutGameFromCollection);
    $r->addRoute(Methods::DELETE, $baseURI . '/collection/game/{id:\d+}', $handleDeleteGameFromCollection);
    $r->addRoute(Methods::GET, $baseURI . '/collection/game/{id:\d+}', $handleGetCollectionById);
    $r->addRoute(Methods::GET, $baseURI . '/collection/{id:\d+}/all', $handleGetCollection);
    $r->addRoute(Methods::GET, $baseURI . '/collection/{id:\d+}/platform/{platform:[A-Za-z0-9\s]+}', $handleGetCollectionByPlatform);
    $r->addRoute(Methods::GET, $baseURI . '/collection/{id:\d+}/publisher/{publisher:[A-Za-z0-9\s]+}', $handleGetCollectionByPublisher);
    $r->addRoute(Methods::GET, $baseURI . '/collection/{id:\d+}/genre/{genre:[A-Za-z0-9]+}', $handleGetCollectionByGenre);

    //User Handles
    $handlePostUser = function(){
        return (new VGBacklog\Controllers\UserController)->postNewUser();
    };

    $handlePutUser = function(){
        return (new VGBacklog\Controllers\UserController)->putUser();
    };

    $handleDeleteUser = function($args){
        return (new VGBacklog\Controllers\UserController)->deleteUser($args);
    };

    $handleGetUserById = function($args){
        return (new VGBacklog\Controllers\UserController)->getUserById($args);
    };

    $handleGetAllUsers = function($args){
        return (new VGBacklog\Controllers\UserController)->getAllUsers($args);
    };

    //User Routes
    $r->addRoute(Methods::POST, $baseURI . '/users', $handlePostUser);
    $r->addRoute(Methods::PUT, $baseURI . '/users', $handlePutUser);
    $r->addRoute(Methods::DELETE, $baseURI . '/users/{id:\d+}', $handleDeleteUser);
    $r->addRoute(Methods::GET, $baseURI . '/users/{id:\d+}', $handleGetUserById);
    $r->addRoute(Methods::GET, $baseURI . '/users/all', $handleGetAllUsers);
});

$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

$pos = strpos($uri, '?');
if ($pos !== false) {
    $uri = substr($uri, 0, $pos);
}

$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($method, $uri);

switch($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        //http_response_code(VGBacklog\Http\StatusCodes::NOT_FOUND);
        //Handle 404
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        //http_response_code(VGBacklog\Http\StatusCodes::METHOD_NOT_ALLOWED);
        //Handle 403
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler  = $routeInfo[1];
        $vars = $routeInfo[2];

        $response = $handler($vars);
        echo json_encode($response);
        break;
}