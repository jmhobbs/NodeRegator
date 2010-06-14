require.paths.unshift('../lib')
require('express')
require('express/plugins')

/* http://howtonode.org/express-mongodb */

// var EventProvider = require('./eventprovider').EventProvider;
// var UserProvider = require('./userprovider').UserProvider;

configure(
	function () {
		use(MethodOverride);
		use(ContentLength);
		use(Cookie);
		use(Session);
		use(Flash);
		use(Logger);
		use(Static);
		set( 'root', __dirname )
	}
)

var MongoDB = require('./mongo').MongoDB;

var DB = new MongoDB( 'localhost', 27017 );

get(
	'/*.css',
	function ( file ) {
		this.render( file + '.css.sass', { layout: false } );
	}
);

get(
	'/track',
	function () {
		self.contentType( 'json' )
		self.respond( 200, JSON.encode( {} ) )
	}
)

get(
	'/',
	function () {
		if( this.session.logged_in == true ) {
			this.render(
				'dashboard.html.haml',
				{
					locals:
					{
						title: 'NodeRegator Dashboard',
						flashes: this.flash( 'info' )
					}
				}
			)
		}
		else {
			this.render(
				'index.html.haml',
				{
					locals:
					{
						title: 'Welcome To NodeRegator',
						flashes: this.flash( 'info' )
					}
				}
			)
		}
	}
)

get(
	'/login',
	function () {
		if( this.session.logged_in == true ) { this.redirect( '/' ); }
		this.render(
			'login.html.haml',
			{
				locals:
				{
					title: 'Log In To NodeRegator',
					flashes: this.flash( 'info' )
				}
			}
		)
	}
)

post(
	'/login',
	function () {
		if( this.session.logged_in == true ) { this.redirect( '/' ); }

		var request = this;

		DB.getCollection(
			'users',
			function ( error, collection ) {
				if( error ) { request.flash( 'info', 'Database Error, Sorry!' ); request.redirect( '/login' ); }
				else {
					collection.findOne(
						{ username: request.param( 'username' ) },
						function( error, result ) {
							if( error ) { request.flash( 'info', 'Database Error, Sorry!' ); request.redirect( '/login' ); }
							else {
								require('sys').puts( JSON.encode( result ) );
								if( "undefined" != typeof( result ) && request.param( 'password' ) == result.password ) {
									request.session.logged_in = true;
									request.flash( 'info', 'Logged You In' );
									request.redirect( '/' );
								}
								else {
									request.flash( 'info', 'Invalid Credentials' );
									request.redirect( '/login' );
								}
							}
						}
					);
				}
			}
		);
	}
)

get(
	'/logout',
	function () {
		if( this.session.logged_in == true ) {
			this.session.logged_in = false;
			this.flash( 'info', 'Logged you out.' );
			this.redirect( '/login' );
		}
	}
)

run()
