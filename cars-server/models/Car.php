<?php
include("Model.php");

class Car extends Model {
    private int $id;
    private string $name;
    private string $year;
    private string $color;

    protected static string $table = "cars";

    public function getID(){
        return $this->attributes["id"];
    }

    public function setName(string $name){
        $this->attributes["name"] = $name;
    }

    public function getName(){
        return $this->attributes["name"];
    }

    public function setColor(string $color){
        $this->attributes["color"] = $color;
    }

    public function getColor(){
        return $this->attributes["color"];
    }

    public function __toString(){
        return $this->id . " | " . $this->name . " | " . $this->year. " | " . $this->color;
    }
    
    public function toArray(){
        return ["id" => $this->id, "name" => $this->name, "year" => $this->year, "color" => $this->color];
    }

}

?>