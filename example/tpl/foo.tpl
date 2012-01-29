<?php $extend = "index.tpl"?>

<?php $block("page_title");?>
New Page Title
<?php $endblock();?>

<?php $block("title");?>
Fancy New Title
<?php $endblock();?>

<?php $block("content");?>
Second dir's content.
<?php $endblock();?>

<?php $block_append("content");?>
And some extra content.
<?php $endblock();?>
