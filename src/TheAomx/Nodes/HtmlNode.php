<?php

namespace TheAomx\Nodes;

require_once 'Node.php';
require_once 'EmptyNode.php';
require_once 'NodeAttribute.php';
require_once 'HtmlBuilder.php';
require_once 'Indentation.php';

class HtmlNode implements Node {
    private $tag;
    private $attributes = array();
    private $classes = array();
    private $childNodes = array();
    
    public function __construct($tag = "") {
        $this->tag = $tag;
    }
    
    public function addChildNode (Node $node) {
        if ($node == null) {
            return;
        }
        
        array_push($this->childNodes, $node);
        return $this;
    }
    
    public function append (Node $node) {
        return $this->addChildNode($node);
    }
    
    public function addClass ($class) {
        if ($class == null || $this->hasClass($class)) {
            return;
        }
        
        array_push($this->classes, $class);
        return $this;
    }
    
    public function addClasses($classes) {
        $exploded = explode(" ", trim($classes));
    
        foreach ($exploded as $class) {
            $this->addClass(trim($class));
        }
    }
    
    public function hasClass($class) {
        return in_array($class, $this->classes);
    }
    
    public function getClasses() {
        return $this->classes;
    }
    
    public function removeClass($class) {
        if ($this->hasClass($class)) {
            $key = array_search($class,$this->classes);
            unset($this->classes[$key]);
        }

        return $this;
    }
    
    public function removeAllClasses() {
        $this->classes = array();
        return $this;
    }
    
    public function attribute ($name, $value) {
        $attribute = new NodeAttribute($name, $value);
        $this->addAttribute($attribute);
        return $this;
    }
    
    public function attr($name, $value) {
        return $this->attribute($name, $value);
    }
    
    public function child (Node $node) {
        $this->addChildNode($node);
        return $this;
    }
    
    public function addAttribute (NodeAttribute $attribute) {
        if ($attribute == null) {
            return;
        }

        if ($attribute->name === "class") {
            $this->addClasses($attribute->value);
        } else {
            $this->attributes[$attribute->name] = $attribute;
        }
    }
    
    /**
     * 
     * @param String $attribute
     * @return NodeAttribute
     * @throws \RuntimeException
     */
    
    public function getAttribute ($attribute) {
        if ($attribute === "class") {
            return new NodeAttribute("class", $this->getClassAttribute());
        }
        
        if (!key_exists($attribute, $this->attributes)) {
            throw new \RuntimeException("Attribute $attribute in node $this->tag does not exist yet!");
        }
        
        return $this->attributes[$attribute];
    }
    
    public function hasChildren() {
        return count($this->childNodes) != 0;
    }
    
    private function getClassAttribute() {
        $classAttribute = "";
        foreach ($this->classes as $class) {
            $classAttribute .= $class .  " ";
        }
        return substr($classAttribute, 0, -1);
    }
    
    public function getOpeningTag() {
        $html = "";
        $html .= "<" . $this->tag;
        
        $html .= count($this->classes) == 0 ? '' : ' class="' . $this->getClassAttribute() . '"';
        
        foreach ($this->attributes as $attribute) {
            $html .= $attribute->getHtml();
        }
        
        $html .= $this->hasChildren() ? ">" : " />";
        return $html;
    }
    
    public function getEndTag() {
        return "</{$this->tag}>";
    }
    
    public function format($identation = 0) {
        return $this->getHtml($identation);
    }
    
    private function createTagEndCallback () {
        $self = $this;
        return function ($identation) use ($self) {
            return Indentation::indent($identation, $self->getEndTag());
        };
    }
    
    public function getHtml($indentationDepth = 0) {
        $html = Indentation::indent($indentationDepth, $this->getOpeningTag());
        
        if ($this->hasChildren()) {
            foreach ($this->childNodes as $childNode) {
                $html .= $childNode->format($indentationDepth + Indentation::getIndentationDepth());
            }

            $html .= Indentation::indent($indentationDepth, $this->getEndTag());
        }

        return $html;
    }
    
    public function getHtmlIterative ($indentationDepth = 0) {
        $stack = array();
        array_push($stack, $this);
        
        $html = "";
        
        while (($node = array_pop($stack)) != null) {
            if (is_callable($node)) {
                $html .= $node($indentationDepth);
                $indentationDepth -= Indentation::getIndentationDepth();
                continue;
            }
            
            if ($node instanceof HtmlNode) {
                $html .= Indentation::indent($indentationDepth, $node->getOpeningTag());
                if ($node->hasChildren()) {
                    array_push($stack, $node->createTagEndCallback());
                    $indentationDepth += Indentation::getIndentationDepth();
                }
                foreach (array_reverse($node->childNodes) as $childNode) {
                    array_push($stack, $childNode);
                }
            } else if ($node instanceof TextNode) {
                $html .= $node->format($indentationDepth);
                $indentationDepth -= Indentation::getIndentationDepth();
            }
        }
        
        return $html;
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
    
    public static function get_empty() {
        return new EmptyNode();
    }
}
