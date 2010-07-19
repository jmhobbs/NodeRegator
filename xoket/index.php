<?php

	error_reporting( E_ALL ^ E_NOTICE ^ E_DEPRECATED );

	/**
	 * Set these as needed. Usually only if you have moved them outside of the web root.
	 */
	define( 'SYSTEM_ROOT', dirname( __FILE__ ) . '/system' );
	define( 'APP_ROOT', dirname( __FILE__ ) . '/application' );
	define( 'MOD_ROOT', dirname( __FILE__ ) . '/modules' );

	/**
	 * Set this array to all of the modules you wish to include.
	 **/
	$_xoket_modules = array(
		'auth'
	);

	/*!
		This autoloader drives the proper inclusion of all classes.
	*/
	function __autoload ( $name ) {
		global $_xoket_modules;

		$force_core = false;
		$is_library_check = false;

		// Trim it up and learn about it from it's name.
		if( "_Core" == substr( $name, -5 ) ) {
			$name = substr( $name, 0, -5 );
			$force_core = true;
		}


		$raw_file = strtolower( $name ) . '.php';
		$file = '.php'; //! \todo Bad 'invalid' file...
		if( "_Controller" == substr( $name, -11 ) )
			$file = '/controller/' . strtolower( substr( $name, 0, -11 ) ) . '.php';
		else if ( '_Context' == substr( $name, -8 ) )
			$file = '/context/' . strtolower( substr( $name, 0, -8 ) ) . '.php';
		else if ( '_Document' == substr( $name, -9 ) )
			$file = '/document/' . strtolower( substr( $name, 0, -9 ) ) . '.php';

		// Now go looking for the class.
		if( ! $force_core ) {
			// Try the application directory first.
			if( file_exists( APP_ROOT . $file ) ) {
				require_once( APP_ROOT . $file );
				return;
			}
			else if( file_exists( APP_ROOT . '/helper/' . $raw_file ) ) {
				require_once( APP_ROOT . '/helper/' . $raw_file );
				return;
			}
			else if( file_exists( APP_ROOT . '/library/' . $raw_file ) ) {
				require_once( APP_ROOT . '/library/' . $raw_file );
				return;
			}
			// Now try the modules directories
			else {
				foreach( $_xoket_modules as $module ) {
					if( file_exists( MOD_ROOT . '/' . $module . $file ) ) {
						require_once( MOD_ROOT . '/' . $module . $file );
						return;
					}
					else if( file_exists( MOD_ROOT . '/' . $module . '/helper/' . $raw_file ) ) {
						require_once( MOD_ROOT . '/' . $module . '/helper/' . $raw_file );
						return;
					}
					else if( file_exists( MOD_ROOT . '/' . $module . '/library/' . $raw_file ) ) {
						require_once( MOD_ROOT . '/' . $module . '/library/' . $raw_file );
						return;
					}
				}
			}
		}

		// If all the others have failed, look in the system directory.
		if( file_exists( SYSTEM_ROOT . $file ) ) {
			require_once( SYSTEM_ROOT . $file );
			if( ! $force_core )
				@class_alias( "{$name}_Core", "$name" );
		}
		else if( file_exists( SYSTEM_ROOT . '/helper/' . $raw_file ) ) {
			require_once( SYSTEM_ROOT . '/helper/' . $raw_file );
			@class_alias( "{$name}_Helper", "$name" );
		}
		else if( file_exists( SYSTEM_ROOT . '/library/' . $raw_file ) ) {
			require_once( SYSTEM_ROOT . '/library/' . $raw_file );
			if( ! $force_core )
				@class_alias( "{$name}_Core", "$name" );
		}
		else {
			throw new Exception( "Class Not Found: $name" );
		}

	} // __autoload()

	// Tricky bugger. If we have documents stored in session, and this is pre-autoloader we can't find any classes.
	session_start();

	if( false === strpos( $_SERVER['REQUEST_URI'], Config::get( 'xoket.location' ) ) )
		die( 'Configuration Error - xoket.location is invalid.' );

	$uri = substr( $_SERVER['REQUEST_URI'], strlen( Config::get( 'xoket.location' ) ) );
	$uri = URI::route( $uri );
	Request::load( $uri );

	$context = Context::instance();
	$context->headers();
	echo $context->execute();
	Flash::update();
