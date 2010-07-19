<?php
	/**
	 * Add-on for authentication and roles.
	 *
	 * @author John Hobbs (jmhobbs@littlefilament.com)
	 *
	 * @package Xoket
	 * @subpackage Module.Auth
	 */
	class Auth {

		protected static $user = null;

		/**
		 * Initializes the static members from session data. Internal function.
		 **/
		protected static function init () {
			if( self::$user == null ) {
				self::$user = false;
				if( isset( $_SESSION['_xoket_auth_user'] ) and ! empty( $_SESSION['_xoket_auth_user'] ) ) {
					self::$user = Document::instance( 'user' )->findOne( array( 'username' => $_SESSION['_xoket_auth_user'] ) );
				}
			}
		}

		/**
		 * Accessor to get the current user.
		 *
		 * @returns The current user document, or false if none logged in.
		 **/
		public static function get_user () {
			self::init();
			return self::$user;
		}

		/**
		 * Attempt to log in with a username and password.
		 *
		 * @returns True on log in, false on failure.
		 **/
		public static function login ( $username, $password ) {
			$user = Document::instance( 'user' )->findOne( array( 'username' => $username, 'password' => $password ) ); //! \todo Hashing!
			if( $user ) {
				$_SESSION['_xoket_auth_user'] = $user->username;
				self::$user = $user;
				return true;
			}
			else {
				self::logout();
				return false;
			}
		}

		/**
		 * Force a login without password, as long as the user exists.
		 *
		 * @returns True on log in, false on failure.
		 **/
		public static function force_login ( $username ) {
			self::init();
			$user = Document::instance( 'user' )->findOne( array( 'username' => $username ) );
			if( $user ) {
				$_SESSION['_xoket_auth_user'] = $user->username;
				self::$user = $user;
				return true;
			}
			else {
				self::logout();
				return false;
			}
		}

		/**
		 * Logs the current user out.
		 **/
		public static function logout () {
			self::init();
			$_SESSION['_xoket_auth_user'] = '';
			unset( $_SESSION['_xoket_auth_user'] );
		}

		/**
		 * Checks if the current user is capable of an action, i.e. has the correct role.
		 *
		 * @param role
		 *
		 * @returns True if capable, false if not.
		 **/
		public static function capable ( $role = null ) {
			self::init();
			if( self::$user === false ) { return false; }
			//! \todo Finish role implementation.
			return true;
		}
	}
