<?php

require_once( 'connect.php' );

print_r( $_POST );

$user = mysqli_real_escape_string( $connection, $_POST['username'] );
$client = mysqli_real_escape_string( $connection, $_POST['clientname'] );

$query = "SELECT server_file, file_" . $client . " FROM user_" . $user;
echo $query . "\n";

$result = mysqli_query( $connection, $query );
while ( $row = mysqli_fetch_assoc( $result ) ) {
	// print_r( $row );
	$client_file = $row['file_' . $client];
	echo $client_file . "\t" . filemtime( $client_file ) . "\n";
}

mysqli_close( $connection );
