<?php
return array(
	// MySQL
	'mysql_dao' => array(
		'default' => array(
			'host' => 'localhost', // The hostname of your database server. 
			'port' => '3306', // Port number, string
			'name' => '', // The name of the database you want to connect to
			'user' => '', // The username used to connect to the database
			'pass' => '', // The password used to connect to the database
			'charset' => 'utf8', // The character collation used in communicating with the database
			'collate' => 'utf8_general_ci', //  The Database Collate type. Don't change this if in doubt.
		),
		
		'world' => array(
			'host' => 'localhost',
			'port' => '3306', // Port number, string
			'name' => '', 
			'user' => '',
			'pass' => '',
			'charset' => 'utf8',
			'collate' => 'utf8_general_ci',
		),
	),
	
	// MySQLi
	'mysqli_dao' => array(
		'default' => array(
			'host' => 'localhost',
			'port' => 3306, // Port number, integer
			'name' => '',
			'user' => '',
			'pass' => '',
			'charset' => 'utf8',
			'collate' => 'utf8_general_ci', 
		),
		
		'world' => array(
			'host' => 'localhost',
			'port' => 3306, // Port number, integer
			'name' => '',
			'user' => '',
			'pass' => '', 
			'charset' => 'utf8', 
			'collate' => 'utf8_general_ci', 
		),
	),
	
	// PostgreSQL
	'postgresql_dao' => array(
		'default' => array(
			'host' => 'localhost',
			'port' => 5432, // Port number, integer
			'name' => '',
			'user' => '',
			'pass' => '',
			'charset' => 'UTF8',
		),
	),
	
	// SQLite
	'sqlite_dao' => array(
		'test' => array(
			'path' => APP_DIR.'test.sqlite',
		),
	),
);

// End of file: ./application/configs/data_source.php 