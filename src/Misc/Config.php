<?php

namespace Misc;

/**
 * Class Config
 *
 * Accede a archivos de configuracion y a sus propiedades.
 *
 * @author Maxi Nivoli <m_nivoli@hotmail.com>
 * @package Misc
 */

class Config{

    /**
     * @var Config
     */
    private static $instance;

	private $config = array();

	/**
	 * Constructor
	 * 
	 * @access private
	 */ 
	private function __construct(){}

    /**
     * Get Instance
     *
     * Retorna una instancia de
     * Siervo.
     *
     * @return Config
     */
    public static function getInstance(){
        if(!isset(self::$instance)){
            $clase = __CLASS__;
            self::$instance = new $clase;
        }
        return self::$instance;
    }

    /**
     * __clone
     *
     * Para que no se puedan crear nuevos
     * objetos por medio de la clonación.
     *
     * @return void
     */
    private function __clone(){}

	/**
	 * Load
	 *
	 * Carga ficheros de configuracion creados por los usuarios 
	 * cuya caracteristica es que los valores de configuracion 
	 * deben estar contenidos en una variable tipo diccionario
     * clave => valor llamada $config
	 *
	 * @access public
	 * @param string
	 */
	public function load($file = ''){
		if(file_exists($file)){
			require($file);
			$this->config = array_merge($this->config, $config);
		}else{
			//ver de disparar un error o tirar u log..
		}
	}

	/**
	 * Item
	 *
	 * Retorna el valor correspondiente a la
     * o las claves pasadas, se pueden pasar
     * como maximo 3 claves ($config['one']['two']['three'];
     * $c->item('one','two','three'))
	 *
	 * @access public
	 * @return string/boolean (el valor del item o false si el item no existe)
	 */
	public function item(){
		$value = false;

        switch(func_num_args()){
            case 1:
                if(isset($this->config[func_get_arg(0)])){
                    $value = $this->config[func_get_arg(0)];
                }
                break;
            case 2:
                if(array_key_exists(func_get_arg(0), $this->config) && array_key_exists(func_get_arg(1), $this->config[func_get_arg(0)])){
                    $value = $this->config[func_get_arg(0)][func_get_arg(1)];
                }
                break;
            case 3:
                if(array_key_exists(func_get_arg(0), $this->config) && array_key_exists(func_get_arg(1), $this->config[func_get_arg(0)]) && array_key_exists(func_get_arg(2), $this->config[func_get_arg(0)][func_get_arg(1)])){
                    $value = $this->config[func_get_arg(0)][func_get_arg(1)][func_get_arg(2)];
                }
                break;
        }
		return $value;
	}
}