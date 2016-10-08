<?php

require_once( 'connect.php' );

// print_r( $_POST );

$user = $_POST['username'];

$query = "SELECT hash, server_file, client_file FROM user_" . $user;
// echo $query . "\n";

$statement = $connection->prepare( $query );
$statement->execute();

while ( $row = $statement->fetch( PDO::FETCH_ASSOC ) ) {
	// print_r( $row );

	// if null...
	echo $row['hash'] . "\t" . $row['client_file'] . "\t" . filemtime( $row['server_file'] ) . "\n";
}
// die...
