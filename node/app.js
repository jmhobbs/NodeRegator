var http = require( 'http' ),
    url  = require( 'url' );
var MongoDB = require('./mongo').MongoDB;
var DB = new MongoDB( 'localhost', 27017 );

// I've inlined this source so it will stay in memory.
var NodeRegate = 'var NodeRegate={domain:"localhost:8124",path:"/track",track:function(a){url="http://"+NodeRegate.domain+NodeRegate.path;url+="?code=";url+=escape(a);url+="&protocol=";url+=escape(window.location.protocol);url+="&host=";url+=escape(window.location.host);url+="&path=";url+=escape(window.location.pathname);NodeRegate.xhr(url)},xhr:function(a){var c=false;if(window.XMLHttpRequest){c=new XMLHttpRequest();if(c.overrideMimeType){c.overrideMimeType("text/xml")}}else{if(window.ActiveXObject){try{c=new ActiveXObject("Msxml2.XMLHTTP")}catch(b){try{c=new ActiveXObject("Microsoft.XMLHTTP")}catch(b){}}}}if(!c){return false}c.onreadystatechange=function(){if(c.readyState==4){if(c.status==200){return true}else{return false}}};c.open("GET",a,true);c.send(null)}};'
var NodeRegateLength = NodeRegate.length;
var NodeRegateModified = 'Wed, 16 Jun 2010 17:21:51 GMT'

http.createServer(
	function( req, res ) {
		parsed_url = url.parse( req.url, true );
		if( '/track' == parsed_url.pathname ) {
			// Check that eveything is in place
			if(
				"undefined" == typeof( parsed_url.query.code ) ||
				"undefined" == typeof( parsed_url.query.protocol ) ||
				"undefined" == typeof( parsed_url.query.host ) ||
				"undefined" == typeof( parsed_url.query.path )
			) {
				// TODO: Log error?
				res.writeHead( 200, { 'Content-Type': 'application/json' } );
				res.end( '{ "error": "Missing Required Argument" }' );
				return;
			}

			DB.save(
				'hits',
				{
					time: Math.floor( new Date().getTime() / 1000 ),
					code: parsed_url.query.code,
					protocol: parsed_url.query.protocol,
					host: parsed_url.query.host,
					path: parsed_url.query.path,
					ip: req.connection.remoteAddress
				},
				function ( error, result ) {
					// TODO: Log errors?
					res.writeHead( 200, { 'Content-Type': 'application/json' } );
					res.end( '{ "error": false }' );
				}
			);

		}
		else if ( '/noderegate.js' == parsed_url.pathname ) {
			expires = new Date();
			date = expires.toUTCString();
			expires.setFullYear( expires.getFullYear() + 1 );
			// TODO: Better cache control. If-Modified-Since, ETags, etc.
			res.writeHead(
				200,
				{
					'Content-Length': NodeRegateLength,
					'Content-Type': 'text/javascript',
					'Expires': expires.toUTCString(),
					'Cache-Control': 'public, max-age=2592000',
					'Date': date,
					'Last-Modified': NodeRegateModified
				}
			);
			res.end( NodeRegate );
		}
		else {
			res.writeHead( 404, { 'Content-Type': 'text/plain' } );
			res.end( '404 - Not Found' );
		}
	}
).listen( 8124, "127.0.0.1" );