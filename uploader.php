<?php

// error_reporting( E_ALL );
// ini_set( 'display_errors', 1 );

require_once( 'connect.php' );

// print_r( $_POST );
// print_r( $_FILES );

$user = $_POST['username'];		
$client = $_POST['clientname'];

$directory = '/var/www/dev.coderagora.com/crispy-data/user-' . $user . '/';
if ( ! is_dir( $directory ) ) {
	mkdir( $directory );
}

$temp_file = $_FILES['file']['tmp_name'];
$server_file = $directory . $_FILES['file']['name'] . '.' . uniqid();
$client_file = $_POST['filepath'];

$hash = md5_file( $temp_file );
// echo $hash;

// Check if user table and file column are valid with INFORMATION_SCHEMA
$query = "SELECT hash, server_file FROM user_" . $user . " WHERE file_" . $client . " = ?";
echo $query;

$statement = $connection->prepare( $query );
$statement->bindParam( 1, $client_file );
$statement->execute();

$rows = $statement->fetchAll();
$count = count( $rows );
echo "Count: " . $count . "\n";
print_r( $rows );
var_dump( $rows );

if ( $count == 0 || $count == 1) {
	if ( $count == 0 ) { // NEW FILE
		// Should check size, security, etc.
		// Writable? ( ! ( is_dir( '/var/www/dev.coderagora.com/crispy-data/' ) && is_writable( '/var/www/dev.coderagora.com/crispy-data/' ) ) )
		if ( move_uploaded_file( $temp_file, $server_file ) ) {
			$query = "INSERT INTO user_" . $user . " ( hash, server_file, file_" . $client . " ) VALUES ( ?, ?, ? )";
			// echo $query;

			$statement = $connection->prepare( $query );
			$statement->bindParam( 1, $hash );
			$statement->bindParam( 2, $server_file );
			$statement->bindParam( 3, $client_file );
			$statement->execute();
		} else die( "There was an unexpected error" );
	} else { // REPLACE FILE
		/*$query = "SELECT hash, server_file FROM user_" . $user . " WHERE file_" . $client . " = ?";
		// echo $query;

		$statement = $connection->prepare( $query );
		$statement->bindParam( 1, $client_file );
		$statement->execute();

		$count = $statement->fetch();
		echo "Hash: " . $hash;
		echo "Result Hash: " . $result['hash'];
		if ( $hash != $result['hash'] ) {
			echo "FileM: " . filemtime( $temp_file );
			echo "Result FileM: " . filemtime( $result['server_file'] );
			if ( filemtime( $temp_file ) > filemtime( $result['server_file'] )  ) {
				if ( move_uploaded_file( $temp_file, $server_file ) ) {
					$query = "UPDATE user_" . $user . " SET server_file = ? WHERE server_file = ?";
					echo $query;

					$statement = mysqli_prepare( $connection, $query );
					mysqli_stmt_bind_param( $statement, "ss", $server_file, $result['server_file'] );
					mysqli_stmt_execute( $statement );

					mysqli_stmt_close( $statement );

					unlink( $result['server_file'] ); // rm old file
				} else die( "There was an unexpected error" );
			} else die( "Unexpected modified time" );
		} else die( "Unexpected hash" );*/
	}
} else die( "How did that happen?" );
