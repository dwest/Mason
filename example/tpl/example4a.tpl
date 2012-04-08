<?php $extend = "example.tpl"?>

<?php $block("heading")?>
Example #4 - Append and Prepend
<?php $end("heading")?>

<?php $block("content")?>
<p>Append and prepend add content to the start or end of a block respectively.  Append and prepend are especially useful for adding site wide css and javascript.  They are also handy for adding links to navigation links and other such tasks.</p>

<ul>
<?php $block("demo")?>
  <li>I'm the default list item.</li>
<?php $end("demo")?>
</ul>
<?php $end("content")?>
