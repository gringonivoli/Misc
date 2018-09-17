<?php

namespace Misc;

use Exception;
use finfo;
use RuntimeException;

/**
 * Class Upload
 *
 * Clase para subir ficheros, aunque esta primera implementacion
 * esta basada en imagenes mas que nada.
 */

class Upload {#IMPLEMENTAR LA VERIFICACION DE SI SE PUEDE ESCRIBIR EN EL DIRECTORIO.
    private $maxSize = 15728640;
    private $imgMime = array("jpg" => "image/jpeg", "png" => "image/png", "gif" => "image/gif");
    private $error;
    private $tmpFile;
    private $fileSize;
    private $targetDir;
    private $fileName;
    private $file;

    /**
     * Construct
     *
     * Construye un objeto upload y setea las propiedades necesarias
     * para verificar el fichero y guardarlo.
     *
     * @param string $file ($_FILES['yourfile'])
     * @param string $targetDir (directory)
     * @param $extension (file extension, '.jpg')
     */
    function __construct($file = "", $targetDir = "", $extension = ""){
        if($file != "" && is_array($file)){
            $this->error = $file['error'];
            $this->tmpFile = $file['tmp_name'];
            $this->fileSize = $file['size'];
            $extension = $extension || '.jpg';
            $this->setFileName($file['name'], $extension);
        }
        $this->targetDir = $targetDir;
    }

    public function setNewFile($file = "", $targetDir, $extension = ""){
        if($file != "" && is_array($file)){
            $this->error = $file['error'];
            $this->tmpFile = $file['tmp_name'];
            $this->fileSize = $file['size'];
            $this->setFileName($file['name'], $extension);
        }
        $this->targetDir = $targetDir;
    }

    /**
     * @param string $targetDir
     */
    public function setTargetDir($targetDir){
        $this->targetDir = $targetDir;
    }

    /**
     * Set File Name
     *
     * Crea un nombre unico para el fichero a guardar.
     *
     * @param $name
     * @param $extension
     */
    public function setFileName($name, $extension){
        $name = str_replace(" ", "", $name);
        $name = $this->cleanString($name);
        $fileName = uniqid("", true).basename($name);
        $this->fileName = $fileName.$extension;
    }

    public function setCustomFileName($name){
        $name = str_replace(" ", "", $name);
        $name = $this->cleanString($name);
        $this->fileName = $name;
    }

    /**
     * Is Size Ok
     *
     * Verifica si el peso del fichero no es mayor al permitido
     * en $this->maxSize.
     *
     * @return bool
     */
    public function isSizeOk(){
        $ok = true;
        if($this->fileSize > $this->maxSize){
            $ok = false;
        }
        return $ok;
    }

    /**
     * Is Image
     *
     * Inspecciona un fichero para saber si es del
     * tipo imagen.
     *
     * @return bool
     */
    public function isImage(){
        $ok = true;
        $fileInfo = new finfo(FILEINFO_MIME_TYPE);
        if(false === $ext = array_search($fileInfo->file($this->tmpFile), $this->imgMime, true)){
            $ok = false;
        }
        return $ok;
    }

    /**
     * Find Upload Error
     *
     * Busca y dispara una excepcion si encuetra errores
     * en la subida del fichero.
     *
     */
    public function findUploadError(){
        switch ($this->error) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                throw new RuntimeException('No file sent.');
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                throw new RuntimeException('Exceeded filesize limit.');
            default:
                throw new RuntimeException('Unknown errors.');
        }
    }

    /**
     * Check Input
     *
     * Verifica si la entrada, el fichero a subir, es correcto.
     *
     * @return bool
     */
    public function checkInput(){
        $ok = true;
        if(!isset($this->error) || is_array($this->error)){
            $ok = false;
        }
        return $ok;
    }

    /**
     * Save Compress Image
     *
     * @param int $quality
     */
    public function saveCompressImage($quality = 60){
        $info = getimagesize($this->tmpFile);
        $image = ""; #--> VERIFICAR ALGO? VER!
        $target = $this->targetDir.$this->fileName;
        switch($info['mime']){
            case "image/jpeg":
                $image = imagecreatefromjpeg($this->tmpFile);
                imagejpeg($image, $target, $quality);
                break;
            case "image/gif":
                $image = imagecreatefromgif($this->tmpFile);
                imagejpeg($image, $target, $quality);
                break;
            case "image/png":
                $image = imagecreatefrompng($this->tmpFile);
                imagealphablending($image, false);
                imagesavealpha($image, true);
                imagepng($image, $target);
                break;
        }
    }

    /**
     * Set Max Size
     *
     * @param int $maxSize
     */
    public function setMaxSize($maxSize){
        $this->maxSize = $maxSize;
    }

    /**
     * Set File
     *
     * @param mixed $file
     */
    public function setFile($file){
        $this->file = $file;
    }

    /**
     * Get Target Dir
     *
     * @return string
     */
    public function getTargetDir()
    {
        return $this->targetDir;
    }

    /**
     * Get File Name
     *
     * @return mixed
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     *
     */
    public function makeThumb($src, $dest, $desired_width, $desired_height = 0){
        try{
            /* read the source image */
            $source_image = imagecreatefromjpeg($src);
            $width = imagesx($source_image);
            $height = imagesy($source_image);

            if($desired_height == 0){
                /* find the "desired height" of this thumbnail, relative to the desired width  */
                $desired_height = floor($height * ($desired_width / $width));
            }

            /* create a new, "virtual" image */
            $virtual_image = imagecreatetruecolor($desired_width, $desired_height);

            /* copy source image at a resized size */
            imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);

            /* create the physical thumbnail image to its destination */
            imagejpeg($virtual_image, $dest);
        }catch(Exception $e){

        }
    }

  public function cleanString($string = ''){
    $string = str_replace(array('á','à','â','ã','ª','ä'),"a",$string);
    $string = str_replace(array('Á','À','Â','Ã','Ä'),"A",$string);
    $string = str_replace(array('Í','Ì','Î','Ï'),"I",$string);
    $string = str_replace(array('í','ì','î','ï'),"i",$string);
    $string = str_replace(array('é','è','ê','ë'),"e",$string);
    $string = str_replace(array('É','È','Ê','Ë'),"E",$string);
    $string = str_replace(array('ó','ò','ô','õ','ö','º'),"o",$string);
    $string = str_replace(array('Ó','Ò','Ô','Õ','Ö'),"O",$string);
    $string = str_replace(array('ú','ù','û','ü'),"u",$string);
    $string = str_replace(array('Ú','Ù','Û','Ü'),"U",$string);
    $string = str_replace(array('[','^','´','`','¨','~',']','?','¿'),"",$string);
    $string = str_replace("ç","c",$string);
    $string = str_replace("Ç","C",$string);
    $string = str_replace("ñ","n",$string);
    $string = str_replace("Ñ","N",$string);
    $string = str_replace("Ý","Y",$string);
    $string = str_replace("ý","y",$string);

    $string = str_replace("&aacute;","a",$string);
    $string = str_replace("&Aacute;","A",$string);
    $string = str_replace("&eacute;","e",$string);
    $string = str_replace("&Eacute;","E",$string);
    $string = str_replace("&iacute;","i",$string);
    $string = str_replace("&Iacute;","I",$string);
    $string = str_replace("&oacute;","o",$string);
    $string = str_replace("&Oacute;","O",$string);
    $string = str_replace("&uacute;","u",$string);
    $string = str_replace("&Uacute;","U",$string);
    return $string;
  }
}