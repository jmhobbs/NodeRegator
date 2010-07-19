<?php
	/**
	 * Base class for MongoDB documents.
	 *
	 * @author John Hobbs (jmhobbs@littlefilament.com)
	 *
	 * @package Xoket
	 * @subpackage Core
	 */
	class Document_Core implements ArrayAccess {

		protected $_schema = null;
		protected $_name = null;
		protected $_modified = false;
		protected $_data = null;
		protected $_database = null;

		/**
		 * Get an instance of a document by name, if possible.
		 *
		 * @param name The name of the document type to fetch.
		 * @param database_name The name of the database configuration to use.
		 *
		 * @returns An instance of that document.
		 */
		public static function instance ( $name = 'Document', $database_name = 'default' ) {
			$name .= '_Document';
			return new $name( $database_name );
		}

		/**
		 * Shortcut to wrap a Mongo document in our Document object.
		 *
		 * @param document The document object from Mongo
		 * @param name The name of the class to wrap it in. Defaults to 'Document'
		 * @param database_name The name of the database configuration to use.
		 *
		 * @returns A Document based object with the provided data members.
		 */
		public static function wrap ( $document, $name = 'Document', $database_name = 'default' ) {
			$item = Document::instance( $name, $database_name );
			$item->load( $document );
			return $item;
		}

		public function __construct ( $database_name = 'default' ) {
			$this->_data = new stdClass();
			if( is_null( $this->_name ) ) { $this->_name = strtolower( substr( get_called_class(), 0, -9 ) ); }
			$this->_database = Database::instance( $database_name );
		}

		/**
		 * Verify that the data in the document is valid.
		 *
		 * @returns True if the document is valid.
		 **/
		public function verify () { return true; }

		/**
		 * Save the document to the database.
		 */
		public function save () {
			$this->_database->{$this->_name}->save( $this->_data );
		}

		/**
		 * Load an object into this object.
		 *
		 * @param object The object to load, typically from a Mongo cursor.
		 */
		public function load ( $object ) {
			$this->_data = $object;
		}

		public function __get ( $key ) { return $this[$key]; }
		public function __set ( $key, $value ) { return $this[$key] = $value; }

		// ArrayAccess
		public function offsetSet ( $offset, $value ) {
			if( is_array( $this->_data ) )
				$this->_data[$offset] = $value;
			else
				$this->_data->$offset = $value;
			$this->_modified = true;
		}
		public function offsetGet ( $offset ) {
			if( is_array( $this->_data ) )
				return $this->_data[$offset];
			else
				return isset( $this->_data->$offset ) ? $this->_data->$offset : null;
		}
		public function offsetExists ( $offset ) {
			if( is_array( $this->_data ) )
				return isset( $this->_data[$offset] );
			else
				return isset( $this->_data->$offset );
		}
		public function offsetUnset ( $offset ) {
			if( is_array( $this->_data ) )
				unset( $this->_data[$offset] );
			else
				unset( $this->_data->$offset );
		}

		// Fake some Mongo stuff
		public function find ( $query = array(), $fields = array() ) {
			return $this->_database->{$this->_name}->find( $query, $fields );
		}
		public function findOne ( $query = array() ) {
			$document = $this->_database->{$this->_name}->findOne( $query );
			return ( $document ) ? Document::wrap( $document, $this->_name ) : null;
		}
		public function distinct ( $key, $query=array() ) {
			$result = $this->_database->command( array( "distinct" => $this->_name, "key" => $key, "query" => $query ) );
			if( 1 != $result['ok'] ) return false;
			return $result['values'];
		}
	}