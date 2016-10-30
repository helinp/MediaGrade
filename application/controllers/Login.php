<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('Users_model','',TRUE);
    }

    function index()
    {
        if($this->input->post()) $this->login_route();

        // redirection if on index page
        if($this->session->role === 'student') redirect('/projects');
        if($this->session->role === 'admin') redirect('/admin');

        $this->load->model('Projects_model','',TRUE);
        $this->data['random_media'] = $this->Projects_model->random_media();

        $this->load->helper(array('form'));
        $this->load->template('login_form', $this->data, false);
    }

    private function login_route()
    {
        // Check Form
        $this->load->library('form_validation');

        $this->form_validation->set_rules('username', 'Username', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');

        if($this->form_validation->run() == FALSE)
		{
        	redirect('login', 'refresh');
		}
        elseif($this->Users_model->login($this->input->post('username'), $this->input->post('password')))
        {
            // user routing
            if($this->session->role === 'admin')
			{
            	redirect('admin', 'refresh');
			}
			else
			{
				redirect('projects', 'refresh');
			}
		}
    }

}

?>
