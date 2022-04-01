<?php
require_once "connection.php";

class GetModel{
    //Peticiones GET sin filtro
    static public function getData($table,$orderBy,$orderMode,$startAt,$endAt)
    {
        if ($orderBy != null && $orderMode != null && $startAt == null && $endAt == null){
            $stmt = Connection::connect()->prepare("SELECT * FROM $table ORDER BY $orderBy $orderMode");
        }elseif($orderBy != null && $orderMode != null && $startAt != null && $endAt != null){
            $stmt = Connection::connect()->prepare("SELECT * FROM $table ORDER BY $orderBy $orderMode LIMIT $startAt, $endAt");
        }
        else{
            $stmt = Connection::connect()->prepare("SELECT * FROM $table");
        }

        $stmt -> execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }

    //Peticiones GET con filtro
    static public function getFilterData($table,$linkTo,$equalTo,$orderBy,$orderMode,$startAt,$endAt)
    {
        if ($orderBy != null && $orderMode != null && $startAt == null && $endAt == null){
            $stmt = Connection::connect()->prepare("SELECT * FROM $table WHERE $linkTo = :$linkTo ORDER BY $orderBy $orderMode");
        }elseif($orderBy != null && $orderMode != null && $startAt != null && $endAt != null){
            $stmt = Connection::connect()->prepare("SELECT * FROM $table WHERE $linkTo = :$linkTo ORDER BY $orderBy $orderMode LIMIT $startAt, $endAt");
        }
        else{
         $stmt = Connection::connect()->prepare("SELECT * FROM $table WHERE $linkTo = :$linkTo");
        }

        $stmt -> bindParam(":".$linkTo,$equalTo, PDO::PARAM_STR);

        $stmt -> execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }

    //Peticiones GET entre tablas relacionadas sin filtro
    static public function getRelData($rel,$type,$orderBy, $orderMode,$startAt,$endAt){

        $relArray = explode(",",$rel);
        $typeArray = explode(",",$type);

        //Relacionar dos tablas
        if(count($relArray) == 2 && count($typeArray) == 2){
            $on1 = $relArray[0].".id_".$typeArray[1]."_".$typeArray[0];
            $on2 = $relArray[1].".id_".$typeArray[1];
            if ($orderBy != null && $orderMode != null && $startAt == null && $endAt == null){
                $stmt = Connection::connect()->prepare("SELECT * FROM $relArray[0] inner join $relArray[1] ON $on1 = $on2 ORDER BY $orderBy $orderMode");
            }elseif($orderBy != null && $orderMode != null && $startAt != null && $endAt != null){
                $stmt = Connection::connect()->prepare("SELECT * FROM $relArray[0] inner join $relArray[1] ON $on1 = $on2 ORDER BY $orderBy $orderMode LIMIT $startAt, $endAt");
            }
            else{
                $stmt = Connection::connect()->prepare("SELECT * FROM $relArray[0] inner join $relArray[1] ON $on1 = $on2");

            }
        }

        //relacionar tres tablas
        if(count($relArray) == 3 && count($typeArray) == 3){

            $on1a =$relArray[0].".id_".$typeArray[1]."_".$typeArray[0];
            $on1b =$relArray[1].".id_".$typeArray[1];

            $on2a =$relArray[0].".id_".$typeArray[2]."_".$typeArray[0];
            $on2b =$relArray[2].".id_".$typeArray[2];
            if ($orderBy != null && $orderMode != null && $startAt == null && $endAt == null){
                $stmt = Connection::connect()->prepare("SELECT * FROM $relArray[0] inner join $relArray[1] ON $on1a = $on1b INNER JOIN
                    $relArray[2] ON $on2a = $on2b ORDER BY $orderBy $orderMode");
            }elseif($orderBy != null && $orderMode != null && $startAt != null && $endAt != null){
                $stmt = Connection::connect()->prepare("SELECT * FROM $relArray[0] inner join $relArray[1] ON $on1a = $on1b INNER JOIN
                    $relArray[2] ON $on2a = $on2b ORDER BY $orderBy $orderMode LIMIT $startAt, $endAt");
            }
            else{
                $stmt = Connection::connect()->prepare("SELECT * FROM $relArray[0] inner join $relArray[1] ON $on1a = $on1b INNER JOIN
                    $relArray[2] ON $on2a = $on2b");
            }

        }
        //relacionar cuatro tablas
        if(count($relArray) == 4 && count($typeArray) == 4){

            $on1a =$relArray[0].".id_".$typeArray[1]."_".$typeArray[0];
            $on1b =$relArray[1].".id_".$typeArray[1];

            $on2a =$relArray[0].".id_".$typeArray[2]."_".$typeArray[0];
            $on2b =$relArray[2].".id_".$typeArray[2];

            $on3a =$relArray[0].".id_".$typeArray[3]."_".$typeArray[0];
            $on3b =$relArray[3].".id_".$typeArray[3];
            if ($orderBy != null && $orderMode != null && $startAt == null && $endAt == null){
                $stmt = Connection::connect()->prepare("SELECT * FROM $relArray[0] inner join $relArray[1] ON $on1a = $on1b INNER JOIN
                    $relArray[2] ON $on2a = $on2b INNER JOIN $relArray[3] ON $on3a = $on3b ORDER BY $orderBy $orderMode");
            }elseif($orderBy != null && $orderMode != null && $startAt != null && $endAt != null){
                $stmt = Connection::connect()->prepare("SELECT * FROM $relArray[0] inner join $relArray[1] ON $on1a = $on1b INNER JOIN
                    $relArray[2] ON $on2a = $on2b INNER JOIN $relArray[3] ON $on3a = $on3b ORDER BY $orderBy $orderMode LIMIT $startAt, $endAt");
            }
            else{
                $stmt = Connection::connect()->prepare("SELECT * FROM $relArray[0] inner join $relArray[1] ON $on1a = $on1b INNER JOIN
                    $relArray[2] ON $on2a = $on2b INNER JOIN $relArray[3] ON $on3a = $on3b");
            }

        }
        $stmt -> execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS);

    }

    //Peticiones GET entre tablas relacionadas con filtro
    static public function getRelFilterData($rel,$type,$linkTo,$equalTo,$orderBy, $orderMode,$startAt,$endAt){

        $relArray = explode(",",$rel);
        $typeArray = explode(",",$type);

        //Relacionar dos tablas
        if(count($relArray) == 2 && count($typeArray) == 2){
            $on1 = $relArray[0].".id_".$typeArray[1]."_".$typeArray[0];
            $on2 = $relArray[1].".id_".$typeArray[1];
            if ($orderBy != null && $orderMode != null && $startAt == null && $endAt == null){
                $stmt = Connection::connect()->prepare("SELECT * FROM $relArray[0] inner join $relArray[1] ON $on1 = $on2 WHERE $linkTo = :$linkTo ORDER BY $orderBy $orderMode");
            }elseif($orderBy != null && $orderMode != null && $startAt != null && $endAt != null){
                $stmt = Connection::connect()->prepare("SELECT * FROM $relArray[0] inner join $relArray[1] ON $on1 = $on2 WHERE $linkTo = :$linkTo ORDER BY $orderBy $orderMode LIMIT $startAt, $endAt");
            }
            else{
                $stmt = Connection::connect()->prepare("SELECT * FROM $relArray[0] inner join $relArray[1] ON $on1 = $on2 WHERE $linkTo = :$linkTo");
            }
        }

        //relacionar tres tablas
        if(count($relArray) == 3 && count($typeArray) == 3){

            $on1a =$relArray[0].".id_".$typeArray[1]."_".$typeArray[0];
            $on1b =$relArray[1].".id_".$typeArray[1];

            $on2a =$relArray[0].".id_".$typeArray[2]."_".$typeArray[0];
            $on2b =$relArray[2].".id_".$typeArray[2];
            if ($orderBy != null && $orderMode != null && $startAt == null && $endAt == null){
                $stmt = Connection::connect()->prepare("SELECT * FROM $relArray[0] inner join $relArray[1] ON $on1a = $on1b INNER JOIN
                    $relArray[2] ON $on2a = $on2b WHERE $linkTo = :$linkTo ORDER BY $orderBy $orderMode");
            }elseif($orderBy != null && $orderMode != null && $startAt != null && $endAt != null){
                $stmt = Connection::connect()->prepare("SELECT * FROM $relArray[0] inner join $relArray[1] ON $on1a = $on1b INNER JOIN
                    $relArray[2] ON $on2a = $on2b WHERE $linkTo = :$linkTo ORDER BY $orderBy $orderMode LIMIT $startAt, $endAt");
            }
            else{
                $stmt = Connection::connect()->prepare("SELECT * FROM $relArray[0] inner join $relArray[1] ON $on1a = $on1b INNER JOIN
                    $relArray[2] ON $on2a = $on2b WHERE $linkTo = :$linkTo");
            }

        }
        //relacionar cuatro tablas
        if(count($relArray) == 4 && count($typeArray) == 4){

            $on1a =$relArray[0].".id_".$typeArray[1]."_".$typeArray[0];
            $on1b =$relArray[1].".id_".$typeArray[1];

            $on2a =$relArray[0].".id_".$typeArray[2]."_".$typeArray[0];
            $on2b =$relArray[2].".id_".$typeArray[2];

            $on3a =$relArray[0].".id_".$typeArray[3]."_".$typeArray[0];
            $on3b =$relArray[3].".id_".$typeArray[3];
            if ($orderBy != null && $orderMode != null && $startAt == null && $endAt == null){
                $stmt = Connection::connect()->prepare("SELECT * FROM $relArray[0] inner join $relArray[1] ON $on1a = $on1b INNER JOIN
                    $relArray[2] ON $on2a = $on2b INNER JOIN $relArray[3] ON $on3a = $on3b WHERE $linkTo = :$linkTo ORDER BY $orderBy $orderMode");

            }elseif($orderBy != null && $orderMode != null && $startAt != null && $endAt != null){
                $stmt = Connection::connect()->prepare("SELECT * FROM $relArray[0] inner join $relArray[1] ON $on1a = $on1b INNER JOIN
                    $relArray[2] ON $on2a = $on2b INNER JOIN $relArray[3] ON $on3a = $on3b WHERE $linkTo = :$linkTo ORDER BY $orderBy $orderMode LIMIT $startAt, $endAt");
            }
            else{
                $stmt = Connection::connect()->prepare("SELECT * FROM $relArray[0] inner join $relArray[1] ON $on1a = $on1b INNER JOIN
                    $relArray[2] ON $on2a = $on2b INNER JOIN $relArray[3] ON $on3a = $on3b WHERE $linkTo = :$linkTo");

            }
        }
        $stmt -> bindParam(":".$linkTo,$equalTo, PDO::PARAM_STR);

        $stmt -> execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS);

    }

    //peticiones GET para el buscador
    static public function getSearchData($table, $linkTo,$search,$orderBy, $orderMode,$startAt,$endAt){
        if ($orderBy != null && $orderMode != null && $startAt == null && $endAt == null){
            $stmt = Connection::connect()->prepare("SELECT * FROM $table WHERE $linkTo LIKE '%$search%' ORDER BY $orderBy $orderMode");
        }elseif($orderBy != null && $orderMode != null && $startAt != null && $endAt != null){
            $stmt = Connection::connect()->prepare("SELECT * FROM $table WHERE $linkTo LIKE '%$search%' ORDER BY $orderBy $orderMode LIMIT $startAt, $endAt");
        }
        else{
            $stmt = Connection::connect()->prepare("SELECT * FROM $table WHERE $linkTo LIKE '%$search%'");
        }
        $stmt -> execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }


}
