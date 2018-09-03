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

	if($percentage < 40)
	{
		return 1;
	}
	elseif($percentage < 70)
	{
		return 2;
	}
	elseif($percentage < 100)
	{
		return 3;
	}
	else
	{
		return 4;
	}
}

function returnLSUTextFromPercentage($percentage)
{
	$code = convertPercentageToLSUCode($percentage);
	return returnLSUTextFromLSUCode($code);
}

function returnLSUTextFromLSUCode($code)
{
	if( ! is_numeric($code) || $code < 0 || $code > 4 || ! $code)
	{
		return 'Non évalué';
	}
	else
	{
		$lsu = array('Non Acquis', 'En Acquisition', 'Acquis', 'Maitrisé');
		return $lsu[$code - 1];
	}
}

function returnLSUColorFromPercentage($percentage)
{
	if($percentage === NULL) return 'white';
	 return returnLSUColorFromLSUCode(convertPercentageToLSUCode($percentage));
}

function returnLSUColorFromLSUCode($code)
{
	if( $code < 0 || $code > 4)
	{
		return FALSE;
	}
	else
	{
		$lsu = array('rgb(217, 83, 79)', 'rgb(240, 173, 78)', 'rgb(92, 184, 92)', 'rgb(51, 122, 183)');
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

function returnFunMentionTextFromPercentage($percentage)
{
	if($percentage === NULL)
	{
		return FALSE;
	}
	elseif($percentage >= 90)
	{
		return 'Professionnel';
	}
	elseif($percentage >= 70)
	{
		return 'Amateur confirmé';
	}
	elseif($percentage >= 40)
	{
		return 'Débutant';
	}
	else
	{
		return "Tatie à la mer";
	}
}

function returnColorFromPercentage($percentage)
{

	if($percentage === NULL) return 'white';

	$colors = array('rgb(217,83,79)',// red
									'rgb(217,83,79)',
									'rgb(225,113,79)',
									'rgb(232,143,78)',
									'rgb(240,173,78)',
									'rgb(191,177,83)', // orange
									'rgb(141,180,87)',
									'rgb(92,184,92)',
									'rgb(78,163,122)', // verrt
									'rgb(65,143,153)',
									'rgb(51,122,183)'// blue
								);

		$index = (int) ($percentage / 10);
		return $colors[$index];
}

function returnTextExplainationsFromPercentage($percentage)
{
	if( ! is_numeric($percentage) || $percentage < 0 || $percentage > 100 || ! $percentage)
	{
		return FALSE;
	}
	elseif($percentage >= 90)
	{
		return 'Je suis un Dieu de l\'Audiovisuel, d\'ailleurs, c\'est moi qui donne cours aux professeurs!';
	}
	elseif($percentage >= 70)
	{
		return 'Je comprends l\'essentiel, mon travail est presque professionnel';
	}
	elseif($percentage >= 40)
	{
		return 'Je comprends un peu, mais je dois me concentrer plus et ne pas hésiter à demander de l\'aide';
	}
	else
	{
		return "Je dois bien lire les consignes, demander de l'aide, me concentrer et rendre tous mes travaux";
	}
}
?>
