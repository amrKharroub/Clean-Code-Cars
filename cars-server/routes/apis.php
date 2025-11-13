<?php 

//array of routes - a mapping between routes and controller name and method!
$apis = [
    '/cars'         => ['controller' => 'CarController', 'method' => 'getCarByID'],
    '/cars/[0-9]+'         => ['controller' => 'CarController', 'method' => 'getCarByID'],
    '/users'         => ['controller' => 'UserController', 'method' => 'getUsers']
];
