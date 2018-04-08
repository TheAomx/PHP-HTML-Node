<?php

namespace TheAomx\Nodes;

class EmptyNode implements Node {
    public function __construct() {}
    
    public function format($identation = 0) {
        return "";
    }
}
