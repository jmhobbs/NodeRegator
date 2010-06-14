require.paths.unshift('../lib')
require('express')
require('express/plugins')

/* http://howtonode.org/express-mongodb */

var EventProvider= require('./eventprovider').EventProvider;
var UserProvider= require('./userprovider').UserProvider;

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
		if( this.param( 'username' ) == 'jmhobbs' && this.param( 'password' ) == 'password' ) {
			this.session.logged_in = true;
			this.flash( 'info', 'Logged You In' );
			this.redirect( '/' );
		}
		else {
			this.flash( 'info', 'Invalid Credentials' );
			this.redirect( '/login' );
		}
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
