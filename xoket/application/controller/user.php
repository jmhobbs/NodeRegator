<?php

	class User_Controller extends Protected_Controller {

		public $context_availability = array( 'xhtml' );

		public function index () {
			$this->document->title = "Dashboard";
			$this->document->user = Auth::get_user();
			$this->document->domains = Document::instance( 'hit' )->distinct( 'host', array( 'user' => Auth::get_user()->_id ) );
		}

		public function login () {
			$this->document->title = "Log In";

			if( Auth::capable() ) { $this->redirect( URI::get( 'user' ) ); }

			if( $_POST ) {
				if( ! Form::check_request_token() ) {
					Flash::set( 'Error processing your request!' );
					$this->redirect( 'user/login' );
				}

				if( Auth::login( $_POST['username'], $_POST['password'] ) ) {
					Flash::set( 'Logged you in.' );
					$this->redirect( URI::get( 'user' ) );
				}
				else {
					Flash::set( 'Bad e-mail or password.' );
					$this->redirect( URI::get( 'user/login' ) );
				}
			}
		}

		public function logout () {
			Auth::logout();
			Flash::set( 'Logged you out.' );
			$this->redirect( URI::get( 'user/login' ) );
		}

	}
