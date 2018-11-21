<?php
namespace tool_richardnz\local;

class debugging {
    public static function logit($message, $value) {
        global $CFG;

        $file = fopen('mylog.log', 'a');

        if ($file) {
            fwrite($file, print_r($message, true));
            fwrite($file, print_r($value, true));
            fwrite($file, "\n");
            fclose($file);
        }
    }
}