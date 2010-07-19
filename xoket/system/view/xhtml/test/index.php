<h2>Unit Tests</h2>

<h3>Application Tests</h3>
<ul>
<?php
	foreach( $document->application_tests as $test ) {
		$test = substr( basename( $test ), 0, -4 );
		print '<li>' . html::link( 'test/run/application/' . $test,  htmlspecialchars( $test ) ) . '</li>';
	}
?>
</ul>

<h3>Xoket Tests</h3>
<ul>
<?php
	foreach( $document->system_tests as $test ) {
		$test = substr( basename( $test ), 0, -4 );
		print '<li>' . html::link( 'test/run/system/' . $test,  htmlspecialchars( $test ) ) . '</li>';
	}
?>
</ul>
