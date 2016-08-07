<?php

namespace Nodes;

require_once 'root.php';

require_once get_src_folder() . 'HtmlBuilder.php';

class HtmlBuilderTest extends \PHPUnit_Framework_TestCase {
     protected function setUp() {
        Indentation::$indentationCharacter = "";
        Indentation::$indentationDepth = 0;
        Indentation::$lineBreaker = "";
    }
    
    public function test_simple_builder() {
       $builder = \Nodes\HtmlNode::get_builder("p")->attribute("style", "color: red;")->
                   attribute("align", "left")->text("Hello World");
       $node = $builder->build();
       $expected = '<p style="color: red;" align="left">Hello World</p>';
       $this->assertEquals($expected, $node->getHtml());
    }
    
    public function test_nested_builder () {
       $builder = \Nodes\HtmlNode::get_builder("p")->attribute("style", "color: red;")->
                     attribute("align", "left");
       $b = \Nodes\HtmlNode::get_builder("b")->text("Hello World")->build();
       $builder->add_node($b);
       $p = $builder->build();
       $expected = '<p style="color: red;" align="left"><b>Hello World</b></p>';
       $this->assertEquals($expected, $p->getHtml());
    }

}
