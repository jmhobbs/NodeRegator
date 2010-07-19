<?php
	/**
	 * Convinience methods to get and set "flash" messages.
	 *
	 * @author John Hobbs (jmhobbs@littlefilament.com)
	 *
	 * @package Xoket
	 * @subpackage Core
	 */
	class Flash_Core {

		/**
		 * Set a flash message.
		 * Note that TTL > 1 is not yet supported. Flash::get needs to fix that.
		 *
		 * @param message The message.
		 * @param key Optional. Allows you to partition different message types.
		 * @param ttl Optional. Time to live, i.e. page views it will show up.
		 */
		public static function set ( $message, $key='flash', $ttl=1 ) {
			$messages = ( isset( $_SESSION['xoket_flash'] ) ) ? $_SESSION['xoket_flash'] : false;
			if( false === $messages ) { $messages = array(); }
			if( ! isset( $messages[$key] ) ) { $messages[$key] = array(); }
			$messages[$key][] = array( 'message' => $message, 'ttl' => $ttl );
			$_SESSION['xoket_flash'] = $messages;
		}

		/**
		 * Get a flash message from the system.
		 *
		 * @param key Optional. What message type to pull from.
		 */
		public static function get ( $key='flash' ) {
			$messages = ( isset( $_SESSION['xoket_flash'] ) ) ? $_SESSION['xoket_flash'] : false;
			if( isset( $messages[$key] ) and 0 != count( $messages[$key] ) ) {
				// Get the message
				$message = $messages[$key][0]['message'];
				// See if that message needs to die
				if( --$messages[$key][0]['ttl'] <= 0 )
					array_shift( $messages[$key] );
				// Store it all back
				$_SESSION['xoket_flash'] = $messages;
				return $message;
			}
			return false;
		}

		/**
		 * Update the flash message data, pruning dead entries.
		 */
		public static function update () {
			$messages = ( isset( $_SESSION['xoket_flash'] ) ) ? $_SESSION['xoket_flash'] : false;
			if( false === $messages ) { return; }
			// Decrement ttl and clear out any dead messages
			foreach( $messages as $key => $queue ) {
				for( $i = 0; $i < count( $queue ); ++$i ) {
					if( --$messages[$key][$i]['ttl'] <= 0 )
						unset( $messages[$key][$i] );
				}
				// Fix any index errors we have
				$messages[$key] = array_values( $messages[$key] );
			}
			$_SESSION['xoket_flash'] = $messages;
		}

	}