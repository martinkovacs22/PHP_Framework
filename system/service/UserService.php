<?php

namespace service;

require_once __DIR__ . '\..\..\Autoloader.php';

use model\UserModel;
use config\JWThandler;
use config\Exception;
use config\HttpStatus;
use config\Req;


class UserService 
{
    public static function login($data)
    {
        $data["password"] = hash("sha256", $data["password"]);
        $arr = array(
            "email"=>$data["email"],
            "password"=>$data["password"]
        );
        $JWTData = UserModel::CallProcedure($arr, "login");
        return array("err" => $JWTData["err"], "JWT" => JWThandler::generateJWT($JWTData), "data" => $JWTData["data"]);
    }

    public static function sign($data)
    {
        if (!isset($data["email"])) {
            $data["email"] = null;
        } else {
            $email = $data["email"];


            if (preg_match('/@.*\.(com|hu)$/', $email)) {

                $data["email"] = $email;
            } else {
                return array("err" => true, "data" => "Wrong Email");
            }
        }
        if (!isset($data["password"])) {
            return array("err" => true, "data" => "Wrong Password");
        } else {
            $password = $data["password"];
            if (preg_match('/^(?=.*[a-z])(?=.*[A-Z]).{8,}$/', $password)) {
                $data["password"] = hash("sha256", $data["password"]);
            } else {
                return array("err" => true, "data" => "Wrong Password");
            }
        }
        if (!isset($data["username"])) {
            return array("err" => true, "data" => "Wrong Username");
        } else {
            $username = $data["username"];
            if (preg_match('/^[a-zA-Z0-9]+$/', $username)) {
                $data["username"] = $username;
            } else {
                return array("err" => true, "data" => "Wrong Username");
            }
        }
        return UserModel::CallProcedure($data, "signup");
    }
    
    public static function getUserById($data)
    {
        if (!isset($data["id"]) ||  !is_int($data["id"])) {
            return array("err" => true, "data" => "Id must not be null.");
        }
        return UserModel::CallProcedure($data, "getUserByID");
    }
    
    public static function getUserByUsername($data)
    {
        return UserModel::CallProcedure($data, "getUserByUsername");
    }
    public static function getUserByUserIdWithOutFriend($data)
    {
        $verifyJWT = JWThandler::verifyJWT(Req::getReqToken());
        if(isset($verifyJWT["data"]["data"][0]["id"])){
            $sendData = ["userId"=>$verifyJWT["data"]["data"][0]["id"]];
            return UserModel::CallProcedure($sendData, 'getUserByUserIdWithOutFriend');
        }else{
            Exception::msg(["err"=>true,"data"=>"UnEx Token"],HttpStatus::EXPECTATION_FAILED);
        }   
    }
    
    public static function userUpdate($data)
    {
        $errors = array();

        // Ellenőrizze az "id"-t
        if (!isset($data["id"])) {
            return array("err" => true, "data" => "Id must not be null.");
        }

        // Ellenőrizze a "username"-t
        if (isset($data["username"])) {
            $username = $data["username"];

            if (!preg_match('/^[a-zA-Z0-9]+$/', $username)) {
                $errors[] = "Wrong Username";
            }
        }

        // Ellenőrizze a "password"-t
        if (isset($data["password"])) {
            $password = $data["password"];

            if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z]).{8,}$/', $password)) {
                $errors[] = "Wrong Password";
            } else {
                $data["password"] = hash("sha256", $password);
            }
        }

        // Ellenőrizze az "email"-t
        if (isset($data["email"])) {
            $email = $data["email"];

            if (!preg_match('/@.*\.(com|hu)$/', $email)) {
                $errors[] = "Wrong Email";
            }
        }

        // Kezelje a többi mezőt
        $fields = ["username", "email", "password" ,"bio","profilePicture","level"];

        foreach ($fields as $field) {
            $data[$field] = isset($data[$field]) ? $data[$field] : null;
        }

        // Ha vannak hibák, térjen vissza velük
        if (!empty($errors)) {
            return array("err" => true, "data" => implode(", ", $errors));
        }

        // Minden rendben, hívja meg a userUpdate tárolt eljárást
        return UserModel::CallProcedure($data, "userUpdate");
    }
    
    public static function getUserMessages($data){
        // if (!isset($data["id"])) {
        //     Exception::msg(array("err" => true, "data" => "No messages found for this user."));
        //     return 0;
        // }
            $verifyJWT = JWThandler::verifyJWT(Req::getReqToken());
            if(isset($verifyJWT["data"]["data"][0]["id"])){
                $sendData = ["userId"=>$verifyJWT["data"]["data"][0]["id"],"friendId"=>$data["receiverId"]];
return UserModel::CallProcedure($sendData, 'getAllMessagesById');
            }else{
                Exception::msg(["err"=>true,"data"=>"UnEx Token"],HttpStatus::EXPECTATION_FAILED);
            }


        
    }
    public static function createMessage($data){

        $verifyJWT = JWThandler::verifyJWT(Req::getReqToken());
            if(isset($verifyJWT["data"]["data"][0]["id"])){
                $sendData = ["userId"=>$verifyJWT["data"]["data"][0]["id"],"friendId"=>$data["receiverId"],"text"=>$data["text"]];
return UserModel::CallProcedure($sendData, 'createMessage');
            }else{
                Exception::msg(["err"=>true,"data"=>"UnEx Token"],HttpStatus::EXPECTATION_FAILED);
            }

    }
    public static function deleteMessage($data){

        $verifyJWT = JWThandler::verifyJWT(Req::getReqToken());
            if(isset($verifyJWT["data"]["data"][0]["id"])){
                $sendData = ["userId"=>$verifyJWT["data"]["data"][0]["id"],"messageId"=>$data["messageId"]];
return UserModel::CallProcedure($sendData, 'deleteMessage');
            }else{
                Exception::msg(["err"=>true,"data"=>"UnEx Token"],HttpStatus::EXPECTATION_FAILED);
            }

    }
    public static function updateMessage($data){

        $verifyJWT = JWThandler::verifyJWT(Req::getReqToken());
            if(isset($verifyJWT["data"]["data"][0]["id"])){
                $sendData = ["messageId"=>$data["messageId"],"newText"=>$data["newText"],"userId"=>$verifyJWT["data"]["data"][0]["id"]];
return UserModel::CallProcedure($sendData, 'updateMessage');
            }else{
                Exception::msg(["err"=>true,"data"=>"UnEx Token"],HttpStatus::EXPECTATION_FAILED);
            }

    }

    public static function JWTValidate($JWT)
    {
        $verifyJWT = JWThandler::verifyJWT($JWT);
        if ($verifyJWT) {
            $newJWT = JWThandler::generateJWT($verifyJWT["data"]);
            $arr = array("err" => false, "JWT" => $newJWT,"newJWT"=>JWThandler::verifyJWT($newJWT));
            return $arr;
        } else {
            Exception::msg(array("err" => true, "data" => "Unexpected error."));
        }
    }
    public static function getProfilUser()
    {
        $verifyJWT = JWThandler::verifyJWT(Req::getReqToken());
        
        if ($verifyJWT["data"]["data"][0]["id"]) {
            return UserModel::CallProcedure(array("userId"=>$verifyJWT["data"]["data"][0]["id"]), 'getProfilUser');
        }
        else{
            Exception::msg(array("err"=>true,"data"=>"Null"));
        }

    }
    public static function getFollowByUserId()
    {
        $verifyJWT = JWThandler::verifyJWT(Req::getReqToken());
        
        if (isset($verifyJWT["data"]["data"][0]["id"])) {
            return UserModel::CallProcedure(array("userId"=>$verifyJWT["data"]["data"][0]["id"]), 'getFollowByUserId');
        }
        else{
            Exception::msg(array("err"=>true,"data"=>"Null"));
        }

    }
    public static function getFollowerByUserId()
    {
        $verifyJWT = JWThandler::verifyJWT(Req::getReqToken());
        
        if (isset($verifyJWT["data"]["data"][0]["id"])) {
            return UserModel::CallProcedure(array("userId"=>$verifyJWT["data"]["data"][0]["id"]), 'getFollowerByUserId');
        }
        else{
            Exception::msg(array("err"=>true,"data"=>"Null"));
        }

    }

    public static function createFollow(array $bodyValue ){

        $verifyJWT = JWThandler::verifyJWT(Req::getReqToken());
        
        if (isset($verifyJWT["data"]["data"][0]["id"])) {
            return UserModel::CallProcedure(array("follow"=>$bodyValue["follow"],"follower"=>$verifyJWT["data"]["data"][0]["id"]), 'createFollow');
        }
        else{
            Exception::msg(array("err"=>true,"data"=>"Not Valid Data"));
        }

    
    }
    public static function getTopBlogger(){
        return UserModel::CallProcedure(array(),"getTopBlogger");
    }

    static public function validator(array $data):bool|array{
        return true;
    }
    static public function getNotificationById(){

        $verifyJWT = JWThandler::verifyJWT(Req::getReqToken());
       
        if (isset($verifyJWT["data"]["data"][0]["id"])) {
            
            $returnData =  UserModel::CallProcedure(array("userId"=>$verifyJWT["data"]["data"][0]["id"]), 'getNotificationById');
            return $returnData;
        }
        else{
            Exception::msg(array("err"=>true,"data"=>"Null"));
        }

    }
    static public function selectedNotification(array $data){

        $verifyJWT = JWThandler::verifyJWT(Req::getReqToken());
       
        if (isset($verifyJWT["data"]["data"][0]["id"])) {
            
            $returnData =  UserModel::CallProcedure(array("userId"=>$verifyJWT["data"]["data"][0]["id"],"notificationId"=>$data["notificationId"]), 'selectedNotification');
            return $returnData;
        }
        else{
            Exception::msg(array("err"=>true,"data"=>"Null"));
        }

    }
    static public function getAllFriend(){

        $verifyJWT = JWThandler::verifyJWT(Req::getReqToken());

        if (isset($verifyJWT["data"]["data"][0]["id"])) {
            return UserModel::CallProcedure(array("userId"=>$verifyJWT["data"]["data"][0]["id"]), 'getAllFriend');
        }
        else{
            Exception::msg(array("err"=>true,"data"=>"Null"));
        }

    }
}
