<html>
  <head>
    <title><?php $block("page_title");?> Default Page Title <?php $end("page_title");?></title>
  </head>
  <body>
    <h1><?php $block("title");?>Default Title<?php $end("page_title");?></h1>
    <div>
      <?php $block("content"); ?>
      Default Content.
      <?php $end("page_title"); ?>
    </div>
  </body>
</html>
