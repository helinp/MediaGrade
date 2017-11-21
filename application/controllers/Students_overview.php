<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Students_overview extends CI_Controller {

	private $school_year = FALSE;
	private $students_list = array();
	private $projects_list = array();

    function __construct()
    {
        parent::__construct();

        $this->load->model('Users_model','',TRUE);
        $this->Users_model->loginCheck();

        $this->load->model('UsersManager_model','',TRUE);
        $this->load->model('Results_model','',TRUE);
		$this->load->model('Classes_model','',TRUE);
		$this->load->model('Achievements_model','',TRUE);

		if($this->session->role === 'admin')
		{
			$this->load->model('Projects_model','',TRUE);
			$this->load->model('skills_model','',TRUE);
			$this->skills_groups = $this->skills_model->getAllSkillsGroups();
			$this->data['skills_groups'] = $this->skills_groups;
		}

		$this->school_year = $this->input->get('school_year');
		$this->data['classes'] = $this->Classes_model->getAllClasses();

    }

	function index()
	{
		// FILTERS
		$class = $this->input->get('classe');
		if($class)
		{
			$this->students_list = $this->Users_model->getAllUsersByClass('student', $class, TRUE);
		}
		else
		{
			$this->students_list = $this->Users_model->getAllUsers();
		}

		// Gets averages and achievements
		foreach ($this->students_list as $key => $student)
		{
			// GETS RESULTS FOR ADMIN
			if($this->session->role === 'admin')
			{
				$this->students_list[$key]->results = $this->Results_model->getUserVoteAverageByTermAndSchoolYear($student->id, FALSE, $this->school_year);
				$this->projects_list = $this->Projects_model->getAllActiveProjectsByClassAndSchoolYear($student->class, $this->school_year);
				$this->students_list[$key]->all_results = $this->Results_model->getUserOverallResults(FALSE, $this->projects_list, $student->id, $this->school_year);
				//$this->students_list[$key]->progression = $this->Results_model->getLastProgression($this->projects_list, $student->id, $this->school_year);

				foreach ($this->skills_groups as $skills_group)
				{
					$this->students_list[$key]->skills_groups_results[$skills_group->id] = $this->Results_model->getUserOverallResultsBySkillGroup($skills_group->name, $student->id, $this->school_year);
				}
				$this->students_list[$key]->trend = $this->_trendLine($this->students_list[$key]->all_results);
				$this->students_list[$key]->progression = $this->_progression($this->_linearProgression($this->students_list[$key]->all_results), 1);
			}
			$this->students_list[$key]->achievements = $this->Achievements_model->getAllAchievementsByStudent($student->id);
		}
		$this->data['students'] = $this->students_list;

		// VIEW
		if($this->input->get('view') === 'general' || $this->session->role === 'student')
		{
			$this->general();
		}
		else
		{
			$this->detailled();
		}
	}

	// general view
	function general()
	{
		//dump($this->students_list);
		$this->load->template('students_overview', $this->data);
	}

	function detailled()
	{
		$this->Users_model->adminCheck();
		$this->load->template('admin/students_detailled', $this->data);
	}


	/* TODO Sould be helpers functions*/
	private function _progression($results = array(), $round = FALSE)
	{
		//dump($results);
		$n = count($results);
		if( ! $n | $n < 2 ) return 0;

		if($round)
		{
			$results[0] = round($results[0] 			* 2 / 100, 2);
			$results[$n - 1] = round($results[$n - 2] 	* 2 / 100, 2);
		}

		if($results[$n - 1] > $results[0]) return 1;
		if($results[$n - 1] < $results[0]) return -1;
		else return 0;
	}

	private function _trendLine($array_y = array())
	{
		$i = 0; $y = $x = false;
		foreach ($array_y as $array)
		{
			if ($array <> 'null')
			{
				 $y[] = $array;
				 $x[] = $i;
				 $i++;
			}
			# code...
		}
		if( ! $x) return false;

		/**/
		$data = $y;

		$range;
		if($i > 10) $range = $i / 2;
		elseif($i >= 5) $range = 3;
		elseif($i > 1) $range = 2;
		else return 0;

		$range = (int)($range) ;
		$sum = array_sum(array_slice($data, 0, $range));

	    $result = array($range - 1 => $sum / $range);

	    for ($i = $range, $n = count($data); $i != $n; ++$i) {
	        $result[$i] = (int) ( $result[$i - 1] + ($data[$i] - $data[$i - $range]) / $range);
	    }

		/**/


		return ($result);
	}

	private function _linearProgression($array_y = array())
	{
		$i = 0; $y = $x = false;
		foreach ($array_y as $array)
		{
			if ($array <> 'null')
			{
				 $y[] = $array;
				 $x[] = $i;
				 $i++;
			}
			# code...
		}
		if( ! $x) return false;

		$n     = count($x);     // number of items in the array
		$x_sum = array_sum($x); // sum of all X values
		$y_sum = array_sum($y); // sum of all Y values

		$xx_sum = 0;
		$xy_sum = 0;

		for($i = 0; $i < $n; $i++) {
		$xy_sum += ( $x[$i]*$y[$i] );
		$xx_sum += ( $x[$i]*$x[$i] );
		}
	    // Slope
		$divider = ( ( $n * $xx_sum ) - ( $x_sum * $x_sum ) );
	    $slope = ( ( $n * $xy_sum ) - ( $x_sum * $y_sum ) ) / ($divider === 0 ? 1 : $divider);

	    // calculate intercept
	    $intercept = ( $y_sum - ( $slope * $x_sum ) ) / $n;

	    $trend = array(
	        'slope'     => $slope,
	        'intercept' => $intercept,
	    );

		for($x = 0 ; $x <= $n ; $x++)
		{
			$graph[] = (int)($trend['intercept'] + ($x * $trend['slope']));
		}

		return ($graph);

	}
}
?>
