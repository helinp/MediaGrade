<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profile extends CI_Controller {

    function __construct()
    {
        parent::__construct();

        $this->load->model('Users_model','',TRUE);
        $this->Users_model->loginCheck();

        $this->load->model('UsersManager_model','',TRUE);
        $this->load->model('Submit_model','',TRUE);
        $this->load->helper('form');
        $this->load->helper('format');
        $this->load->helper('school');
    }

    function index()
    {

        $this->data['user_data'] =  $this->Users_model->getUserInformations();
        $this->data['preferences_data'] = $this->Users_model->getUserPreferences('email');
        $this->load->template('profile', $this->data);
    }

    public function upload()
    {

        $config = $this->Submit_model->getAvatarConfig();

        //var_dump($_POST);die;

        $error = $this->Submit_model->do_upload($config, 'avatar_file');

        if (isset($error['error']))
        {
            $this->data['error'] = show_error($error['error']);
        }
        else
        {
            $file_path = '/assets/uploads/users/avatars/' . $this->upload->data()['file_name'];
            $this->UsersManager_model->saveAvatar(false, $file_path);
        }
        // update session
        $this->session->set_userdata('avatar', $file_path);

        redirect('/profile');

    }

    public function update()
    {
        $this->postRouter();

        // TODO validation Message

        redirect('profile');
    }

    private function postRouter()
    {
        $post = $this->input->post();

        // update DB
        if(isset($post['change_password']))
            $this->UsersManager_model->changePassword(false, $post['current_password'], $post['new_password'], $post['new_password_confirmation']);
        elseif(isset($post['change_email']))
            $this->UsersManager_model->changeEmail(false, $post['email']);
        elseif(isset($post['change_mail_preferences']))
            $this->UsersManager_model->changeEmailPreferences(false, $post);
        else
            return false;

        // update session
        $this->Users_model->updateSession();

        return true;
    }
}

?>
