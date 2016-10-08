<?php

require_once( 'connect.php' );

// print_r( $_POST );

$user = $_POST['username'];
$client = $_POST['clientname'];

$query = "SELECT hash, server_file, file_" . $client . " FROM user_" . $user;
// echo $query . "\n";

$statement = $connection->prepare( $query );
$statement->execute();

while ( $row = $statement->fetch( PDO::FETCH_ASSOC ) ) {
	// print_r( $row );

	// if null...
	echo $row['hash'] . "\t" . $row['file_' . $client ] . "\t" . filemtime( $row['server_file'] ) . "\n";
}
// die...
