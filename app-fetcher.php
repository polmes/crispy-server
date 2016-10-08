<?php

require_once( 'connect.php' );

$user = $_POST['username'];		
$app = $_POST['app'];

$query = "SELECT server_file FROM user_" . $user . " WHERE app = :app";
// echo $query;

$statement = $connection->prepare( $query );
$statement->bindParam( ':app', $app );
$statement->execute();

while ( $row = $statement->fetch( PDO::FETCH_ASSOC ) ) {
	echo $row['server_file'] . "\n";
}
