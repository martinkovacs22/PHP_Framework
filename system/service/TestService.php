<?php

namespace service;

require_once __DIR__ . '\..\..\Autoloader.php';

use model\TestModel;
use config\JWThandler;



class TestService 
{
    public static function testFun($data)
    {
        $data["password"] = hash("sha256", $data["password"]);
        $arr = array(
            "email"=>$data["email"],
            "password"=>$data["password"]
        );
        $JWTData = TestModel::CallProcedure($arr, "login");
        return array("err" => $JWTData["err"], "JWT" => JWThandler::generateJWT($JWTData), "data" => $JWTData["data"]);
    }

}
