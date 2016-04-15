<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

 function __construct()
 {
   parent::__construct();
   $this->load->model('Users_model','',TRUE);
   $this->Users_model->loginCheck();
 }

 function index()
 {
   $this->output->enable_profiler(TRUE);
   $this->load->helper(array('form'));
   $this->load->template('login_form');
 }

}

?>
