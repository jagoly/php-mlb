<?php

//----------------------------------------------------------------------------//

function jg_new_database_connection()
{
    $host     = 'localhost';
    $port     = '8000';
    $dbname   = 'MyLifeBalance';
    $user     = 'root';
    $password = 'password';
    
    $pdoAttrs = 
    [
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
	];

	// will throw a PDOException if the db can not be opened
	return new PDO("mysql: host=$host; port=$port; dbname=$dbname;", $user, $password, $pdoAttrs);
}

//----------------------------------------------------------------------------//

?>
