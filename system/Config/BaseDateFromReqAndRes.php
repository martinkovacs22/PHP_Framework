<?php

namespace Config;
use Config\Base64;

abstract class BaseDateFromReqAndRes{

    private Array $body; 
    private Array $cookie; 
    private Array $header; 
    private String $token;
    private String $fun;
    private Base64 $file;

    public function activeHeader(){
        foreach ($this->header as $key => $value) {
            header($key.":".$value);
        }
    }
    
    public function activeCookie(){
        foreach ($this->cookie as $key => $value) {
            setcookie($key,$value);
        }
    }

    public function getBody(){return $this->body;}
    public function setBody($value){$this->body=$value;}
    public function getCookie(){return $this->cookie;}
    public function setCookie($value){$this->cookie = $value;}
    public function getHeader(){return $this->header;}
    public function setHeader($value){$this->header = $value;}
    public function getToken(){return $this->token;}
    public function setToken($value){$this->token = $value;}
    
    public function addDateToCookie($key,$value){
        $this->cookie[$key] = $value;
    }
} 


?>