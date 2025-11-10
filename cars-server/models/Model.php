<?php
abstract class Model{

    protected static string $table;
    protected static string $primary_key = "id";

    public static function find(mysqli $connection, int $id){
        $sql = sprintf("SELECT * from %s WHERE %s = ?",
                       static::$table,
                       static::$primary_key);

        $query = $connection->prepare($sql);
        $query->bind_param("i", $id);
        $query->execute();               

        $data = $query->get_result()->fetch_assoc();

        return $data ? new static($data) : null;
    }

    public static function findAll(mysqli $connection){
        $sql = sprintf("SELECT * FROM %s",
        static::$table);

        $query = $connection->prepare($sql);
        $query ->execute();

        $data = $query -> get_result()->fetch_assoc();

        if(!$data){
            return null;
        }
        $result = [];
        while($d = $data){
            $result[] = new static($d);
        }
        return $result;
    }

    public static function delete(mysqli $connection, int $id){
        $sql = sprintf("DELETE FROM %s WHERE %s = ?", static::$table, static::$primary_key);

        $query = $connection->prepare($sql);
        $query->bind_param("i", $id);
        $query->execute();

        return $query->affected_rows;
    }

}



?>
