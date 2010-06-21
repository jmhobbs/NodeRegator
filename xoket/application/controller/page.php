<?php

	class Page_Controller extends Controller {

		public $context_availability = array( 'xhtml' );

		public function index () {
			$this->document->title = "Welcome!";
		}

	}
