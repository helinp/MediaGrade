<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Typeahead extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('Users_model','',TRUE);
        $this->Users_model->loginCheck();
        $this->Users_model->adminCheck();
    }

    /*
     * TypeaHead JSON Data provider
     *
     */
    function index()
    {
        header('Content-type: application/json');
        if($this->input->get())
        {
            if($this->input->get('criterion'))
            {
                $this->db->select('criterion');
                $this->db->like('criterion', $this->input->get('criterion'), 'both');
            }
            elseif($this->input->get('cursor'))
            {
                $this->db->select('cursor');
                $this->db->like('cursor', $this->input->get('cursor'), 'both');
            }
            else
            {
                exit;
            }

            $this->db->distinct();
            $autocomplete = $this->db->get('assessments', 7)->result_array();

            // Generate JSON page
            print(json_encode($autocomplete, JSON_PRETTY_PRINT));
        }
    }
}

?>
