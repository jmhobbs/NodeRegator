var NodeRegator = {

	domain: 'ping.noderegator.com',
	path: '/track',

	track: function ( code ) {
		url = 'http://' + NodeRegator.domain + NodeRegator.path;
		url += '?code=';
		url += escape( code );
		url += '&protocol=';
		url += escape( window.location.protocol );
		url += '&host=';
		url += escape( window.location.host );
		url += '&path=';
		url += escape( window.location.pathname );
		NodeRegator.xhr( url );
	},

	// Adapted from http://articles.sitepoint.com/article/take-command-ajax
	xhr: function ( url ) {
		var http_request = false;
		// Mozilla, Safari,...
		if ( window.XMLHttpRequest ) {
			http_request = new XMLHttpRequest();
			if ( http_request.overrideMimeType ){
				http_request.overrideMimeType( 'text/xml' );
			}
		}
		// IE
		else if ( window.ActiveXObject ) {
			try {
				http_request = new ActiveXObject( "Msxml2.XMLHTTP" );
			}
			catch ( e ) {
				try {
					http_request = new ActiveXObject( "Microsoft.XMLHTTP" );
				}
				catch ( e ) {}
			}
		}

		if ( ! http_request ) {
			// Silent failure :-|
			return false;
		}

		http_request.onreadystatechange = function () {
			if ( http_request.readyState == 4 ) {
				if ( http_request.status == 200 ) {
// 					console.log( http_request.responseText );
					return true;
				}
				else {
// 					console.log( 'There was a problem with the request.(Code: ' + http_request.status + ')' );
					return false;
				}
			}
		};

		http_request.open( 'GET', url, true );
		http_request.send( null );
	}
}
