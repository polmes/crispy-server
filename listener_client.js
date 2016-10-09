var source = new EventSource( 'https://dev.coderagora.com/crispy/stream.php' );
source.addEventListener( 'message', function(e) {
	console.log( e.data );
}, false );
