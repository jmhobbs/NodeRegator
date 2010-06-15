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
		function( error, collection ) {
			if( error ) callback( error );
			else callback( null, collection );
		}
	);
};

MongoDB.prototype.save = function( model, object, callback ) {
	this.getCollection(
		model,
		function ( error, collection ) {
			if( error ) callback( error );
			else {
				collection.save(
					object,
					function ( error, result ) {
						if( error ) callback( error );
						else callback( null, result );
					}
				);
			}
		}
	);
};

exports.MongoDB = MongoDB;
