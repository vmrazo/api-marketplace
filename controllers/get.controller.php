<?php
class GetController{
    //Peticiones GET sin filtro

    public function getData($table, $orderBy, $orderMode, $startAt, $endAt){

        $response = GetModel::getData($table,$orderBy,$orderMode,$startAt,$endAt);

        $return = new GetController();
        $return-> fncResponse($response,"getData");
    }

    //Peticiones GET con filtro
    static function getFilterData($table,$linkTo,$equalTo,$orderBy, $orderMode,$startAt,$endAt){ //Le tuve que cambiar a static para que la funciones put funcionen

        $response = GetModel::getFilterData($table,$linkTo,$equalTo,$orderBy, $orderMode,$startAt,$endAt);

        $return = new GetController();
        $return-> fncResponse($response,"getFilterData");
    }


//Peticiones GET entre tablas relacionadas sin filtro
    public function getRelData($rel,$type,$orderBy, $orderMode,$startAt,$endAt){
        $response = GetModel::getRelData($rel,$type,$orderBy, $orderMode,$startAt,$endAt);



        $return = new GetController();
        $return-> fncResponse($response,"getRelData");
    }

    //Peticiones GET entre tablas relacionadas con filtro
    public function getRelFilterData($rel,$type,$linkTo,$equalTo,$orderBy,$orderMode,$startAt,$endAt){
        $response = GetModel::getRelFilterData($rel,$type,$linkTo,$equalTo,$orderBy, $orderMode,$startAt,$endAt);


        $return = new GetController();
        $return-> fncResponse($response,"getRelFilterData");
    }

    //Peticiones GET para el buscador
    public function getSearchData($table,$linkTo,$search,$orderBy, $orderMode,$startAt,$endAt){


        $response = GetModel::getSearchData($table,$linkTo,$search,$orderBy, $orderMode,$startAt,$endAt);

        $return = new GetController();
        $return-> fncResponse($response,"getSearchData");
    }

    //Peticiones GET para el buscador entre tablas relacionadas
    public function getSearchRelData($rel,$type,$linkTo,$search,$orderBy,$orderMode,$startAt,$endAt){

        $response = GetModel::getSearchRelData($rel,$type,$linkTo,$search,$orderBy, $orderMode,$startAt,$endAt);
        $return = new GetController();
        $return-> fncResponse($response,"getSearchRelData");
    }
        //respuesta del controlador
    public function fncResponse($response, $method){
        if (!empty($response)){
            $json = array(
                "status" => 200,
                "total" => count($response),
                "results" => $response
            );
        }else{
            $json = array(
                "status" => 404,
                "results" => "Not found",
                "method" =>$method
            );
        }


        echo json_encode($json, http_response_code($json['status']));
    }
}