<?php

namespace Misc;

use PDO;

/**
 * Db
 *
 * Facilita la creación de objetos PDO usando los perfiles
 * definidos en un archivo de configuración.
 * Ej. definición de perfil:
 * $config['default']['database'] = "mysql";
 * $config['default']['hostname'] = "localhost";
 * $config['default']['username'] = "username";
 * $config['default']['password'] = "pass";
 * $config['default']['dbname']   = "dbname";
 *
 * IMPORTANTE: El archivo de configuración se debe haber cargado
 * con la clase Config.
 */

trait Db{

    /**
     * Get PDO
     *
     * Crea y retorna un objeto PDO creado con los datos del perfil especificado
     * haciendo referencia a los perfiles definidos en un archivo de configuración.
     *
     * @param string $profile
     * @param array $args propios del PDO ej.: PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES UTF8"
     * @return PDO
     */
    public function getPDO($profile = "default", $args = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES UTF8")){
        $dbConfig = Config::getInstance();
        return new PDO($dbConfig->item($profile, 'database').
            ":host=".$dbConfig->item($profile, 'hostname').
            ";dbname=".$dbConfig->item($profile,'dbname')
            ,$dbConfig->item($profile,'username')
            ,$dbConfig->item($profile,'password')
            ,$args);
    }
}