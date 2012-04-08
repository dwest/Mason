<html>
  <head>
    <title><?php $block("page_title")?> Default Page Title <?php $end("page_title")?></title>
  </head>
  <body>
    <h1>Examples</h1>
    
    <p>Below each of the examples in the ``examples'' directory should be rendered.  Each of the examples extends the examples template and overrides the heading and content blocks defined therein.  I would recommend playing with the example.tpl structure and watch how it changes the output.  It would probably also be helpful to make sure you understand how variable substitution and control structures function within templates.</p>
    
    <?php echo $render("example1.tpl")?>
    <?php echo $render("example2.tpl", array("time" => date("l, F j Y, h:i:s A")))?>
    <?php echo $render("example3.tpl", array("name"      => "Maria",
                                             "favorites" => array("Raindrops on roses",
                                                                  "Whiskers on Kittens",
                                                                  "Bright copper kettles",
                                                                  "Warm woolen mittens",
                                                                  "Brown paper packages tied up with strings",
                                                                  )))?>
  </body>
</html>
