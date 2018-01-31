<?php
Class Email_model extends CI_Model
{

	/**
	* Loads CodeIgniter email library
	*
	*/
	public function __construct()
	{
		$this->load->library('email');
	}

	/**
	* Basic function to send a mail
	*
	* @param string $subject
	* @param string $message
	* @param string $email
	* @return boolean
	* @todo enable print_debugger() on debug mode ($config)
	*/
	public function sendObjectMessageToEmail($subject, $message, $email)
	{
		// no way to send an email in demonstration version
		if($this->config->item('mode') === 'demo') return FALSE;

		date_default_timezone_set($this->config->item('timezone'));
		$this->email->from($this->config->item('no_reply_mail'), 'MediaGrade');
		$this->email->to($email);
		$this->email->subject($subject);
		$this->email->message($message);

		if ($this->email->send()) return TRUE;
		// TODO add: elseif ( *debugmode* === TRUE) show_error($this->email->print_debugger());
		elseif($this->session->role ==='admin') show_error($this->email->print_debugger());
		return FALSE;
	}

	/**
	* Sends a submit email confirmation to the student
	*
	* @param string $project_name
	* @param string $email
	* @return boolean
	*/
	public function sendSubmitConfirmationToUser($project_name, $email)
	{
		$subject = _('Projet remis!');
		$message = _("Ton travail ($project_name) a bien été remis. Félicitations!");

		return $this->sendObjectMessageToEmail($subject, $message, $email);
	}

	public function sendHtmlSubmitConfirmationToUser($project_name, $email, $thumb_url = FALSE)
	{
		$subject = _('Projet remis!');
		$message = _('<p>Bonjour ' . $this->session->first_name . ",</p> <p>Ton travail ($project_name) a bien été remis.</p><p>Félicitations!</p>");
		if($thumbs_url ==! FALSE )
		{
			foreach ($thumbs_url as $thumb_url)
			{
				$this->email->attach($thumb_url, 'inline');
			}
		}
		return $this->sendObjectMessageToEmail($subject, $message, $email);
	}

	/**
	* Sends an assessment email notification to the student
	*
	* @param string $project_name
	* @param string $email
	* @return boolean
	*/
	public function sendAssessmentNotificationToUser($project_name, $email)
	{
		$subject = _('Projet corrigé!');
		$message = _('<p>Bonjour ' . $this->session->first_name . ",</p><p>Ton travail ($project_name) vient d'être évalué. Tu peux désormais consulter tes résultats sur MediaGrade.</p>");

		return $this->sendObjectMessageToEmail($subject, $message, $email);
	}

	/**
	* Sends an email confirmation to the teacher for a submitted project
	*
	* @param string $project_name
	* @param string $email
	* @return boolean
	*/
	public function sendSubmitConfirmationToAdmin($project_name, $email, $thumbs_url = FALSE)
	{
		$subject = _('Projet remis!');
		$date = date('l d/m/Y \à H:i:s', time());

		// loads get_user_ip() helper
		$this->load->helper('system');

		$message = '<p>Bonjour Maître,</p><p>Le Padawan ' . $_SESSION['name'] . ' ' . $_SESSION['last_name'] . ' a remis le projet "'
		. $project_name . '" ce ' . $date . ' depuis l\'adresse ' . get_user_ip() . '.</p>';

		if($thumbs_url ==! FALSE )
		{
			foreach ($thumbs_url as $thumb_url)
			{
				$this->email->attach($thumb_url, 'inline');
			}
		}
		return $this->sendObjectMessageToEmail($subject, $message, $email);
	}
}
?>
