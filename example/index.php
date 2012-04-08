<?php
include("../src/Mason.php");
use mason\Mason as Mason;

$mason = new Mason("./tpl");
echo $mason->render("foo.tpl");

?>
