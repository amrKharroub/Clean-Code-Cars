<?php
require_once(__DIR__ . "/../models/Car.php");

class CarService{
    public static function findCarByID(mysqli $connection, $id): array{
        $car = Car::find($connection, $id);
        $car = $car ? $car->toArray() : [];
        return $car;
    }
    public static function sortCarsByColor(mysqli $connection): array{
        $cars = Car::findAll($connection);
        usort($cars, function ($a, $b){
            return strcmp($a->getColor(), $b->getColor());
        });
        return $cars;
    }
    public static function getAllCars(mysqli $connection){
        return Car::findAll($connection);
    }
}