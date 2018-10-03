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

		/* SUBMENU */
		$submenu = array();
		$submenu[] = array('title' => 'Par élève', 'url' => '/admin/grade/by_student');
		$submenu[] = array('title' => 'Par projet', 'url' => '/admin/grade/by_project');
		$this->data['submenu'] = $submenu;
	}

	// TODO index (list) and grade methods
	public function index($dest = NULL)
	{
		if( ! $dest)
		{
			redirect('/admin/grade/by_student');
		}
		else
		{
			redirect('/admin/grade/' . $dest);
		}
	}
	public function by_student($class = FALSE, $term = FALSE)
	{
		// cleaner url
		if($this->input->get('class') | $this->input->get('term'))
		{
			redirect('/admin/grade/by_student/' . $this->input->get('class') . '/' . $this->input->get('term'));
		}

		if($class === 'all')
		{
			$class = FALSE;
		}
		if($term === 'all')
		{
			$term = FALSE;
		}

		$class_roll = $this->Users_model->getAllStudentsSortedByClass($class);

		// TODO mhash_keygen_s2k table CLASS -> USER_ID -> PROJECTS & USER
		$table = NULL;
		foreach($class_roll as $class => $students_in_class)
		{
			foreach($students_in_class as $user)
			{
				$user->class_name = $this->Classes_model->getClass($user->class)->name;
				$table[$class][$user->id]['user'] = $user;

				$projects = $this->Projects_model->getAllActiveProjectsByClassAndTermAndSchoolYear($class, $term, get_school_year());

				//workaround
				//$projects = array_reverse($projects);

				foreach($projects as $key => $project)
				{
					$projects[$key]->term_name = @$this->Terms_model->getTerm($projects[$key]->term)->name;
					$projects[$key]->is_graded = $this->Results_model->IsProjectGraded($user->id, $project->project_id);

					if($project->external)
					{
						$projects[$key]->is_submitted = $this->Submit_ext_model->IsSubmittedByUserAndProjectId($user->id, $project->project_id);
					}
					else
					{
						$projects[$key]->is_submitted = $this->Submit_model->IsSubmittedByUserAndProjectId($user->id, $project->project_id);
					}
				}
				$table[$class][$user->id]['projects'] = $projects;
			}
		}
		$this->data['grade_table'] = $table;
		$this->data['class_users'] = $class_roll;
		$this->data['page_title'] = _('Projets à corriger');
		$this->load->template('admin/grade_list', $this->data);
	}

	public function by_project($class = FALSE, $term = FALSE)
	{
		// cleaner url
		if($this->input->get('class') || $this->input->get('term'))
		{
			redirect('/admin/grade/by_project/' . $this->input->get('class') . '/' . $this->input->get('term'));
		}

		if($class === 'all')
		{
			$class = FALSE;
		}
		if($term === 'all')
		{
			$term = FALSE;
		}

		$projects = $this->Projects_model->getAllActiveProjectsByTermAndClassAndSchoolYear($term, $class, get_school_year());

		$table = NULL;
		$status = NULL;
		foreach($projects as $project)
		{
			$students = $this->Users_model->getAllStudentsByClass($project->class);
			$project->class_name = @$this->Classes_model->getClass($project->class)->name;
			$project->term_name = @$this->Terms_model->getTerm($project->term)->name;
			$table[$project->project_id]['project'] = $project;

			foreach ($students as $student)
			{
				$status['is_graded'] = $this->Results_model->IsProjectGraded($student->id, $project->project_id);

				if($project->external)
				{
					$status['is_submitted'] = $this->Submit_ext_model->IsSubmittedByUserAndProjectId($student->id, $project->project_id);
				}
				else
				{
					$status['is_submitted'] = $this->Submit_model->IsSubmittedByUserAndProjectId($student->id, $project->project_id);
				}

				$table[$project->project_id]['students'][$student->id]['student'] = $student;
				$table[$project->project_id]['students'][$student->id]['status'] = $status;
			}
		}

		$this->data['grade_table'] = $table;
		$this->data['page_title'] = _('Projets à corriger');
		$this->load->template('admin/grade_list_by_project', $this->data);
	}

	function assess($class, $project_id, $user_id)
	{
		// gather informations
		$this->load->helper('user_message');
		$project = $this->Projects_model->getProjectDataByProjectId($project_id);
		if($project->external)
		{
			$submitted_project = $this->Submit_ext_model->getSubmittedInfosByUserIdAndProjectId($user_id, $project_id);
		}
		else
		{
			$submitted_project = $this->Submit_model->getSubmittedInfosByUserIdAndProjectId($user_id, $project_id);
		}

		$this->data['project'] = $project;
		$this->data['exif'] = $this->Submit_model->getExifByUserIdAndProjectId($user_id, $project_id);

		$this->data['user'] = $this->Users_model->getUserInformations($user_id);
		$this->data['submitted'] = $submitted_project;
		$this->data['self_assessments'] = $this->Submit_model->getSelfAssessmentByProjectId($project_id, TRUE, $user_id);
		$this->data['comment'] = preg_replace('<br/>', "/\n", $this->Comments_model->getCommentsByProjectIdAndUserId($project_id, $user_id)->comment);
		$this->data['assessment_table'] = $this->Results_model->getResultsTable($user_id, $project_id);

		// GET
		$this->load->template('admin/grade', $this->data, TRUE);
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
				// -1 vote means not graded so don't record in DB
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
