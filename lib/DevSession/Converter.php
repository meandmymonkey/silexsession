<?php

namespace DevSession;

class Converter
{
    public function toFahrenheit($celsius)
    {
        $input = (float) $celsius;
        
        return $input * 9 / 5 + 32;
    }
    
    public function toCelsius($fahrenheit)
    {
        $input = (float) $fahrenheit;
        
        return ($input - 32) / 9 * 5;
    }
}
