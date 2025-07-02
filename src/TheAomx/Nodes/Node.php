<?php

namespace TheAomx\Nodes;

interface Node {
    public function format(int $indentation = 0): string;
}
