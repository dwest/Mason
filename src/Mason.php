<?php
/**
 * Copyright (c) 2011, Daniel West
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are
 * met:
 *
 * 1) Redistributions of source code must retain the above copyright notice,
 * this list of conditions and the following disclaimer.
 *
 * 2) Redistributions in binary form must reproduce the above
 * copyright notice, this list of conditions and the following disclaimer
 * in the documentation and/or other materials provided with the
 * distribution.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */

namespace mason;

require_once(dirname(__FILE__)."/FileFinder.php");
require_once(dirname(__FILE__)."/TemplateMethods.php");

class Mason
implements FileFinder
{

  protected $tplDirs = array();

  protected $before;

  protected $finder;

  public function __construct($tplDir="./tpl/")
  {
    $this->addTplDir($tplDir);
  }

  /**
   * Prepends the directory to the list of template search paths.
   *
   * @param String $tplDir - directory to prepend to search paths
   * @return void
   */
  public function addTplDir($tplDir)
  {
    array_unshift($this->tplDirs, realpath($tplDir));
  }
  
  /**
   *     Prevent malicious template file names from leaving the template
   * directory.
   *
   * @param String $dir - the directory to constrain files to
   * @param String $file - the filename
   */
  protected function getFileRootedAtDir($dir, $file)
  {
    $filePath = array();

    foreach(explode("/", trim($file, "/")) as $element) {
      switch($element) {
      case ".":
        break;
      case "..":
        array_pop($filePath);
        break;
      default:
        array_push($filePath, $element);
      }
    }

    return $dir . "/" . implode("/", $filePath);
  }

  /**
   *     Disambiguates the template $name and returns the full path
   * to the first template it encounters with that name.  Template
   * directories are searched in the reverse order that they were
   * added.
   *
   * @param String $file - template file name
   * @return String - string containing full path to the file on success
   * @throws Exception - if no such file
   */
  public function getFile($file)
  {
    foreach($this->tplDirs as $dir) {
      $f = $this->getFileRootedAtDir($dir, $file);
      if(file_exists($f))
        return $f;
    }

    throw new \Exception("No such file: $file");
  }

  /**
   *     Get the directory that contains $file according to the file
   * resolver.
   *
   * @param String $file - template file name
   * @return String - directory containing the template file
   * @throws Exception - if no such file
   */
  public function getDir($file) {
    $path = explode("/", $this->getFile($file));
    array_pop($path);
    
    return implode("/", $path);
  }

  /**
   *     Render the template file and return it's output.  Will also
   * render any extended templates to produce the final output.
   *
   * @param String $file - name of the template file to render
   * @param Array $context - template variables
   * @return String - rendered template
   */
  public function render($file, Array $context = array())
  {
    /**
     * Bootstrap the render call to call the template render method.
     */
    $tm = new TemplateMethods();
    extract($tm->getMethods());

    $output = $render($this->getFileFinder(), $file, $context);
    return $output;
  }

  /**
   *     Set the callback to call before a file is included.  The name
   * of the file to include is passed as the first parameter.
   *
   * @param Callable $callback - function to call before a file is
   *   included
   */
  public function setBeforeInclude($callback) {
    if(is_callable($callback))
      $this->before = $callback;
    else
      throw \Exception("Your callback is not callable!");
  }

  /**
   *     Call the user defined callback before inclusion.
   *
   * @param String $file - name of the file that is included
   */
  public function beforeInclude($file) {
    if(is_callable($this->before))
      call_user_func($this->before, $file);
  }

  /**
   *     Get the class used to find files.  If you set the class to
   * something other than $this mason object then you should add your
   * template directories through that object instead of through
   * mason.
   *
   * @return FileFinder - the object used to find template files
   */
  public function getFileFinder() {
    if(isset($this->finder))
      return $this->finder;

    return $this;
  }

  /**
   * If you replace the object used to find template files then you
   * should set your template directories through that objects specific
   * interface.
   *
   * @param FileFinder $finder - the object used to find template
   * files
   */
  public function setFileFinder(FileFinder $finder) {
    $this->finder = $finder;
  }
  
}

//?>
