<?php

//----------------------------------------------------------------------------//

function jg_echo_basic_head(String $title)
{
    echo '<head>';

    echo '  <meta charset="UTF-8">';
    echo '  <title>', $title, '</title>';

    echo '  <link rel="stylesheet" href="/styles/default.css">';

    if (defined('JG_HEAD_EXTRA')) echo JG_HEAD_EXTRA;

    echo '</head>';
}

//----------------------------------------------------------------------------//

function jg_echo_basic_header(String $title)
{
    echo '<header>';

    $logInOutText = isset($_SESSION['login']) ? 'LOGOUT' : 'LOGIN';
    $logInOutAction = isset($_SESSION['login']) ? 'logout' : 'login';

    echo '  <div id=HeaderNavigation>';
    echo '    <nav>';
    echo '      <div>';
    echo '        <a href="enrollments.php"> ENROLLMENTS </a>';
    echo '        <a href="customers.php"> CUSTOMERS </a>';
    echo '      </div>';
    echo '      <div>';
    echo '        <a href=""> MyLifeBalance </a>';
    echo "        <a href='user.php?action=$logInOutAction'> $logInOutText </a>";
    echo '      </div>';
    echo '    </nav>';
    echo '  </div>';

    echo '  <div id=HeaderTitle>';
    echo '    <h1>', $title, '</h1>';
    echo '  </div>';

    echo '</header>';
}

//----------------------------------------------------------------------------//

function jg_echo_basic_footer()
{
    echo '<footer>';

    echo '  <div id=FooterMain>';
    echo '    <p>footer stuff goes here</p>';
    echo '  </div>';

    echo '</footer>';
}

//----------------------------------------------------------------------------//

?>
