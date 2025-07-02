<?php

namespace TheAomx\Nodes;

class EmptyNode implements Node {
    public function __construct() {}
    
    public function format(int $indentation = 0): string {
        return "";
    }
}
