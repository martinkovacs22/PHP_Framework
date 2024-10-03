<?php

namespace Config;

// Autoload.php fájl importálása a külső függőségek betöltéséhez

require_once(__DIR__.'\..\..\vendor\autoload.php');

// Szükséges osztályok importálása
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
// JWTHandler osztály definiálása
class JWThandler{
    // Titkos kulcs az aláíráshoz
    private static  $secret = "JWT";
    // JWT generálása a kapott felhasználói adatok alapján
    public static function generateJWT($userData) {
        $currentTime = time();
        $expirationTime = $currentTime + 3600;

        $payload = array(
            "data" => $userData,
            "exp" => $expirationTime
        );

        $token = JWT::encode($payload, self::$secret, 'HS256');
        return $token;
    }
    // JWT ellenőrzése és dekódolása
    public static function verifyJWT($token) {
        try {
            // JWT dekódolás: token, kulcs, algoritmus
            $decoded = (array) JWT::decode($token, new Key(self::$secret, 'HS256'));
            
            // Rekurzív átalakítás: minden objektumot tömbbé alakítunk át
            $decodedArray = self::objectToArray($decoded);
            
            return $decodedArray;
        } catch (Exception $e) {
            // Hiba esetén false visszaadása
            return false;
        }
    }
    
    // Rekurzív függvény objektumok tömbbé alakítására
    private static function objectToArray($obj) {
        if (is_object($obj)) {
            $obj = (array) $obj;
        }
        if (is_array($obj)) {
            foreach ($obj as &$value) {
                $value = self::objectToArray($value);
            }
        }
        return $obj;
    }
    
    
}

