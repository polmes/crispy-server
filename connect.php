<?php

$config = parse_ini_file( '/var/www/dev.coderagora.com/config.ini' );
if ( ! $config ) die( "Config access error" );

$connection = mysqli_connect( $config['server'], $config['user'], $config['password'], $config['database'] );
if ( ! $connection ) die( "DB connection error: " . mysqli_connect_error() );

// echo "Connected successfully\n";
