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
	'/',
	function () {
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
)

get(
	'/track',
	function () {
		this.contentType( 'javascript' );
		return '{}';
	}
)

run()
