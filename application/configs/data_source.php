<?php
return array(
	// MySQL
	'mysql_adapter' => array(
		'default' => array(
			'host' => 'localhost', // The hostname of your database server.
			'name' => 'users', // The name of the database you want to connect to
			'user' => 'root', // The username used to connect to the database
			'pass' => '', // The password used to connect to the database
			'charset' => 'utf8', // The character collation used in communicating with the database
			'collate' => 'utf8_general_ci', //  The Database Collate type. Don't change this if in doubt.
		),
	),
	
	// MySQLi
	'mysqli_adapter' => array(
		'default' => array(
			'host' => 'localhost', // The hostname of your database server.
			'name' => 'users', // The name of the database you want to connect to
			'user' => 'root', // The username used to connect to the database
			'pass' => '', // The password used to connect to the database
			'charset' => 'utf8', // The character collation used in communicating with the database
			'collate' => 'utf8_general_ci', //  The Database Collate type. Don't change this if in doubt.
		),
	),
	
	// SQLite
	'sqlite_adapter' => array(
		'test' => array(
			'path' => APP_DIR.'test.sqlite',
		),
	),
);

// End of file: ./application/configs/data_source.php 