<?php
	/**
	 * Base class for views.
	 *
	 * @author John Hobbs (jmhobbs@littlefilament.com)
	 *
	 * @package Xoket
	 * @subpackage Core
	 */
	class View_Core {
	
		protected $path = '';
	
		/**
		 * Get a view object.
		 *
		 * @param context The context of the view.
		 * @param controller The controller of the view.
		 * @param method The method if the view.
		 *
		 * @returns A View object.
		 */
		public static function view ( $context = null, $controller = null, $method = null ) {
			if( is_null( $context ) ) { $context = Request::$context; }
			if( is_null( $controller ) ) { $controller = Request::$controller; }
			if( is_null( $method ) ) { $method = Request::$method; }
			return new View( APP_ROOT . '/view/' . $context . '/' . $controller . '/' . $method . '.php' );
		}
		
		/**
		 * Get a layout for a context.
		 *
		 * @param context The context of the layout.
		 *
		 * @returns A View object.
		 */
		public static function layout ( $context ) {
			return new View( APP_ROOT . '/view/' . $context . '/layout.php' );
		}
	
		/**
		 * Get a new View object.
		 *
		 * @param path The path of the view file to load.
		 */
		public function __construct ( $path ) {
			$this->path = $path;
		}
		
		/**
		 * Render the View.
		 *
		 * @param document The Document to use as a datasource for the View.
		 *
		 * @returns A string containing the rendered View.
		 */
		public function render ( $document ) {
			ob_start();
			require_once( $this->path );
			$output = ob_get_contents();
			ob_end_clean();
			return $output;
		}
	
	
	}