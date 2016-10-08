<?php

// error_reporting( E_ALL );
// ini_set( 'display_errors', 1 );

require_once( 'connect.php' );

// print_r( $_POST );
// print_r( $_FILES );

$user = mysqli_real_escape_string( $connection, $_POST['username'] );
$client = mysqli_real_escape_string( $connection, $_POST['clientname'] );

$directory = '/var/www/dev.coderagora.com/crispy-data/user-' . $user . '/';
if ( ! is_dir( $directory ) ) {
	mkdir( $directory );
}

$temp_file = $_FILES['file']['tmp_name'];
$server_file = $directory . $_FILES['file']['name'] . '.' . uniqid();
$client_file = $_POST['filepath'];

$hash = md5_file( $temp_file );
// echo $hash;

// Check if user is valid
$query = "SELECT server_file, hash FROM user_" . $user . " WHERE file_" . $client . " = ?";
// echo $query;

$statement = mysqli_prepare( $connection, $query );
mysqli_stmt_bind_param( $statement, "s", $client_file );
mysqli_stmt_execute( $statement );

$count = 0;
mysqli_stmt_bind_result( $statement, $result );
while ( mysqli_stmt_fetch( $statement ) ) $count++;

echo "Count: " . $count . "\n";
// echo "Result: " . "\n";
// print_r( $result );

mysqli_stmt_close( $statement );

if ( $count == 0 || $count == 1) {
	if ( $count == 0 ) { // NEW FILE
		// Should check size, security, etc.
		// Writable? ( ! ( is_dir( '/var/www/dev.coderagora.com/crispy-data/' ) && is_writable( '/var/www/dev.coderagora.com/crispy-data/' ) ) )
		if ( move_uploaded_file( $temp_file, $server_file ) ) {
			$query = "INSERT INTO user_" . $user . " ( hash, server_file, file_" . $client . " ) VALUES ( ?, ?, ? )";
			// echo $query;

			$statement = mysqli_prepare( $connection, $query );
			mysqli_stmt_bind_param( $statement, "sss", $hash, $server_file, $client_file );
			mysqli_stmt_execute( $statement );

			mysqli_stmt_close( $statement );
		} else die( "There was an unexpected error" );
	} else { // REPLACE FILE
		echo "Hash: " . $hash;
		echo "Result Hash: " . $result[0]['hash'];
		if ( $hash != $result[0]['hash'] ) {
			echo "FileM: " . filemtime( $temp_file );
			echo "Result FileM: " . filemtime( $result[0]['server_file'] );
			if ( filemtime( $temp_file ) > filemtime( $result[0]['server_file'] )  ) {
				if ( move_uploaded_file( $temp_file, $server_file ) ) {
					$query = "UPDATE user_" . $user . " SET server_file = ? WHERE server_file = ?";
					echo $query;

					$statement = mysqli_prepare( $connection, $query );
					mysqli_stmt_bind_param( $statement, "ss", $server_file, $result[0]['server_file'] );
					mysqli_stmt_execute( $statement );

					mysqli_stmt_close( $statement );

					unlink( $result[0]['server_file'] ); // rm old file
				} else die( "There was an unexpected error" );
			} else die( "Unexpected modified time" );
		} else die( "Unexpected hash" );
	}
} else die( "How did that happen?" );

mysqli_close( $connection );
