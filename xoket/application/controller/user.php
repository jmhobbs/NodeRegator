<?php

	class User_Controller extends Controller {

		public $context_availability = array( 'xhtml' );

		public function index () {
			if( ! isset( $_SESSION['Auth.LoggedIn'] ) or true !== $_SESSION['Auth.LoggedIn'] )
				$this->redirect( '/user/login' );

			$this->document->title = "Dashboard";
// 			$this->document->user = $_SESSION['Auth.User'];

		}

		public function login () {
			$this->document->title = "Log In";

			if( $_POST ) {
				if( ! Form::check_request_token() ) {
					//! TODO Error!
					die( 'I don\'t think so buddy.' );
					return;
				}

				$user = Document::instance( 'user' )->findOne( array( 'email' => $_POST['email'] ) );
				if( ! $user or $_POST['password'] != $user->password ) {
					die( 'No Go.' );
				}
				else {
					$_SESSION['Auth.LoggedIn'] = true;
					$_SESSION['Auth.User'] = $user;
					$this->redirect( '/user' );
				}
			}
		}

	}
