<?php
/**
  * Returns scolar year from timestamp
  * Es: 2015-2016
  */
 function get_school_year($time_stamp = FALSE)
 {
   if ($time_stamp)
   {
       $time = strtotime($time_stamp);
       $yy = date('Y', $time);
       $md = date('md', $time);
   }
   else
   {
       $yy = date('Y');
       $md = date('md');
   }

   if($md > END_OF_YEAR) return $yy . '-' . ($yy + 1);

   else return (($yy - 1) . '-' . $yy);
 }


 ?>
