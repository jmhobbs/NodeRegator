<?php
  /**
   * Provides an XML response context.
   *
   * @package Xoket
   * @subpackage Context
   */
	class XML_Context extends Context {

		public function headers () {
			header( 'Content-type: text/xml' );
		}

	}
