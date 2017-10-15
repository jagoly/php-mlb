<?php

function jg_session_get_login()
{
    return isset($_SESSION['login']) ? $_SESSION['login'] : NULL;
}

?>
