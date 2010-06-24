<?php echo $document->user->email; ?>
<h2>Domains</h2>
<?php
	foreach( $document->domains as $domain )
		print $domain . '<br/>';