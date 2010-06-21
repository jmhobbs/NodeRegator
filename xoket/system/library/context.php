<?php
	/**
	 * Abstract class for context's.
	 *
	 * @author John Hobbs (jmhobbs@littlefilament.com)
	 * 
	 * @package Xoket
	 * @subpackage Core
	 */
	abstract class Context_Core {
	
		public static $contexts = array( 'xhtml', 'json', 'xml', 'text' );
	
		protected static $instance = null;

		protected function __construct () {}
	
		/**
		 * Get an instance of a Context by name.
		 *
		 * @param context_name The name of the context. Defaults to Request::$context.
		 * 
		 * @returns A Context object.
		 */
		public static function instance ( $context_name = null ) {

			if( is_null( self::$instance ) ) {
				if( is_null( $context_name ) )
					$classname = Request::$context . '_Context';
				else
					$classname = $context_name . '_Context';
				self::$instance = new $classname;
			}
			return self::$instance;
		}
		
		/**
		 * Output context specific headers.
		 */
		public abstract function headers ();
	
		/**
		 * Runs the context.
		 *
		 * @param render If true the context will print the output to php://stdout and exit. Defaults to true.
		 *
		 * @returns If render is false, a string containing the context output.
		 */
		public function execute ( $render = true ) {
		
			$document = new Document();
		
			$controller = Request::$controller . '_Controller';
			$controller = new $controller( $document );
			
			// See if this is a valid context for this controller
			$valid_context = in_array( '*', $controller->context_availability );
			if( in_array( Request::$context, $controller->context_availability ) ) {
				$valid_context = true;
			}
			else if (
				array_key_exists( Request::$method, $controller->context_availability ) and
				(
					in_array( Request::$context, $controller->context_availability[Request::$method] ) or
					in_array( '*', $controller->context_availability[Request::$method] )
				)
			) {
				$valid_context = true;
			}
			
			// Check exclusion list...
			if (
				array_key_exists( Request::$method, $controller->context_exclusion ) and
				(
					in_array( Request::$context, $controller->context_exclusion[Request::$method] ) or
					in_array( '*', $controller->context_exclusion[Request::$method] )
				)
			) {
				$valid_context = false;
			}
			
			if( ! $valid_context )
				die( "Invalid Context!" );
			
			// Try context specific method first...
			if( is_callable( array( $controller, Request::$method . '_' . Request::$context ) ) ) {
				if( false === call_user_func_array( array( &$controller, Request::$method . '_' . Request::$context ), Request::$arguments ) )
					die( "Error Calling Method for " . Request::$controller . ": " . Request::$method . '_' . Request::$context );
			}
			// Nothing? Okay, fire off the default method
			else {
				if( ! is_callable( array( $controller, Request::$method ) ) )
					die( "Bad Method for " . Request::$controller . ": " . Request::$method );
				
				if( false === call_user_func_array( array( &$controller, Request::$method ), Request::$arguments ) )
					die( "Error Calling Method for " . Request::$controller . ": " . Request::$method );
			}
			
			$layout = View::layout( Request::$context );
			
			$output = $layout->render( $document );
			
			if( $render ) {
				$this->headers();
				die( $output );
			}
			else {
				return $render;
			}
		} // Context_Core::execute()
	
	}
