<?php

//----------------------------------------------------------------------------//

function jg_new_database_connection()
{
    $host     = 'localhost';
    $port     = '3306';
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

function jg_database_insert_helper(String $table, Array $fields, Array $values)
{
    try
    {
        $args = array_map(function(String $key) use($values) {
            if (array_key_exists($key, $values)) return $values[$key];
            throw new OutOfBoundsException("no value found for key '$key'");
        }, $fields);

        $strNames = join(', ', $fields);
        $strArgs = ':' . join(', :', $fields);

        $sql = "INSERT INTO $table ( $strNames ) VALUES ( $strArgs )";
        echo $sql;

        $db = jg_new_database_connection();

        ( $statement = $db->prepare($sql) ) -> execute($args);

        return $statement->rowCount();
    }

    catch (PDOException $e)
    {
        jg_log_exception($e);
        return 0;
    }

    catch (Exception $e)
    {
        jg_log_exception($e);
        return -1;
    }
}

//----------------------------------------------------------------------------//

?>
