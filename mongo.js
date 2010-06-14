require.paths.unshift('lib')

var Db = require('mongodb/db').Db,
		ObjectID = require('mongodb/bson/bson').ObjectID,
		Server = require('mongodb/connection').Server;

MongoDB = function ( host, port ) {
	this.db = new Db( 'node-aggregated-analytics', new Server( host, port, { auto_reconnect: true }, {} ) );
	this.db.open( function () {} );
};

MongoDB.prototype.getCollection = function( model, callback ) {
	this.db.collection(
		model,
		function( error, user_collection ) {
			if( error ) callback( error );
			else callback( null, user_collection );
		}
	);
};

exports.MongoDB = MongoDB;
