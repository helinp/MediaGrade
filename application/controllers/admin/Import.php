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

		$submenu[] = array('title' => 'Manuel', 'url' => '/admin/import/manual');
		$submenu[] = array('title' => 'Automatique', 'url' => '/admin/import/automatic');
		$this->data['submenu'] = $submenu;
	}


	public function index()
	{
		redirect('/admin/import/automatic');
	}

	public function automatic()
	{
		if( ! empty($this->input->get('term')))
		{
			$term = $this->input->get('term');
		}
		else
		{
			$term = FALSE;
		}

		$this->data['projects'] = $this->Projects_model->getAllActiveProjectsByTermAndSchoolYear($term, $this->school_year);
		$this->data['page_title'] = _('Importer des évaluations papier');
		$this->load->template('admin/import_assessments_automatic', $this->data);
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

		// create dirs (2018-2019/classe/imp_project_name/ ) sanitize_name
		// TODO

		foreach ($temp_files as $key => $temp_file)
		{
			if(empty($qr_codes[$key]))
			{
				continue;
			}
			$file = $data['upload_data']['file_path'] . $temp_file;
			$imagick = new Imagick($file);
			$imagick->levelImage(7000, 1, 45000);

			// get names
			$qr_data = explode(';', $qr_codes[$key]);
			$file_path = './assets/uploads/import/' . trim($qr_data[0]) . '_' . trim($qr_data[1]) . '.jpg';

			$imagick->writeImage($file_path);
			$imagick->clear();
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

		dump($qr_codes);

		//
		// update db
		//
		// TODO

		// return errors and report
		// TODO

	}

}
