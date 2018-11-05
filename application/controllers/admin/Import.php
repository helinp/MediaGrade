<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Import extends MY_AdminController {

	private $data;

	function __construct()
	{
		parent::__construct();

		$this->load->model('Pdf_model','',TRUE);

		$this->load->helper(array('form', 'url'));

		$this->data['classes'] = $this->Classes_model->getAllClasses();
		$this->data['terms'] = $this->Terms_model->getAll();

		if($this->input->get('school_year'))
		{
			$this->school_year = $this->input->get('school_year');
		}
		else
		{
			$this->school_year = get_school_year();
		}
		$this->data['school_years'] = $this->Projects_model->getSchoolYears();

		$submenu[] = array('title' => 'Automatique', 'url' => '/admin/import/automatic');
		$submenu[] = array('title' => 'Manuel', 'url' => '/admin/import/manual');
		$this->data['submenu'] = $submenu;

		$this->data['projects'] = $this->Projects_model->getAllActiveProjectsByExternalAndSchoolYear(TRUE, $this->school_year);

		$this->Users_model->orderBy('class, last_name, first_name');
		if($this->input->get('class'))
		{
			$this->data['students'] = $this->Users_model->getAllStudentsByClass($this->input->get('class'));
		}
		else
		{
			$this->data['students'] = $this->Users_model->getAllStudents();
		}

	}


	public function index()
	{
		redirect('/admin/import/automatic');
	}

	public function automatic()
	{
		$this->data['page_title'] = _('Importer des évaluations papier');
		$this->load->template('admin/import_assessments_automatic', $this->data);
	}

	public function manual()
	{
		$this->data['projects'] = $this->Projects_model->getAllActiveProjectsByExternalAndSchoolYear(TRUE, $this->school_year);
		$this->data['page_title'] = _('Importer des évaluations papier');
		$this->load->template('admin/import_assessments_manual', $this->data);
	}


	public function upload()
	{
		//
		// get files in temp folder
		//
		$config['upload_path']          = './assets/uploads/import/temp';
		$config['allowed_types']        = 'gif|jpg|png|pdf';
		$config['file_name']            = 'importation';
		$config['overwrite']            = TRUE;
		$config['file_ext_tolower']      = TRUE;

		$this->upload->initialize($config);
		if ( ! $this->upload->do_upload('userfile'))
		{
			$data = array('error' => $this->upload->display_errors());
		}
		else
		{
			$data = array('upload_data' => $this->upload->data());
		}
		//
		// convert to image
		//
		$this->load->library('image_lib');

		$imagick = new Imagick();
		$imagick->setResolution(250, 250);

		// save pages to temp path
		$imagick->readImage($data['upload_data']['full_path']);
		$imagick->writeImages($data['upload_data']['file_path'].'converted.jpg', false);
		$imagick->clear();
		unlink($data['upload_data']['full_path']);

		//
		// read QR code
		//
		$crop_size = 400; // in px
		$qr_codes = array();
		$temp_files = scandir ($data['upload_data']['file_path']);
		$temp_files = array_slice($temp_files, 2); // Fastest way to get a list of files without dots.

		foreach ($temp_files as $key => $temp_file)
		{
			$file = $data['upload_data']['file_path'] . $temp_file;

			$imagick = new Imagick($file);
			$imagick->modulateImage(90, 0, 100); // transform to b/w
			$height = $imagick->getImageLength();
			$width = $imagick->getImageWidth();

			// level and crop qr codes
			$imagick->cropImage($crop_size, $crop_size, $width - $crop_size - 100 , 100 );
			$imagick->contrastImage(10);
			$imagick->levelImage(6000, 1, 45000);
			$imagick->trimImage(0.5);

			// save image
			$imagick->writeImage($data['upload_data']['file_path'] . 'qr_' . $temp_file);
			$imagick->clear();

			// read QR Code
			$qrcode = new Zxing\QrReader($data['upload_data']['file_path'] . 'qr_' . $temp_file);
			$qr_codes[] = $qrcode->text(); //return decoded text from QR Code
			unset($qrcode); // freememory
		}

		//
		// Save files
		//
		$not_found = array();

		// get project data
		$project_data = $this->Projects_model->getProjectDataByProjectId($this->input->post('project_id'));

		foreach ($temp_files as $key => $temp_file)
		{
			if($this->input->post('user_id')) // manual importation
			{
				$user_id = $this->input->post('user_id');
			}
			else // automatic importation
			{
				// get user
				$qr_data = explode(',', $qr_codes[$key]);
				if( ! isset($qr_data[0]) && ! isset($qr_data[1]))
				{
					// qr ot readeable not found
					$not_found[] = $qr_codes[$key]  . ' (QR code not readable)';;
					continue;
				}

				$user_id = $this->Users_model->getUserIdByName(@trim($qr_data[1]), trim($qr_data[0]));

				if(empty($user_id))
				{
					// user  not found
					$not_found[] = $qr_codes[$key] . ' (user not found)';
					continue;
				}
				else
				{
					$success[] = $qr_codes[$key];
				}
			}
			$user_data = $this->Users_model->getUserInformations($user_id);

			// get page number
			// TODO:
			$page_number = $this->input->post('page_number');

			// Save file
			$file_name = generateProjectFileName($project_data, $user_data, $page_number);

			$file = $data['upload_data']['file_path'] . $temp_file;
			$imagick = new Imagick($file);
			$imagick->levelImage(7000, 1, 45000);

			$dir = './assets/uploads/importations/' . get_school_year() . '/' . sanitize_name($project_data->class_name) . '/' . sanitize_name($project_data->term_name) . '/' .  sanitize_name($project_data->project_name)  . '/';
			$file_path = $dir . $file_name . 'jpg';

			if ( ! file_exists($dir))
			{
				mkdir($dir, 0777, true);
			}
			$imagick->writeImage($file_path);

			// Save makeThumbnail
			$file_path = $dir . 'thumb_' . $file_name . 'jpg';
			$imagick->adaptiveResizeImage(1024,768, TRUE);
			$imagick->writeImage($file_path);

			$imagick->clear();

			//
			// update db
			//
			$this->Submit_ext_model->add($project_data->id, $user_id, ltrim($dir, '.'), $file_name . 'jpg', $page_number);
		}

		//
		// empty temp folder
		//
		$files = glob('./assets/uploads/import/temp/*'); // get all file names
		foreach($files as $file)
		{
			if(is_file($file))
			{
				unlink($file);
			}
		}

		// load template
		$this->data['success'] = $success;
		$this->data['not_found'] = $not_found;
		$this->automatic();

	}

}
