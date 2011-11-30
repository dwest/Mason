<!docttype html>

<html>
  <head>
  </head>
  <body>
<?php
include("../src/Flash.php");
use flash\Flash as Flash;

$flash = new Flash("../tpl");
$flash->render("foo.tpl");
?>
  </body>
</html>
