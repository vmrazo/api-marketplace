<?php

$routesArray =explode("/",$_SERVER['REQUEST_URI']);
$routesArray = array_filter($routesArray);

/*Cuando no se hace ninguna petición a la API*/

if (count($routesArray) == 0){
    $json = array(
        "status" => 404,
        "result" => "Not found"
    );

    echo json_encode($json, http_response_code($json['status']));

    return;
}else {

    //Peticiones GET
    if (count($routesArray) == 1 && isset($_SERVER["REQUEST_METHOD"]) &&
        $_SERVER["REQUEST_METHOD"] == "GET") {

        //Peticiones GET con filtro
        if (isset($_GET["linkTo"]) && isset($_GET["equalTo"]) && !isset($_GET["rel"]) && !isset($_GET["type"])) {
        //Preguntamos si vienen variables de orden
            if (isset($_GET['orderBy']) && isset($_GET['orderMode'])){
                $orderBy = $_GET['orderBy'];
                $orderMode = $_GET['orderMode'];
            }else{
                $orderBy = null;
                $orderMode = null;
            }
            //Preguntamos si vienen variables de limite
            if (isset($_GET['startAt']) && isset($_GET['endAt'])){
                $startAt = $_GET['startAt'];
                $endAt = $_GET['endAt'];
            }else{
                $startAt = null;
                $endAt = null;
            }
            $response = new GetController();
            $response->getFilterData(explode("?", $routesArray[1])[0], $_GET["linkTo"], $_GET["equalTo"],$orderBy,$orderMode,$startAt,$endAt);

            //Peticiones GET entre tablas relacionadas sin filtro
        } else if (isset($_GET["rel"]) && isset($_GET["type"]) && explode("?", $routesArray[1])[0] == "relations" && !isset($_GET["linkTo"]) && !isset($_GET["equalTo"])) {
            //Preguntamos si vienen variables de orden
            if (isset($_GET['orderBy']) && isset($_GET['orderMode'])){
                $orderBy = $_GET['orderBy'];
                $orderMode = $_GET['orderMode'];
            }else{
                $orderBy = null;
                $orderMode = null;
            }
            //Preguntamos si vienen variables de limite
            if (isset($_GET['startAt']) && isset($_GET['endAt'])){
                $startAt = $_GET['startAt'];
                $endAt = $_GET['endAt'];
            }else{
                $startAt = null;
                $endAt = null;
            }
            $response = new GetController();
            $response->getRelData($_GET["rel"], $_GET["type"],$orderBy,$orderMode,$startAt,$endAt);

        }
        //Peticiones GET entre tablas relacionadas con filtro

        else if (isset($_GET["rel"]) && isset($_GET["type"]) && explode("?", $routesArray[1])[0] == "relations" &&
        isset($_GET["linkTo"]) && isset($_GET["equalTo"])) {
            //Preguntamos si vienen variables de orden
            if (isset($_GET['orderBy']) && isset($_GET['orderMode'])){
                $orderBy = $_GET['orderBy'];
                $orderMode = $_GET['orderMode'];
            }else{
                $orderBy = null;
                $orderMode = null;
            }
            //Preguntamos si vienen variables de limite
            if (isset($_GET['startAt']) && isset($_GET['endAt'])){
                $startAt = $_GET['startAt'];
                $endAt = $_GET['endAt'];
            }else{
                $startAt = null;
                $endAt = null;
            }
        $response = new GetController();
        $response->getRelFilterData($_GET["rel"], $_GET["type"], $_GET["linkTo"], $_GET["equalTo"],$orderBy,$orderMode,$startAt,$endAt);

        //Peticiones GET para el buscador

    }elseif(isset($_GET["linkTo"])&& isset($_GET["search"])){
            //Preguntamos si vienen variables de orden
            if (isset($_GET['orderBy']) && isset($_GET['orderMode'])){
                $orderBy = $_GET['orderBy'];
                $orderMode = $_GET['orderMode'];
            }else{
                $orderBy = null;
                $orderMode = null;
            }
            //Preguntamos si vienen variables de limite
            if (isset($_GET['startAt']) && isset($_GET['endAt'])){
                $startAt = $_GET['startAt'];
                $endAt = $_GET['endAt'];
            }else{
                $startAt = null;
                $endAt = null;
            }

            $response = new GetController();
            $response->getSearchData(explode("?", $routesArray[1])[0], $_GET["linkTo"], $_GET["search"],$orderBy,$orderMode,$startAt,$endAt);
        }
        //Peticiones GET sin filtro
        else {

        //Preguntamos si vienen variables de orden
            if (isset($_GET['orderBy']) && isset($_GET['orderMode'])){
                $orderBy = $_GET['orderBy'];
                $orderMode = $_GET['orderMode'];
            }else{
                $orderBy = null;
                $orderMode = null;
            }
            //Preguntamos si vienen variables de limite

            if (isset($_GET['startAt']) && isset($_GET['endAt'])){
                $startAt = $_GET['startAt'];
                $endAt = $_GET['endAt'];
            }else{
                $startAt = null;
                $endAt = null;
            }
        $response = new GetController();
        $response->getData(explode("?",$routesArray[1])[0],$orderBy,$orderMode,$startAt,$endAt);
    }

    }


    //Peticiones POST

    if (count($routesArray) == 1 && isset($_SERVER["REQUEST_METHOD"]) &&
        $_SERVER["REQUEST_METHOD"] == "POST") {

        //traemos el listado de columnas de la tabla a cambiar

        $columns = array();

        $database = RoutesController::database();

        $response = PostController::getColumnsData(explode("?", $routesArray[1])[0],$database);

        foreach($response as $key => $value){
            array_push($columns, $value->item);
        }

        //Quitamos el primer y ultimo indice
        array_shift($columns);
        array_pop($columns);


        //Recibimos los valores post
        if (isset($_POST)) {
            //Validamos que las variables POST coincidan con los nombres de las columnas de la base de datos
            $count = 0;
            foreach($columns as $key => $value){
               if (array_keys($_POST)[$key] == $value) {
                    $count++;
               }else{
                   $json = array(
                       "status" => 404,
                       "result" => "Error: Fields in the form do not match the database"
                   );

                   echo json_encode($json, http_response_code($json['status']));

                   return;
               }
            }

            if($count == count($columns)){
                //Solicitamos respuesta del controlador para crear datos en cualquier tabla
                $response = new PostController();
                $response->postData(explode("?", $routesArray[1])[0], $_POST);
            }
        }
    }


    //Peticiones PUT
    if (count($routesArray) == 1 && isset($_SERVER["REQUEST_METHOD"]) &&
        $_SERVER["REQUEST_METHOD"] == "PUT") {

        //Preguntamos si viene ID
        if(isset($_GET["id"]) && isset($_GET["nameId"])) {
            //Validamos que exista el ID
            $table = explode("?", $routesArray[1])[0];
            $linkTo = $_GET["nameId"];
            $equalTo = $_GET["id"];
            $orderBy = null;
            $orderMode = null;
            $startAt = null;
            $endAt = null;


            $response = PutController::getFilterData($table,$linkTo,$equalTo,$orderBy, $orderMode,$startAt,$endAt);
            if($response){
                //Capturamos los datos del formulario

                $data = array();
                parse_str(file_get_contents('php://input'), $data);
                //traemos el listado de columnas de la tabla a cambiar
                $columns = array();
                $database = RoutesController::database();
                $response = PostController::getColumnsData(explode("?", $routesArray[1])[0],$database);
                foreach($response as $key => $value){
                    array_push($columns, $value->item);
                }

                //Quitamos el primer y ultimo indice
                array_shift($columns);
                array_pop($columns);
                array_pop($columns);

                //Validamos que loc campos PUT coincidan con los nombres de columnas de la base de datos
                $count = 0;
                foreach (array_keys($data) as $value){
                    $count = array_search($value, $columns);
                }
                if ($count > 0){
                    //Solicitamos respuesta del controlador para editar cualquier tabla

                    $response = new PutController();
                    $response->putData(explode("?", $routesArray[1])[0],$data, $_GET["id"],$_GET["nameId"]);
                }else{
                    $json = array(
                        'status' => 400,
                        'results' => "Error: Fields in the form do not match the database"
                    );
                    echo json_encode($json, http_response_code($json["status"]));
                    return;
                }
                return;

            }else{
                $json = array(
                    'status' => 400,
                    'results' => "Error: The Id is not found in the database"
                );
                echo json_encode($json, http_response_code($json['status']));
                return;
            }

        }
    }

    //Peticiones DELETE
    if (count($routesArray) == 1 && isset($_SERVER["REQUEST_METHOD"]) &&
        $_SERVER["REQUEST_METHOD"] == "DELETE") {
        $json = array(
            "status" => 200,
            "result" => "DELETE"
        );

        echo json_encode($json, http_response_code($json['status']));

        return;
    }
}




