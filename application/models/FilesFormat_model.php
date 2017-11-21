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

		/**
		 * Returns Mime Format from extension
		 *
		 * @return array
		 */
		public function returnMimeFromExtension($ext)
		{
			$this->db->distinct();
			$this->db->select('mime');
			$this->db->where('extension', $ext);

			$result = $this->db->get('files_format')->result_array();

			if($result)
			{
				return array_values($result[0]);
			}
		}
}
