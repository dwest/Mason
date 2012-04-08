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

require_once("util.php");

class Blocks extends \ArrayObject {}
/**
 * Wrapper class for all of the available template methods.  Use
 * getMethods to obtain a copy of these methods for inclusion in a
 * template. 
 *
 * @package mason
 */

class TemplateMethods {
  /**
   * These methods are extracted inside of render so that they are
   * available within the template.
   */

  protected $methods = array();

  /**
   * Getter method for template methods array.
   */
  public function getMethods() {
    return $this->methods;
  }

  /**
   * Define the template methods.
   */
  public function __construct() {
    $this->methods['block'] = function($blocks, $name) {
      /* Using ob callback to store the block content. */
      ob_start(function($output) use(&$blocks, $name){
          if(!isset($blocks[$name]))
            $blocks[$name] = $output;

          return $blocks[$name];
        });
    };

    $this->methods['prepend'] = function($blocks, $name) {
      ob_start(function($output) use(&$blocks, $name){
          if(!isset($blocks[$name]))
            $blocks[$name] = $output;
          else
            $blocks[$name] .= $output;

          return $blocks[$name];
        });
    };

    $this->methods['append'] = function($blocks, $name) {
      ob_start(function($output) use(&$blocks, $name){
          if(!isset($blocks[$name]))
            $blocks[$name] = $output;
          else
            $blocks[$name] = $output . $blocks[$name];

          return $blocks[$name];
        });
    };

    $this->methods['end'] = function() {
      /* End the last opened block */
      ob_get_flush();
    };

    $methods = $this->methods;
    $this->methods['render'] = Y(function($render) use ($methods) {
        return curry(function($filefinder, $file, $context = array()) use ($render, $methods) {
            /**
             * This allows us to sidestep an issue with
             * func_get_args.  Namely that we aren't allowed to pass
             * by reference and use that function to simulate varargs.
             */
            $blocks = new Blocks();

            foreach($methods as $name=>$method) {
              if(in_array($name, array('block', 'prepend', 'append')))
                $$name = curry($method, $blocks);
              else
                $$name = $method;
            }
            $render = $render($filefinder);

            $file = $filefinder->getFile($file);

            /**
             * Because the user context is extracted after the methods you <em>can
             * override them!</em>  Therefor you should be careful about using the
             * names $block, $endblock, $blocks, or $extends in your context
             * unless you really want to replace them.
             */
            $extend = NULL; // make sure extend is always set but
                            // allow a user to override it with a tag
            extract($context);

            /* Process at least once, keep going until we stop extending templates */
            do {
              $done = TRUE;
              ob_start();
              include($file);
              $output = ob_get_clean();

              if(!is_null($extend)) {
                $done = FALSE;
                $file = $filefinder->getFile($extend);
                $extend = NULL;
              }
            } while (!$done);

            return $output;
          });
      });
  }
  
}
//?>