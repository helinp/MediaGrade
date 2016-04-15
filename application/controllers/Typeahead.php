<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Typeahead extends CI_Controller {

 function __construct()
 {
   parent::__construct();
   $this->load->model('Users_model','',TRUE);
   $this->Users_model->loginCheck();
   $this->Users_model->adminCheck();
 }

 function index()
 {

     header("Content-type: application/json; charset=utf-8'");

     if($this->input->get())
     {
         $autocomplete = "";

         if($this->input->get('criterion'))
         {
             $this->db->select('criterion');
             $this->db->distinct();
             $this->db->like('criterion', $this->input->get('criterion'), 'both');
             $query = $this->db->get('assessments', 7);
             $autocomplete = $query->result();
         }
         if($this->input->get('cursor'))
         {
             $this->db->select('cursor');
             $this->db->distinct();
             $this->db->like('cursor', $this->input->get('cursor'), 'both');
             $query = $this->db->get('assessments', 7);
             $autocomplete = $query->result();
         }

         print(json_encode($autocomplete, JSON_PRETTY_PRINT));
     }

 }

}

?>
