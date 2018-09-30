<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Statistics extends MY_AdminController {

	function __construct()
	{
		parent::__construct();

	}

	function index()
	{
		redirect('/admin/statistics/classes');
	}


	function classes()
	{
		// prepare for graph

		// GET ALL Classes
		$classes =  $this->Classes_model->getAllClasses();
		// Get all school_years
		$school_years = $this->Projects_model->getSchoolYears('ASC');

		$history_graph = array();

		foreach ($school_years as $school_year)
		{
			foreach ($classes as $classe)
			{
				$history_graph['school_years'][$school_year->school_year] = $school_year->school_year;
				$history_graph['values'][$classe->name][$school_year->school_year] = $this->Careers_model->statsBySchoolYearAndClassId($school_year->school_year, $classe->id)['n_students'];
			}
		}
//dump($history_graph);
		$this->data['history'] = $history_graph;
		$this->data['page_title'] = 'Statistiques';
		$this->load->template('admin/statistics', $this->data);
	}
}

?>
