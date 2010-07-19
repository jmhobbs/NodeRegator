<?php
	/**
	 * Provides utility functions for HTML display.
	 *
	 * @package Xoket
	 * @subpackage Helper
	 */
	class HTML_Helper extends Helper {
	
		public static function link ( $destination, $text ) {
			return '<a href="' . HTML::escape( URI::get( $destination ) ) . '">' . HTML::escape( $text ) . '</a>';
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