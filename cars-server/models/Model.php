<?php
abstract class Model{

    protected static string $table;
    protected static string $primary_key = "id";
    protected array $attributes = [];

    public function __construct(array $data = []){
        $this->attributes = $data;
    }

    protected static function getBindType(mixed $var): string {
        switch(gettype($var)){
            case "integer":
                return "i";
            case "double":
                return "d";
            default:
                return "s";
        }
    }
 
    public static function find(mysqli $connection, mixed $id, string|null $primary_key = null){
        $pk = $primary_key ?? static::$primary_key;
        
        $sql = sprintf(
            "SELECT * from %s WHERE %s = ?",
            static::$table,
            $pk
        );

        $type = static::getBindType($id);
        $query = $connection->prepare($sql);
        $query->bind_param($type, $id);
        $query->execute();               

        $data = $query->get_result()->fetch_assoc();

        return $data ? new static($data) : null;
    }

    public static function findAll(mysqli $connection): array{
        $sql = sprintf("SELECT * FROM %s", static::$table);

        $query = $connection->prepare($sql);
        $query->execute();

        $resultSet = $query -> get_result();

        if(!$resultSet){
            throw new Exception("Failed to obtain results");
        }
        $results = [];
        while($row = $resultSet->fetch_assoc()){
            $results[] = new static($row);
        }
        return $results;
    }

    public function save(mysqli $connection): bool{
        if(isset($this->attributes[static::$primary_key])){
            return $this->update($connection);
        } else {
            return $this->insert($connection);
        }
    }

    public function insert(mysqli $connection): bool{
        $data = $this->attributes;

        $columns = array_keys($data);
        $values = array_values($data);
        
        $column_sql = implode(", ", $columns);
        $placeholder_sql = rtrim(str_repeat("?,", count($values)), ",");

        $sql = sprintf(
            "INSERT INTO %s (%s) VALUES %s",
            static::$table,
            $column_sql,
            $placeholder_sql
        );

        $query = $connection->prepare($sql);

        $types = "";
        foreach ($values as $val){
            $types .= static::getBindType($val);
        }

        $query->bind_param($types, ...$values);
        $result = $query->execute();

        return $result;
    }

    public function update(mysqli $connection): bool{
        $pk_value = $this->attributes[static::$primary_key];

        $data_to_update = $this->attributes;
        unset($data_to_update[static::$primary_key]);

        $values = [];
        $set_pairs = [];
        $types = "";

        foreach($data_to_update as $key => $value){
            $set_pairs[] = "$key = ?";
            $values[] = $value;
            $types .= static::getBindType($value);
        }
        $set_sql = implode(", ", $set_pairs);
        $values[] = $pk_value;
        $types .= static::getBindType($pk_value);

        $sql = sprintf(
            "UPDATE %s SET %s WHERE %s = ?",
            static::$table,
            $set_sql,
            static::$primary_key
        );

        $query = $connection->prepare($sql);
        $query->bind_param($types, ...$values);

        
        return $query->execute();
    }

    public static function delete(mysqli $connection, mixed $id): bool{
        $sql = sprintf(
            "DELETE FROM %s WHERE %s = ?",
            static::$table,
            static::$primary_key
        );

        $type = static::getBindType($id);
        $query = $connection->prepare($sql);
        $query->bind_param($type, $id);
        
        return $query->execute();
    }
}



?>
