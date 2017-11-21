<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pdf_project extends CI_Controller {


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
	}

	/*
	 *  Generates PDF from project's results
	 *  @todo Use model method to avoid repetition
	 *
	 */
	public function index()
	{
		// creates new PDF object
        $pdf = new pdf();
		$this->Pdf_model->setDefaultConfig($pdf);
        $pdf->SetAuthor($this->session->name . ' ' . $this->session->last_name);
        $pdf->SetTitle(_('Fiche d\'évaluation'));

		// adds a page for each student
		$data = $this->_prepareAssessmentRecords($this->input->post('project_id'), $this->input->post('term'));
		foreach ($data as $student)
		{
			$pdf->AddPage();
			$html = $this->Pdf_model->processAssessmentsRecordHtml($student);
			$pdf->writeHTML($html, false, false, false, false, '');

			// insert PDF submitted project, if EXISTS
			if( ! empty($student->submitted) && substr($student->submitted, -3) === 'pdf')
			{
				$pdf->AddPage();
			    $pdf_doc = $pdf->setSourceFile($student->submitted);
			    $pdf->SetAutoPageBreak(FALSE, 0);
			    $pdf->ImportPage($pdf_doc);
			}
		}

		//Closes and opens a PDF file in new tab (arg 'I' in Output method)
		$this->load->helper('school');
		$filename = sanitize_name(get_school_year() . '_' .  $data[0]['term'] . '_' . $data[0]['class'] . '_' . $data[0]['project_name']);
		$pdf->Output($filename . '.pdf', 'I');
    }

	/*
	 *  Returns assessment data from project and term for PDF generation
	 *
	 */
	private function _prepareAssessmentRecords($projects_id, $term)
	{
		foreach ($projects_id as $project_id)
		{
			$project = $this->Projects_model->getProjectDataByProjectId($project_id);
			$students = $this->Users_model->getAllUsersByClass('student', $project->class);
			$students = $students[$project->class];
			$skills = implode(', ', $this->Skills_model->getAllSkillsByProjects($project_id, TRUE));

			foreach ($students as $student)
			{
				$submitted = $this->Submit_model->getSubmittedInfosByUserIdAndProjectId($student->id, $project_id);

				$submitted_time = (isset($submitted[0]->time) ? $submitted[0]->time : '');
				$submitted_thumbnail = (isset($submitted[0]->thumbnail) ? $submitted[0]->thumbnail : '');

				$totals = $this->Results_model->getUserProjectOverallResult($student->id, $project_id);

				if ( ! empty($totals->total_user))
				{
					$total_user = $totals->total_user;
					$total_max = $totals->total_max;
				}
				else
				{
					$total_user = '--';
					$total_max = '--';
				}

				$data[] = array(
					'project_name' => $project->project_name,
					'term' => $project->term,
					'name' => $student->name,
					'last_name' => $student->last_name,
					'class' => $project->class,
					'school_year' => $project->school_year,
					'submitted' => $submitted_time,
					'thumbnail' => '.' . $submitted_thumbnail,
					'skill_id' => $skills,
					'total_user' => $total_user,
					'total_max' => $total_max,
					'results' => $this->Results_model->getResultsTable($student->id, $project_id),
					'comment' => $this->Comments_model->getCommentsByProjectIdAndUserId($project_id, $student->id),
					'self_assessments' => $this->Submit_model->getSelfAssessmentByProjectId($project_id, TRUE, $student->id)
				);
			}
		}
		return $data;
	}
}
