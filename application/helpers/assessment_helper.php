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
