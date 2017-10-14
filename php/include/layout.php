<?php

//----------------------------------------------------------------------------//

function jg_echo_basic_head(String $title)
{

echo <<<ENDSTR

<head>
  <meta charset='UTF-8'>
  <title>$title</title>
  <link rel="stylesheet" href="/styles/default.css">
</head>

ENDSTR;

}

//----------------------------------------------------------------------------//

function jg_echo_basic_header(String $title)
{

echo <<<ENDSTR

<header>

  <div id=HeaderNavigation>
    <nav>
      <a href="enrollments.php">ENROLLMENTS</a>
      <a href="customers.php">CUSTOMERS</a>
      <a href="">MyLifeBalance</a>
    </nav>

  </div>

  <div id=HeaderTitle>
    <h1>$title</h1>
  </div>

</header>

ENDSTR;

}

//----------------------------------------------------------------------------//

function jg_echo_basic_footer()
{

echo <<<ENDSTR

<footer>
  <div id=FooterMain>
    <p>footer stuff goes here</p>
  </div>
</footer>

ENDSTR;

}

//----------------------------------------------------------------------------//

?>
