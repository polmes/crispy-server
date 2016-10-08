<?php

$config = parse_ini_file( '/var/www/dev.coderagora.com/config.ini' );
if ( ! $config ) die( "Config access error" );

try {
	$connection = new PDO( 'mysql:host=' . $config['server'] . ';dbname=' . $config['database'],  $config['user'], $config['password'] );
	// echo "Connected successfully\n";
} catch ( PDOException $e ) {
	die( "DB connection error: " . $e->getMessage() );
}
