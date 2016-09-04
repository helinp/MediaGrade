<?php

 function makeHtmlObjectForPdf($url = NULL)
 {
     if(!$url) return null;

     $string = '<object data="' . base_url() . '/assets/'. $url . '#view=FitBH&navpanes=0&pagemode=thumbs
                      type="application/pdf"
                      width="60%"
                     height="100%"><a href="' . base_url() . 'assets/'. $url . '"> '. 'assets/'. $url . '</a></object>';

     return $string;
 }

 ?>
