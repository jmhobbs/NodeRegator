<h1>Log In To NodeRegator</h1>

<?php
	echo Form::open();
	echo '<div class="form-input">';
	echo Form::label( 'email', 'E-Mail:' );
	echo Form::input( 'email' );
	echo '</div>';
	echo '<div class="form-input">';
	echo Form::label( 'password', 'Password:' );
	echo Form::password( 'password' );
	echo '</div>';
	echo Form::submit( 'Log In' );
	echo Form::close();