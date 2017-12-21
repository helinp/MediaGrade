<?php
Class Submit_model extends CI_Model
{

	function __construct()
	{
		$this->load->library('upload');
		$this->load->model('Users_model','',TRUE);
	}

	/**
	* Returns wether or not a project is graded
	*
	* @param 	integer	$user_id = $this->session->id;
	* @param	integer	$project_id
	* @return	object
	*/
	public function IsSubmittedByUserAndProjectId($user_id = FALSE, $project_id )
	{
		if( ! $user_id) $user_id = $this->session->id;

		$this->db->get_where('submitted', array('user_id' => $user_id, 'project_id' => $project_id), 1);
		return ($this->db->count_all_results() > 0 ? TRUE : FALSE);
	}

	public function getNSubmittedByProjectId($project_id)
	{
		$query = $this->db->get_where('submitted', array('project_id' => $project_id));
		return count($query->result());
	}

	/**
	* Returns student's answers of a given project id
	*
	* @param	integer	$project_id
	* @param 	integer	$user_id = $this->session->id;
	* @return	object
	*/
	public function getAnswersByProjectIdAndUser($project_id, $user_id = FALSE)
	{
		if ( ! $user_id) $user_id = $this->session->id;

		// get self assessment ids
		$sql = "SELECT answers FROM submitted WHERE project_id = ? AND user_id = ? LIMIT 1";
		$query = $this->db->query($sql, array($project_id, $user_id));
		$serialized = $query->row();

		if( ! $serialized) return FALSE;

		$serialized = $serialized->answers;
		$answers = unserialize($serialized);

		return $answers;
	}

	/**
	* Returns self assessments questions
	*
	* @todo: Method should do only one thing!
	*
	* @param	integer	$project_id
	* @param 	$get_answers = FALSE
	* @param 	integer	$user_id = $this->session->id;
	* @return	object
	*/
	public function getSelfAssessmentByProjectId($project_id, $get_answers = FALSE, $user_id = FALSE)
	{
		// get self assessment ids
		$sql = "SELECT self_assessment_ids
		FROM projects
		WHERE id = ? LIMIT 1";

		$query = $this->db->query($sql, array($project_id));
		$serialized = $query->row();
		$questions = $serialized->self_assessment_ids;

		if( ! $questions && ! $get_answers) return '';

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
			$answers = $this->getAnswersByProjectIdAndUser($project_id, $user_id);
			foreach($questions as $question)
			{
				if ( ! empty($answers[$i]['answer']))
				{
					$self_assessments[$i]['answer'] = $answers[$i]['answer'];
					$i++;
				}
			}
		}
		return $self_assessments;
	}

	/**
	* Returns self assessments questions
	*
	* @todo: Method should do only one thing!
	*
	* @param	integer	$project_id
	* @param 	$get_answers = FALSE
	* @param 	integer	$user_id = $this->session->id;
	* @return	object
	*/
	public function getSubmitInformations($project_id)
	{
		if( ! $project_id) return FALSE;
		$sql = "SELECT project_name, class, number_of_files, extension, term FROM projects WHERE id = ? LIMIT 1";
		$query = $this->db->query($sql, array($project_id));
		$row = $query->row();

		return $row;
	}

	/**
	* Returns paths of students projects files
	*
	* @param	integer	$project_id
	* @param 	$user_id = $this->session->id
	* @return	object
	*/
	public function getSubmittedFilesPathsByProjectAndUser($project_id, $user_id = FALSE)
	{
		if (!$user_id) $user_id = $this->session->id;

		$sql = "SELECT CONCAT('/assets/', file_path, file_name) as file,
		CONCAT('/assets/', file_path, 'thumb_', file_name) as thumbnail,
		RIGHT(file_name, 3) as extension,
		answers
		FROM submitted WHERE user_id = ? AND project_id = ?";
		$query = $this->db->query($sql, array($user_id, $project_id));
		$submitted = $query->result();

		return $submitted;
	}

	/**
	* Returns all data of a submitted projects
	*
	* @param 	$user_id = $this->session->id
	* @param	integer	$project_id
	* @return	object
	*/
	public function getSubmittedInfosByUserIdAndProjectId($user_id, $project_id)
	{
		// format data language in french TODO set a config file
		$sql = "SET lc_time_names = 'fr_FR'";
		$this->db->query($sql);

		$sql = "SELECT file_name, file_path, answers, time as raw_time, DATE_FORMAT(`time`, '%d %M %Y à %H:%i') as `time`,  RIGHT(file_name, 3) as extension,
		CONCAT('/assets/', file_path, 'thumb_', file_name) as thumbnail
		FROM submitted
		WHERE user_id = ?
		AND project_id = ?";

		$query = $this->db->query($sql, array($user_id, $project_id));
		return $query->result();
	}

	/**
	* Uploads a file
	*
	* @param 	array 	$config
	* @param	string	$field_name
	* @return	mixed
	*/
	public function do_upload($config, $field_name)
	{
		// $this->load->library('upload', $config);
		$this->upload->initialize($config);

		if ( ! $this->upload->do_upload($field_name))
		{
			$error = array('error' => $this->upload->display_errors());
			return $error;
		}
		else
		{
			$data = array('upload_data' => $this->upload->data());
			return TRUE;
		}
	}

	/**
	* Returns config for uploading an avatar
	*
	* @param 	integer 	$user_id = $this->session->id
	* @return	array
	*/
	public function getAvatarConfig($user_id = FALSE)
	{
		if( ! $user_id) $user_id = $this->session->id;
		$user = $this->Users_model->getUserInformations($user_id);

		// rename file LASTNAME_Name_avatar
		$file_name = strtoupper(sanitize_name($user->last_name)) . '_' . $user->name . '_avatar';

		$config['file_name']            = $file_name;
		$config['overwrite']            = TRUE;
		$config['file_ext_tolower']      = TRUE;
		$config['upload_path']          = './assets/uploads/users/avatars/';
		$config['allowed_types']        = 'gif|jpg|png';
		$config['max_size']             = 50000;

		return($config);
	}

	/**
	* Checks if a project deadline is reached
	*
	* @param 	integer 	$project_id
	* @return	boolean
	*/
	public function isDeadlineReached($project_id)
	{
		$this->db->where(array('id' => $project_id));
		$q = $this->db->get('projects', 1);

		$deadline = $q->row('deadline');
		$today = date('Y-m-d');

		if($today > $deadline)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	public function getNFilesToSubmitFromProjectId($project_id)
	{
		$this->db->select('extension, number_of_files');
		$this->db->from('projects');
		$this->db->where('id', $project_id);
		$result = $this->db->get()->row();

		if($result->extension)
		{
			return $result->number_of_files;
		}
		else
		{
			return 0;
		}
	}

	/**
	* Returns config for submitting a project
	*
	* @param 	integer 	$user_id = $this->session->id
	* @return	array
	*/
	public function getProjectSubmitConfig($project_id, $i = 1)
	{
		$config['file_name']            = $this->generateProjectFileName($project_id, $i);
		$config['overwrite']            = TRUE;
		$config['file_ext_tolower']      = TRUE;
		$config['upload_path']          = './assets/' . $this->_generateProjectFilePath($project_id);
		$config['allowed_types']        = $this->getAllowedSubmittedFileType($project_id);

		return($config);
	}

	/**
	* Generate a filepath from a project id
	* Format: upload/SCHOOL_YEAR/class/term
	*
	* @param 	integer 	$project_id
	* @return	string
	*/
	private function _generateProjectFilePath($project_id)
	{
		$this->load->helper('school');
		$this->load->helper('format');

		// get data
		$project_data = $this->getSubmitInformations($project_id);
		$sanitized_project_name = sanitize_name($project_data->project_name);

		// puts the file in ./upload/2015-2016/class/term
		$upload_dir = 'uploads/' . get_school_year() . '/' . $project_data->class . '/' . strtolower($project_data->term) . '/' . $sanitized_project_name . '/';

		// create dir if doesn't exist
		if ( ! is_dir('assets/' . $upload_dir)) mkdir('assets/' . $upload_dir, 0755, TRUE);

		return $upload_dir;
	}

	/**
	* Generate a filname from a project id
	*
	* @param 	integer 	$project_id
	* @return	string
	*/
	private function generateProjectFileName($project_id, $i = 1)
	{
		$this->load->model('Users_model','',TRUE);
		$this->load->helper('school');
		$this->load->helper('format');

		// get data
		$project_data = $this->getSubmitInformations($project_id);
		$sanitized_user_name = sanitize_name(strtoupper($this->session->last_name) . "_" .  $this->session->name);
		$sanitized_project_name = sanitize_name($project_data->project_name);

		$file_name =  $sanitized_user_name . '_' . $sanitized_project_name . '_' . sprintf("%02d", $i) . '.' . $project_data->extension;

		return $file_name;
	}

	/**
	* Return file type allowed for a given project
	*
	* @param 	integer 	$project_id
	* @return	string
	*/
	public function getAllowedSubmittedFileType($project_id)
	{
		return $this->getSubmitInformations($project_id)->extension;
	}

	/**
	* Insert subitted project in database
	*
	* @param 	integer 	$project_id
	* @param 	string		$file_name
	* @param 	string		$answers
	* @return	boolean
	*/
	public function submitProject($project_id, $file_name, $answers)
	{
		$data = array(
			'user_id' => $this->session->id,
			'project_id' => $project_id,
			'file_path' => $this->_generateProjectFilePath($project_id),
			'file_name' => $file_name,
			'answers' => serialize($answers)
		);

		$where = array(
			'user_id' => $this->session->id,
			'project_id' => $project_id,
			'file_name' => $file_name
		);

		// checks if record exists
		$this->db->where($where);
		$q = $this->db->get_where('submitted', $where, 1);

		// if true, update
		if ($q->num_rows() > 0)
		{
			$this->db->where($where);
			$this->db->update('submitted', $data);
		}
		// else insert
		else
		{
			$this->db->insert('submitted', $data);
		}

		return TRUE;
	}

	/**
	* Generate thumbnail from file
	*
	* @param 	string 	$image_full_path
	* @param 	string		$full_path
	* @return	string filepath
	*/
	public function makeThumbnail($image_full_path, $full_path)
	{
		$this->load->helper('path');

		$file_name = basename($image_full_path);

		$config['image_library'] = 'GD2';
		$config['source_image'] = $image_full_path;
		$config['new_image'] = $full_path . 'thumb_' . $file_name;
		$config['thumb_marker']  = '';
		$config['create_thumb'] = TRUE;
		$config['maintain_ratio'] = TRUE;
		$config['width']         = 500;
		$config['height']       = 500;

		$this->load->library('image_lib', $config);
		$this->image_lib->initialize($config);

		$this->image_lib->resize();

		//dump($this->image_lib->display_errors());
		return $config['new_image'];
	}

	public function rotateThumbnail($image_full_path, $rotation = FALSE)
	{
		$this->load->helper('path');

		$file_name = basename($image_full_path);
		$file_path = pathinfo($image_full_path)['dirname'];

		$config = array();
		$config['image_library'] = 'GD2';
		$config['source_image'] = $file_path . '/thumb_' . $file_name;
		$config['rotation_angle'] = $rotation;

		$this->load->library('image_lib', $config);
		$this->image_lib->initialize($config);
		$this->image_lib->rotate();
	}

	/**
	* Return data for the N lasts submitted projects
	*
	* @param 	string 		$class = FALSE
	* @param 	integer		$limit
	* @param 	string		$school_year = FALSE
	* @return	object
	*/
	public function getNLastSubmitted($class = FALSE, $limit = 10, $school_year = FALSE)
	{
		if($class) $this->db->where('projects.class', $class);

		$query = $this->db   ->select('name, last_name, project_name, projects.class')
		->select("DATE_FORMAT(`time`, '%d-%m-%Y à %k:%i') AS 'time'", FALSE)
		->where('admin_id', $this->session->id)
		->from('submitted')
		->join('users', 'user_id = users.id', 'left')
		->join('projects', 'project_id = projects.id', 'left')
		->group_by('users.name')
		->order_by('submitted.id', 'DESC')
		->limit($limit);

		if($school_year) $this->db->where('school_year', $school_year);

		return $query->get()->result();
	}

}
?>
