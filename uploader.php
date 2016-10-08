<?php

print_r( $_POST );
print_r( $_FILES );

// Should check size, security, etc.
if ( move_uploaded_file( $_FILES['file']['tmp_name'], '/var/www/dev.coderagora.com/crispy-data/' . $_FILES['file']['name'] ) ) {
	echo "Success! Maybe?";
} else if  ( ! ( is_dir( '/var/www/dev.coderagora.com/crispy-data/' ) && is_writable( '/var/www/dev.coderagora.com/crispy-data/' ) ) ) {
	die( "Write error" );
} else {
	die( "There was an unexpected error" );
}

$user = mysqli_real_escape_string( $_POST['username'] );
$client = mysqli_real_escape_string( $_POST['clientName'] );

/* SAVE INFO IN DB */

require_once( 'connect.php' );

// Check if user is valid
$query = "SELECT FROM user_" . $user . " WHERE file_" . $client . " = ?";
$statement = mysqli_prepare( $connection, $query );
mysqli_stmt_bind_param( $statement, "s", $_POST['filePath'] );

if ( mysqli_stmt_execute( $statement ) ) {
	echo "If";
	$result = mysqli_store_result( $connection );
	echo mysqli_affected_rows( $connection );
} else {
	echo "No luck";
}
