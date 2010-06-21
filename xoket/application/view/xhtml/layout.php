<html>
	<head>
		<title>NodeRegator - <?php echo $document->title; ?></title>
	</head>
	<body style="padding: 10px;">
		<?php print $document->content->render( $document ); ?>
	</body>
</html>