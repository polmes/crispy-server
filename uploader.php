<?php

// error_reporting( E_ALL );
// ini_set( 'display_errors', 1 );

require_once( 'connect.php' );

// print_r( $_POST );
// print_r( $_FILES );

$user = $_POST['username'];		
$client_file = $_POST['filepath'];
$chmod = $_POST['chmod'];
$chown = $_POST['chown'];

$directory = '/var/www/dev.coderagora.com/crispy-data/user-' . $user . '/';
if ( ! is_dir( $directory ) ) {
	mkdir( $directory );
}

$temp_file = $_FILES['file']['tmp_name'];
$server_file = $directory . $_FILES['file']['name'] . '.' . uniqid();

$hash = md5_file( $temp_file );
// echo $hash;

// Check if user table and file column are valid with INFORMATION_SCHEMA
$query = "SELECT hash, server_file FROM user_" . $user . " WHERE client_file = :client_file";
// echo $query;

$statement = $connection->prepare( $query );
$statement->bindParam( ':client_file', $client_file );
$statement->execute();

$rows = $statement->fetchAll( PDO::FETCH_ASSOC );
$count = count( $rows );
// echo "Count: " . $count . "\n";
// print_r( $rows );

if ( $count == 0 || $count == 1) {
	if ( $count == 0 ) { // NEW FILE
		// Should check size, security, etc.
		// Writable? ( ! ( is_dir( '/var/www/dev.coderagora.com/crispy-data/' ) && is_writable( '/var/www/dev.coderagora.com/crispy-data/' ) ) )
		if ( move_uploaded_file( $temp_file, $server_file ) ) {
			$query = "SELECT app FROM apps WHERE default_file = :default_file";
			// echo $query;

			$statement = $connection->prepare( $query );
			$statement->bindParam( ':default_file', $client_file );
			$statement->execute();
			$rows = $statement->fetchAll( PDO::FETCH_ASSOC );

			$app = null;
			if ( count( $rows ) == 1 ) $app = $rows[0]['app'];
			else if ( count( $rows ) > 1 ) die( "Cannot have more than one file per path" );

			$query = "INSERT INTO user_" . $user . " ( app, hash, server_file, client_file, chmod, chown ) VALUES ( :app, :hash, :server_file, :client_file, :chmod, :chown )";
			// echo $query;

			$statement = $connection->prepare( $query );
			$statement->bindParam( ':app', $app );
			$statement->bindParam( ':hash', $hash );
			$statement->bindParam( ':server_file', $server_file );
			$statement->bindParam( ':client_file', $client_file );
			$statement->bindParam( ':chmod', $chmod );
			$statement->bindParam( ':chown', $chown );
			$statement->execute();
		} else die( "There was an unexpected error" );
	} else { // REPLACE FILE
		if ( $hash != $rows[0]['hash'] ) {
			if ( filemtime( $temp_file ) > filemtime( $rows[0]['server_file'] )  ) {
				if ( move_uploaded_file( $temp_file, $server_file ) ) {
					$query = "UPDATE user_" . $user . " SET server_file = :new_server_file, hash = :hash, chmod = :chmod, chown = :chown WHERE server_file = :old_server_file";
					// echo $query;

					$statement = $connection->prepare( $query );
					$statement->bindParam( ':new_server_file', $server_file );
					$statement->bindParam( ':hash', $hash );
					$statement->bindParam( ':old_server_file', $rows[0]['server_file'] );
					$statement->bindParam( ':chmod', $chmod );
					$statement->bindParam( ':chown', $chown );
					$statement->execute();

					unlink( $rows[0]['server_file'] ); // rm old file
				} else die( "There was an unexpected error" );
			} else die( "Unexpected modified time" );
		} else die( "Unexpected hash" );
	}
} else die( "How did that happen?" );
