<?php
	/**
	 * Provides utility functions for creating HTML forms.
	 *
	 * @package Xoket
	 * @subpackage Helper
	 */
	class Form_Helper extends Helper {
	
		public static $token = null;
	
		/**
		 * Internal funcion. Every form based request/response should include an 
		 * authenticity token to prevent CSRF. This function gets the current token 
		 * for the current response.
		 *
		 * @returns A string representing the authenticty token for this response.
		 */
		public static function get_request_token () {
			if( null == self::$token ) {
				self::$token = sha1( time() . Config::get( "form.authenticity_salt" ) );
				$_SESSION['_xoket_authenticity_token'] = self::$token;
			}
			return self::$token;
		}
		
		/**
		 * Compares the request token against it's expected value.
		 *
		 * @returns True or False, depending on if the tokens matched.
		 */
		public static function check_request_token () {
			return ( $_REQUEST['authenticity_token'] == $_SESSION['_xoket_authenticity_token'] );
		}
	
		/**
		 * Open a new form. Automatically includes the request token.
		 *
		 * <b>Example:</b>
		 * <code>
		 *	echo Form::open( '/todo/create' );
		 * </code>
		 *
		 * @param action The action to post to. Defaults to empty.
		 * @param method The HTTP method to use. Defaults to POST.
		 *
		 * @returns A string containing the form elements.
		 */
		public static function open ( $action="", $method="POST" ) {
			$form = '<form action="' . $action . '" method="' . $method . '">';
			$form .= "\n";
			$form .= '<input type="hidden" name="authenticity_token" value="' . self::get_request_token() . '" />';
			return $form;
		}
	
		/**
		 * Closes a form.
		 *
		 * <b>Example:</b>
		 * <code>
		 * 	echo Form::close();
		 * </code>
		 *
		 * @returns A string containing the form elements.
		 */
		public static function close () {
			return  '</form>';
		}
		
		public static function input ( $name, $value="" ) {
			return '<input type="text" name="' . $name . '" id="' . $name . '" value="' . $value . '" />';
		}
		
		public static function label ( $name, $text ) {
			return '<label for="' . $name . '">' . $text . '</label>';
		}
		
		public static function submit ( $value="Submit" ) {
			return '<input type="submit" value="' . $value . '" />';
		}
		
	}