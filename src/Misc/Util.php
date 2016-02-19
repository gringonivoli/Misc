<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 20/04/15
 * Time: 10:55
 */

namespace Misc;

/**
 * Trait Util
 *
 * @author Maxi Nivoli <m_nivoli@hotmail.com>
 * @package Misc
 */
trait Util {

    /**
     * Format Date
     *
     * Formatea una fecha pasada en TIEMSTAMP, obtenida
     * desde una DB MySQL.
     *
     * @param $date
     * @param string $format
     * @param string $timeZone
     * @return bool|string
     */
    public function formatDate($date, $format = 'd/m/Y', $timeZone = 'America/Argentina/Cordoba'){
        date_default_timezone_set($timeZone);
        return date($format, strtotime($date));
    }

    /**
     * Build Spinal String
     *
     * Retorna una string en la que se convierte todo a
     * minúscula, se reemplazan los espacios por guiones
     * medios y los acentos o caracteres especiales por los
     * que correspondan, especial para usar en urls.
     *
     * @param $title
     * @return mixed
     */
    public function buildSpinalString($title){
        return str_replace(" ", "-", $this->cleanString(strtolower($title)));
    }

    /**
     * Clean String
     *
     * Limpia de acentos y caracteres especiales
     * una string.
     *
     * @param string $string
     * @return mixed|string
     */
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

    /**
     * Get Timestamp
     *
     * Retorna la marca de tiempo para una fecha
     * pasada. (ej.: d-m-y para formato latinoamericano,
     * m/d/y para norteamericano).
     *
     * @param string $str
     * @param string $timeZone
     * @return int
     */
    public function getTimestamp($str = '', $timeZone = 'America/Argentina/Cordoba'){
        date_default_timezone_set($timeZone);
        return strtotime($str);
    }

    /**
     * Get Current Timestamp
     *
     * Retorna el timestamp actual en formato adecuado para
     * ser guardado en una DB MySQL.
     *
     * @param string $timeZone
     * @return bool|string
     */
    public function getCurrentTimestamp($timeZone = 'America/Argentina/Cordoba'){
        date_default_timezone_set($timeZone);
        return date("Y-m-d H:i:s", time());
    }

    /**
     * getTimestampForMySQL
     *
     * Pasandole un timestamp generado con php
     * retorna uno adecuado para ser almacenado
     * en una base de datos MySQL.
     *
     * @param $timestamp
     * @param string $timeZone
     * @return bool|string
     */
    public function getTimestampForMySQL($timestamp, $timeZone = 'America/Argentina/Cordoba'){
        date_default_timezone_set($timeZone);
        return date("Y-m-d H:i:s", $timestamp);
    }

    /**
     * Whatever to Camel Case
     *
     * Convierte un string snake_case, spinal-case, o
     * whatever-case a camelCase, toma como referencia
     * el separador de palabras que se define en el objeto
     * SpanArt, antes de la ejecución, da la posibilidad de
     * convertir el primer caracter de la cadena a mayus.
     *
     * @param $string
     * @param string $stringStyle (opciones: '-', '_')
     * @param bool $firstCharCaps
     * @return mixed
     */
    public function whateverToCamelcase($string, $stringStyle = '-' ,$firstCharCaps = false){
        if($firstCharCaps){
            $string[0] = strtoupper($string[0]);
        }
        return preg_replace_callback(
            '/'.$stringStyle.'([a-z])/',
            function($c){
                return strtoupper($c[1]);
            },
            $string
        );
    }

    /**
     * extr
     *
     * Extrae una variable de una clase,
     * verificando previamente que esa variable
     * exista en esa clase, de esta forma se
     * evitan problemas al tratar con objetos
     * creados al vuelo, especialmente creados
     * con la clase stdClass.
     *
     * @param $class
     * @param $property
     * @return null
     */
    public function extr($class, $property){
        return property_exists($class, $property) ? $class->$property : null;
    }
}