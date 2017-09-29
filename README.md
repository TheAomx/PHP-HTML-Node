# PHP-HTML-Node
This is a project that allows you to represent arbritary HTML-AST-Node-Trees in your PHP-Code. This is useful for several reasons. If you use these classes in your projects
you are working on trees of nodes instead of strings. Therefore you can pass these instances from one method to another and manipulate the trees directly. Only if the root of the tree
or another part of the tree is printed with echo or print then the tree gets converted into the right HTML-Tags. Please take a look at the index.php that creates a sample website and
you can see the relevant api-calls there.

## simple example which creates an HTML5 barebone website
```php
use \TheAomx\Nodes\HtmlNode as HtmlNode;
use \TheAomx\Nodes\Indentation as Indentation;

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

// only at this step the root ast-node $html gets converted to a string!
echo $html;
```

The resulting html of the above example would be like this. If you want you can
disable the pretty printing of the html output, such that no blank lines or spaces
have to be transferred.

```html
<html lang="de">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>
      Hello World Site
    </title>
  </head>
  <body>
    <h1>
      Welcome stranger!
    </h1>
    <p style="color: red;" align="left">
      <b>
        Hello World with umlaute &auml;&ouml;&uuml;&amp;%
      </b>
    </p>
  </body>
</html>
```
