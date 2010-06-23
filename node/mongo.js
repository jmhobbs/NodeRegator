require.paths.unshift('lib')

var Db = require('mongodb/db').Db,
		ObjectID = require('mongodb/bson/bson').ObjectID,
		Server = require('mongodb/connection').Server;

MongoDB = function ( host, port, database ) {
	this.db = new Db( database, new Server( host, port, { auto_reconnect: true }, {} ) );
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

MongoDB.prototype.findOneById = function ( model, id, callback ) {
	this.getCollection(
		model,
		function ( error, collection ) {
			if( error ) callback( error );
			else {
				collection.find(
					{ '_id': new ObjectID( id ) },
					function ( error, cursor ) {
						if( error ) callback( error );
						else {
							cursor.nextObject(
								function ( error, item ) {
									if( error ) callback( error );
									else if ( null == item ) callback( error );
									else if ( "undefined" != typeof( item["$err"] ) ) callback( item["$err"] );
									else callback( null, item );
								}
							);
						}
					}
				);
			}
		}
	);
}

exports.MongoDB = MongoDB;
