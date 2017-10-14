<?php

require('include/helpers.php');
require('include/database.php');
require('include/layout.php');

?>

<html>

<body>

<?php jg_echo_basic_head("My Life Balance | Enrollments"); ?>

<?php jg_echo_basic_header("Edit Enrollments"); ?>

<div id=Content>

<?php {

//==========================================================================//

jg_dump_pretty_json($_POST, '_POST');

$action = isset($_GET['action']) ? $_GET['action'] : NULL;

if ($action == NULL) {} // don't do anything here

else if ($action == 'update')
{
    try
    {
        $db = jg_new_database_connection();

        $sql = 'UPDATE Enrollments SET Grade = :Grade WHERE WorkshopID = :WorkshopID AND CustomerID = :CustomerID';

        $args = [ 'WorkshopID' => jg_sane_map_access($_POST, 'WorkshopID'),
                  'CustomerID' => jg_sane_map_access($_POST, 'CustomerID'),
                  'Grade' => jg_sane_map_access($_POST, 'Grade') ];

        ( $statement = $db->prepare($sql) ) -> execute($args);

        if ($statement->rowCount() != 0) echo '<p>Successfully updated record.</p>';
    }

    catch (PDOException $e)
    {
        jg_log_exception($e);
        echo '<p>WARNING: Could not update record.</p>';
    }

    catch (Exception $e)
    {
        jg_log_exception($e);
        echo '<p>Oops. Something went wrong.</p>';
    }
}

else if ($action == 'insert')
{
    $rowCount = jg_database_insert_helper
    (
        'Enrollments', ['CustomerID', 'WorkshopID', 'Grade'],
        array_merge($_POST, ['Grade' => ''])
    );

    if ($rowCount >  0) echo '<p>Successfully inserted record.</p>';
    if ($rowCount == 0) echo '<p>Could not insert record.</p>';
    if ($rowCount <  0) echo '<p>Oops. Something went wrong.</p>';
}

else if ($action == 'delete')
{
    try
    {
        $db = jg_new_database_connection();

        $sql = 'DELETE FROM Enrollments WHERE CustomerID = :CustomerID AND WorkshopID = :WorkshopID';

        $args = [ 'CustomerID' => jg_sane_map_access($_POST, 'CustomerID'),
                  'WorkshopID' => jg_sane_map_access($_POST, 'WorkshopID') ];

        ( $statement = $db->prepare($sql) ) -> execute($args);

        if ($statement->rowCount() != 0) echo '<p>Successfully deleted record.</p>';
    }

    catch (PDOException $e)
    {
        jg_log_exception($e);
        echo '<p>WARNING: Could not delete record.</p>';
    }

    catch (Exception $e)
    {
        jg_log_exception($e);
        echo '<p>Oops. Something went wrong.</p>';
    }
}

else echo "<p>WARNING: Unrecognised action '$action'</p>";

//==========================================================================//

} ?>

  <form class=BasicForm action="?action=insert" method="POST">

    <legend> Add Enrollment </legend>

    <label> Customer <input type="text" name="CustomerID"> </label>
    <label> Workshop <input type="text" name="WorkshopID"> </label>

    <button type="insert"> INSERT </button>

  </form>

<?php {

//==========================================================================//

try
{
    $db = jg_new_database_connection();

    $result = $db->query( 'SELECT W.WorkshopID, C.CustomerID, C.LastName, C.FirstName, W.WorkshopDate, E.Grade ' .
                          'FROM Workshops AS W, Customers AS C, Enrollments AS E ' .
                          'WHERE W.WorkshopID = E.WorkshopID AND C.CustomerID = E.CustomerID' );

    echo '<table class=BasicTable>';
    {
        echo '<colgroup>'; {
            echo '<col style="width:4rem">';
            echo '<col style="width:4rem">';
            echo '<col style="width:8rem">';
            echo '<col style="width:8rem">';
            echo '<col style="width:6rem">';
            echo '<col style="width:2rem">';
        } echo '<colgroup>';

        echo '<tbody>';
        {
            echo '<tr>'; {
                echo '<th title="Workshops.WorkshopID"> W. ID </th>';
                echo '<th title="Customers.CustomerID"> C. ID </th>';
                echo '<th title="Customers.LastName"> Last Name </th>';
                echo '<th title="Customers.FirstName"> First Name </th>';
                echo '<th title="Enrollments.Grade"> Grade </th>';
            } echo '</tr>';

            foreach ($result as $row)
            {
                echo '<tr>';
                {
                    echo "<td>{$row->WorkshopID}</td>";
                    echo "<td>{$row->CustomerID}</td>";
                    echo "<td>{$row->LastName}</td>";
                    echo "<td>{$row->FirstName}</td>";

                    echo '<td>'; {
                        echo "<form method='post' action='?action=update'>";
                        echo "<input type='hidden' name='CustomerID' value='{$row->CustomerID}'>";
                        echo "<input type='hidden' name='WorkshopID' value='{$row->WorkshopID}'>";
                        echo "<input type='text' name='Grade' value='{$row->Grade}'>";
                        echo "</form>";
                    } echo '</td>';

                    echo '<td>'; {
                        echo "<form method='post' action='?action=delete'>";
                        echo "<input type='hidden' name='CustomerID' value='{$row->CustomerID}'>";
                        echo "<input type='hidden' name='WorkshopID' value='{$row->WorkshopID}'>";
                        echo "<button title='Delete Record' type='submit'> ⌫ </button>";
                        echo "</form>";
                    } echo '</td>';
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
