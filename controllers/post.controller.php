<?php
use Firebase\JWT\JWT;

class PostController{
    //Petición para tomar nombre de las columnas

    static public function getColumnsData($table, $database){
        $response = PostModel::getColumnsData($table, $database);

        return $response;
    }
    //Petición Post para crear datos

    public function postData($table,$data){
        $response = PostModel::postData($table,$data);

        $return = new PostController();
        $return->fncResponse($response,"postData",null);
    }

    //Peticion post para registro de usuarios

    public function postRegister($table,$data){
        if (isset($data["password_user"]) && $data["password_user"] != null){
            $crypt = crypt($data["password_user"], '$2a$07$azsdrtgmnhgpmv652126$');
            $data["password_user"] = $crypt;

            $response = PostModel::postData($table,$data);

            $return = new PostController();
            $return->fncResponse($response,"postData",null);
        }
    }

    //Peticion post para login de usuarios
    public function postLogin($table,$data){
        $response = GetModel::getFilterData($table,"email_user",$data["email_user"],null,null,null,null);
        if(!empty($response)){
            //encriptamos la contraseña
            $crypt = crypt($data["password_user"], '$2a$07$azsdrtgmnhgpmv652126$');
            if($response[0]->password_user == $crypt){

                //Creación de JWT
                $time = time();
                $key = "asdwrudhf574163987sdfsdf";
                $algoritmo = 'HS256'; //Tipo de algoritmo de JWT
                $token = array(
                    "iat" => $time, //tiempo que inició el token
                    "exp" => $time + (60*60*24), //Tiempo que espirará el token (+1 dia)
                    "data" => [
                        "id" => $response[0]->id_user,
                        "email" => $response[0]->email_user
                    ]
                );
                $jwt = JWT::encode($token,$key,$algoritmo);

                //Actualizamos la base datos con el Token del usuario
                $data = array(
                    "token_user" => $jwt,
                    "token_exp_user" => $token['exp']
                );
                $update = PutModel::putData($table, $data,$response[0]->id_user, "id_user");

               if($update == "The process was successful"){
                   $response[0]->token_user = $jwt;
                   $response[0]->token_exp_user = $token['exp'];
                   $return = new PostController();
                   $return->fncResponse($response,"postLogin",null);
               }
            }else{
                $response = null;
                $return = new PostController();
                $return->fncResponse($response,"postLogin","Wrong password");
            }
        }else{
            $response = null;
            $return = new PostController();
            $return->fncResponse($response,"postLogin","Wrong email");
        }
    }

    //respuesta del controlador
    public function fncResponse($response, $method, $error){
        if (!empty($response)){
            //Quitamos la contraseña de la respuesta
            if(isset($response[0]->password_user)){
                unset($response[0]->password_user);
            }
            $json = array(
                "status" => 200,
                "results" => $response
            );
        }else{
            if($error != null){
                $json = array(
                    "status" => 404,
                    "results" => $error
                );
            }else{
                $json = array(
                    "status" => 404,
                    "results" => "Not Found",
                    "method" =>$method
                );
            }
        }


        echo json_encode($json, http_response_code($json['status']));
    }
}
