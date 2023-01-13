<?php
require_once "connection.php";

class PostModel{
        //Petición para tomar nombre de las columnas
    static public function getColumnsData($table, $database){
        return Connection::connect()->query("SELECT COLUMN_NAME AS item FROM information_schema.columns WHERE table_schema = '$database' AND table_name = '$table'")
        ->fetchAll(PDO::FETCH_OBJ);
    }

    //Peticion Post para crear datos

    static public function postData($table,$data){
        $columns = "(";
        $params = "(";
        foreach($data as $key => $value){
            $columns .= $key.",";
            $params .= ":".$key.",";
        }

        $columns = substr($columns, 0, -1);
        $params = substr($params, 0, -1);

        $columns .= ")";
        $params .= ")";

        echo '<pre>'; print_r($columns); print_r($params); '</pre>';

        $stmt = Connection::connect()->prepare("INSERT INTO $table $columns VALUES $params");

        foreach($data as $key => $value){
            $stmt->bindParam(":".$key, $data[$key],PDO::PARAM_STR);
        }

        if ($stmt->execute()){
            return "The process was successful";
        }else{
            echo Connection::connect()->errorInfo();
        }
    }
}