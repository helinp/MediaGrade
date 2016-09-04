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
		$this->load->model('Skills_model','',TRUE);
	}

	/*
	 *  Generates PDF from project's results
	 *
	 */
    function index()
    {
		$projects_id = $this->input->post('project_id');
		$term = $this->input->post('term');

		foreach ($projects_id as $project_id)
		{
			$project = $this->Projects_model->getProjectDataByProjectId($project_id);
			$students = $this->Users_model->getAllUsersByClass('student', $project->class);
			$students = $students[$project->class];
			$skills = implode(', ', $this->Skills_model->getAllSkillsByProjects($project_id, TRUE));

			foreach ($students as $student)
			{
				$submitted = $this->Submit_model->getSubmittedByUserIdAndProjectId($student->id, $project_id);

				$submitted_time = (isset($submitted[0]->time) ? $submitted[0]->time : '');
				$submitted_thumbnail = (isset($submitted[0]->thumbnail) ? $submitted[0]->thumbnail : '');

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

		require_once(APPPATH.'libraries/pdf/pdf_assessment.php');
		$this->Pdf_assessment_model->get_assessment_pdf($data);
    }
}
