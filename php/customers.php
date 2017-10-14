<?php

require('include/helpers.php');
require('include/database.php');
require('include/layout.php');

?>

<html>

<body>

<?php jg_echo_basic_head('My Life Balance | Results'); ?>

<?php jg_echo_basic_header('Results of Database Query'); ?>

<div id=Content>

<?php {

//==========================================================================//

jg_dump_pretty_json($_POST, '_POST');

try
{
    $db = jg_new_database_connection();

    $allEmpty = true;
    $sql = 'SELECT * FROM Customers where ';
    $args = array();

    $add_to_query = function (String $name) use (&$allEmpty, &$sql, &$args)
    {
        $value = jg_sane_map_access($_POST, $name);

        if (empty($value) == false)
        {
            if (!$allEmpty) $sql .= ' and ';
            else $allEmpty = false;

            $sql .= "$name like :$name";
            $args[$name] = $_POST[$name];
        }
    };

    $add_to_query('LastName');
    $add_to_query('FirstName');
    $add_to_query('Address');

    if ($allEmpty == false)
    {
        ( $statement = $db->prepare($sql) ) -> execute($args);
        $result = $statement->fetchAll();

        if (empty($result) == false)
        {
            echo '<table class=ResultTable>';

            echo '<tr>';
            echo '<th>CustomerID</th>';
            echo '<th>LastName</th>';
            echo '<th>FirstName</th>';
            echo '<th>Address</th>';
            echo '</tr>';

            foreach ($result as $row)
            {
                echo '<tr>';
                echo "<td>{$row->CustomerID}</td>";
                echo "<td>{$row->LastName}</td>";
                echo "<td>{$row->FirstName}</td>";
                echo "<td>{$row->Address}</td>";
                echo '</tr>';
            }

            echo '</table>';

            jg_dump_pretty_json($result, 'result');
        }
        else echo '<p>No results found.</p>';
    }
    else echo '<p>You must fill at least one query field.</p>';
}

catch (PDOException $e)
{
    jg_log_exception($e);
    echo '<p>Oops. There was a database issue.</p>';
}

catch (Exception $e)
{
    jg_log_exception($e);
    echo '<p>Oops. Something went wrong.</p>';
}

//==========================================================================//

} ?>

</div>

<?php jg_echo_basic_footer(); ?>

</body>

</html>
