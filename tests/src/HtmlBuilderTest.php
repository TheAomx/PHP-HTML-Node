<?php

namespace Nodes;

require_once 'root.php';
require_once get_src_folder() . 'HtmlBuilder.php';

use \TheAomx\Nodes\HtmlNode as HtmlNode;
use \TheAomx\Nodes\Indentation as Indentation;
use PHPUnit\Framework\TestCase as Testcase;

class HtmlBuilderTest extends Testcase {
    #[\Override]
    protected function setUp() : void {
        Indentation::$indentationCharacter = "";
        Indentation::$indentationDepth = 0;
        Indentation::$lineBreaker = "";
    }

    public function test_simple_builder() {
       $builder = HtmlNode::get_builder("p")->attribute("style", "color: red;")->
                   attribute("align", "left")->text("Hello World");
       $node = $builder->build();
       $expected = '<p style="color: red;" align="left">Hello World</p>';
       $this->assertEquals($expected, $node->getHtml());
    }

    public function test_nested_builder () {
       $builder = HtmlNode::get_builder("p")->attribute("style", "color: red;")->
                     attribute("align", "left");
       $b = HtmlNode::get_builder("b")->text("Hello World")->build();
       $builder->add_node($b);
       $p = $builder->build();
       $expected = '<p style="color: red;" align="left"><b>Hello World</b></p>';
       $this->assertEquals($expected, $p->getHtml());
    }
    
    public function test_node_without_endtag () {
        $builder = HtmlNode::get_builder("br");
        $br = $builder->build();
        $expected = '<br />';
        $this->assertEquals($expected, $br->getHtml());
    }
    
    public function test_appended_empty_node_is_ignored() {
        $builder = HtmlNode::get_builder("p");
        $builder->add_node(HtmlNode::get_empty());
        $builder->add_node(HtmlNode::get_builder("b")->s_text("Hello World")->build());
        $p = $builder->build();
        $expected = '<p><b>Hello World</b></p>';
        $this->assertEquals($expected, $p->getHtml());
    }       
    
    public function test_integration_test_with_full_site() {
        $html = HtmlNode::get_builder("html")->attr("lang", "de")->build();

        $head = HtmlNode::get_builder("head")->build();
        $charset = HtmlNode::get_builder("meta")->attr("charset", "utf-8")->build();
        $viewport = HtmlNode::get_builder("meta")->attr("name", "viewport")->
                        attr("content", "width=device-width, initial-scale=1.0")->build();
        $title = HtmlNode::get_builder("title")->text("Hello World Site")->build();
        $head->addChildNode($charset)->addChildNode($viewport)->addChildNode($title);
        
        $body = HtmlNode::get_builder("body")->build();
        $html->append($head)->append($body);
        
        $h1 = HtmlNode::get_builder("h1")->text("Welcome stranger!")->build();
        $p = HtmlNode::get_builder("p")->attr("style", "color: red;")->
                        attribute("align", "left")->build();
        $b = HtmlNode::get_builder("b")->s_text("Hello World with umlaute äöü&%")->build();
        $p->append($b);

        $body->append($h1)->append($p);
        
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
}
