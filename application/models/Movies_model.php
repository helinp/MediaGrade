<?php
Class Movies_model extends CI_Model
{
	public function getMoviesList()
	{
		$sql = "SELECT ROUND(AVG(vote), 1) as avg_vote,
					COUNT(maf_id) as num_vote,
					maf_id, title, overview, year, poster_path,
					original_title
					FROM movies_advisor
					GROUP BY maf_id
					ORDER BY num_vote DESC, vote DESC, title ASC";

		$query = $this->db->query($sql);
		$results = $query->result();

		return $results;
	}

	// work in progress
	public function voteUp($maf_id)
	{

		// check if user already voted up
		$where = array(
				'user_id' => $this->session->id,
				'maf_id' => $maf_id
		);

		// checks if record exists
		$this->db->where($where);
		$q = $this->db->get_where('movies_advisor', $where, 1);

		if ($q->num_rows() > 0) return FALSE;

		// get movie info
		$where = array(
				'maf_id' => $movie_info['id']
		);

		// get data
		$data = $this->db->get_where('movies_advisor', $where, 1);
		$data['user_id'] = $this->session->id;

		//insert data
		$this->db->insert('movies_advisor', $data);

		return TRUE;
	}


	public function addMovie($movie_info, $movie_vote)
	{
		$this->load->helper('format');

		$movie_year = substr($movie_info['release_date'], 0, 4);
		$poster_path = '/assets/movies/poster_' . $movie_year . '_' . sanitize_name($movie_info['title']) . '_' . $movie_info['id'] . '.jpg';

		if ( ! copy($movie_info['poster_path'], '.' . $poster_path)) return FALSE;

		$data = array(
				'user_id' => $this->session->id,
				'maf_id' => $movie_info['id'],
				'title' => $movie_info['title'],
				'original_title' => $movie_info['original_title'],
		        'year' => $movie_year,
		        'overview' => htmlspecialchars($movie_info['overview']),
				'poster_path' => $poster_path,
				'vote' => $movie_vote
		);

		$where = array(
				'user_id' => $this->session->id,
				'maf_id' => $movie_info['id']
		);

		// checks if record exists
        $this->db->where($where);
        $q = $this->db->get_where('movies_advisor', $where, 1);

        // if true, update
        if ($q->num_rows() > 0)
        {
            $this->db->where($where);
            $this->db->update('movies_advisor', $data);
        }
        // else insert
        else
        {
            $this->db->insert('movies_advisor', $data);
        }

		return TRUE;
	}

	public function getMovieInfoByNameAndYear($name, $year)
	{
		$url = 'http://www.myapifilms.com/tmdb/searchMovie?'
			. 'movieName=' . str_replace(' ', '+', $name)
			. '&searchYear=' . $year
			. '&token=' . MY_API_FILM_TOKEN
			. '&format=json&language=fr&includeAdult=0';

		if($json = file_get_contents($url))
		{
			$ar = json_decode($json);
			$ar = $ar->data->results;

			if ( ! empty($ar))
			{
				$ar = (array) $ar[0];
				$ar['poster_path'] = 'https://image.tmdb.org/t/p/w185/' . $ar['poster_path'];
				return $ar;
			}
		}
		return FALSE;
	}

	public function getMovieInfoByName($name)
	{
		$url = 'http://www.myapifilms.com/tmdb/searchMovie?'
			. 'movieName=' . $name
			. '&token=' . MY_API_FILM_TOKEN
			. '&format=json&language=fr&includeAdult=0';

		if($json = file_get_contents($url))
		{
			$ar = json_decode($json);

			if(isset($ar->error)) return false;

			$ar = $ar->data->results;

			if ( ! empty($ar))
			{
				$ar = (array) $ar[0];
				$ar['poster_path'] = 'https://image.tmdb.org/t/p/w185/' . $ar['poster_path'];
				return $ar;
			}
		}
		return FALSE;
	}

	public function getMovieInfoById($id)
	{
		$url = 'http://www.myapifilms.com/tmdb/movieInfoImdb?'
			. 'idIMDB=' . $id
			. '&token=' . MY_API_FILM_TOKEN
			. '&format=json&language=fr&alternativeTitles=1&casts=0&images=1&keywords=0&releases=0&videos=0&translations=0&similar=0&reviews=0&lists=0';

		if($json = file_get_contents($url))
		{
			$ar = (array) json_decode($json)->data;
			$ar['poster_path'] = 'https://image.tmdb.org/t/p/w185/' . $ar['poster_path'];
			return $ar;
		}
		else
		{
			return FALSE;
		}
	}

	public function getNLastSubmitted($n = 10)
	{
		$sql = "SELECT vote, title, name, last_name
					FROM movies_advisor
					LEFT JOIN users
						ON user_id = users.id
					ORDER BY movies_advisor.id DESC
					LIMIT $n";

		$query = $this->db->query($sql);
		$results = $query->result();

		return $results;
	}

}
?>
