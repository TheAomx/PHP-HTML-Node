<?php


namespace Nodes;


class Indentation {
    public static $indentationDepth = 0;
    public static $lineBreaker = "";
    public static $indentationCharacter = " ";
    
    public static function getIndentationDepth() {
        return self::$indentationDepth;
    }
    
    public static function getIndentation ($identation) {
        $t = "";
        
        for ($i = 0; $i < $identation; $i++) {
            $t .= self::$indentationCharacter;
        }
        
        return $t;
    }
    
    public static function getLineBreaker() {
        return self::$lineBreaker;
    }
}
