<?php
	function user_message($message)
	{
		//    require('application/views/templates/header.php');
		require('application/views/apologize.php');
		//    require('application/views/templates/footer.php');
		exit;
	}

	function parse_and_divide($string, $explode = '/')
	{
		$parts = explode("/", $string);
		return implode("/", array(1, $parts[0]/$parts[1]));

	}
	function exif_mode_translate($data)
	{
		if($data==1) $data = 'Manuel';
		else if($data==2) $data = 'Programme';
		else if($data==3) $data = 'Priorité Ouverture';
		else if($data==4) $data = 'Priorité Vitesse';
		else if($data==5) $data = 'Program Creative';
		else if($data==6) $data = 'Program Action';
		else if($data==7) $data = 'Portrait';
		else if($data==8) $data = 'Landscape';
		else $data = 'Unknown ('.$data . ')';

		return $data;
	}

	function exif_exposure_translate($data, $model)
	{
		$parts = explode("/", $data);
		if(strpos($model, 'Canon'))
		{
			return implode("/", array('1' , $parts[0] / $parts[1])) . 's';
		}
		else
		{

			return implode("/", array('1', $parts[1] / $parts[0])) . 's';
		}
			return $data;
	}
	function exif_focal_lenght($data)
	{
		$parts = explode("/", $data);
		return ($parts[1] > 0 ? $parts[0] / $parts[1] : 'Err.');
	}

	function exif_exposure_time($exposure_raw) {
		$arrExposureTime = explode('/', $exposure_raw);
		if ($arrExposureTime[1] == 0) {
		    $ExposureTime = '1/?';
		// In case numerator is zero.
		} elseif ($arrExposureTime[0] == 0) {
		    $ExposureTime = '0/' . $arrExposureTime[1];
		// When denominator is 1, display time in whole seconds, minutes, and/or hours.
		} elseif ($arrExposureTime[1] == 1) {
		    // In the Seconds range.
		    if ($arrExposureTime[0] < 60) {
		        $ExposureTime = $arrExposureTime[0];
		    // In the Minutes range.
		    } elseif (($arrExposureTime[0] >= 60) && ($arrExposureTime[0] < 3600)) {
		        $ExposureTime = gmdate("i\m:s\s", $arrExposureTime[0]);
		    // In the Hours range.
		    } else {
		        $ExposureTime = gmdate("H\h:i\m:s\s", $arrExposureTime[0]);
		    }
		// When inverse is evenly divisable, show reduced fractional exposure.
		} elseif (($arrExposureTime[1] % $arrExposureTime[0]) == 0) {
		    $ExposureTime = '1/' . $arrExposureTime[1]/$arrExposureTime[0];
		// If the value is greater or equal to 3/10, which is the smallest standard
		// exposure value that doesn't divid evenly, show it in decimal form.
		} elseif (($arrExposureTime[0]/$arrExposureTime[1]) >= 3/10) {
		    $ExposureTime = round(($arrExposureTime[0]/$arrExposureTime[1]), 1);
		// If all else fails, just display it as it was found.
		} else {
		    $ExposureTime = $arrExposureTime[0] . '/' . $arrExposureTime[1];
		}
		return $ExposureTime;
	}

?>
