<?php $extend = "example.tpl"?>

<?php $block("heading")?>
  Example #2 - Variable Substitution
<?php $end("heading")?>

<?php $block("content")?>
  <p>This example demonstrations variable substitution.  For example the current date and time is <?php echo $time?></p>
<?php $end("content")?>
