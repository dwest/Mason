<?php $extend = "index.tpl"?>

<?php $block("page_title");?>
New Page Title
<?php $end();?>

<?php $block("title");?>
Fancy New Title
<?php $end();?>

<?php $block("content");?>
Second dir's content.
<?php $end();?>

<?php $append("content");?>
And some extra content.
<?php $end();?>
