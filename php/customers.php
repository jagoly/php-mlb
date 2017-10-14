<?php

require('include/helpers.php');
require('include/database.php');
require('include/layout.php');

?>

<html>

<body>

<?php jg_echo_basic_head("My Life Balance | Customers"); ?>

<?php jg_echo_basic_header("Edit Customers"); ?>

<div id=Content>

  <form class=BasicForm action="?action=insert" method="POST">

    <legend> Add Customer </legend>

    <label> Last Name <input type="text" name="LastName"> </label>
    <label> First Name <input type="text" name="FirstName"> </label>
    <label> Address <input type="text" name="Address"> </label>

    <button type="insert"> INSERT </button>

  </form>

  <form class=BasicForm action="?action=search" method="POST">

    <legend> Search Customers </legend>

    <label> Last Name <input type="text" name="LastName"> </label>
    <label> First Name <input type="text" name="FirstName"> </label>
    <label> Address <input type="text" name="Address"> </label>

    <button type="submit"> SEARCH </button>

  </form>

<?php {

//==========================================================================//

//jg_dump_pretty_json($_POST, '_POST');

$action = isset($_GET['action']) ? $_GET['action'] : NULL;

if ($action == NULL) {} // don't do anything here

else if ($action == 'update')
{
    $rowCount = jg_database_update_helper
    (
        'Customers',
        ['LastName', 'FirstName', 'Address'],
        ['CustomerID'],
        $_POST
    );

    if ($rowCount >  0) echo '<p>Successfully updated record.</p>';
    if ($rowCount == 0) echo '<p>Could not update record.</p>';
    if ($rowCount <  0) echo '<p>Oops. Something went wrong.</p>';
}

else if ($action == 'insert')
{
    $rowCount = jg_database_insert_helper
    (
        'Customers',
        ['LastName', 'FirstName', 'Address'],
        $_POST
    );

    if ($rowCount >  0) echo '<p>Successfully inserted record.</p>';
    if ($rowCount == 0) echo '<p>Could not insert record.</p>';
    if ($rowCount <  0) echo '<p>Oops. Something went wrong.</p>';
}

else if ($action == 'delete')
{
    $rowCount = jg_database_delete_helper
    (
        'Customers',
        ['CustomerID'],
        $_POST
    );

    if ($rowCount >  0) echo '<p>Successfully deleted record.</p>';
    if ($rowCount == 0) echo '<p>Could not delete record.</p>';
    if ($rowCount <  0) echo '<p>Oops. Something went wrong.</p>';
}

else if ($action == 'search') {} // handled later

else echo "<p>WARNING: Unrecognised action '$action'</p>";

//==========================================================================//

try
{
    $sql = 'SELECT CustomerID, LastName, FirstName, Address FROM Customers';
    $args = array();

    if ($action == 'search')
    {
        $clauses = array();

        foreach (['LastName', 'FirstName', 'Address'] as $field)
        {
            if (array_key_exists($field, $_POST) == false)
                throw new OutOfBoundsException("missing arg for '$field'");

            if (empty($_POST[$field]) == false)
            {
                $clauses[] = "$field like :$field";
                $args[$field] = $_POST[$field];
            }
        }

        if (empty($clauses) == false)
            $sql .= ' WHERE ' . join(' AND ', $clauses);
    }

    //jg_dump_pretty_json($sql, 'sql');

    $db = jg_new_database_connection();
    ( $statement = $db->prepare($sql) ) -> execute($args);
    $result = $statement->fetchAll();

    //jg_dump_pretty_json($result, 'result');

    if ($action == 'search' && empty($result))
        echo '<p>Search returned no results.</p>';

    echo '<table class=BasicTable>';
    {
        echo '<colgroup>'; {
            echo '<col style="width:4rem">';
            echo '<col style="width:8rem">';
            echo '<col style="width:8rem">';
            echo '<col style="width:12rem">';
            echo '<col style="width:2rem">';
        } echo '<colgroup>';

        echo '<tbody>';
        {
            echo '<tr>'; {
                echo '<th title="Customers.CustomerID"> ID </th>';
                echo '<th title="Customers.LastName"> Last Name </th>';
                echo '<th title="Customers.FirstName"> First Name </th>';
                echo '<th title="Customers.Address"> Address </th>';
            } echo '</tr>';

            foreach ($result as $row)
            {
                echo '<tr>';
                {
                    echo "<td>{$row->CustomerID}</td>";

                    echo "<form method='post' action='?action=update'>";
                    {
                        echo "<input type='hidden' name='CustomerID' value='{$row->CustomerID}'>";

                        echo "<td><input type='text' name='LastName' value='{$row->LastName}'></td>";
                        echo "<td><input type='text' name='FirstName' value='{$row->FirstName}'></td>";
                        echo "<td><input type='text' name='Address' value='{$row->Address}'></td>";
                    }
                    echo "</form>";

                    echo "<form method='post' action='?action=delete'>";
                    {
                        echo "<input type='hidden' name='CustomerID' value='{$row->CustomerID}'>";
                        echo "<td><button title='Delete Record' type='submit'> ⌫ </button></td>";
                    }
                    echo "</form>";
                }
                echo '</tr>';
            }
        }
        echo '</tbody>';
    }
    echo '</table>';
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
