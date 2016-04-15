<?php
/**
 *  Converts and format bytes into readable value
 *  Credits: wiede at gmx dot net on php.net
 */
function format_bytes($bytes)
{
    $fr_prefix = array( 'o', 'Ko', 'Mo', 'Go', 'To', 'Eo', 'Zo', 'Yo' );
    $si_prefix = array( 'B', 'KB', 'MB', 'GB', 'TB', 'EB', 'ZB', 'YB' );

    $si_prefix =  $fr_prefix;

    $base = 1024;
    $class = min((int)log($bytes , $base) , count($si_prefix) - 1);
    return sprintf('%1.2f' , $bytes / pow($base,$class)) . ' ' . $si_prefix[$class];
}

?>
