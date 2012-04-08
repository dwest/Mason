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

require_once("TemplateMethods.php");

class Mason {

  protected $tplDirs = array();

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
   * getFileRootedAtDir
   *     Prevent malicious template file names from leaving the template
   * directory.
   *
   * @param String $dir - the directory to constrain files to
   * @param String $file - the filename
   */
  function getFileRootedAtDir($dir, $file)
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
   * getFile
   *     Disambiguates the template $name and returns the full path
   * to the first template it encounters with that name.  Template
   * directories are searched in the reverse order that they were
   * added.
   *
   * @param String $name - template file name
   * @return mixed - String containing full path on success, false
   *   otherwise.
   */
  public function getFile($name)
  {
    foreach($this->tplDirs as $dir) {
      $file = $this->getFileRootedAtDir($dir, $name);
      if(file_exists($file))
        return $file;
    }

    throw new \Exception("No such file: $file");
  }

  /**
   * render
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

    $output = $render($this, $file, $context);
    return $output;
  }
}

//?>
