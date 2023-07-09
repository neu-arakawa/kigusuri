<?php
/*
 * Twig support for the php tag. This enables embedding of PHP syntax within
 * a Twig template.
 *
 *   {% <?php %}
 *   {% ?> %}
 *
 * @author     Bill Lee <bill@cachecrew.com>
 * @package    PiePHP
 * @since      Version 0.0
 * @copyright  Copyright (c) 2011, Pie Software Foundation
 * @license    http://www.piephp.com/license
 *
 * @see http://www.twig-project.org/doc/templates.html
 */

/**
 * Support for Twig_TokenParserPHP class
 */
class Twig_NodePHP extends Twig_Node
{
    public function __construct(Twig_NodeInterface $body, $lineno, $tag = null)
    {
        parent::__construct(array('body' => $body), array(), $lineno, $tag);
    }

    /**
     * Compiles the node to PHP.
     *
     * @param Twig_Compiler A Twig_Compiler instance
     */
    public function compile(Twig_Compiler $compiler)
    {
        $compiler->addDebugInfo($this);
        // if (version_compare(PHP_VERSION, '5.3.0') < 0) {
            $compiler
                ->write('{ // php'.PHP_EOL)
                ->indent()
                // ->subcompile($this->getNode('body') /*->getAttribute('data')*/)
                // ->write($this->getNode('body')->getAttribute('data'))
                ->write(str_replace("\n","\n            ", trim($this->getNode('body')->getAttribute('data'))).PHP_EOL)
                ->outdent()
                ->write('} // endphp'.PHP_EOL)
            ;
        // }
        // else { // Use anonymous function for better sandboxing
            // $compiler
                // ->write('$_fn = function ($context,$blocks) { // php'.PHP_EOL)
                // ->indent()
                // // ->subcompile($this->getNode('body') [>->getAttribute('data')<])
                // // ->write($this->getNode('body')->getAttribute('data'))
                // ->write(str_replace("\n","\n            ", trim($this->getNode('body')->getAttribute('data'))).PHP_EOL)
                // ->outdent()
                // ->write('}; // function'.PHP_EOL)
                // ->write('$_fn($context, $blocks); // endphp: execute!'.PHP_EOL)
            // ;
        // }
    } // compile()
} // class TwigNode_PHP
