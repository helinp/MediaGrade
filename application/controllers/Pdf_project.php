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
		$this->load->model('Pdf_assessment_model','',TRUE);
	}

    function index()
    {
		$projects_id = $this->input->post('project_id');
		$periode = $this->input->post('periode');

		foreach ($projects_id as $project_id)
		{
			$project = $this->Projects_model->getProjectData($project_id);
			$students = $this->Users_model->getAllUsersByClass('student', $project->class);
			$students = $students[$project->class];
			$skills = implode(', ', $this->Assessment_model->listAllSkillsByProjects($project_id, TRUE));

			foreach ($students as $student)
			{
				$submitted = $this->Submit_model->getSubmittedProject($student->id, $project_id);

				$submitted_time = (isset($submitted[0]->time) ? $submitted[0]->time : '');
				$submitted_thumbnail = (isset($submitted[0]->thumbnail) ? $submitted[0]->thumbnail : '');
// dump($submitted_thumbnail);
				$totals = $this->Results_model->getUserProjectOverallResult($student->id, $project_id);

				if ( ! empty($totals))
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
					'periode' => $project->periode,
					'name' => $student->name,
					'last_name' => $student->last_name,
					'class' => $project->class,
					'submitted' => $submitted_time,
					'thumbnail' => '.' . $submitted_thumbnail,
					'skill_id' => $skills,
					'total_user' => $total_user,
					'total_max' => $total_max,
					'results' => $this->Results_model->getResultsTable($student->id, $project_id),
					'comment' => $this->Comments_model->getAssessmentComment($student->id, $project_id),
					'self_assessments' => $this->Projects_model->getSelfAssessment($project_id, TRUE, $student->id)
				);
			}
		}
	//	dump($data);




		require_once(APPPATH.'libraries/pdf/pdf_assessment.php');
		$this->Pdf_assessment_model->get_assessment_pdf($data);
    }
}
