<?php
class GetController{
    //Peticiones GET sin filtro

    public function getData($table){

        $response = GetModel::getData($table);

        if (!empty($response)){
            $json = array(
                "status" => 200,
                "total" => count($response),
                "result" => $response
            );
        }else{
            $json = array(
                "status" => 404,
                "result" => "Not found"
            );
        }


        echo json_encode($json, http_response_code($json['status']));
    }

    //Peticiones GET con filtro
    public function getFilterData($table,$linkTo,$equalTo){

        $response = GetModel::getFilterData($table,$linkTo,$equalTo);

        if (!empty($response)){
            $json = array(
                "status" => 200,
                "total" => count($response),
                "result" => $response
            );
        }else{
            $json = array(
                "status" => 404,
                "result" => "Not found"
            );
        }


        echo json_encode($json, http_response_code($json['status']));
    }


}