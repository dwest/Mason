<html>
  <head>
    <title><?php $block("page_title");?> Default Page Title <?php $endblock();?></title>
  </head>
  <body>
    <h1><?php $block("title");?>Default Title<?php $endblock();?></h1>
    <div>
      <?php $block("content"); ?>
      Default Content.
      <?php $endblock(); ?>
    </div>
  </body>
</html>
