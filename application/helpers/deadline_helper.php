<?php
function countdown($deadline)
{
	$curr_date = date('Y-m-d');

	if($deadline < $curr_date) return FALSE;

	$left = round( (strtotime($deadline) - strtotime($curr_date) ) / 60 / 60 / 24);


	return ($left + 1 ? $left + 1 : '0');
}

?>
