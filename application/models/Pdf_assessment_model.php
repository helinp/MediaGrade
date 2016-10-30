<?php

Class Pdf_assessment_model extends CI_Model
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

		// remove header and footer
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);

		$pdf->SetFont('dejavusans', '', 9);
		$pdf->SetDefaultMonospacedFont('dejavusans', '', 8);

		// set margins
		$pdf->SetMargins(15, 15, 15);
		$pdf->SetHeaderMargin(10);
		$pdf->SetFooterMargin(10);

		// set auto page breaks
		$pdf->SetAutoPageBreak(FALSE, PDF_MARGIN_BOTTOM);

		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
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
}
