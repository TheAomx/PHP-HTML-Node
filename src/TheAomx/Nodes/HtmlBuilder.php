<?php

namespace TheAomx\Nodes;

require_once 'HtmlNode.php';
require_once 'NodeAttribute.php';
require_once 'TextNode.php';

class HtmlBuilder {
    private $node;
    
    public function __construct($tag) {
        $this->node = new HtmlNode($tag);
    }
    
    public function add_node (Node $node) {
        $this->node->addChildNode($node);
        return $this;
    }
    
    public function add_attribute (NodeAttribute $attribute) {
        $this->node->addAttribute($attribute);
        return $this;
    }
    
    private function sanitizeString ($string) {
        return htmlentities($string, ENT_QUOTES|ENT_XHTML, 'UTF-8', true);
    }
    
    public function attribute($name, $value) {
        $attribute = new NodeAttribute($name, $value);
        $this->node->addAttribute($attribute);
        return $this;
    }
    
    public function s_attribute($name, $value) {
        $attribute = new NodeAttribute($name, $this->sanitizeString($value));
        $this->node->addAttribute($attribute);
        return $this;
    }
    
    /**
     * Add a text node to the html node without sanitizing the input.
     * @param String $value
     * @return \Nodes\HtmlBuilder
     */
    
    public function text ($value) {
        $text = new TextNode($value);
        $this->node->addChildNode($text);
        return $this;
    }
    
    /**
     * Add a text node to the html node and sanitize the input string with htmlentities.
     * @param type $value
     * @return \Nodes\HtmlBuilder
     */
    
    public function s_text ($value) {
        $s_value = $this->sanitizeString($value);
        $text = new TextNode($s_value);
        $this->node->addChildNode($text);
        return $this;
    }
    
    /**
     * 
     * @return HtmlNode
     */
    
    public function build () {
        return $this->node;
    }
}
