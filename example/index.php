<?php
include("../src/Flash.php");
use flash\Flash as Flash;

$flash = new Flash("../tpl");
$flash->addTplDir("./tpl");
echo $flash->render("foo.tpl");
?>
