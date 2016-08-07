<?php
require_once "HtmlBuilder.php";
use \Nodes\HtmlNode as HtmlNode;

error_reporting(E_ALL);
ini_set('display_errors', 1);

$html = HtmlNode::get_builder("html")->attribute("lang", "de")->build();

$head = HtmlNode::get_builder("head")->build();
$charset = HtmlNode::get_builder("meta")->attribute("charset", "utf-8")->no_ending_tag()->build();
$viewport = HtmlNode::get_builder("meta")->attribute("name", "viewport")->
            attribute("content", "width=device-width, initial-scale=1.0")->no_ending_tag()->build();
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
?>
<!doctype html>
<?php
echo $html;
?>
