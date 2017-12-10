<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Grade extends MY_AdminController {

	private $data;

	function __construct()
	{
		parent::__construct();

		$this->data['classes'] = $this->Classes_model->getAllClasses();
		$this->data['terms'] = $this->Terms_model->getAll();

		if($this->input->get('school_year'))
		{
			$this->school_year = $this->input->get('school_year');
		}
		else
		{
			$this->school_year = get_school_year();
		}
		$this->data['school_years'] = $this->Projects_model->getSchoolYears();
	}

	public function index($class = FALSE, $project_id = FALSE, $user_id = FALSE)
	{
		$this->load->model('Comments_model','',TRUE);
		// Load Models
		$this->load->model('Users_model','',TRUE);
		$this->load->model('Results_model','',TRUE);
		$this->load->model('Submit_model','',TRUE);

		// catch gets for grading_list table
		if( ! $class = $this->input->get('classe')) $class = NULL;
		if( ! $term = $this->input->get('term')) $term = NULL;

		$this->data['class_users'] = $this->Users_model->getAllUsersByClass('student', $class);

		// TODO mhash_keygen_s2k table CLASS -> USER_ID -> PROJECTS & USER
		$table = '';
		foreach($this->data['class_users'] as $class => $students_in_class)
		{
			foreach($students_in_class as $user)
			{
				$table[$class][$user->id]['user'] = $user;

				$projects = $this->Projects_model->getAllActiveProjectsByClassAndTermAndSchoolYear($class, $term, get_school_year());

				//workaround
				//$projects = array_reverse($projects);

				foreach($projects as $key => $project)
				{
					$projects[$key]->is_graded = $this->Results_model->IsProjectGraded($user->id, $project->project_id);
					$projects[$key]->is_submitted = $this->Submit_model->IsSubmittedByUserAndProjectId($user->id, $project->project_id);
				}
				$table[$class][$user->id]['projects'] = $projects;
			}
		}
		$this->data['grade_table'] = $table;


		/**
		 *  Routing
		 */
		// to grade page
		if($class && $project_id && $user_id)
		{
			// gather informations
			$this->data['user'] = $this->Users_model->getUserInformations($user_id);
			$this->data['submitted'] = $this->Submit_model->getSubmittedInfosByUserIdAndProjectId($user_id, $project_id);
			$this->data['self_assessments'] = $this->Submit_model->getSelfAssessmentByProjectId($project_id, TRUE, $user_id);
			$this->data['comment'] = preg_replace('<br/>', "/\n", $this->Comments_model->getCommentsByProjectIdAndUserId($project_id, $user_id)->comment);
			$this->data['assessment_table'] = $this->Results_model->getResultsTable($user_id, $project_id);
			$this->data['project'] = $this->Projects_model->getProjectDataByProjectId($project_id);

			// GET
			$this->load->template('admin/grade', $this->data, TRUE);
		}
		// To grade_list
		else
		{
			$this->load->template('admin/grade_list', $this->data);
		}
	}

	function record()
	{
		if($this->input->post() && $this->Projects_model->isProjectIdFromThisSchoolYear($this->input->post('project_id')))
		{
			$this->load->model('Grade_model','',TRUE);
			$user_id = $this->input->post('user_id');

			// save grades in DB
			$i = 0;
			foreach ($this->input->post('assessments_id') as $assessment_id)
			{
				$user_vote = $this->input->post("user_vote[$i]");

				if($user_vote == -1 && $this->Grade_model->isAssessmentGraded($assessment_id, $user_id))
				{
					$this->Grade_model->removeVote($assessment_id, $user_id);
				}
				elseif($user_vote <> -1)
				{
					$this->Grade_model->grade(	$this->input->post('project_id'),
												$user_id,
												$assessment_id,
												$user_vote
											 );
				}
				$i++;
			}

			// saves comment to DB
			$this->Comments_model->comment($this->input->post('project_id'),
				$this->input->post('user_id'),
				$this->input->post('comment')
				);

			if($this->input->post('origin'))
			{
				redirect($this->input->post('origin'));
			}
			else
			{
				redirect("/admin/grade/");
			}
		}
	}
}
