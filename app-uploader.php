<?php

require_once( 'connect.php' );

$user = $_POST['username'];		
$app = $_POST['app'];

$query = "SELECT default_file FROM apps WHERE app = :app";
// echo $query;

$statement = $connection->prepare( $query );
$statement->bindParam( ':app', $app );
$statement->execute();

while ( $row = $statement->fetch( PDO::FETCH_ASSOC ) ) {
	echo $row['default_file'] . "\n";
}
