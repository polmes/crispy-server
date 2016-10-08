<?php

require_once( 'connect.php' );

// print_r( $_POST );

$user = mysqli_real_escape_string( $connection, $_POST['username'] );
$client = mysqli_real_escape_string( $connection, $_POST['clientname'] );

$query = "SELECT server_file, file_" . $client . " FROM user_" . $user;
// echo $query . "\n";

$result = mysqli_query( $connection, $query );
while ( $row = mysqli_fetch_assoc( $result ) ) {
	// print_r( $row );
	
	// if null...
	echo $row['file_' . $client ] . "\t" . filemtime( $row['server_file'] ) . "\n";
}
// die...

mysqli_close( $connection );
