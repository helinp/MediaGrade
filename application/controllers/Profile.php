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
		// TODO getAvatarConfig() shouln'd be in submit model
        $config = $this->Submit_model->getAvatarConfig();
        $error = $this->Submit_model->do_upload($config, 'avatar_file');

        if (isset($error['error']))
        {
            $this->data['error'] = show_error($error['error']);
        }
        else
        {
			$file_path = '/assets/uploads/users/avatars/' . $this->upload->data()['file_name'];
			$this->UsersManager_model->saveAvatar(FALSE, $file_path);

			$img_size = getimagesize('.' . $file_path);

			$this->load->library('image_lib');
			$config['image_library'] 	= 'gd2';
			$config['source_image'] 	= '.' . $file_path;
			$config['maintain_ratio'] 	= TRUE;
			$config['x_axis']			= 0;
			$config['y_axis'] 			= 0;

			if($img_size[0] > $img_size[1])
			{
				$config['height']      	= 300;
			}
			else
			{
				$config['width']       	= 300;
			}
			//$this->load->library('image_lib', $config);

			$this->image_lib->initialize($config);

			if ( ! $this->image_lib->resize())
			{
			        show_error($this->image_lib->display_errors());
			}

			$config['maintain_ratio'] 	= FALSE;
			$config['width']       		= 300;
			$config['height']       	= 300;

			$this->image_lib->initialize($config);

			if ( ! $this->image_lib->crop())
			{
			        show_error($this->image_lib->display_errors());
			}

			// crop and resize picture
			// del original

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
		{
            $this->UsersManager_model->changePassword(FALSE, $post['current_password'], $post['new_password'], $post['new_password_confirmation']);
		}
        elseif(isset($post['change_email']))
		{
            $this->UsersManager_model->changeEmail(FALSE, $post['email']);
		}
        elseif(isset($post['change_mail_preferences']))
		{
            $this->UsersManager_model->changeEmailPreferences(FALSE, $post);
		}
		elseif(isset($post['motto']))
		{
			$this->UsersManager_model->changeMotto(FALSE, $post['motto']);
		}
        else
		{
            return FALSE;
		}
        // update session
        $this->Users_model->updateSession();

        return true;
    }
}

?>
