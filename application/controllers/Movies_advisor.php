<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Movies_Advisor extends CI_Controller {


	function __construct()
	{
		parent::__construct();
		$this->load->helper('text');
		$this->load->model('Users_model','',TRUE);
        $this->load->model('Movies_model','',TRUE);
		$this->Users_model->loginCheck();
		
		$this->load->model('Classes_model','',TRUE);
		$this->data['classes'] = $this->Classes_model->listAllClasses();
	}

	public function index()
	{
		$this->data['content'] = $this->Movies_model->getMoviesList();

	 	$this->load->template('movies-advisor/movies-advisor', $this->data, true);
	}

	public function search()
	{
		$movie_name = $this->input->post('movie-name');
		$movie_year = $this->input->post('movie-year');
		$movie_id = $this->input->post('movie-id');

		if( ! empty($movie_id))
		{
			$this->data['movie_info'] = $this->data['movie_info'] = $this->Movies_model->getMovieInfoById($movie_id);
			$this->session->set_userdata('movie', $this->data['movie_info']);
		}
		elseif ( ! empty($movie_name) && empty($movie_year))
		{
			$this->data['movie_info'] = $this->Movies_model->getMovieInfoByName($movie_name);
			$this->session->set_userdata('movie', $this->data['movie_info']);
		}
		elseif ( ! empty($movie_name) && ! empty($movie_year))
		{
			$this->data['movie_info'] = $this->Movies_model->getMovieInfoByNameAndYear($movie_name, $movie_year);
			$this->session->set_userdata('movie', $this->data['movie_info']);
		}

		$this->index();

	}

	public function vote()
	{
		if($this->session->has_userdata('movie'))
		{
			$movie_vote = $this->input->post('movie-vote');
			$this->Movies_model->addMovie($this->session->movie, $movie_vote);
			$this->session->unset_userdata('movie');
		}
		$this->index();
	}

	public function voteUp()
	{
		$movie_id = $this->input->post('id_maf');
		$this->Movies_model->voteUp($movie_id);
		$this->session->unset_userdata('movie');

		$this->index();
	}

}
