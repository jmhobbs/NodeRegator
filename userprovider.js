require.paths.unshift('lib')

var Db = require('mongodb/db').Db,
		ObjectID = require('mongodb/bson/bson').ObjectID,
		Server = require('mongodb/connection').Server;

UserProvider = function ( host, port ) {
	this.db = new Db( 'node-aggregated-analytics', new Server( host, port, { auto_reconnect: true }, {} ) );
	this.db.open( function () {} );
};

UserProvider.prototype.getCollection = function( callback ) {
	this.db.collection(
		'users',
		function( error, user_collection ) {
			if( error ) callback( error );
			else callback( null, user_collection );
		}
	);
};

UserProvider.prototype.findAll = function( callback ) {
		this.getCollection( function(error, user_collection ) {
			if( error ) callback( error )
			else {
				user_collection.find(
					function( error, cursor ) {
						if( error ) callback( error )
						else {
							cursor.toArray(
								function( error, results ) {
									if( error ) callback( error )
									else callback( null, results )
								}
							);
						}
					}
				);
			}
		}
	);
};

UserProvider.prototype.findById = function( id, callback ) {
	this.getCollection(
		function( error, user_collection ) {
			if( error ) callback(error)
			else {
				user_collection.findOne(
					{_id: ObjectID.createFromHexString( id ) },
					function( error, result ) {
						if( error ) callback( error )
						else callback( null, result )
					}
				);
			}
		}
	);
};

UserProvider.prototype.save = function( users, callback ) {
	this.getCollection(
		function( error, user_collection ) {
			if( error ) callback(error)
			else {
				if( typeof( users.length ) == "undefined" ) { users = [users]; }
				for( var i = 0; i < users.length; i++ ) {
					user = users[i];
					user.created_at = new Date();
					if( user.comments === undefined ) { user.comments = []; }
					for( var j = 0; j < user.comments.length; j++ ) {
						user.comments[j].created_at = new Date();
					}
				}
				user_collection.insert( users, function() { callback( null, users ); } );
			}
		}
	);
};

exports.UserProvider = UserProvider;
