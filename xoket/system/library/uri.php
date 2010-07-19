<?php
	/**
	 * Handles routing tasks.
	 *
	 * @author John Hobbs (jmhobbs@littlefilament.com)
	 *
	 * @package Xoket
	 * @subpackage Core
	 */
	class URI_Core {

		protected static $routes = null;

		/**
		 * Route a URI to the correct resource.
		 *
		 * @todo A more robust routing system.
		 *
		 * @param uri The URI to route.
		 *
		 * @returns A routed URI string.
		 */
		public static function route ( $uri ) {
			self::_load_routes();

			foreach( self::$routes as $expression => $route ) {
				$alt = preg_replace( $expression, $route, $uri );
				if( $alt != $uri )
					return $alt;
			}

			return $uri;
		}

		/**
		 * Load routes from the configuration files.
		 */
		protected static function _load_routes () {
			if( is_null( self::$routes ) )
				self::$routes = Config::get( 'routes.routes', array() );
		}

		/**
		 * Converts an in-system path to a full URI.
		 **/
		public static function get ( $destination ) {
			if( is_array( $destination ) ) {
				/**
				 * @todo This needs work.  It should be
				 * array(
				 *   'controller' => 'whatever',
				 *   'method' => 'whatever',
				 *   'arguments' => array( 'arg1', 'arg2' )
				 * );
				 *
				 * And this should be nicely routed.
				 */
				$destination = implode( '/', $destination );
			}

			if( '/' != substr( $destination, 0, 1 ) ) {
				$destination = Config::get( 'xoket.location', '/' ) . $destination;
			}

			return $destination;
		}

	}