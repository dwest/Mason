<?php
/** Copyright (c) 2011, Daniel West All rights reserved.

Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions are
met:

Redistributions of source code must retain the above copyright notice,
this list of conditions and the following disclaimer.  Redistributions
in binary form must reproduce the above copyright notice, this list of
conditions and the following disclaimer in the documentation and/or
other materials provided with the distribution.  THIS SOFTWARE IS
PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY
EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR
PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR
CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,
EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO,
PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR
PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF
LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/

namespace flash;

require_once("util.php");

class Flash {

  protected $tplDir;

  public function __construct($tplDir="./tpl/")
  {
    $this->tplDir = realpath($tplDir);
  }

  /**
   * render

   *     Render the template file and return it's output.  Will also
   * render any extended templates to produce the final output.
   */
  public function render($file, Array $context = array())
  {
    /**
     *   Some functions and variables that need to be in scope
     * inside the template.
     */
    $extend = NULL;

    /* Stores the blocks content */
    $blocks = array();

    /*
     * block
     *    Starts the output buffer and stores the blocks' content
     * in the blocks array as soon as it encounters and endblock.
     * If the block value was already defined it drops the content
     * on the floor.
     *
     * @param String $name - block name
     * @param Array $blocks - blocks array to store the content in.
     */
    $block = function($name) use (&$blocks) {
      /* Using ob callback to store the block content. */
      ob_start(function($output) use(&$blocks, $name){
          if(!isset($blocks[$name]))
            $blocks[$name] = $output;

          return $blocks[$name];
        });
    };

    $endblock = function() {
      /* End the last opened block */
      ob_get_flush();
    };
    /* End util methods */

    $file = getFileRootedAtDir($this->tplDir, $file);
    /**
     *    Because extract is called after the utils are defined you *can*
     * override them.  Therefor you should be careful about using the
     * names $block, $endblock, $blocks, or $extends in your context
     * unless you really want to replace them.
     */
    extract($context);

    /* Process at least once, keep going until we stop extending templates */
    do {
      $done = TRUE;
      ob_start();
      include($file);
      $output = ob_get_clean();

      if(!is_null($extend)) {
        $done = FALSE;
        $file = getFileRootedAtDir($this->tplDir, $extend);
        $extend = NULL;
      }
    } while (!$done);

    return $output;
  }

}

//?>
