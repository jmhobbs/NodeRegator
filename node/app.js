var http = require( 'http' ),
    url  = require( 'url' );

var Config = require( './config' ).Config

var MongoDB = require( './mongo' ).MongoDB;
var DB = new MongoDB( Config.Mongo.Host, Config.Mongo.Port, Config.Mongo.Database );

http.createServer(
	function( req, res ) {
		parsed_url = url.parse( req.url, true );
		if( '/track.js' == parsed_url.pathname ) {
			// Check that eveything is in place
			if(
				"undefined" == typeof( parsed_url.query ) ||
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

			// mod_proxy will sometimes get in the way, this is a work around
			var ip_address = null;
			try {
				ip_address = req.headers['x-forwarded-for'];
			}
			catch ( error ) {
				ip_address = req.connection.remoteAddress;
			}

			var user_agent = '';
			try { user_agent = req.headers['user-agent']; } catch ( error ) {}

			var language = '';
			try { language = req.headers['accept-language']; } catch ( error ) {}

			DB.save(
				'hits',
				{
					time: Math.floor( new Date().getTime() / 1000 ),
					code: parsed_url.query.code,
					protocol: parsed_url.query.protocol,
					host: parsed_url.query.host,
					path: parsed_url.query.path,
					ip: ip_address,
					user_agent: user_agent,
					language: language
				},
				function ( error, result ) {
					// TODO: Log errors?
					res.writeHead( 200, { 'Content-Type': 'application/json' } );
					res.end( '{ "error": false }' );
				}
			);

		}
		else {
			res.writeHead( 404, { 'Content-Type': 'text/plain' } );
			res.end( '404 - Not Found' );
		}
	}
).listen( Config.Node.Port, Config.Node.Address );
