<?php
/**
  * Returns scolar year from timestamp
  * Es: 2015-2016
  */
 function get_school_year($time_stamp = false)
 {
   if ($time_stamp)
   {
       $time = strtotime($time_stamp);
       $yy = date('Y', $time);
       $dm = date('dm', $time);
   }
   else
   {
       $yy = date('Y');
       $dm = date('dm');
   }

   if($dm < END_OF_YEAR) return ($yy - 1 . '-' . $yy);
   else return ($yy . '-' . $yy + 1);
 }

 ?>
