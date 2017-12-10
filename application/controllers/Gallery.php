<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gallery extends CI_Controller {

	private $pag_config = array();
	private $limit = FALSE;

	function __construct()
	{
		parent::__construct();
		$this->load->model('Users_model','',TRUE);
		$this->Users_model->loginCheck();

		$this->load->model('Gallery_model','',TRUE);
		$this->load->helper('form');

		$this->data['classes'] = $this->Classes_model->getAllClasses();
		$this->data['students'] = $this->Users_model->getAllUsers();

		// pagination config
		$this->pag_config['per_page'] = $this->limit = 12;
		$this->pag_config['reuse_query_string'] = TRUE;
		$this->pag_config['use_page_numbers'] = TRUE;
		$this->pag_config['last_tag_open'] = $this->pag_config['first_tag_open'] = $this->pag_config['next_tag_open'] = $this->pag_config['prev_tag_open'] = $this->pag_config['num_tag_open'] = '<li>';
		$this->pag_config['last_tag_close'] = $this->pag_config['first_tag_close'] = $this->pag_config['next_tag_close'] = $this->pag_config['prev_tag_close'] = $this->pag_config['num_tag_close'] = '</li>';
		$this->pag_config['cur_tag_open'] = '<li class="active"><a href="#">';
		$this->pag_config['cur_tag_close'] = '</a></li>';
	}


	function index($offset = 0, $project_id = FALSE)
	{
		// cleaner url
		if(is_numeric($this->input->get('project')))
		{
			redirect('/gallery/' . $offset . '/' . $this->input->get('project'));
		}

		$class = $this->input->get('classe');

		$args = array( 'projects.class' => $class,
		'projects.id' => $project_id
		);

	$this->data['projects'] = $this->Projects_model->getAllActiveProjectsByClass($class);
	$this->data['medias'] =  $this->Gallery_model->getProjectsGalleryBy($args, $offset * $this->limit, $this->limit);

	// PAGINATION
	$this->load->library('pagination');
	$this->pag_config['num_links'] =  $this->pag_config['total_rows'] = count($this->Gallery_model->getProjectsGalleryBy($args));

	$this->pag_config['base_url'] = '/gallery/';
	$this->pagination->initialize($this->pag_config);
	// . PAGINATION

	$this->load->template('gallery/gallery', $this->data);
}
}

?>
