<?php
require_once "TheAomx/Nodes/HtmlBuilder.php";

use \TheAomx\Nodes\HtmlNode as HtmlNode;
use \TheAomx\Nodes\Indentation as Indentation;

error_reporting(E_ALL);
ini_set('display_errors', 1);

Indentation::$indentationCharacter = " ";
Indentation::$indentationDepth = 2;
Indentation::$lineBreaker = "\n";

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
?>
<!doctype html>
<?php
echo $html;
?>
