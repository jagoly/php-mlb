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

jg_echo_basic_head('My Life Balance | User');

echo '<body>';

jg_echo_basic_header("MyLifeBalance User");

echo '<div id=Content>';

//==========================================================================//

//jg_dump_pretty_json($_POST, '_POST');

$login = isset($_SESSION['login']) ? $_SESSION['login'] : NULL;
$action = isset($_GET['action']) ? $_GET['action'] : NULL;

if ($action == NULL) {} // don't do anything here

//==========================================================================//

else if ($action == 'login')
{
    if ($login == NULL)
    {
        if (isset($_POST['User']) && isset($_POST['Password']))
        {
            $user = $_POST['User'];
            $password = $_POST['Password'];

            $loginMap = [ 'user' => 'password', 'admin' => 'admin' ];

            if (array_key_exists($user, $loginMap) && $loginMap[$user] == $password)
            {
                $login = $_SESSION['login'] = $user;
                echo '<p>You have been logged in.</p>';
            }

            else echo '<p>Invalid user or password.</p>';
        }
    }

    else echo '<p>You are already logged in.</p>';
}

//==========================================================================//

else if ($action == 'logout')
{
    if ($login != NULL)
    {
        unset($login, $_SESSION['login']);
        echo '<p>You have been logged out.</p>';
    }

    else echo '<p>You are already logged out.</p>';
}

//==========================================================================//

else echo "<p>WARNING: Unrecognised action '$action'</p>";

//==========================================================================//

if ($login == NULL && $action != 'logout')
{
    echo '  <form class=BasicForm action="?action=login" method="POST">';
    echo '    <fieldset>';
    echo '      <label> User <input type="text" name="User"> </label>';
    echo '      <label> Password <input type="password" name="Password"> </label>';
    echo '      <button type="submit"> LOG IN </button>';
    echo '    </fieldset>';
    echo '  </form>';
}

//==========================================================================//

echo '</div>';

jg_echo_basic_footer();

echo '</body>';

echo '</html>';

?>
