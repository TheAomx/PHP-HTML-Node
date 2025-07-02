<?php

namespace TheAomx\Nodes;

require_once 'HtmlNode.php';
require_once 'NodeAttribute.php';
require_once 'TextNode.php';

class HtmlBuilder {
    private $node;
    
    public function __construct(string $tag) {
        $this->node = new HtmlNode($tag);
    }
    
    public function add_node (Node $node): HtmlBuilder {
        $this->node->addChildNode($node);
        return $this;
    }
    
    public function add_attribute (NodeAttribute $attribute): HtmlBuilder {
        $this->node->addAttribute($attribute);
        return $this;
    }
    
    private function sanitizeString (string $string): string {
        return htmlentities($string, ENT_QUOTES|ENT_XHTML, 'UTF-8', true);
    }
    
    public function attribute(string $name, string $value): HtmlBuilder {
        $attribute = new NodeAttribute($name, $value);
        $this->node->addAttribute($attribute);
        return $this;
    }
    
    public function attr(string $name, string $value): HtmlBuilder {
        return $this->attribute($name, $value);
    }
    
    public function s_attribute(string $name, string $value): HtmlBuilder {
        $attribute = new NodeAttribute($name, $this->sanitizeString($value));
        $this->node->addAttribute($attribute);
        return $this;
    }
    
    public function s_attr(string $name, string $value): HtmlBuilder {
        return $this->s_attribute($name, $value);
    }
    
    /**
     * Add a text node to the html node without sanitizing the input.
     * @param String $value
     * @return \Nodes\HtmlBuilder
     */
    
    public function text (string $value): HtmlBuilder {
        $text = new TextNode($value);
        $this->node->addChildNode($text);
        return $this;
    }
    
    /**
     * Add a text node to the html node and sanitize the input string with htmlentities.
     * @param type $value
     * @return \Nodes\HtmlBuilder
     */
    
    public function s_text (string $value): HtmlBuilder {
        $s_value = $this->sanitizeString($value);
        $text = new TextNode($s_value);
        $this->node->addChildNode($text);
        return $this;
    }
    
    /**
     * 
     * @return HtmlNode
     */
    
    public function build (): HtmlNode {
        return $this->node;
    }
}
