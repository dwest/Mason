<?php $extend = "example4a.tpl"?>

<?php $prepend("demo")?>
<li>I was prepended first.</li>
<?php $end("demo")?>

<?php $append("demo")?>
<li>I was appended.</li>
<?php $end("demo")?>

<?php $append("demo")?>
<li>I was also appended.</li>
<?php $end("demo")?>

<?php $prepend("demo")?>
<li>I was prepended second, notice that I appear first.</li>
<?php $end("demo")?>
