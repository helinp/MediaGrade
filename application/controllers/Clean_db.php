<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Clean_db extends CI_Controller {

 function __construct()
 {
   parent::__construct();
   $this->load->model('Users_model','',TRUE);
   $this->Users_model->loginCheck();
   $this->Users_model->adminCheck();
 }

 function index()
 {

 }

}

?>
