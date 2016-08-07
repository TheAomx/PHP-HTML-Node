<?php

namespace Nodes;

class NodeAttribute {
    public $name, $value;
    
    public function __construct($name, $value) {
        $this->name = $name;
        $this->value = $value;
    }
    
    public function getHtml() {
        return " $this->name=\"$this->value\"";
    }
    
    public function __toString() {
        return $this->getHtml();
    }
}
