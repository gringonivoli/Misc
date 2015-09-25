<?php

namespace Misc;

/**
 * Class Session
 *
 * Clase envoltoria (wrapper) de la php session con funciones básicas.
 *
 * @author Maxi Nivoli <m_nivoli@hotmail.com>
 * @package Misc
 */

class Session{	

	/**
	 * Set 
	 *
	 * Setea una variable de session que pertenece a una session ya 
	 * creada.
	 *
	 * @access public
	 * @param String $key nombre de la variable de session.
	 * @param mixed $value value de la variable de session.
	 */
	public static function set($key, $value){
		$_SESSION[$key] = $value;
	}

	/**
	 * Get
	 *
	 * Retorna el valor de una variable de session de una session ya 
	 * creada.
	 *
	 * @access public
	 * @param  String $key nombre de la variable de session.
	 * @return String      valor de la variable solicitada.
	 */
	public static function get($key){		
		return (isset($_SESSION[$key])) ? $_SESSION[$key] : false;
	}

	/**
	 * Start
	 *
	 * Inicia o reanuda una session existente.
	 *
	 * @access public
	 * @return boolean true si fue iniciada correctamente, false en caso contrario.
	 */
	public static function start(){
		return session_start();
	}

	/**
	 * Destroy
	 *
	 * Destruye toda la información registrada de una sesison.
	 *
	 * @access public
	 * @return boolean true en caso de éxito, de lo contrario false.
	 */
	public static function destroy(){
		return session_destroy();
	}

	/**
	 * Get Id
	 *
	 * Retorna en id de la session.
	 *
	 * @access public
	 * @return String id de la session.
	 */
	public static function getId(){
		return session_id();
	}

	/**
	 * setExpireTime
	 *
	 * Setea el tiempo para que expire la cache de la session.
	 *
	 * @access public
	 * @param String $newCacheExpire tiempo en que expirará en cache de la session.
	 * @return String tiempo en que expirará la cache de la session.
	 */
	public static function setExpireTime($newCacheExpire){
		return session_cache_expire($newCacheExpire);
	}

	/**
	 * RegenerateId
	 *
	 * Para que una nueva session tome un nuevo id, no el de la session 
	 * anterior.
	 *
	 * @access public
	 * @param boolean $deleteOldSession si se borran el archivo asociado antiguo o no.
	 * @return true en caso de éxito, de lo contrario false.
	 */
	public static function regenerateId($deleteOldSession = false){
		return session_regenerate_id($deleteOldSession);
	}

	/**
	 * Value Exists
	 *
	 * Verifica si una variable de session esta vacia o no.
	 *
	 * @access public
	 * @param  String $key nombre de la variable de session a verificar.
	 * @return boolean true en caso de que este vacia, de lo contrario false.
	 */
	public static function isEmpty($key){
		return empty($_SESSION[$key]);
	}
}