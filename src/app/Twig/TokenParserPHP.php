<?php
/**
 * Implement php/endphp tags for Twig.
 *
 *      $twig = new Twig_Environment($loader);
 *      $twig->addFilter('php', new Twig_Filter_Function('TwigController::twigPhpFilter'));
 *      $twig->addTokenParser(new Twig_TokenParserPHP());
 */
class Twig_TokenParserPHP extends Twig_TokenParser
{
    /**
     * Parses a token and returns a node.
     *
     * @param Twig_Token $token A Twig_Token instance
     *
     * @return Twig_NodeInterface A Twig_NodeInterface instance
     */
    public function parse(Twig_Token $token)
    {
        $this->parser->getStream()->expect(Twig_Token::BLOCK_END_TYPE);
        $body = $this->parser->subparse(array($this, 'decideBlockEnd'), true);
        $this->parser->getStream()->expect(Twig_Token::BLOCK_END_TYPE);

        return new Twig_NodePHP($body, $token->getLine(), $this->getTag());
    } // parse()

    public function decideBlockEnd(Twig_Token $token)
    {
        return $token->test('endphp');
    } // decideBlockEnd()

    /**
     * Gets the tag name associated with this token parser.
     *
     * @param string The tag name
     */
    public function getTag()
    {
        return 'php';
    } // getTag()

} // class TwigTokenParser_PHP
