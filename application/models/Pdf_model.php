<?php

Class Pdf_model extends CI_Model
{
	/**
	* Loads helpers
	*
	*/
	function __construct()
	{
		$this->load->helpers('format');
		$this->load->helpers('school');
		$this->load->library('Pdf');
	}

	/**
	* Set PDF generator default config
	*
	* New object arguments are:
	* new Pdf($orientation='P', $unit='mm', $format='A4', $unicode=true, $encoding='UTF-8', $diskcache=false, $pdfa=false);
	*
	* @param 	Pdf object		$pdf
	* @param 	Array			$content
	* @return	pdf file
	*/
	public function setDefaultConfig($pdf)
	{
		// set document information
		$pdf->SetCreator('MediaGrade');
		$pdf->SetAuthor($this->session->name . ' ' . $this->session->last_name);

		// remove header and footer
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(true);

		//	$pdf->AddFont('robotof', 'thin', '/public/application/libraries/tcpdf/fonts/robotothin.php');
		$pdf->SetFont('helvetica', '', 8);

		//$pdf->SetFont('dejavusans', '', 8);
		$pdf->SetDefaultMonospacedFont('dejavusans', '', 8);

		// set margins
		$pdf->SetMargins(10, 10, 10);
		$pdf->SetHeaderMargin(10);
		$pdf->SetFooterMargin(10);

		// set auto page breaks
		$pdf->SetAutoPageBreak(FALSE, PDF_MARGIN_BOTTOM);

		// set image scale factor
		$pdf->setImageScale('1.6');
	}

	/**
	*	Creates HTML assessment record page
	*
	*  @todo remove "Laboratoire d\'audiovisuel" and get course name from project
	*  @todo use _('') for french hardcoded data
	*	@param array $data
	*	@return string HTML
	*/
	public function processAssessmentsRecordHtml($student)
	{
		// Table with rowspans and THEAD
		$tbl = '
		<h1>' . $this->session->last_name . ' / Laboratoire d\'audiovisuel / '. $student['project_name'] . '</h1>
		<table width="50%" style="padding:2px;">
		<tbody >
		<tr>
		<td>Nom: '. $student['last_name'] . '</td>
		<td>Prénom: '. $student['name'] . '</td>
		</tr>
		<tr>
		<td>Classe: <b>'. $student['class'] . '</b></td>
		<td>Année scolaire: ' . $student['school_year'] . '</td>
		</tr>
		<tr>
		<td>Date de remise:</td>
		<td>'. ($student['submitted'] ? $student['submitted'] . ' ' : '') . '(' . $student['term'] . ')</td>
		</tr>
		</tbody>
		</table>';

		if(file_exists($student['thumbnail']) && substr($student['thumbnail'], -3) === 'jpg')
		{
			$tbl .= '<br /><br /><img src="'. $student['thumbnail'] . '" height="200px" />';
		}
		$tbl .= '<br />

		<h3>Fiche d\'évaluation</h3>';

		$tbl .= '
		<p>Compétences travaillées: '. $student['skill_id'] .'.</p>
		<style type="text/css">
		.tg  {padding:3px;border-collapse:collapse;border-spacing:0;}
		.tg td{border-style:solid;border-width:1px;}
		.tg th{font-weight: bold;border-style:solid;border-width:1px;}
		.tg .tg-fqys{background-color:#333333;color:#ffffff;vertical-align:top}
		.tg .tg-yw4l{vertical-align:top
		}
		</style>
		<table class="tg">
		<tr>
		<th width="15%" class="tg-fqys">Objectif</th>
		<th width="20%" class="tg-fqys">Critères</th>
		<th width="45%" class="tg-fqys">Indicateurs (l\'élève a:)</th>
		<th width="20%" class="tg-fqys">Résultat</th>
		</tr>';

		foreach($student['results'] as $result)
		{
			$tbl .= '
			<tr>
			<td class="tg-yw4l">'. $result->skills_group . '</td>
			<td class="tg-yw4l">'. $result->criterion . '</td>
			<td class="tg-yw4l">'. $result->cursor . '<br /></td>
			<td class="tg-yw4l">'. $result->user_vote . '/' . $result->max_vote . ' (' . ($result->acquis ? _('Acquis') : _('Non acquis')) . ')</td>
			</tr>';
		}

		$tbl .= '
		<tr>
		<td class="tg-yw4l"></td>
		<td class="tg-yw4l"></td>
		<td class="tg-yw4l" style="text-align:right;"><b>Total ' .' : </b></td>
		<td class="tg-yw4l">' . $student['total_user'] . '/' . $student['total_max'] .'</td>
		</tr>';

		$tbl .= '</table>';

		if($student['self_assessments'])
		{
			$tbl .= '<h3>Auto-évaluation</h3>';

			foreach ($student['self_assessments'] as $row)
			{
				$tbl .= '
				<b>' . $row['question'] . '</b>
				<p><pre>"' . (isset($row['answer']) ? $row['answer'] : '') . '"</pre></p>';
			}
		}
		if($student['comment'])
		{
			$tbl .=  '
			<h3>Commentaire du professeur</h3>
			<p><pre>' . $student['comment']->comment . '</pre></p>';
		}

		return $tbl;
	}

	/**
	*	Creates HTML assessment Report
	*
	*	@param array $data
	*	@return string HTML
	*/
	function processStudentAssessmentReport($data)
	{
		$html = '<h1 style="text-align:center">Cahier de compétences de '. $data['name'] . ' ' . $data['last_name'] . '</h1>

		<h2 style="text-align:center">M. Hélin / Laboratoire d\'Audiovisuel<br />
		<small>Classe de ' . $data['class'] . '- Année scolaire ' . $data['school_year'] . '</small></h2>

		<h3 style="text-align:center">Progression de mes compétences</h3>
		<p style="text-align:center">
		<img src="' . $data['skills_chart'] . '" style="width:600px;margin:10px" />
		</p>
		<p></p>

		<h3 style="text-align:center">Résultats par critère</h3>
		<p style="text-align:center">
		<img src="' . $data['criteria_chart'] . '" style="width:600px;margin:10px"/>
		</p>

		<h3 style="text-align:center">Résultats globaux pondérés</h3>';

		return $html;
	}

	/**
	*	Canvas for lesson
	*
	*	@param array $data
	*	@return string HTML
	*/
	function processLesson($data)
	{

		$data = get_object_vars($data);

		$html = '<h1>'. $data['class']  . ' / Laboratoire d\'Audiovisuel / '. $data['project_name'] . '</h1>

		<h2>M. Hélin / ' . $data['school_year'] . ' / ' . $data['term'] . '</h2>

		<h3>Mise en situation</h3><p>' . $data['instructions_txt']['context'] . '</p>

		<h3>Consignes</h3><p>' . ($data['instructions_txt']['instructions'] ? $data['instructions_txt']['instructions'] : 'Voir page suivante') . '</p>

		<h3>Fichier' . ($data['number_of_files'] > 1 ? 's' : '') .' à remettre</h3><p>' . $data['number_of_files'] . ' fichier' . ($data['number_of_files'] > 1 ? 's' : '') . ' de format ' . strtoupper($data['extension']) . '.</p>

		<h3>Deadline</h3><p>' . $data['deadline'] . '</p>

		<h3>Compétences activées</h3><p>' . $data['skill_ids'] . '</p>

		<h3>Matière vue</h3><p>' . $data['material']. '</p>

		<h3>Grille d\'évaluation</h3>'
		;

		return $html;
	}
}
