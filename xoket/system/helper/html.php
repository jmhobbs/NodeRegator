<?php
	/**
	 * Provides utility functions for HTML display.
	 *
	 * @package Xoket
	 * @subpackage Helper
	 */
	class HTML_Helper extends Helper {
	
		public static function link ( $destination, $text ) {
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
			
			return '<a href="' . HTML::escape( $destination ) . '">' . HTML::escape( $text ) . '</a>';
		}
		
		/**
		 * Safely escape HTML characters to avoid XSS.
		 *
		 * @param string The string to escape.
		 * @returns The escaped string.
		 **/
		public static function escape ( $string ) {
			return htmlspecialchars( $string );
		}
	
	}