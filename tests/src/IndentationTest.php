<?php

namespace Nodes;

require_once 'root.php';
require_once get_src_folder() . 'HtmlNode.php';
require_once get_src_folder() . 'TextNode.php';

use \TheAomx\Nodes\HtmlNode as HtmlNode;
use \TheAomx\Nodes\TextNode as TextNode;
use \TheAomx\Nodes\NodeAttribute as NodeAttribute;
use \TheAomx\Nodes\Indentation as Indentation;
use PHPUnit\Framework\TestCase as Testcase;

class IndentationTest  extends Testcase {
    protected function setUp() : void {
        Indentation::$indentationCharacter = " ";
        Indentation::$indentationDepth = 1;
        Indentation::$lineBreaker = "\n";
    }
    
    public function test_one_indentation_depth () {
        $node = new HtmlNode("p");
        $text = new TextNode("Hello World");
        $node->addChildNode($text);
        $expected = "<p>\n"
                . " Hello World\n"
                . "</p>\n";
        $this->assertEquals($expected, $node->getHtml());
    }
    
    public function test_two_indentation_depth () {
        $p = new HtmlNode("p");
        $span = new HtmlNode("span");
        $text = new TextNode("Hello World");
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
        $head = new HtmlNode("head");
        $script = new HtmlNode("script");
        $script->addAttribute(new NodeAttribute("type", "text/javascript"));
        $script->addAttribute(new NodeAttribute("src", "test.js"));
        $head->addChildNode($script);
        $expected = "<head>\n"
                . " <script type=\"text/javascript\" src=\"test.js\" />\n"
                . "</head>\n";
        $this->assertEquals($expected, $head->getHtml());
    }
}
