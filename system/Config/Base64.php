<?php

namespace Config;

class Base64{
    private String $fileName;
    private String $data;
    private String $extension;
    private String $path;

    public function getFileName(){return $this->fileName;}
    public function setFileName($value){$this->fileName = $value;}
    public function getDate(){return $this->date;}
    public function setDate($value){$this->date = $value;}
    public function getExtension(){return $this->extension;}
    public function setExtension($value){$this->extension = $value;}
    public function getPath(){return $this->path;}
    public function setPath($value){$this->path = $value;}

    public function __constructSimple($base64,$true){
        print_r(getMimeTypeFromBase64($base64));
    }

    public function __construct(Array $value){
        setFileName($value["fileName"]);
        setDate($value["date"]);
        setExtension($value["extension"]);
        setPath($value["value"]);
    }

    public function __constructFromBase64(string $base64Code,$newPath = null) {
        $this->data = $base64Code;

        // Feltételezzük, hogy a fájl típusa a base64 stringből kinyerhető
        $info = explode(',', $base64Code);
        if (count($info) > 1) {
            $meta = explode(';', $info[0]);
            if (count($meta) > 0) {
                $this->extension = str_replace(getMimeTypeFromBase64($base64Code), '', $meta[0]); // Kép formátum kinyerése
                $this->fileName = 'image.' . $this->extension; // Fájlnév generálása
                // További információk, mint a path, ha szükséges, itt beállíthatók
                $this->path = $newPath; // Ha van path, azt beállíthatod
            }
        }
    }

    function getMimeTypeFromBase64($base64String) {
        // Ellenőrizzük, hogy a string tartalmaz-e a "data:" részt
        if (preg_match('/^data:([^;]+);base64,/', $base64String, $matches)) {
            // Visszaadjuk a MIME típust (pl. "image/png", "application/vnd.openxmlformats-officedocument.wordprocessingml.document" stb.)
            return $matches[1];
        }
        return null; // Ha nem található, null-t adunk vissza
    }

}

?>