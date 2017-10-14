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

function jg_database_insert_helper(String $table, Array $fields, Array $args)
{
    foreach ($args as $key => $value)
        if ($value == '') $args[$key] = NULL;

    try
    {
        foreach ($fields as $field)
        {
            if (array_key_exists($field, $args)) continue;
            throw new OutOfBoundsException("missing arg for '$field'");
        }

        $strNames = join(', ', $fields);
        $strArgs = ':' . join(', :', $fields);

        $sql = "INSERT INTO $table ( $strNames ) VALUES ( $strArgs )";

        $db = jg_new_database_connection();
        ( $statement = $db->prepare($sql) ) -> execute($args);

        return $statement->rowCount();
    }

    catch (PDOException $e) { jg_log_exception($e); return 0; }

    catch (Exception $e) { jg_log_exception($e); return -1; }
}

//----------------------------------------------------------------------------//

function jg_database_delete_helper(String $table, Array $fields, Array $args)
{
    foreach ($args as $key => $value)
        if ($value == '') $args[$key] = NULL;

    try
    {
        $clauses = array_map(function($field) use($args) {
            if (array_key_exists($field, $args)) return "$field = :$field";
            throw new OutOfBoundsException("missing arg for '$field'");
        }, $fields);

        $strClauses = join(' AND ', $clauses);

        $sql = "DELETE FROM $table WHERE $strClauses";

        $db = jg_new_database_connection();
        ( $statement = $db->prepare($sql) ) -> execute($args);

        return $statement->rowCount();
    }

    catch (PDOException $e) { jg_log_exception($e); return 0; }

    catch (Exception $e) { jg_log_exception($e); return -1; }
}

//----------------------------------------------------------------------------//

function jg_database_update_helper(String $table, Array $setFields, Array $whereFields, Array $args)
{
    foreach ($args as $key => $value)
        if ($value == '') $args[$key] = NULL;

    try
    {
        $setClauses = array_map(function($field) use($args) {
            if (array_key_exists($field, $args)) return "$field = :$field";
            throw new OutOfBoundsException("missing arg for '$field'");
        }, $setFields);

        $whereClauses = array_map(function($field) use($args) {
            if (array_key_exists($field, $args)) return "$field = :$field";
            throw new OutOfBoundsException("missing arg for '$field'");
        }, $whereFields);

        $strSetClauses = join(', ', $setClauses);
        $strWhereClauses = join(' AND ', $whereClauses);

        $sql = "UPDATE $table SET $strSetClauses WHERE $strWhereClauses";

        $db = jg_new_database_connection();
        ( $statement = $db->prepare($sql) ) -> execute($args);

        return $statement->rowCount();
    }

    catch (PDOException $e) { jg_log_exception($e); return 0; }

    catch (Exception $e) { jg_log_exception($e); return -1; }
}

//----------------------------------------------------------------------------//

?>
