<?php

namespace TheAomx\Nodes;

require_once 'Indentation.php';

class TextNode implements Node {
    public $value;
    
    public function __construct(string $value) {
        $this->value = $value;
    }

    public function format(int $indentation = 0): string {
        return Indentation::indent($indentation, $this->value);
    }
}
