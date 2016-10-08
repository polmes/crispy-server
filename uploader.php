<?php

error_reporting( E_ALL );
ini_set( 'display_errors', 1 );

require_once( 'connect.php' );

// print_r( $_POST );
// print_r( $_FILES );

$user = mysqli_real_escape_string( $connection, $_POST['username'] );
$client = mysqli_real_escape_string( $connection, $_POST['clientname'] );

$directory = '/var/www/dev.coderagora.com/crispy-data/user-' . $user . '/';
if ( ! is_dir( $directory ) ) {
	mkdir( $directory );
}

$server_file = $directory . $_FILES['file']['name'] . '.' . uniqid();
$client_file = $_POST['filePath'];

// Should check size, security, etc.
if ( move_uploaded_file( $_FILES['file']['tmp_name'], $server_file ) ) {
	// echo "Success!";
/*} else if  ( ! ( is_dir( '/var/www/dev.coderagora.com/crispy-data/' ) && is_writable( '/var/www/dev.coderagora.com/crispy-data/' ) ) ) {
	die( "Write error" );*/
} else die( "There was an unexpected error" );

/* SAVE INFO IN DB */

// Check if user is valid
$query = "SELECT COUNT(*) FROM user_" . $user . " WHERE file_" . $client . " = ?";
// echo $query;

$statement = mysqli_prepare( $connection, $query );
mysqli_stmt_bind_param( $statement, "s", $client_file );
mysqli_stmt_execute( $statement );

mysqli_stmt_bind_result( $statement, $count );
mysqli_stmt_fetch( $statement );

mysqli_stmt_close( $statement );

if ( $count == 0 ) {
	$query = "INSERT INTO user_" . $user . " ( server_file, file_" . $client . " ) VALUES ( ?, ? )";
	// echo $query;

	$statement = mysqli_prepare( $connection, $query );
	mysqli_stmt_bind_param( $statement, "ss", $server_file, $client_file );
	mysqli_stmt_execute( $statement );

	mysqli_stmt_close( $statement );
} else if ( $count == 1 ) {
	// update according to dates
} else {
	die( "How did that happen?" );
}

mysqli_close( $connection );
