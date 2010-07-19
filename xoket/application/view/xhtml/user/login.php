<h1>Log In To NodeRegator</h1>

<?php
	echo Form::open();
	echo Form::label( 'username', 'User Name' );
	echo Form::input( 'username' );
	echo '<br/>';
	echo Form::label( 'password', 'Password' );
	echo Form::password( 'password' );
	echo '<br/>';
	echo Form::submit( 'Log In' );
	echo Form::close();