<?php
  /**
   * Provides a plain text response context.
   *
   * @package Xoket
   * @subpackage Context
   */
	class Text_Context extends Context {

		public function headers () {
			header( 'Content-type: text/plain' );
		}

	}
