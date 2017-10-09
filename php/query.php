<?php

require('include/layout.php');

?>

<html>

<body>

<?php jg_echo_basic_head("My Life Balance | Query"); ?>

<?php jg_echo_basic_header("Query the Database"); ?>

<div id=Content>

  <form class=QueryForm action="results.php" method="POST">

    <label> Last Name <input type="text" name="LastName"> </label>
    <label> First Name <input type="text" name="FirstName"> </label>
    <label> Address <input type="text" name="Address"> </label>

    <button type="submit"> GO </button>

  </form>

</div>

<?php jg_echo_basic_footer(); ?>

</body>

</html>
