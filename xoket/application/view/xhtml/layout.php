<html>
	<head>
		<title>NodeRegator - <?php echo $document->title; ?></title>
	</head>
	<body style="padding: 10px;">
		<?php while( $flash = Flash::get() ) { print $flash . '<br/>'; } ?>
		<?php print $document->content->render( $document ); ?>
	</body>
</html>