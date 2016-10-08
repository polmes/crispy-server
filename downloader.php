<?php

require_once( 'connect.php' );

$user = $_POST['username'];
$client = $_POST['clientname'];
$client_file = $_POST['filepath'];

$query = "SELECT server_file FROM user_" . $user . " WHERE file_" . $client . " = :client_file";
// echo $query;

$statement = $connection->prepare( $query );
$statement->bindParam( ':client_file', $client_file );
$statement->execute();

$rows = $statement->fetchAll( PDO::FETCH_ASSOC ); // could also use fetch()
if ( count( $rows ) > 1 ) {
	die( "Cannot have more than one file per path" );
}

$server_file = $rows[0]['server_file'];
echo $server_file;
print_r( $rows );

if ( file_exists( $server_file ) ) {
	// header( 'Accept-Ranges: bytes' ); // if we wanted resumable downloads, but files too small
	header( 'Content-Type: application/octet-stream' ); // generic MIME
	header( 'Content-Disposition: attachment; filename=' . basename( $server_file ) ); // C++ will remove .uniquid extension when file is transfered and checked via hash
	header( 'Content-Length: ' . filesize( $server_file ) );
	readfile( $server_file );
	// exit;
} else die( "File doesn't exist" );
