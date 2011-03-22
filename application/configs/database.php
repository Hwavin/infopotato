<?php
$database = array(
	// Default database connection
	'default' => array(
		'adapter' => 'mysql_adapter', // Name of the database adapter, case-insentive
		'host' => 'localhost', // The hostname of your database server.
		'name' => 'video_request_2', // The name of the database you want to connect to
		'user' => 'root', // The username used to connect to the database
		'pass' => 'Fr4nc0$J3r0m3_', // The password used to connect to the database
		'charset' => 'utf8', // The character collation used in communicating with the database
		'collate' => 'utf8_general_ci', //  The Database Collate type. Don't change this if in doubt.
	),
	// Another connection example if more database required
	'remote' => array(
		'adapter' => 'mysql_adapter',
		'host' => 'localhost',
		'name' => '',
		'user' => 'root',
		'pass' => '',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci',
	),
	// For SQLite, only need db file path
	'sqlite' => array(
		'adapter' => 'sqlite_adapter',
		'path' => 'C:\wamp\www\framework\application\test.db',
	),
);


// End of file: ./application/configs/database.php 