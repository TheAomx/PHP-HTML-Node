<?php

namespace Nodes;

require_once 'root.php';

require_once get_src_folder() . 'HtmlNode.php';
require_once get_src_folder() . 'TextNode.php';

use \TheAomx\Nodes\HtmlNode as HtmlNode;
use \TheAomx\Nodes\TextNode as TextNode;
use \TheAomx\Nodes\NodeAttribute as NodeAttribute;
use \TheAomx\Nodes\Indentation as Indentation;

class NodeTest extends \PHPUnit_Framework_TestCase {
    protected function setUp() {
        Indentation::$indentationCharacter = "";
        Indentation::$indentationDepth = 0;
        Indentation::$lineBreaker = "";
    }

    protected function tearDown() {
        
    }
    
    public function test_simple_paragraph_node () {
        $node = new HtmlNode("p");
        $text = new TextNode("Hello World");
        $node->addChildNode($text);
        $expected = '<p>Hello World</p>';
        $this->assertEquals($expected, $node->getHtml());
    }
    
    public function test_paragraph_node_with_attribute() {
        $node = new HtmlNode("p");
        $node->addAttribute(new NodeAttribute("style", "color:red;"));
        $text = new TextNode("Hello World");
        $node->addChildNode($text);
        $expected = '<p style="color:red;">Hello World</p>';
        $this->assertEquals($expected, $node->getHtml());
    }
    
    public function test_nested_paragraph_node () {
        $p1 = new HtmlNode("p");
        $p2 = new HtmlNode("p");
        $text = new TextNode("Hello World");
        $p1->addChildNode($p2);
        $p2->addChildNode($text);
        $expected = '<p><p>Hello World</p></p>';
        $this->assertEquals($expected, $p1->getHtml());
    }
    
    public function test_multiple_attributes_for_node () {
        $script = new HtmlNode("script");
        $script->addAttribute(new NodeAttribute("type", "text/javascript"));
        $script->addAttribute(new NodeAttribute("src", "blubber.js"));
        $expected = '<script type="text/javascript" src="blubber.js" />';
        $this->assertEquals($expected, $script->getHtml());
    }
    
    public function test_get_of_already_set_attribute () {
        $node = new HtmlNode("p");
        $expectedAttribute = new NodeAttribute("style", "color:red;");
        $node->addAttribute($expectedAttribute);
        $got_attribute = $node->getAttribute("style");
        $this->assertEquals($expectedAttribute->name, $got_attribute->name);
        $this->assertEquals($expectedAttribute->value, $got_attribute->value);
    }
    
    /**
     * @expectedException \RuntimeException
     */
    
    public function test_get_of_not_set_attribute () {
        $node = new HtmlNode("p");
        $node->getAttribute("style");
    }

}
