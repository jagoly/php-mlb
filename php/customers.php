<?php

//==========================================================================//

ob_start();

require_once('include/database.php');
require_once('include/helpers.php');
require_once('include/layout.php');
require_once('include/session.php');

ob_end_clean();

//==========================================================================//

session_start();

echo '<!DOCTYPE html>';

echo '<html>';

define ( 'JG_HEAD_EXTRA', <<< END_RAW_STRING
  <style>
    #ResultTable .TableCell:nth-of-type(1) { width: 2rem; }
    #ResultTable .TableCell:nth-of-type(2) { width: 0; }
    #ResultTable .TableCell:nth-of-type(3) { width: 8rem; }
    #ResultTable .TableCell:nth-of-type(4) { width: 8rem; }
    #ResultTable .TableCell:nth-of-type(5) { width: 12rem; }
    #ResultTable .TableCell:nth-of-type(6) { width: 2rem; }
  </style>
END_RAW_STRING
);

jg_echo_basic_head('My Life Balance | Customers');

echo '<body>';

jg_echo_basic_header('Edit Customers');

echo '<div id=Content>';

//==========================================================================//

echo '  <form class=BasicForm action="?action=insert" method="POST">';
echo '    <fieldset>';
echo '      <legend> Add Customer </legend>';
echo '      <label> Last Name <input type="text" name="LastName"> </label>';
echo '      <label> First Name <input type="text" name="FirstName"> </label>';
echo '      <label> Address <input type="text" name="Address"> </label>';
echo '      <button type="submit"> INSERT </button>';
echo '    </fieldset>';
echo '  </form>';

echo '  <form class=BasicForm action="?action=search" method="POST">';
echo '    <fieldset>';
echo '      <legend> Search Customers </legend>';
echo '      <label> Last Name <input type="text" name="LastName"> </label>';
echo '      <label> First Name <input type="text" name="FirstName"> </label>';
echo '      <label> Address <input type="text" name="Address"> </label>';
echo '      <button type="submit"> SEARCH </button>';
echo '    </fieldset>';
echo '  </form>';

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

    echo '<div id=ResultTable class=Table>';
    {
        echo '<div class=TableHead>';
        {
            echo '<div class=TableRow>';
            {
                echo '<div class=TableCell title="Customers.CustomerID"> <span> ID </span> </div>';
                echo '<div class=TableCell> </div>';
                echo '<div class=TableCell title="Customers.LastName"> <span> Last Name </span> </div>';
                echo '<div class=TableCell title="Customers.FirstName"> <span> First Name </span> </div>';
                echo '<div class=TableCell title="Customers.Address"> <span> Address </span> </div>';
                echo '<div class=TableCell> </div>';
            }
            echo '</div>';
        }
        echo '</div>';

        echo '<div class=TableBody>';
        {
            foreach ($result as $row)
            {
                echo '<div class=TableRow>';
                {
                    $updateId = 'update_' . $row->CustomerID;
                    $deleteId = 'delete_' . $row->CustomerID;

                    echo "<form id=$updateId method='post' action='?action=update'>"; {
                        echo "<input type='hidden' name='CustomerID' value='{$row->CustomerID}'>";
                    } echo "</form>";

                    echo "<form id=$deleteId method='post' action='?action=delete'>"; {
                        echo "<input type='hidden' name='CustomerID' value='{$row->CustomerID}'>";
                    } echo "</form>";

                    echo '<div class=TableCell> <span>', $row->CustomerID, '</span> </div>';

                    echo '<div class=TableCell>', "<input form=$updateId type='submit' hidefocus='true'>", '</div>';

                    echo '<div class=TableCell>', "<input form=$updateId type='text' name='LastName' value='{$row->LastName}'>", '</div>';
                    echo '<div class=TableCell>', "<input form=$updateId type='text' name='FirstName' value='{$row->FirstName}'>", '</div>';
                    echo '<div class=TableCell>', "<input form=$updateId type='text' name='Address' value='{$row->Address}'>", '</div>';

                    echo '<div class=TableCell>', "<button form=$deleteId title='Delete Record' type='submit'> ⌫ </button>", '</div>';
                }
                echo '</div>';
            }
        }
        echo '</div>';
    }
    echo '</div>';
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

echo '</div>';

jg_echo_basic_footer();

echo '</body>';

echo '</html>';

?>
