<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

 function __construct()
 {
   parent::__construct();
 }

 function index()
 {

    // redirection if on index page
    if($this->session->role === 'student') redirect('/projects');
    if($this->session->role === 'admin') redirect('/admin');

    $this->load->model('Projects_model','',TRUE);
    $this->data['random_media'] = $this->Projects_model->random_media();

    // $this->output->enable_profiler(TRUE);
    $this->load->helper(array('form'));
    $this->load->template('login_form', $this->data, false);
 }

}

?>
