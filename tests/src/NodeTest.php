<?php

namespace Nodes;

require_once 'root.php';

require_once get_src_folder() . 'HtmlNode.php';
require_once get_src_folder() . 'TextNode.php';

class NodeTest extends \PHPUnit_Framework_TestCase {
    protected function setUp() {
        Indentation::$indentationCharacter = "";
        Indentation::$indentationDepth = 0;
        Indentation::$lineBreaker = "";
    }

    protected function tearDown() {
        
    }
    
    public function test_simple_paragraph_node () {
        $node = new \Nodes\HtmlNode("p");
        $text = new \Nodes\TextNode("Hello World");
        $node->addChildNode($text);
        $expected = '<p>Hello World</p>';
        $this->assertEquals($expected, $node->getHtml());
    }
    
    public function test_paragraph_node_with_attribute() {
        $node = new \Nodes\HtmlNode("p");
        $node->addAttribute(new \Nodes\NodeAttribute("style", "color:red;"));
        $text = new \Nodes\TextNode("Hello World");
        $node->addChildNode($text);
        $expected = '<p style="color:red;">Hello World</p>';
        $this->assertEquals($expected, $node->getHtml());
    }
    
    public function test_nested_paragraph_node () {
        $p1 = new \Nodes\HtmlNode("p");
        $p2 = new \Nodes\HtmlNode("p");
        $text = new \Nodes\TextNode("Hello World");
        $p1->addChildNode($p2);
        $p2->addChildNode($text);
        $expected = '<p><p>Hello World</p></p>';
        $this->assertEquals($expected, $p1->getHtml());
    }
    
    public function test_multiple_attributes_for_node () {
        $script = new \Nodes\HtmlNode("script");
        $script->addAttribute(new \Nodes\NodeAttribute("type", "text/javascript"));
        $script->addAttribute(new \Nodes\NodeAttribute("src", "blubber.js"));
        $expected = '<script type="text/javascript" src="blubber.js"></script>';
        $this->assertEquals($expected, $script->getHtml());
    }
    
    public function test_get_of_already_set_attribute () {
        $node = new \Nodes\HtmlNode("p");
        $expectedAttribute = new \Nodes\NodeAttribute("style", "color:red;");
        $node->addAttribute($expectedAttribute);
        $got_attribute = $node->getAttribute("style");
        $this->assertEquals($expectedAttribute->name, $got_attribute->name);
        $this->assertEquals($expectedAttribute->value, $got_attribute->value);
    }
    
    /**
     * @expectedException \RuntimeException
     */
    
    public function test_get_of_not_set_attribute () {
        $node = new \Nodes\HtmlNode("p");
        $node->getAttribute("style");
    }

}
