<?php

error_reporting( E_ALL );
ini_set( 'display_errors', 1 );

print_r( $_POST );
print_r( $_FILES );

// Should check size, security, etc.
if ( move_uploaded_file( $_FILES['file']['tmp_name'], '/var/www/dev.coderagora.com/crispy-data/' . $_FILES['file']['name'] ) ) {
	echo "Success!";
} else if  ( ! ( is_dir( '/var/www/dev.coderagora.com/crispy-data/' ) && is_writable( '/var/www/dev.coderagora.com/crispy-data/' ) ) ) {
	die( "Write error" );
} else {
	die( "There was an unexpected error" );
}

$user = $_POST['username'];
$client = $_POST['clientName'];

/* SAVE INFO IN DB */

require_once( 'connect.php' );

// Check if user is valid
$query = "SELECT id FROM user_" . $user . " WHERE file_" . $client . " = ?";
echo $query;

$statement = mysqli_prepare( $connection, $query );
mysqli_stmt_bind_param( $statement, "s", $_POST['filePath'] );
mysqli_stmt_execute( $statement );
mysqli_stmt_bind_result( $statement, $id );
print_r( $id );
mysqli_stmt_close( $statement );
mysqli_close( $connection );

echo "END";
