<?php

	class User_Controller extends Protected_Controller {

		public $context_availability = array( 'xhtml' );

		public function index () {
			$this->document->title = "Welcome!";
		}

		public function login () {
			$this->document->title = "Log In";

			if( Auth::capable() ) { $this->redirect( URI::get( 'user' ) ); }

			if( $_POST ) {
				if( Auth::login( $_POST['username'], $_POST['password'] ) )
					$this->redirect( URI::get( 'user' ) );
				else
					$this->redirect( URI::get( 'user/login' ) );
			}
		}

		public function logout () {
			Auth::logout();
			$this->redirect( URI::get( 'user/login' ) );
		}

	}
