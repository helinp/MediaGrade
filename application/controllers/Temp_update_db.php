<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Temp_update_db extends CI_Controller {

 function __construct()
 {
   parent::__construct();
   $this->load->model('Users_model','',TRUE);
   $this->Users_model->loginCheck();
   $this->Users_model->adminCheck();
 }

 function index()
 {
     $sql ="SELECT id, assessment_ids FROM projects";

     $query = $this->db->query($sql);

     $results = $query->result();
     //dump($results);

     foreach($results as $result)
     {
         $assessment_ids = explode(',', $result->assessment_ids);

         foreach ($assessment_ids as $assessment_id)
         {
             //dump($assessment_id);

             $sql ="INSERT INTO projects_assessments (project_id, assessment_id) VALUES ($result->id, $assessment_id)";

             $query = $this->db->query($sql);


         }

     }


 }

}

?>
