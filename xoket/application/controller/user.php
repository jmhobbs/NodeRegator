<?php

	class User_Controller extends Controller {

		public $context_availability = array( 'xhtml' );

		public function index () {
			if( ! isset( $_SESSION['Auth.LoggedIn'] ) or true !== $_SESSION['Auth.LoggedIn'] )
				$this->redirect( '/user/login' );

			$this->document->title = "Dashboard";
			$this->document->user = $_SESSION['Auth.User'];
			$this->document->domains = Document::instance( 'hit' )->distinct( 'host', array( 'user' => $_SESSION['Auth.User']['_id'] ) );
		}

		public function login () {
			$this->document->title = "Log In";

			if( isset( $_SESSION['Auth.LoggedIn'] ) and $_SESSION['Auth.LoggedIn'] )
				$this->redirect( '/user' );

			if( $_POST ) {
				if( ! Form::check_request_token() ) {
					Flash::set( 'Error processing your request!' );
					$this->redirect( '/user/login' );
				}

				$user = Document::instance( 'user' )->findOne( array( 'email' => $_POST['email'] ) );
				if( ! $user or $_POST['password'] != $user->password ) {
					Flash::set( 'Bad e-mail or password.' );
					$this->redirect( '/user/login' );
				}
				else {
					$_SESSION['Auth.LoggedIn'] = true;
					$_SESSION['Auth.User'] = $user;
					Flash::set( 'Logged you in.' );
					$this->redirect( '/user' );
				}
			}
		}

		public function logout () {
			$_SESSION['Auth.LoggedIn'] = false;
			unset( $_SESSION['Auth.User'] );
			Flash::set( 'Logged you out.' );
			$this->redirect( '/user/login' );
		}

	}
