<?php

namespace controller;

require_once __DIR__. '\..\..\Autoloader.php';



use config\Req;
use config\Res;
use config\HttpStatus;
use service\TestService;
use Config\Base64;
/*Login #DONE, Reg #DONE, JWTValidate #DONE, Follow #TODO, Profile update #DONE, Messages #TODO, Post Notifications #TODO, Likes #TODO*/

class TestController{

static public Res $res;

static function Test(){
    self::$res = new Res();
    $serviceData = TestService::testFun(Req::getReqBody());
    self::$res->setBody($serviceData);
    $serviceData["err"] ? self::$res->setStatus_code(HttpStatus::INTERNAL_SERVER_ERROR) : self::$res->setStatus_code(HttpStatus::OK);
    self::$res->send();

}

static function Base64Match(){

    $base64Code = Req::getReqBody()["base64"];

    $base64Class = new Base64($base64Class,true);


}


}
