<?php
Class FilesFormat_model extends CI_Model
{

 	/**
	 * Returns all distincts authorized file formats
	 *
	 * @return	object
	 */
        public function getAllDistinctFormats()
        {
            $this->db->distinct();
            $this->db->select('extension');
	    	$this->db->order_by('extension', 'ASC');
			
            return $this->db->get('files_format')->result();
        }


}
