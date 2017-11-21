<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pdf_student_report extends CI_Controller {


	function __construct()
	{
		parent::__construct();
		$this->load->model('Projects_model','',TRUE);
		$this->load->model('Users_model','',TRUE);
		$this->load->model('Results_model','',TRUE);
		$this->load->model('Assessment_model','',TRUE);
		$this->load->model('Comments_model','',TRUE);
		$this->load->model('Submit_model','',TRUE);
		$this->load->model('Pdf_model','',TRUE);
		$this->load->model('Skills_model','',TRUE);
		$this->load->model('Terms_model','',TRUE);

		$this->load->model('Highcharts_model','',TRUE);

		$this->load->helper('file');
		$this->load->helper('graph');
	}

	/*
	 *  Generates PDF from project's results
	 *  @todo Use model method to avoid repetition
	 *
	 */
	public function index()
	{
		// get student list
		$students = $this->input->post('students_id');

		if( ! $students)
		{
				show_error(_('Aucun élève sélectionné!'));
		}

		$png_report_files = array();
		$this->school_year = get_school_year();
		$pdf = new Pdf();

		$pdf->SetTitle(_('Compte rendu'));
		$pdf->setFooterData('Généré depuis MediaGrade');

		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		$this->Pdf_model->setDefaultConfig($pdf);

		$skills_groups = $this->Skills_model->getAllSkillsGroups();
		$i = 0;
		foreach ($students as $student_id)
		{
			/***********  Generate png files ***************/
			// generate skill graph
			$student_info = $this->Users_model->getUserInformations($student_id);
			$projects = $this->Projects_model->getAllActiveProjectsByClassAndSchoolYear($student_info->class, $this->school_year);
			$skills_report = $this->Highcharts_model->generateSkillsReport($student_id, $skills_groups, $projects);
			$png = $this->Highcharts_model->renderHighchart($skills_report);
			$filename = sanitize_name($student_info->class . '_' . $student_info->last_name . '_' . $student_id . '_skill_report') . '.png';
			write_file('./assets/reports/' . $filename, $png);
			array_push($png_report_files, './assets/reports/' . $filename);

			// generate polar graph
			$criteria_results = $this->Results_model->getDetailledResults('criterion', $student_id, FALSE, $this->school_year);
			$criteria_report = $this->Highcharts_model->generateCriteriaReport($criteria_results);
			$png = $this->Highcharts_model->renderHighchart($criteria_report);
			$filename = sanitize_name($student_info->class . '_' . $student_info->last_name . '_' . $student_id . '_criteria_report') . '.png';
			write_file('./assets/reports/' . $filename, $png);
			array_push($png_report_files, './assets/reports/' . $filename);

			$data = array(	'name' => $student_info->name,
							'last_name' => $student_info->last_name,
							'class' => $student_info->class,
							'school_year' => get_school_year(),
							'skills_chart' => $png_report_files[$i],
							'criteria_chart' => $png_report_files[$i + 1]);

			/************  render PDF *************************/
			$pdf->AddPage();
			$html = $this->Pdf_model->processStudentAssessmentReport($data);
			$pdf->writeHTML($html, false, false, false, false, '');

			$skills = $this->Skills_model->getAllSkillsGroups();
			$overall_result = $this->Results_model->getUserVoteAverageByTermAndSchoolYear($student_id);
			$w = (int) (($pdf->getPageWidth() - ($pdf->getMargins()['left'] + $pdf->getMargins()['right'])) / count($skills));

			// TODO Add to PDF MODEL processStudentAssessmentReport
			$pdf->SetFontSize(20);
			if($overall_result < 50 && $result !== '--' )
			{
				$pdf->SetTextColor(255,0,0);
			}
			$pdf->Cell(count($skills) * $w, 12, $overall_result . ' %', '', 0, 'C');
			if($overall_result < 50 && $result !== '--' )
			{
				$pdf->SetTextColor(0,0,0);
			}
			$pdf->Ln();
			$pdf->SetFontSize(8);
			$pdf->SetLineWidth(0.05);

			foreach ($skills as $skill)
			{
				$pdf->Cell($w, 6, strtoupper($skill->name), 'LR', 0, 'C');
			}
			$pdf->Ln();
			$pdf->SetFontSize(9);
			foreach ($skills as $skill)
			{
				$result = $this->Results_model->getUserOverallResultsBySkillGroup($skill->name, $student_id);
				if($result < 50 && $result !== '--' )
				{
					$pdf->SetTextColor(255,0,0);
				}

				$pdf->Cell($w, 6, $result . ' %', 'LR', 0, 'C');

				if($result < 50 && $result !== '--' )
				{
					$pdf->SetTextColor(0,0,0);
				}
			}
			$pdf->Ln();

			$i = $i + 2; // 2 png per student
		}
		//Closes and opens a PDF file in new tab (arg 'I' in Output method)
		$this->load->helper('school');
		$filename = sanitize_name(get_school_year() . '_' .date("Y-m-d") . '_students-report');
		$pdf->Output($filename . '.pdf', 'I');

	}
}
