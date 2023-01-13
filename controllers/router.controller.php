<?php
class RoutesController{
    //Ruta principal

    public function index(){
        include "routes/route.php";
    }

    //Nombre de la base de datos
    static public function database(){
        return "marketplace";
    }
}
?>
