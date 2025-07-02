<?php

namespace TheAomx\Nodes;

class NodeAttribute {
    public $name, $value;
    
    public function __construct(string $name, string $value) {
        $this->name = $name;
        $this->value = $value;
    }
    
    public function getHtml(): string {
        return " $this->name=\"$this->value\"";
    }
    
    public function __toString(): string {
        return $this->getHtml();
    }
}
