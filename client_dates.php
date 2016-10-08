<?php

require_once( 'connect.php' );

$user = mysqli_real_escape_string( $connection, $_POST['username'] );
$client = mysqli_real_escape_string( $connection, $_POST['clientName'] );

$query = "SELECT server_file, client_" . $client . " FROM user_" . $user;
echo $query;

$result = mysqli_query( $connection, $query );
print_r( $result );

mysqli_close( $connection );
