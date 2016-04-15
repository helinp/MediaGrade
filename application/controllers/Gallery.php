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
   $this->data['classes'] = $this->Classes_model->listAllClasses();

   $this->data['op_projects'] = $this->Gallery_model->makeOptionArray('projects', 'project_name');
   $this->data['op_users'] = $this->Gallery_model->makeOptionArray('users', 'last_name');
 }

 function index()
 {
   redirect('/gallery/all');
 }

 function all()
 {
   $offset = $this->uri->segment(3, 0);
   $this->data['medias'] =  $this->Gallery_model->getProjectsGallery(false, $offset);
   $this->data['selected_proj_op'] = 'All';
   $this->data['selected_user_op'] = 'All';

   $this->load->template('gallery/gallery', $this->data, 'sort');
 }

 function my()
 {
   $this->Users_model->loginCheck();
   $offset = $this->uri->segment(3, 0);
   $this->data['medias'] =  $this->Gallery_model->getProjectsGallery($this->session->id, $offset);
   $this->load->template('gallery/gallery', $this->data);
 }

 function project()
 {
   if($this->input->post('project') === 'all') redirect('/gallery/all');

   $arg = $this->input->post('project');
   $op_selected = $arg;

   $offset = $this->uri->segment(3, 0);
   $this->data['medias'] =  $this->Gallery_model->filterByProject_id($arg);
   $this->data['selected_proj_op'] = $op_selected;
   $this->data['selected_user_op'] = 'All';

   $this->load->template('gallery/gallery', $this->data, 'sort');
 }

 function user()
 {
   if($this->input->post('user') === 'all') redirect('/gallery/all');

   $arg = $this->input->post('user');
   $op_selected = $arg;

   $offset = $this->uri->segment(3, 0);

   $this->data['medias'] =  $this->Gallery_model->filterByUser_id($arg);
   $this->data['selected_proj_op'] = 'All';
   $this->data['selected_user_op'] = $op_selected;
   $this->load->template('gallery/gallery', $this->data, 'sort');
 }

}

?>
