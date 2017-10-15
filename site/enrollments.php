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
    #ResultTable .TableCell:nth-of-type(1) { width: 3.5rem; }
    #ResultTable .TableCell:nth-of-type(2) { width: 3.5rem; }
    #ResultTable .TableCell:nth-of-type(3) { width: 0; }
    #ResultTable .TableCell:nth-of-type(4) { width: 8rem; }
    #ResultTable .TableCell:nth-of-type(5) { width: 8rem; }
    #ResultTable .TableCell:nth-of-type(6) { width: 6rem; }
    #ResultTable .TableCell:nth-of-type(7) { width: 2rem; }
  </style>
END_RAW_STRING
);

jg_echo_basic_head('My Life Balance | Enrollments');

echo '<body>';

jg_echo_basic_header("Edit Enrollments");

echo '<div id=Content>';

//==========================================================================//

echo '  <form class=BasicForm action="?action=insert" method="POST">';
echo '    <fieldset>';
echo '      <legend> Add Enrollment </legend>';
echo '        <label> Customer <input type="text" name="CustomerID"> </label>';
echo '        <label> Workshop <input type="text" name="WorkshopID"> </label>';
echo '        <button type="submit"> INSERT </button>';
echo '      </fieldset>';
echo '  </form>';

//==========================================================================//

//jg_dump_pretty_json($_POST, '_POST');

$action = isset($_GET['action']) ? $_GET['action'] : NULL;

if ($action == NULL) {} // don't do anything here

else if ($action == 'update')
{
    $rowCount = jg_database_update_helper
    (
        'Enrollments',
        ['Grade'],
        ['CustomerID', 'WorkshopID'],
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
        'Enrollments',
        ['CustomerID', 'WorkshopID', 'Grade'],
        $_POST + ['Grade' => NULL]
    );

    if ($rowCount >  0) echo '<p>Successfully inserted record.</p>';
    if ($rowCount == 0) echo '<p>Could not insert record.</p>';
    if ($rowCount <  0) echo '<p>Oops. Something went wrong.</p>';
}

else if ($action == 'delete')
{
    $rowCount = jg_database_delete_helper
    (
        'Enrollments',
        ['CustomerID', 'WorkshopID'],
        $_POST
    );

    if ($rowCount >  0) echo '<p>Successfully deleted record.</p>';
    if ($rowCount == 0) echo '<p>Could not delete record.</p>';
    if ($rowCount <  0) echo '<p>Oops. Something went wrong.</p>';
}

else echo "<p>WARNING: Unrecognised action '$action'</p>";

//==========================================================================//

try
{
    $db = jg_new_database_connection();

    $result = $db->query( 'SELECT W.WorkshopID, C.CustomerID, C.LastName, C.FirstName, W.WorkshopDate, E.Grade ' .
                          'FROM Workshops AS W, Customers AS C, Enrollments AS E ' .
                          'WHERE W.WorkshopID = E.WorkshopID AND C.CustomerID = E.CustomerID' );

    echo '<div id=ResultTable class=Table>';
    {
        echo '<div class=TableHead>';
        {
            echo '<div class=TableRow>';
            {
                echo '<div class=TableCell title="Workshops.WorkshopID"> <span> W. ID </span> </div>';
                echo '<div class=TableCell title="Customers.CustomerID"> <span> C. ID </span> </div>';
                echo '<div class=TableCell> </div>';
                echo '<div class=TableCell title="Customers.LastName"> <span> Last Name </span> </div>';
                echo '<div class=TableCell title="Customers.FirstName"> <span> First Name </span> </div>';
                echo '<div class=TableCell title="Enrollments.Grade"> <span> Grade </span> </div>';
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
                    $updateId = 'update_' . $row->WorkshopID . '_' . $row->CustomerID;
                    $deleteId = 'delete_' . $row->WorkshopID . '_' . $row->CustomerID;

                    echo "<form id=$updateId method='post' action='?action=update'>"; {
                        echo "<input type='hidden' name='CustomerID' value='{$row->CustomerID}'>";
                        echo "<input type='hidden' name='WorkshopID' value='{$row->WorkshopID}'>";
                    } echo "</form>";

                    echo "<form id=$deleteId method='post' action='?action=delete'>"; {
                        echo "<input type='hidden' name='CustomerID' value='{$row->CustomerID}'>";
                        echo "<input type='hidden' name='WorkshopID' value='{$row->WorkshopID}'>";
                    } echo "</form>";

                    echo '<div class=TableCell> <span>', $row->WorkshopID, '</span> </div>';
                    echo '<div class=TableCell> <span>', $row->CustomerID, '</span> </div>';

                    echo '<div class=TableCell>', "<input form=$updateId type='submit' hidefocus='true'>", '</div>';

                    echo '<div class=TableCell> <span>', $row->LastName, '</span> </div>';
                    echo '<div class=TableCell> <span>', $row->FirstName, '</span> </div>';

                    echo '<div class=TableCell>', "<input form=$updateId type='text' name='Grade' value='{$row->Grade}'>", '</div>';

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
