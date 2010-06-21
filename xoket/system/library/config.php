<?php
	/**
	 * Fetches config information in a cascading fashion.
	 *
	 * @author John Hobbs (jmhobbs@littlefilament.com)
	 *
	 * @package Xoket
	 * @subpackage Core
	 */
	class Config_Core {
	
		protected static $cache = array();
	
		/**
		 * Get an option from a config file.
		 *
		 * This function will get a configuration option, starting with the system
		 * defaults and working up to the application configuration folder.
		 *
		 * <b>Example:</b>
		 * <code>
		 * 	echo Config::get( 'xoket.location', '/' );
		 * </code>
		 *
		 * This example will look for <i>system/config/xoket.php</i> and
		 * load the configuration variable for there. Then it will look for
		 * <i>application/config/xoket.php</i> and load the configuration variable
		 * from there. That way application configuration can override system
		 * defaults.
		 *
		 * If the configuration variable <i>location</i> is not found in either of them,
		 * the optional default <i>/</i> is used.
		 *
		 * @param option A dotted format option.
		 * @param default A default value.
		 */
		public static function get ( $option, $default=null ) {
			
			$components = explode( '.', $option );
			if( 2 > count( $components ) )
				return $default;
			
			$name = $components[0];
			$variable = implode( '.', array_slice( $components, 1 ) );
			
			if( ! array_key_exists( $name, self::$cache ) ) {
				$file = '/config/' . strtolower( $name ) . '.php';
				$config = array();
				@include( SYSTEM_ROOT . $file );
				@include( APP_ROOT . $file );
				self::$cache[$name] = $config;
			}
			
			if( array_key_exists( $variable, self::$cache[$name] ) )
				return self::$cache[$name][$variable];
			else
				return $default;
		} // Config_Core::get

	}