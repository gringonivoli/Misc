<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 20/04/15
 * Time: 10:55
 */

namespace Misc;
use stdClass;

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

    /**
     * arrayToObject
     *
     * Convierte un array en un objeto, en caso
     * de que las key del array esten en snake_case o
     * en spinal-case (param-case) se puede pasar el
     * delimitador de palabras para que sea reemplazado
     * y las keys se conviertan en camelCase.
     * Si no se desea que se cambie el estilo de las
     * keys se puede pasar false en el parámetro
     * $strStyleSrc.
     *
     * @param array $array
     * @param bool $object
     * @param string $strStyleSrc
     * @return bool|stdClass
     */
    public function arrayToObject($array, $object = false, $strStyleSrc = '_') {
        if (!is_object($object)){
            $object = new stdClass();
        }
        foreach ($array as $key => $value) {
            if (!($strStyleSrc === false)) {
                $key = $this->whateverToCamelcase($key, $strStyleSrc);
            }
            if (is_array($value)){
                $object->{$key} = new stdClass();
                $this->arrayToObject($value, $object->{$key});
            } else {
                $object->{$key} = $value;
            }
        }

        return $object;
    }

    /**
     * Is Ok
     *
     * Valida un elemento y en caso de ser incorrecto
     * genera una Excepcion.
     *
     * @param $element
     * @param string $msg
     * @throws \Exception
     */
    public function isOk($element, $msg = "Excepción generada desde isOK"){
        if(!$element):
            throw new \Exception($msg);
        endif;
    }

    /**
     * stringToBoolean
     *
     * Convierte la cadena true al
     * valor booleano true y lo mismo
     * con la cadena false.
     *
     * @param $val
     * @return bool
     */
    public function stringToBoolean($val) {
        return ($val === "true");
    }

    /**
     * getHttpUserAgent
     *
     * Si existe la variable retorna
     * el agente de usuario, si no,
     * retorna null.
     *
     * @return null|string
     */
    public function getHttpUserAgent() {
        return array_key_exists("HTTP_USER_AGENT", $_SERVER) ? $_SERVER['HTTP_USER_AGENT'] : null;
    }

    /**
     * getUserIp
     *
     * Retorna la ip del cliente,
     * usuario conectado, si es que esta
     * se encuentra en alguna de las variables
     * del sistema y es válida.
     *
     * @return string
     */
    public function getUserIp() {
        $ipaddress = "UNKNOWN";
        if (array_key_exists("HTTP_CLIENT_IP", $_SERVER)) {
            $ipaddress = $_SERVER["HTTP_CLIENT_IP"];
        }
        else if(array_key_exists("HTTP_X_FORWARDED_FOR", $_SERVER)) {
            $ipaddress = $_SERVER["HTTP_X_FORWARDED_FOR"];
        }
        else if(array_key_exists("HTTP_X_FORWARDED", $_SERVER)) {
            $ipaddress = $_SERVER["HTTP_X_FORWARDED"];
        }
        else if(array_key_exists("HTTP_FORWARDED_FOR", $_SERVER)) {
            $ipaddress = $_SERVER["HTTP_FORWARDED_FOR"];
        }
        else if(array_key_exists("HTTP_FORWARDED", $_SERVER)) {
            $ipaddress = $_SERVER["HTTP_FORWARDED"];
        }
        else if(array_key_exists("REMOTE_ADDR", $_SERVER)) {
            $ipaddress = $_SERVER["REMOTE_ADDR"];
        }

        if (!filter_var($ipaddress, FILTER_VALIDATE_IP)) {
            $ipaddress = "UNKNOWN";
        }

        return $ipaddress;
    }

    /**
     * varErrorLog
     *
     * Manda la salida de la función
     * var_dump al log de errores de
     * php.
     *
     * @param null $object
     */
    public function varErrorLog($object = null) {
        ob_start();
        var_dump($object);
        $contents = ob_get_contents();
        ob_end_clean();
        error_log($contents);
    }

    /**
     * formatNumber
     *
     * wrapper de la función php
     * format_number pero con
     * valores por defecto.
     *
     * @param $number
     * @param int $decimals
     * @param string $decimalPoint
     * @param string $thousandsSep
     * @return string
     */
    public function formatNumber($number, $decimals = 3, $decimalPoint = ",", $thousandsSep = ".") {
        return number_format($number, $decimals, $decimalPoint, $thousandsSep);
    }

    /**
     * numericVal
     *
     * Retorna el float de un
     * valor pasado como primer
     * parámetro que se pueda convertir
     * usando floatval, en caso de que
     * se quieran pasar a float varias
     * propiedades de un objeto, se pasa
     * como primera parámetro el objeto
     * y como segundo las propiedades del
     * mismo que se desean convertir a float.
     *
     * @param $element
     * @param array $properties
     * @return bool|float
     */
    public function numericVal($element, $properties = []) {
        $result = true;
        if (count($properties)) {
            foreach ($properties as $value) {
                $element->{$value} = floatval($element->{$value});
            }
        } else if (!is_object($element)) {
            $result = floatval($element);
        }
        return $result;
    }

    /**
     * customArrayMerge
     *
     * Reemplaza el valor de las claves
     * de source en las claves identicas de
     * destination, destination es pasado
     * por referencia, por lo que ya queda modificado.
     *
     * @param $source
     * @param $destination
     */
    public function customArrayMerge($source, & $destination) {
        if (is_array($source) && (is_array($destination) || is_object($destination))) {
            foreach ($source as $key => $item) {
                $destination["{$key}"] = $item;
            }
        }
    }

    /**
     * @param array $propertyValue
     * @return stdClass
     */
    public function getObject($propertyValue = []) {
        $object = new stdClass();
        foreach ($propertyValue as $key => $value) {
            $object->{$key} = $value;
        }
        return $object;
    }

    /**
     * @param $string
     * @return string
     */
    public function camelCaseToSnakeCase($string) {
        return strtolower(preg_replace('/([^A-Z])([A-Z])/', "$1_$2", $string));
    }

    /**
     * @param $object
     * @return array
     */
    public function objectToArray($object, $camelCaseToSnakeCase = true) {
        $array = [];
        if (is_object($object)) {
            foreach ($object as $property => $value) {
                $key = $camelCaseToSnakeCase ? $this->camelCaseToSnakeCase($property) : $property;
                $array["{$key}"] = $value;
            }
        }
        return $array;
    }
}