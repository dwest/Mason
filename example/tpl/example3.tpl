<?php $extend = "example.tpl"?>

<?php $block("heading")?>
Example #3 - Lists and Loops
<?php $end("heading")?>

<?php $block("content")?>
  <p>This example demonstrates variable substitution and looping.</p>
  
  <h3>A few of my favorite things</h3>
  <p>Hi, my name is <?php echo $name?>, and here are a few of my favorite things.</p>

  <ul>
    <?php foreach($favorites as $item): ?>
    <li><?php echo $item?></li>
    <?php endforeach ?>
  </ul>
<?php $end("content")?>
