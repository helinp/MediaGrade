<?php
/**
 *  Converts and format bytes into readable value
 *  Credits: wiede at gmx dot net on php.net
 */
 function custom_round($val, $precision = 0.5)
 {
    if(!is_numeric($val)) return $val;
    $output = ceil($val / $precision);
    return $output * $precision;
 }

?>
