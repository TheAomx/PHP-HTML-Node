<?php

namespace TheAomx\Nodes;

class Indentation {
    public static $indentationDepth = 0;
    public static $lineBreaker = "";
    public static $indentationCharacter = " ";
    
    public static function getIndentationDepth(): int {
        return self::$indentationDepth;
    }
    
    public static function getIndentation (int $indentation): string {
        $t = "";
        
        for ($i = 0; $i < $indentation; $i++) {
            $t .= self::$indentationCharacter;
        }
        
        return $t;
    }
    
    public static function getLineBreaker(): string {
        return self::$lineBreaker;
    }
    
    public static function indent (int $indentationDepth, string $content): string {
        $text = Indentation::getIndentation($indentationDepth);
        $text .= $content;
        $text .= Indentation::getLineBreaker();
        return $text;
    }
}


