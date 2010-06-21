<?php
	/**
	 * Manages requests.
	 *
	 * @author John Hobbs (jmhobbs@littlefilament.com)
	 *
	 * @package Xoket
	 * @subpackage Core
	 */
	class Request_Core {
		
		/** The currently executing context. */
		public static $context = '';
		/** The currently executing controller. */
		public static $controller = '';
		/** The currently executing method. */
		public static $method = '';
		/** Any arguments provided in the request. */
		public static $arguments = array();
		
		/**
		 * Parse a URI and load it as the current request.
		 *
		 * @param uri The URI to load.
		 */
		public static function load ( $uri ) {
			$route = self::parse( $uri );
			self::$context = ( is_null( $route['context'] ) ) ? Config::get( 'xoket.default_context' ) : $route['context'];
			self::$controller = ( is_null( $route['controller'] ) ) ? Config::get( 'xoket.default_controller' ) : $route['controller'];
			self::$method = $route['method'];
			self::$arguments = $route['arguments'];
		} // Request_Core::load
		
		/**
		 * Parses a URI string into a usable format.
		 *
		 * @param uri The URI to parse.
		 * @returns An array containing context, controller, method and arguments.
		 */
		public static function parse ( $uri ) {

			$route = array(
				'context' => null,
				'controller' => null,
				'method' => 'index',
				'arguments' => array()
			);

			$components = explode( '/', $uri );

			for( $i = 0;  $i < count( $components ); ++$i )
				if( empty( $components[$i] ) )
					unset( $components[$i] );

			// If we unset anything, we need to fix the array indices
			$components = array_values( $components );

			if( 1 <= count( $components ) && in_array( strtolower( $components[0] ), Context::$contexts ) ) {
				$route['context'] = strtolower( array_shift( $components ) );
			}

			if( 1 <= count( $components ) ) { $route['controller'] = strtolower( $components[0] ); }
			if( 2 <= count( $components ) ) { $route['method'] = strtolower( $components[1] ); }
			if( 3 <= count( $components ) ) { $route['arguments'] = array_slice( $components, 2 ); }
			
			return $route;
		} // Request_Core::parse

	} // Request_Core