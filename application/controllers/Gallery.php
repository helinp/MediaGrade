<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gallery extends CI_Controller {

	private $pag_config = array();
	private $limit = FALSE;

	function __construct()
	{
		parent::__construct();
		$this->load->model('Gallery_model','',TRUE);
		$this->load->helper('form');

		$this->data['classes'] = $this->Classes_model->getAllClasses();
		$this->data['students'] = $this->Users_model->getAllStudents();

		// pagination config
		$this->pag_config['per_page'] = $this->limit = 12;
		$this->pag_config['reuse_query_string'] = TRUE;
		$this->pag_config['use_page_numbers'] = TRUE;
		$this->pag_config['last_tag_open'] = $this->pag_config['first_tag_open'] = $this->pag_config['next_tag_open'] = $this->pag_config['prev_tag_open'] = $this->pag_config['num_tag_open'] = '<li>';
		$this->pag_config['last_tag_close'] = $this->pag_config['first_tag_close'] = $this->pag_config['next_tag_close'] = $this->pag_config['prev_tag_close'] = $this->pag_config['num_tag_close'] = '</li>';
		$this->pag_config['cur_tag_open'] = '<li class="active"><a href="#">';
		$this->pag_config['cur_tag_close'] = '</a></li>';
	}

	function index($offset = 0, $personal =  FALSE)
	{
		$this->data['page_title'] = _('Gallerie');

		$class = $this->input->get('classe');
		$project_id = $this->input->get('project');
		$user_id = FALSE;

		if($personal)
		{
			$user_id = $this->session->user_id;
			unset($this->data['classes']);
			$this->data['page_title'] = _('Ma gallerie');
		}

		$filters = array(	'projects.class' => $class,
			'project_id' => $project_id,
			'user_id' => $user_id
		);

		$this->data['projects'] = $this->Projects_model->getAllActiveProjectsByClass($class);
		$this->data['medias'] =  $this->Gallery_model->getProjectsGalleryBy($filters, $offset * $this->limit, $this->limit);

		// PAGINATION
		$this->load->library('pagination');

		$this->pag_config['base_url'] = '/gallery/';
		$this->pag_config['num_links'] =  10;
		$this->pag_config['total_rows'] = count($this->Gallery_model->getProjectsGalleryBy($filters, FALSE, FALSE));
		$this->pagination->initialize($this->pag_config);

		$this->load->template('gallery/gallery', $this->data);
	}

	function my()
	{
		$this->index();
	}
}

?>
