<?php

set_time_limit(0);
header( 'Content-Type: text/event-stream' );
header( 'Cache-Control: no-cache' );

while ( true ) {
	// if inotify
	echo 'data: ' . date( 'Y-m-d H:i:s' ) . "\n\n";

	ob_flush();
	flush();

	sleep( 3 );
}
