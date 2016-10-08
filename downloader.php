<?php

/**
 * inputs: client path -> server_path -> download
 */

/*require_once( 'connect.php' );

$user = mysqli_real_escape_string( $connection, $_POST['username'] );
$client = mysqli_real_escape_string( $connection, $_POST['clientname'] );
$client_file = $_POST['filepath'];

$query = "SELECT server_file FROM user_" . $user . " WHERE file_" . $client . " = ?";
// echo $query;

$statement = mysqli_prepare( $connection, $query );
mysqli_stmt_bind_param( $statement, "s", $client_file );
mysqli_stmt_execute( $statement );

mysqli_stmt_bind_result( $statement, $server_file );
mysqli_stmt_fetch( $statement );

mysqli_stmt_close( $statement );*/

$server_file = '/var/www/dev.coderagora.com/crispy-data/user-crispy/test2.txt.57f8ca70890ff';
if ( file_exists( $server_file ) ) {
	// header( 'Accept-Ranges: bytes' ); // if we wanted resumable downloads, but files too small
	header( 'Content-Type: application/octet-stream' ); // generic MIME
	header( 'Content-Disposition: attachment; filename=' . basename( $server_file ) ); // C++ will remove .uniquid extension when file is transfered and checked via hash
	header( 'Content-Length: ' . filesize( $server_file ) );
	readfile( $server_file );
	// exit;
} else die( "File doesn't exist" );
