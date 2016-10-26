<?php

namespace Nodes;

require_once 'root.php';
require_once get_src_folder() . 'HtmlNode.php';
require_once get_src_folder() . 'TextNode.php';

class IndentationTest  extends \PHPUnit_Framework_TestCase {
    protected function setUp() {
        Indentation::$indentationCharacter = " ";
        Indentation::$indentationDepth = 1;
        Indentation::$lineBreaker = "\n";
    }
    
    public function test_one_indentation_depth () {
        $node = new \Nodes\HtmlNode("p");
        $text = new \Nodes\TextNode("Hello World");
        $node->addChildNode($text);
        $expected = "<p>\n"
                . " Hello World\n"
                . "</p>\n";
        $this->assertEquals($expected, $node->getHtml());
    }
    
    public function test_two_indentation_depth () {
        $p = new \Nodes\HtmlNode("p");
        $span = new \Nodes\HtmlNode("span");
        $text = new \Nodes\TextNode("Hello World");
        $p->addChildNode($span);
        $span->addChildNode($text);
        $expected = "<p>\n"
                . " <span>\n"
                . "  Hello World\n"
                . " </span>\n"
                . "</p>\n";
        $this->assertEquals($expected, $p->getHtml());
    }
    
    public function test_two_indentation_depth_with_inline_element () {
        $head = new \Nodes\HtmlNode("head");
        $script = new \Nodes\HtmlNode("script");
        $script->addAttribute(new \Nodes\NodeAttribute("type", "text/javascript"));
        $script->addAttribute(new \Nodes\NodeAttribute("src", "test.js"));
        $head->addChildNode($script);
        $expected = "<head>\n"
                . " <script type=\"text/javascript\" src=\"test.js\" />\n"
                . "</head>\n";
        $this->assertEquals($expected, $head->getHtml());
    }
}
