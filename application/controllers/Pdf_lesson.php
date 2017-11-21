<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pdf_lesson extends CI_Controller {


	function __construct()
	{
		parent::__construct();
		$this->load->model('Projects_model','',TRUE);
		$this->load->model('Users_model','',TRUE);
		$this->load->model('Results_model','',TRUE);
		$this->load->model('Assessment_model','',TRUE);
		$this->load->model('Comments_model','',TRUE);
		$this->load->model('Submit_model','',TRUE);
		$this->load->model('Pdf_model','',TRUE);
		$this->load->model('Skills_model','',TRUE);
		$this->load->model('Terms_model','',TRUE);
		$this->load->helper('file');
	}

	/*
	 *  Generates PDF from project's results
	 *  @todo Use model method to avoid repetition
	 *
	 */
	public function index()
	{
		// get student list
		$projects_ids = $this->input->post('projects');

		if( ! $projects_ids)
		{
				show_error(_('Aucun projet sélectionné!'));
		}

		$this->school_year = get_school_year();
		$pdf = new Pdf();

		$pdf->SetTitle(_('Leçon'));
		$pdf->setFooterData('Généré depuis MediaGrade');

		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		// Config
		$this->Pdf_model->setDefaultConfig($pdf);
		$pdf->SetAutoPageBreak(true, 0);
		$pdf->SetMargins(10, 7, 7);
		$pdf->SetHeaderMargin(7);
		$pdf->SetFooterMargin(5);


		foreach ($projects_ids as $project_id)
		{
			$project_data = $this->Projects_model->getProjectDataByProjectId($project_id);
			/************  render PDF *************************/
			$pdf->AddPage();
			$html = $this->Pdf_model->processLesson($project_data);

			$self_assessments_ids = explode(',', $project_data->self_assessment_ids);

			// get assessment table
			$assessment_data = $this->Assessment_model->getAssessmentsByProjectId($project_id);

			$tbl = '
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
							   <th width="20%" class="tg-fqys">Pondération</th>
					         </tr>';

		    foreach($assessment_data as $data)
			{
			   	$tbl .= '
					<tr>
			           <td class="tg-yw4l">'. $data->skills_group . '</td>
			           <td class="tg-yw4l">'. $data->criterion . '</td>
			           <td class="tg-yw4l">'. $data->cursor . '<br /></td>
					   <td class="tg-yw4l">'. $data->max_vote . '<br /></td>
			        </tr>';
			}

			$tbl .= '</table>';

			$html .= $tbl;

			// get self assessments
			if($self_assessments_ids[0])
			{
				$html .= '<h3>Question' . (count($self_assessments_ids) > 1 ? 's' : '') . ' d\'auto-évaluation</h3>';

				foreach ($self_assessments_ids as $self_assessments_id)
				{
					$html .= '- ' . $this->Assessment_model->getSelfAssessmentFromId($self_assessments_id) . '<br />';
				}
			}

			$pdf->writeHTML($html, false, false, false, false, '');

			// add eventual submitted PDF instruction file
			// insert PDF submitted project, if EXISTS
			if($project_data->instructions_pdf)
			{
			/*	$pdf->AddPage();
				$pdf_doc = $pdf->setSourceFile($data['instructions_pdf']);
				$pdf->SetAutoPageBreak(FALSE, 0);
				$pdf->ImportPage($pdf_doc);*/
			}

		}
		//Closes and opens a PDF file in new tab (arg 'I' in Output method)
		$this->load->helper('school');
		$filename = sanitize_name(get_school_year() . '_' .date("Y-m-d") . '_lessons');
		$pdf->Output($filename . '.pdf', 'I');

	}
}
