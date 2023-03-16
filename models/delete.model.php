<?php

require_once "connection.php";

class DeleteModel{

    //Peticion delete para eliminar datos

    static public function deleteData($table, $id, $nameId){

        $stmt = Connection::connect()->prepare("DELETE FROM $table WHERE $nameId = :$nameId");

        $stmt -> bindParam(":".$nameId, $id, PDO::PARAM_INT);
        if ($stmt->execute()){
            return "The process was successfully";
        }else{

            return (Connection::connect()->errorInfo());
        }
    }

}
