<?php
	/**
	 * Set these as needed. Usually only if you have moved them outside of the web root.
	 */
	define( 'SYSTEM_ROOT', dirname( __FILE__ ) . '/system' );
	define( 'APP_ROOT', dirname( __FILE__ ) . '/application' );

	/*!
		This autoloader drives the proper inclusion of all classes.
	*/
	function __autoload ( $name ) {

		$force_core = false;
		$is_library_check = false;

		if( "_Core" == substr( $name, -5 ) ) {
			$name = substr( $name, 0, -5 );
			$force_core = true;
		}

		if( "_Controller" == substr( $name, -11 ) )
			$file = '/controller/' . strtolower( substr( $name, 0, -11 ) ) . '.php';
		else if ( '_Context' == substr( $name, -8 ) )
			$file = '/context/' . strtolower( substr( $name, 0, -8 ) ) . '.php';
		else if ( '_Document' == substr( $name, -9 ) )
			$file = '/document/' . strtolower( substr( $name, 0, -9 ) ) . '.php';
		else {
			$is_library_check = true;
			$raw_file = strtolower( $name ) . '.php';
			$file = '/library/' . $raw_file;
		}

		if( ! $force_core && file_exists( APP_ROOT . $file ) ) {
			require_once( APP_ROOT . $file );
		}
		else if ( ! $force_core && $is_library_check && file_exists( APP_ROOT . '/helper/' . $raw_file ) ) {
			require_once( APP_ROOT . '/helper/' . $raw_file );
		}
		else if ( $is_library_check && file_exists( SYSTEM_ROOT . '/helper/' . $raw_file ) ) {
			require_once( SYSTEM_ROOT . '/helper/' . $raw_file );
			@class_alias( "{$name}_Helper", "$name" );
		}
		else if( file_exists( SYSTEM_ROOT . $file ) ) {
			require_once( SYSTEM_ROOT . $file );
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
	$context->execute();
