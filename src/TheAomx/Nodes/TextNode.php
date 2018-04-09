<?php

namespace TheAomx\Nodes;

require_once 'Indentation.php';

class TextNode implements Node {
    public $value;
    
    public function __construct($value) {
        $this->value = $value;
    }

    public function format($identation = 0) {
        return Indentation::indent($identation, $this->value);
    }
}
