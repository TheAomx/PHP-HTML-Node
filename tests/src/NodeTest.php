<?php

namespace Nodes;

require_once 'root.php';

require_once get_src_folder() . 'HtmlNode.php';
require_once get_src_folder() . 'TextNode.php';
require_once get_src_folder() . 'EmptyNode.php';

use \TheAomx\Nodes\HtmlNode as HtmlNode;
use \TheAomx\Nodes\TextNode as TextNode;
use \TheAomx\Nodes\EmptyNode as EmptyNode;
use \TheAomx\Nodes\NodeAttribute as NodeAttribute;
use \TheAomx\Nodes\Indentation as Indentation;
use PHPUnit\Framework\TestCase as Testcase;

class NodeTest extends Testcase {
    protected function setUp() : void {
        Indentation::$indentationCharacter = "";
        Indentation::$indentationDepth = 0;
        Indentation::$lineBreaker = "";
    }

    protected function tearDown() : void {
        
    }
    
    public function test_simple_paragraph_node () {
        $node = new HtmlNode("p");
        $text = new TextNode("Hello World");
        $node->addChildNode($text);
        $expected = '<p>Hello World</p>';
        $this->assertEquals($expected, $node->getHtml());
    }
    
    public function test_empty_node_evaluates_to_nothing() {
        $node = new HtmlNode("p");
        $text = new TextNode("Hello World");
        $empty = new EmptyNode();
        $node->addChildNode($text);
        $node->addChildNode($empty);
        
        $expected = '<p>Hello World</p>';
        $this->assertEquals($expected, $node->getHtml());
    }
    
    
    public function test_can_add_one_class_to_html_node() {
        $node = new HtmlNode("p");
        $node->addClass("test-class");
        
        $text = new TextNode("Hello World");
        $node->addChildNode($text);
        
        $expected = '<p class="test-class">Hello World</p>';
        $this->assertEquals($expected, $node->getHtml());
    }
    
    public function test_can_add_multiple_classes_to_html_node() {
        $node = new HtmlNode("p");
        $node->addClass("test-class");
        $node->addClass("other-class");
        $node->addClass("another-class");
        
        $text = new TextNode("Hello World");
        $node->addChildNode($text);
        
        $expected = '<p class="test-class other-class another-class">Hello World</p>';
        $this->assertEquals($expected, $node->getHtml());
        $this->assertEquals("test-class other-class another-class", 
                            $node->getAttribute("class")->value);
    }
    
    public function test_has_class_detects_added_classes() {
        $node = new HtmlNode("p");
        $node->addClass("test-class");
        $node->addClass("other-class");
        
        $this->assertTrue($node->hasClass("test-class"));
        $this->assertTrue($node->hasClass("other-class"));
        $this->assertFalse($node->hasClass("not-set-class"));
    }
    
     public function test_get_classes_returns_added_classes() {
        $classes = array("test-class", "other-class", "another-class");
        $node = new HtmlNode("p");
        foreach ($classes as $class) {
            $node->addClass($class);
        }
        
        $this->assertEquals(count($classes), count($node->getClasses()));
        
        $i = 0;
        foreach ($node->getClasses() as $class) {
            $this->assertEquals($classes[$i], $class);
            $i++;
        }
     }
    
    public function test_can_remove_added_classes() {
        $node = new HtmlNode("p");
        $node->addClass("test-class");
        $node->addClass("other-class");
        $node->removeClass("test-class");
    
        $text = new TextNode("Hello World");
        $node->addChildNode($text);
        
        $expected = '<p class="other-class">Hello World</p>';
        $this->assertFalse($node->hasClass("test-class"));
        $this->assertEquals($expected, $node->getHtml());
    }
    
    public function test_add_multiple_classes_with_node_attribute() {
        $node = new HtmlNode("p");
        $node->addAttribute(new NodeAttribute("class", "test-class other-class  another-class"));
        $node->removeClass("another-class");
    
        $text = new TextNode("Hello World");
        $node->addChildNode($text);
        
        $expected = '<p class="test-class other-class">Hello World</p>';
        $this->assertTrue($node->hasClass("test-class"));
        $this->assertTrue($node->hasClass("other-class"));
        $this->assertFalse($node->hasClass("another-class"));
        $this->assertEquals($expected, $node->getHtml());
    }
    
    public function test_remove_multiple_classes_with_remove_all() {
        $node = new HtmlNode("p");
        $node->addAttribute(new NodeAttribute("class", "test-class other-class  another-class"));
        $node->removeAllClasses();
        $node->addClass("a");
        
        $text = new TextNode("Hello World");
        $node->addChildNode($text);
        
        $expected = '<p class="a">Hello World</p>';
        $this->assertTrue($node->hasClass("a"));
        $this->assertFalse($node->hasClass("test-class"));
        $this->assertFalse($node->hasClass("other-class"));
        $this->assertFalse($node->hasClass("another-class"));
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
    
    private function generateDeeplyNestedOutput($depth) {
        $output = "<p>";

        for ($i = 0; $i < $depth; $i++) {
            $output .= "<b>";
        }

        $output .= "test";

        for ($i = 0; $i < $depth; $i++) {
            $output .= "</b>";
        }

        $output .= "</p>";
        return $output;
    }

    public function test_deeply_recursive_nodes () {
        $recursionDepth = 5000;
        $rootNode = new HtmlNode("p");
        
        $parentNode = $rootNode;
        for ($i = 0; $i < $recursionDepth; $i++) {
            $b = new HtmlNode("b");
            $parentNode->append($b);
            $parentNode = $b;
        }
        $parentNode->append(new TextNode("test"));
        
        $this->assertEquals($this->generateDeeplyNestedOutput($recursionDepth),
                            $rootNode->getHtml());
    }

    public function test_get_of_not_set_attribute () {
        $node = new HtmlNode("p");
        $this->expectException(\RuntimeException::class);
        $node->getAttribute("style");
    }

}
