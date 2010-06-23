<?php
	/**
	 * Provides access to MongoDB. Very light wrapper, mostly just connection fluff.
	 *
	 * @author John Hobbs (jmhobbs@littlefilament.com)
	 *
	 * @package Xoket
	 * @subpackage Core
	 */
	class Database_Core {

		protected static $instances = array();

		/**
		 * Get a database instance by name.
		 *
		 * @param name The database name. Defaults to 'default'.
		 */
		public static function instance ( $name = 'default' ) {

			//! \todo Skip this junk and use persistent connections?
			if( ! isset( self::$instances[$name] ) or is_null( self::$instances[$name] ) ) {
				$config = Config::get( 'database.' . $name );

				if( is_null( $config ) )
					throw new Exception( 'Bad Database Configuration' );

				self::$instances[$name] = new Database(
					$config['database'],
					( empty( $config['host'] ) ) ? 'localhost' : $config['host'],
					( empty( $config['port'] ) ) ? 27017 : $config['port'],
					( empty( $config['username'] ) ) ? null : $config['username'],
					( empty( $config['password'] ) ) ? null : $config['password']
				);
			}

			return self::$instances[$name];
		}

		protected $connection = null;
		protected $database = null;

		protected function __construct ( $name, $address ) {
			$this->connection = new Mongo( $address );
			$this->database = $this->connection->$name;
		}

		public function __get ( $name ) {
			return $this->database->$name;
		}

	}