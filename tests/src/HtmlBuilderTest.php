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
    
    public function test_node_without_endtag () {
        $builder = \Nodes\HtmlNode::get_builder("br");
        $br = $builder->build();
        $expected = '<br />';
        $this->assertEquals($expected, $br->getHtml());
    }
    
    public function test_integration_test_with_full_site() {
        $html = HtmlNode::get_builder("html")->attribute("lang", "de")->build();

        $head = HtmlNode::get_builder("head")->build();
        $charset = HtmlNode::get_builder("meta")->attribute("charset", "utf-8")->build();
        $viewport = HtmlNode::get_builder("meta")->attribute("name", "viewport")->
                        attribute("content", "width=device-width, initial-scale=1.0")->build();
        $title = HtmlNode::get_builder("title")->text("Hello World Site")->build();
        $head->addChildNode($charset);
        $head->addChildNode($viewport);
        $head->addChildNode($title);

        $body = HtmlNode::get_builder("body")->build();

        $html->addChildNode($head);
        $html->addChildNode($body);

        $h1 = HtmlNode::get_builder("h1")->text("Welcome stranger!")->build();

        $p = HtmlNode::get_builder("p")->attribute("style", "color: red;")->
                        attribute("align", "left")->build();
        $b = HtmlNode::get_builder("b")->s_text("Hello World with umlaute äöü&%")->build();
        $p->addChildNode($b);

        $body->addChildNode($h1);
        $body->addChildNode($p);
        
        $expected =  '<html lang="de">'
                    . '<head>'
                        . '<meta charset="utf-8" />'
                        . '<meta name="viewport" content="width=device-width, initial-scale=1.0" />'
                        . '<title>Hello World Site</title>'
                    . '</head>'
                    . '<body>'
                        . '<h1>Welcome stranger!</h1>'
                        . '<p style="color: red;" align="left">'
                            . '<b>Hello World with umlaute &auml;&ouml;&uuml;&amp;%</b>'
                        . '</p>'
                    . '</body>'
                    .'</html>';
        $this->assertEquals($expected, $html->getHtml());
    }

    /**
     * @expectedException \RuntimeException
     */

    public function test_null_builder() {
        // this doesnt make mich sense... expect exception...
        \Nodes\HtmlNode::get_builder(null);
    }

    /**
     * @expectedException \RuntimeException
     */

    public function test_number_builder() {
        // this doesnt make mich sense... expect exception...
        \Nodes\HtmlNode::get_builder(1);
    }
    

}
