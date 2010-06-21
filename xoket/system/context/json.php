<?php
	/**
	 * Provides a JSON response context.
	 *
	 * @package Xoket
	 * @subpackage Context
	 */
	class JSON_Context extends Context {

		public function headers () {
			header( 'Content-type: text/javascript' );
		}

	}
