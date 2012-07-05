Mason Templates
===============

Need templates in your PHP project fast, but don't want to deal with
the overhead of a big template library?  Well fear not!  Mason
Templates are a lightweight solution inspired by the famous [PHP
Template Language](http://phptemplatinglanguage.com/) which has been
used by PHP developers the world over since the very first release of
PHP.  Mason templates make it easy to separate the concerns of your
view code from your application logic, all while maintaining the
expressive power of PHP itself.

Examples
========

Basic Operation
---------------

It's easy to get started with Mason Templates, here is a minimal
example:

    include("path/to/your/lib/dir/Mason.php");
    use mason\Mason as Mason;

    /* Tell flash where to find our templates */
    $flash = new Mason("path/to/your/template/directory");

    /* Subdirectories are okay here too! */
    echo $flash->render("favs.tpl");

To plug your variables into a template simply change the last line in
the previous example to:

    echo $flash->render("favs.tpl", array("name"      => "Maria",
                                          "favorites" => array("Raindrops on roses",
                                                               "Whiskers on Kittens",
                                                               "Bright copper kettles",
                                                               ...),
                                          ));

And in your template:

    <h1>A few of my favorite things</h1>
    <p>Hi, my name is <?=$name?>, and here are a few of my favorite things.</p>
    
    <ul>
        <?php foreach($favorites as $item): ?>
        <li><?=$item?></li>
        <?php endforeach; ?>
    </ul>

Template Inheritance
--------------------

Mason also supports (single) template inheritance.  See the examples
directory for a full example.

    index.tpl
    ...
    <title>
        <?php $block("title");?>
            Default Title
        <?php $end();?>
    </title>
    ...
    <body>
        <?php $block("content");?>
            Default Content.
        <?php $end();?>
    </body>
    ...

And in your derived template:

    derived_and_contrived.tpl
    ...
    <!-- Assignment makes it obvious that you can only extend one template per file. -->
    <?php $extend = "index.tpl";?>
    
    <?php $block("title");?>
    Derived and contrived
    <?php $end();?>

    <?php $block("content");?>
    New content for derived_and_contrived.tpl.
    <?php $end();?>

Why
===

Read the above link!  No, but seriously many people forget that PHP
started out as a template language and get wrapped up in trying to
write a complex meta-language, when all they really need is to echo
some variables into a page.  Mason Templates are the minimal amount of
wrapper code to take an array of values and dump them into a template
file.  Mason Templates save you from having to write the (albeit
small) amount of boilerplate required to use PHP as your template
engine.  If you're starting a project from scratch, or considering
replacing your template engine why not give Mason Templates a try?