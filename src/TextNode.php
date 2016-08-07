<?php

namespace Nodes;

require_once 'Indentation.php';

class TextNode implements Node {
    private $value;
    
    public function __construct($value) {
        $this->value = $value;
    }

    public function format($identation = 0) {
        $html = Indentation::getIndentation($identation);
        $html .= $this->value;
        $html .= Indentation::getLineBreaker();
        return $html;
    }

}
