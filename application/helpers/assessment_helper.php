<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function format_assessment_type($assessment_type)
{
	$assessment = array();
	switch ($assessment_type) {
		case 'diagnostic':
			$assessment['type'] = LABEL_ASSESSMENT_DIAGNOSTIC;
			$assessment['label'] = 'primary';
			break;
		case 'type_1':
			$assessment['type'] = LABEL_ASSESSMENT_TYPE_1;
			$assessment['label'] = 'success';
			break;
		case 'type_2':
			$assessment['type'] = LABEL_ASSESSMENT_TYPE_2;
			$assessment['label'] = 'info';
			break;
		case 'certificative':
			$assessment['type'] = LABEL_ASSESSMENT_CERTIFIED;
			$assessment['label'] = 'danger';
			break;
		default:
			$assessment['type'] = 'ERROR: No match';
			$assessment['label'] = 'ERROR: No match';
		}

	return $assessment;
}

function convertPercentageToLSUCode($percentage)
{
	if($percentage < 0)
	{
		return 0;
	}

	if($percentage < 50)
	{
		return 1;
	}
	elseif($percentage < 70)
	{
		return 2;
	}
	elseif($percentage < 80)
	{
		return 3;
	}
	else
	{
		return 4;
	}
}

function returnLSUTextFromLSUCode($code)
{
	if( ! is_numeric($code) || $code < 0 || $code > 4 || ! $code)
	{
		return 'Non évalué';
	}
	else
	{
		$lsu = array('insuffisante', 'fragile', 'satisfaisante', 'très bonne');
		return 'Maîtrise ' . $lsu[$code - 1];
	}
}

function returnLSUColorFromLSUCode($code)
{
	if( $code < 0 || $code > 4)
	{
		return FALSE;
	}
	else
	{
		$lsu = array('red', 'orange', '#eeff33', '#33cc33');
		return $lsu[$code - 1];
	}

}

function returnLSUMentionTextFromPercentage($percentage)
{
	if( ! is_numeric($percentage) || $percentage < 0 || $percentage > 100 || ! $percentage)
	{
		return FALSE;
	}
	elseif($percentage >= 80)
	{
		return "Très bien";
	}
	elseif($percentage >= 70)
	{
		return "Bien";
	}
	elseif($percentage >= 60)
	{
		return "Assez bien";
	}
	else
	{
		return "Pas de mention";
	}
}
?>
