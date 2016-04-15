<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gradebook extends CI_Controller {

 function __construct()
 {
   parent::__construct();
   $this->load->model('Results_model','',TRUE);
   $this->load->model('Users_model','',TRUE);
   $this->Users_model->loginCheck();
 }

 function index()
 {

   $this->data['results'] = $this->Results_model->getUserOverallResults();
   $this->data['projects'] = $this->Results_model->getUserProjects();

   $this->load->template('gradebook', $this->data, false);
 }

}

?>
