<?php

namespace HiFolks\Milk\Utils;

class Obj
{
    public static function echo($object)
    {
        foreach ($object as $key => $value) {
            if (gettype($value) == "string" || gettype($value) == "integer" || gettype($value) == "boolean") {
                echo "$key => $value" . PHP_EOL;
            } else {
                echo "$key => (" . gettype($value) . ")" . PHP_EOL;
            }
        }
    }
}
