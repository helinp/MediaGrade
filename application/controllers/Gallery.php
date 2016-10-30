<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gallery extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('Users_model','',TRUE);
        $this->load->model('Projects_model','',TRUE);
        $this->load->model('Gallery_model','',TRUE);
        $this->load->helper('form');
        $this->load->model('Classes_model','',TRUE);

        $this->data['classes'] = $this->Classes_model->getAllClasses();

    }


    function view($offset = 0)
    {
        $limit = 12;
        $class = $this->input->get('classe');
        $project_id = $this->input->get('project');

        $args = array( 'projects.class' => $class,
                       'projects.id' => $project_id
                     );

	    $this->data['projects'] = $this->Projects_model->getAllActiveProjectsByClass($class);
	    $this->data['medias'] =  $this->Gallery_model->getProjectsGalleryBy($args, $offset * $limit, $limit);

	    // PAGINATION
	    $this->load->library('pagination');

	    $config['base_url'] = '../../gallery/view/';
	    $config['num_links'] =  $config['total_rows'] = count($this->Gallery_model->getProjectsGalleryBy($args));
	    $config['per_page'] = $limit;
	    $config['reuse_query_string'] = TRUE;
	    $config['use_page_numbers'] = TRUE;
	    $config['last_tag_open'] = $config['first_tag_open'] = $config['next_tag_open'] = $config['prev_tag_open'] = $config['num_tag_open'] = '<li>';
	    $config['last_tag_close'] = $config['first_tag_close'] = $config['next_tag_close'] = $config['prev_tag_close'] = $config['num_tag_close'] = '</li>';
	    $config['cur_tag_open'] = '<li class="active"><a href="#">';
	    $config['cur_tag_close'] = '</a></li>';

	    $this->pagination->initialize($config);
	    // . PAGINATION

	    $this->load->template('gallery/gallery', $this->data);
	}


	function index()
	{
	    redirect('./gallery/view/');

	}

	function my($offset = 0)
	{
	    $limit = 12;
	    $arg[0] = $this->input->get('classe');
	    $arg[1] = $this->input->get('project');
	    $arg[2] = $this->session->id;

	    $args = array( 'projects.class' => $arg[0],
	    'project_id' => $arg[1],
	    'user_id' => $arg[2]
	    );

	    $this->data['projects'] = $this->Projects_model->getAllActiveProjectsByClass($arg[0]);
	    $this->data['medias'] =  $this->Gallery_model->getProjectsGalleryBy($args, $offset * $limit, $limit);

	    // PAGINATION
	    $this->load->library('pagination');

	    $config['base_url'] = '../../gallery/view/';
	    $config['num_links'] =  $config['total_rows'] = count($this->Gallery_model->getProjectsGalleryBy($args));
	    $config['per_page'] = $limit;

	    $config['reuse_query_string'] = TRUE;
	    $config['use_page_numbers'] = TRUE;
	    $config['last_tag_open'] = $config['first_tag_open'] = $config['next_tag_open'] = $config['prev_tag_open'] = $config['num_tag_open'] = '<li>';
	    $config['last_tag_close'] = $config['first_tag_close'] = $config['next_tag_close'] = $config['prev_tag_close'] = $config['num_tag_close'] = '</li>';
	    $config['cur_tag_open'] = '<li class="active"><a href="#">';
	    $config['cur_tag_close'] = '</a></li>';


	    $this->pagination->initialize($config);
	    // . PAGINATION

	    $this->load->template('gallery/gallery', $this->data);
	}
}

?>
