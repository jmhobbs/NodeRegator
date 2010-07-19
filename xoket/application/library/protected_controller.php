<?php

	class Protected_Controller extends Controller {

		protected $protected = array();

		public function __construct ( &$document ) {
			parent::__construct( $document );
		}

	}