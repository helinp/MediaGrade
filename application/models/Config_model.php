<?php
/**
* Short description for class
*
* Long description for class (if any)...
*
*/

Class Config_model extends CI_Model
{


	/**
	 * Returns descriptive assessment votes
	 *
	 * @param integer $project_id
	 * @return object
	 * @todo use querybuilder
	 */
	public function getTextualAssessmentList()
	{
		$this->db->where('name', $name);
		$this->db->get('config', $name);
	}



}
?>
