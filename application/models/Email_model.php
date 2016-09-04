<?php
Class Email_model extends CI_Model
{

	public function __construct()
	{
		$this->load->library('email');
	}

	public function sendObjectMessageToEmail($subject, $message, $email)
	{
		$this->email->from(NO_REPLY_MAIL, 'MediaGrade');
		date_default_timezone_set(TIMEZONE);

		if( ! $email) return FALSE;

		if(DEMO_VERSION) return FALSE;

		$this->email->to($email);
		$this->email->subject($subject);
		$this->email->message($message);

		if ($this->email->send())
            return TRUE;
        elseif (TRUE)
            show_error($this->email->print_debugger());
		else
			return FALSE;
	}

	public function sendSubmitConfirmationToUser($project_name, $email)
	{
		$subject = _('Projet remis!');
		$message = _("Ton travail ($project_name) a bien été remis. Félicitations!");

		$this->sendObjectMessageToEmail($subject, $message, $email);
	}

	public function sendAssessmentNotificationToUser($project_name, $email)
	{
		$subject = _('Projet remis!');
		$message = _("Ton travail ($project_name) vient d'être évalué. Tu peux désormais consulter tes résultats sur MediaGrade.");

		$this->sendObjectMessageToEmail($subject, $message, $email);
	}

	public function sendSubmitConfirmationToAdmin($project_name, $email)
	{
		$subject = _('Projet remis!');
		$date = date('d/m/Y \à h:i:s', time());

		$this->load->helper('system');

		$message = $_SESSION['name'] . ' ' . $_SESSION['last_name'] . ' a remis le projet "' . $project_name . '" ce ' . $date . ' depuis l\'adresse ' . get_user_ip() . '.';

		$this->sendObjectMessageToEmail($subject, $message, $email);
	}

}
?>
