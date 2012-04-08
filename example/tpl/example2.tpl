<?php $extend = "example.tpl"?>

<?php $block("heading")?>
  Example #2 - Variable Substitution
<?php $end("heading")?>

<?php $block("content")?>
  <p>This example demonstrations variable substitution.  For example the current date and time is <?php echo $time?>.  This file is located under: <b><em><?php echo $filefinder->getDir('example2.tpl')?></em></b></p>
<?php $end("content")?>
