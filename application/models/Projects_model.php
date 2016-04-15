<?php
Class Projects_model extends CI_Model
{


	public function listAllActiveProjectsByClassAndUser($class = false, $user_id = false)
	{

		if (!$class) $class = $this->session->class;
		if (!$user_id) $user_id = $this->session->id;

		// format data language in french TODO set a config file
		$sql = "SET lc_time_names = 'fr_FR'";
		$this->db->query($sql);

						// query gets projects by class and bool is_submitted by user
		$sql = 					"SELECT DISTINCT projects.id as project_id,
											project_name,
											periode,
											DATE_FORMAT(deadline, '%W %d %M %Y') as deadline,
											(CASE
												WHEN ISNULL(file_path)
													THEN 0
												ELSE 1
											END) as is_submitted,
											file_path
										FROM projects
										LEFT JOIN submitted
											ON projects.id = submitted.project_id";

		if($user_id !== 'all') $sql .=	" AND submitted.user_id = ?";

		if($class !== 'all') $sql .=	" WHERE class = ?";

		$sql .=							" AND is_activated = 1
										ORDER BY class, projects.periode DESC, deadline ASC";



		if($class === 'all' && $user_id !== 'all') $query = $this->db->query($sql, array($user_id));
		if($class !== 'all' && $user_id === 'all') $query = $this->db->query($sql, array($class));
		if($class === 'all' && $user_id === 'all') $query = $this->db->query($sql);
		if($class !== 'all' && $user_id !== 'all') $query = $this->db->query($sql, array($user_id, $class));

		if($query)
		{
			return $query->result();
		}
		else
		{
			return false;
		}
	}

	public function listAllActiveProjectsByClass($class = FALSE, $periode = FALSE)
	{

		if (!$class) $class = $this->session->class;

		// query gets projects by class and bool is_submitted by user
		$sql = 					"SELECT DISTINCT projects.id as project_id,
											project_name,
											periode,
											deadline
										FROM projects
										WHERE class = ? AND is_activated = 1 ";
		if($periode) $sql .=				"AND periode = ? ";
		$sql .=							"ORDER BY class, projects.periode, projects.deadline";

		if($periode)
			$query = $this->db->query($sql, array($class, $periode));
		else
			$query = $this->db->query($sql, array($class));

		if($query)
		{
			return $query->result();
		}
		else
		{
			return false;
		}
	}


	/**
	*
	*	return array
	*
	*/
	public function getProjectInstructions($project_id)
  {
	  if( ! $project_id) return FALSE;
		$sql = "SELECT instructions_pdf as pdf, instructions_txt as txt FROM projects WHERE id = ? LIMIT 1";
			$query = $this->db->query($sql, array($project_id));
			$row = $query->row();
			$row->pdf = $this->pdfToHtml($row->pdf);

			return $row;
	}

	/**
	*
	*	return bool
	*
	*/
	public function checkProjectId($project_id, $class = false)
  {
	  	if( ! $project_id) return FALSE;
			if(!$class) $class = $this->session->class;

			$sql = "SELECT id FROM projects WHERE id = ? AND class = ? LIMIT 1";
			$query = $this->db->query($sql, array($project_id, $class));
			$row = $query->row();

			return ($row) ? true : false;
	}

	public function getSelfAssessmentAnswers($project_id, $user_id = false)
	{
		if( ! $project_id) return FALSE;
			if (!$user_id) $user_id = $this->session->id;

			// get self assessment ids
			$sql = "SELECT answers FROM submitted WHERE project_id = ? AND user_id = ? LIMIT 1";
			$query = $this->db->query($sql, array($project_id, $user_id));
			$serialized = $query->row();

			if(!$serialized) return null;
			$serialized = $serialized->answers;
			$answers = unserialize($serialized);

			return $answers;
	}

	public function listAllActiveProjects($active = TRUE)
	{


		// query gets projects by class and bool is_submitted by user
		$sql = 					"SELECT DISTINCT projects.id as project_id,
											project_name,
											periode,
											deadline,
											is_activated,
											class
										FROM projects";
		if ($active) $sql .=					"		WHERE is_activated = 1";
		$sql .=					"		ORDER BY projects.periode DESC, class,  deadline DESC";

		$query = $this->db->query($sql);

		if($query)
		{
			return $query->result();
		}
	}

	public function listAllActiveProjectsByPeriod($periode = FALSE)
	{

		if( ! $periode) return $this->listAllActiveProjects();

		// query gets projects by class and bool is_submitted by user
		$sql = 					"SELECT DISTINCT projects.id as project_id,
											project_name,
											periode,
											deadline,
											is_activated,
											class
										FROM projects
										WHERE is_activated = 1
										AND periode = ?
										ORDER BY projects.periode DESC, class,  deadline DESC";

		$query = $this->db->query($sql, array($periode));

		if($query)
		{
			return $query->result();
		}
	}

	public function pdfToHtml($url)
	{
			if(empty($url)) return null;

			return "<object data='/assets/". $url .
											"#view=FitBH&navpanes=0&pagemode=thumbs'
											 type='application/pdf'
											 width='60%'
											height='100%'>".
											_("Il semble qu'il y ait un problème avec la lecture du PDF, essayez de le télécharger ") .
											'<a href="' . $url .'"> '. _('ici') . '</a></object>';
	}




	public function getSelfAssessment($project_id, $get_answers = false, $user_id = false)
	{

		if( ! $project_id) return FALSE;
			// get self assessment ids
			$sql = "SELECT self_assessment_ids FROM projects
										WHERE id = ? LIMIT 1";
			$query = $this->db->query($sql, array($project_id));
			$serialized = $query->row();
			$questions = $serialized->self_assessment_ids;

			if(!$questions && !$get_answers) return null;

			// get questions
			$questions = explode(',', $questions);
			$self_assessments = '';
			$i = 0;

			if( ! empty($questions[0]))
			{
				foreach($questions as $question)
			  	{
						$query =  $this->db->query("SELECT question, id FROM self_assessments WHERE id = $question LIMIT 1");
						$self_assessments[$i]['question'] = $query->row()->question;
						$self_assessments[$i]['id'] = $query->row()->id;
						$i++;
				}
			}

			// get answers if setted in
			if ($get_answers && ! empty($questions[0]))
			{
					$i = 0;
					foreach($questions as $question)
				   {
							$answers = $this->getSelfAssessmentAnswers($project_id, $user_id);
 							if ( ! empty($answers))
							{
								$self_assessments[$i]['answer'] = $answers[$i]['answer'];
								$i++;
							}
					}
			}


			return $self_assessments;
	}



	public function getResultsComments($project_id, $user_id = false)
	{
		if( ! $project_id) return FALSE;
			if (!$user_id) $user_id = $this->session->id;

			$sql = "SELECT comment FROM comments WHERE project_id = ? AND user_id = ? LIMIT 1";
			$query = $this->db->query($sql, array($project_id, $user_id));

			if(!$query->row()) return null;

			$comments = $query->row()->comment;

			return $comments;
	}

	public function getProjectName($project_id)
	{
		if( ! $project_id) return FALSE;

		  $sql = "SELECT project_name FROM projects WHERE id = ? LIMIT 1";

			$query = $this->db->query($sql, array($project_id));
			$results = $query->row();

			if($results) return $results->project_name;
			return null;
	}

	public function getProjectData($project_id)
	{
		if( ! $project_id) return FALSE;

		  $sql = "SELECT * FROM projects WHERE id = ? LIMIT 1";

			$query = $this->db->query($sql, array($project_id));
			$results = $query->row();

			if($results) return $results;
			return null;
	}
	public function random_media()
	{
	    $sql = "SELECT CONCAT(file_path, file_name) as url,
							RIGHT(file_name, 3) as extension
	            FROM submitted
	            ORDER BY RAND()
	            LIMIT 1";

	    $query = $this->db->query($sql);

	    $url = $query->row()->url;

	    $url = '/assets/' . $url;

	    return $url;
	}


}
?>
