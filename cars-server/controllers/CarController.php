<?php
include("../models/Car.php");
include("../connection/connection.php");
include("../services/ResponseService.php");

function getCarByID(){
    global $connection;

    if(isset($_GET["id"])){
        $id = $_GET["id"];
    }else{
        echo ResponseService::response(500, "ID is missing");
        return;
    }

    $car = Car::find($connection, $id);
    echo ResponseService::response(200, $car->toArray());
    return;
}

function getCars(){
    global $connection;
    if(isset($_GET["id"])){
        $car = Car::find($connection, $_GET["id"]);
        echo ResponseService::response(200, $car->toArray());
    }
    $cars = Car::findAll($connection);
    $cars_as_array = array_map(fn($car) => $car->toArray(), $cars);
    echo ResponseService::response(200, $cars_as_array);
}

//getCarById();
// getCars();

//ToDO: 
//transform getCarByID to getCars()
//if the id is set? then we retrieve the specific car 
//if no ID, then we retrieve all the cars


?>