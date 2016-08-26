<?php

namespace Nodes;

require_once 'Node.php';
require_once 'NodeAttribute.php';
require_once 'HtmlBuilder.php';
require_once 'Indentation.php';

class HtmlNode implements Node {
    private $tag;
    private $attributes = array();
    private $childNodes = array();
    private $hasEndingTag = true;
    
    public function __construct($tag = "") {
        $this->tag = $tag;
    }
    
    public function addChildNode (Node $node) {
        if ($node == null) {
            return;
        }
        
        array_push($this->childNodes, $node);
    }
    
    public function addAttribute (NodeAttribute $attribute) {
        if ($attribute == null) {
            return;
        }
        
        //array_push($this->attributes, $attribute);
        $this->attributes[$attribute->name] = $attribute;
    }
    
    /**
     * 
     * @param String $attribute
     * @return NodeAttribute
     * @throws \RuntimeException
     */
    
    public function getAttribute ($attribute) {
        if (!key_exists($attribute, $this->attributes)) {
            throw new \RuntimeException("Attribute $attribute in node $this->tag does not exist yet!");
        }
        
        return $this->attributes[$attribute];
    }
    
    private function getOpeningTag() {
        $html = "";
        $html .= "<" . $this->tag;
        
        foreach ($this->attributes as $attribute) {
            $html .= $attribute->getHtml();
        }
        
        $html .= ($this->hasEndingTag) ? ">" : " />";
        return $html;
    }
    
    public function format($identation = 0) {
        return $this->getHtml($identation);
    }
    
    public function getHtml($identation = 0) {
        $html = Indentation::getIndentation($identation);
        $html .= $this->getOpeningTag();
        $html .= Indentation::getLineBreaker();
        
        if (!$this->hasEndingTag) {
            return $html;
        }
        
        foreach ($this->childNodes as $childNode) {
            $html .= $childNode->format($identation + Indentation::getIndentationDepth());
        }
        
        $html .= Indentation::getIndentation($identation);
        $html .= "</$this->tag>";
        $html .=  Indentation::getLineBreaker();
        return $html;
    }
    
    public function hasNoEndingTag () {
        $this->hasEndingTag = false;
    }
    
    public function __toString() {
        return $this->getHtml(0);
    }
    
    public static function get_builder($tag) {
        if ($tag == null) {
            throw new \RuntimeException("get_builder called with null");
        }
        
        if (!is_string($tag)) {
            throw new \RuntimeException("tag at get_builder must be a string");
        }
        
        return new HtmlBuilder($tag);
    }
}
