<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('Users_model','',TRUE);

	}

	function index()
	{
		if($this->input->post())
		{
			$this->login_route();
		}

		// redirection if on index page
		if($this->Users_model->isLoggedIn() && $this->Users_model->isAdmin())
		{
			redirect('/admin');
	 	}
		elseif($this->Users_model->isLoggedIn())
		{
			redirect('/student');
		}

		$this->data['random_media'] = $this->Projects_model->random_media();

		if($this->config->item('captcha'))
		{
			$this->data['captcha'] = $this->_captcha();
		}

		$this->load->helper(array('form'));
		$this->load->template('login_form', $this->data, false);
	}

	private function login_route()
	{
		// Check Form
		$this->load->library('form_validation');

		$this->form_validation->set_rules('username', 'Username', 'trim|required');
		$this->form_validation->set_rules('password', 'Password', 'trim|required');

		if($this->form_validation->run() == FALSE)
		{
			redirect('login', 'refresh');
		}

		if($this->config->item('captcha'))
		{
			// First, delete old captchas
			$expiration = time() - 7200; // Two hour limit
			$this->db->where('captcha_time < ', $expiration)
				->delete('captcha');

			// Then see if a captcha exists:
			$sql = 'SELECT COUNT(*) AS count FROM captcha WHERE word = ? AND ip_address = ? AND captcha_time > ?';
			$binds = array($_POST['captcha'], $this->input->ip_address(), $expiration);
			$query = $this->db->query($sql, $binds);
			$row = $query->row();

			if ($row->count == 0)
			{
				redirect('login', 'refresh');
			}
		}

		// Authentification
		if($this->Users_model->logUser($this->input->post('username'), $this->input->post('password')))
		{
			$this->Users_model->setSession();
			// user routing
			if($this->Users_model->isAdmin())
			{
				redirect('admin', 'refresh');
			}
			else
			{
				redirect('/', 'refresh');
			}
		}
		else
		{
			$this->session->set_flashdata('error','Mauvais nom d\'utilisateur et/ou mot de passe');
		}
	}

	private function _captcha()
	{
		$this->load->helper('captcha');
		$this->load->model('captcha_model', '', TRUE);

		$captcha_vals = array(
			'word'          => $this->captcha_model->getRandomWord(),
			'img_path'      => 'captcha/',
			'img_url'       => base_url() . 'captcha/',
			'font_path'     => './' . base_url() . 'captcha/fonts/captcha4.ttf',
			'img_width'     => '250',
			'img_height'    => 50,
			'expiration'    => 7200,
			//'word_length'   => 6,
			'font_size'     => 15,
			'pool'          => '23456789ABCDEFGHJKLMNPQRSTUVWXYZ',

			// White background and border, black text and red grid
			'colors'        => array(
				'background' => array(255, 255, 255),
				'border' => array(255, 255, 255),
				'text' => array(0, 0, 255),
				'grid' => array(0, 0, 255)
			)
		);

		$cap = create_captcha($captcha_vals);

		$data = array(
			'captcha_time'  => $cap['time'],
			'ip_address'    => $this->input->ip_address(),
			'word'          => $cap['word']
		);
		$query = $this->db->insert_string('captcha', $data);
		$this->db->query($query);

		return 	$cap['image']
		.'<input type="text" class="form-control"  autocomplete="off" name="captcha" value="" placeholder="Captcha" />';

	}
}

?>
