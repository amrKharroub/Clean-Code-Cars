<?php
require_once(__DIR__ . "/../models/Car.php");
require_once(__DIR__ . "/../connection/connection.php");
require_once(__DIR__ . "/../services/ResponseService.php");
require_once(__DIR__ . "/../services/CarService.php");

class CarController {

    function getCarByID(){
        global $connection;

        if(isset($_GET["id"])){
            $id = $_GET["id"];
        }else{
            echo ResponseService::response(500, "ID is missing");
            return;
        }
       
        $car = CarService::findCarByID($connection, $id);
        echo ResponseService::response(200, $car);
        return;
    }

    function getAllCars(){
        global $connection;
        $cars = CarService::getAllCars($connection);
        try{
            $json_cars = json_encode($cars);
            echo ResponseService::response(200, $cars);
        } catch (Exception $e){
            echo ResponseService::response(500, "failed to incode");
        }
        return;
    }

    function getSortedCarsByColor(){
        global $connection;

        $cars = CarService::sortCarsByColor($connection);
        echo ResponseService::response(200, $cars);
        return;
    }
}

?>