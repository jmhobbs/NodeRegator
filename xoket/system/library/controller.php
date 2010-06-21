<?php
	/**
	 * Stub class for controllers.
	 *
	 * @author John Hobbs (jmhobbs@littlefilament.com)
	 *
	 * @package Xoket
	 * @subpackage Core
	 */
	class Controller_Core {
	
		public $context_availability = array( '*' );
		public $context_exclusion = array();
		protected $document = null;
	
		public function __construct( &$document ) {
			$this->document =& $document;
			$this->document->content = View::view();
		}
	
	}