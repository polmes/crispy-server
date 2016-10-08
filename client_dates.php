<?php

require_once( 'connect.php' );

print_r( $_POST );

$user = mysqli_real_escape_string( $connection, $_POST['username'] );
$client = mysqli_real_escape_string( $connection, $_POST['clientname'] );

$query = "SELECT server_file, client_" . $client . " FROM user_" . $user;
echo $query . '\n';

$result = mysqli_query( $connection, $query );
while ( $row = mysqli_fetch_array( $result ) ) {
	echo $row . '\n';
}

mysqli_close( $connection );
