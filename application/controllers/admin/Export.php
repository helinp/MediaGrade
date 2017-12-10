<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Export extends MY_AdminController {

	private $data;

	function __construct()
	{
		parent::__construct();

		$this->load->model('Pdf_model','',TRUE);
		$this->load->model('Highcharts_model','',TRUE);

		$this->load->helper('file');
		$this->load->helper('graph');

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
	}

	/*
	*  VIEWS ROUTING
	*
	*/
	public function projects_assessments()
	{
		if( ! empty($this->input->get('term')))
		{
			$term = $this->input->get('term');
		}
		else
		{
			$term = FALSE;
		}
		if($this->input->get('school_year')) $school_year = $this->input->get('school_year');

		$this->data['projects'] = $this->Projects_model->getAllActiveProjectsByTermAndSchoolYear($term, $school_year);
		$this->load->template('admin/export_assessments', $this->data);
	}

	public function students_report()
	{
		$this->data['students_list'] = $this->Users_model->getAllUsersByClass();
		$this->load->template('admin/export_student_report', $this->data);
	}

	public function lessons()
	{
		if( ! empty($this->input->get('class')))
		{
			$class = $this->input->get('class');
		}
		else
		{
			$class = FALSE;
		}
		$this->data['projects'] = $this->Projects_model->getAllActiveProjectsByClassAndSchoolYear($class, $this->school_year);
		$this->load->template('admin/export_lessons', $this->data);
	}


	/*
	 *
	 *  EXPORTATION METHODS
	 *
	 */

	/*
	 *  Generates PDF from project's results
	 *  @todo Use model method to avoid repetition
	 *
	 */
	public function pdf_lesson()
	{
		// get student list
		$projects_ids = $this->input->post('projects');

		if( ! $projects_ids)
		{
			show_error(_('Aucun projet sélectionné!'));
		}

		$this->school_year = get_school_year();
		$pdf = new Pdf();

		$pdf->SetTitle(_('Leçon'));
		$pdf->setFooterData('Généré depuis MediaGrade');

		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		// Config
		$this->Pdf_model->setDefaultConfig($pdf);
		$pdf->SetAutoPageBreak(true, 0);
		$pdf->SetMargins(10, 7, 7);
		$pdf->SetHeaderMargin(7);
		$pdf->SetFooterMargin(5);


		foreach ($projects_ids as $project_id)
		{
			$project_data = $this->Projects_model->getProjectDataByProjectId($project_id);
			/************  render PDF *************************/
			$pdf->AddPage();
			$html = $this->Pdf_model->processLesson($project_data);

			$self_assessments_ids = explode(',', $project_data->self_assessment_ids);

			// get assessment table
			$assessment_data = $this->Assessment_model->getAssessmentsByProjectId($project_id);

			$tbl = '
			<style type="text/css">
			.tg  {padding:3px;border-collapse:collapse;border-spacing:0;}
			.tg td{border-style:solid;border-width:1px;}
			.tg th{font-weight: bold;border-style:solid;border-width:1px;}
			.tg .tg-fqys{background-color:#333333;color:#ffffff;vertical-align:top}
			.tg .tg-yw4l{vertical-align:top
			}
			</style>
			<table class="tg">
			<tr>
			<th width="15%" class="tg-fqys">Objectif</th>
			<th width="20%" class="tg-fqys">Critères</th>
			<th width="45%" class="tg-fqys">Indicateurs (l\'élève a:)</th>
			<th width="20%" class="tg-fqys">Pondération</th>
			</tr>';

			foreach($assessment_data as $data)
			{
				$tbl .= '
				<tr>
				<td class="tg-yw4l">'. $data->skills_group . '</td>
				<td class="tg-yw4l">'. $data->criterion . '</td>
				<td class="tg-yw4l">'. $data->cursor . '<br /></td>
				<td class="tg-yw4l">'. $data->max_vote . '<br /></td>
				</tr>';
			}

			$tbl .= '</table>';

			$html .= $tbl;

			// get self assessments
			if($self_assessments_ids[0])
			{
				$html .= '<h3>Question' . (count($self_assessments_ids) > 1 ? 's' : '') . ' d\'auto-évaluation</h3>';

				foreach ($self_assessments_ids as $self_assessments_id)
				{
					$html .= '- ' . $this->Assessment_model->getSelfAssessmentFromId($self_assessments_id) . '<br />';
				}
			}

			$pdf->writeHTML($html, false, false, false, false, '');

			// add eventual submitted PDF instruction file
			// insert PDF submitted project, if EXISTS
			if($project_data->instructions_pdf)
			{
				/*	$pdf->AddPage();
				$pdf_doc = $pdf->setSourceFile($data['instructions_pdf']);
				$pdf->SetAutoPageBreak(FALSE, 0);
				$pdf->ImportPage($pdf_doc);*/
			}

		}
		//Closes and opens a PDF file in new tab (arg 'I' in Output method)
		$this->load->helper('school');
		$filename = sanitize_name(get_school_year() . '_' .date("Y-m-d") . '_lessons');
		$pdf->Output($filename . '.pdf', 'I');
	}

	/*
	*  Generates PDF from project's results
	*  @todo Use model method to avoid repetition
	*
	*/
	public function pdf_project()
	{
		// creates new PDF object
		$pdf = new pdf();
		$this->Pdf_model->setDefaultConfig($pdf);
		$pdf->SetAuthor($this->session->name . ' ' . $this->session->last_name);
		$pdf->SetTitle(_('Fiche d\'évaluation'));

		// adds a page for each student
		$data = $this->_prepareAssessmentRecords($this->input->post('project_id'), $this->input->post('term'));
		foreach ($data as $student)
		{
			$pdf->AddPage();
			$html = $this->Pdf_model->processAssessmentsRecordHtml($student);
			$pdf->writeHTML($html, false, false, false, false, '');

			// insert PDF submitted project, if EXISTS
			if( ! empty($student->submitted) && substr($student->submitted, -3) === 'pdf')
			{
				$pdf->AddPage();
				$pdf_doc = $pdf->setSourceFile($student->submitted);
				$pdf->SetAutoPageBreak(FALSE, 0);
				$pdf->ImportPage($pdf_doc);
			}
		}

		//Closes and opens a PDF file in new tab (arg 'I' in Output method)
		$this->load->helper('school');
		$filename = sanitize_name(get_school_year() . '_' .  $data[0]['term'] . '_' . $data[0]['class'] . '_' . $data[0]['project_name']);
		$pdf->Output($filename . '.pdf', 'I');
	}

	/*
	*  Returns assessment data from project and term for PDF generation
	*
	*/
	private function _prepareAssessmentRecords($projects_id, $term)
	{
		foreach ($projects_id as $project_id)
		{
			$project = $this->Projects_model->getProjectDataByProjectId($project_id);
			$students = $this->Users_model->getAllUsersByClass('student', $project->class);
			$students = $students[$project->class];
			$skills = implode(', ', $this->Skills_model->getAllSkillsByProjects($project_id, TRUE));

			foreach ($students as $student)
			{
				$submitted = $this->Submit_model->getSubmittedInfosByUserIdAndProjectId($student->id, $project_id);

				$submitted_time = (isset($submitted[0]->time) ? $submitted[0]->time : '');
				$submitted_thumbnail = (isset($submitted[0]->thumbnail) ? $submitted[0]->thumbnail : '');

				$totals = $this->Results_model->getUserProjectOverallResult($student->id, $project_id);

				if ( ! empty($totals->total_user))
				{
					$total_user = $totals->total_user;
					$total_max = $totals->total_max;
				}
				else
				{
					$total_user = '--';
					$total_max = '--';
				}

				$data[] = array(
					'project_name' => $project->project_name,
					'term' => $project->term,
					'name' => $student->name,
					'last_name' => $student->last_name,
					'class' => $project->class,
					'school_year' => $project->school_year,
					'submitted' => $submitted_time,
					'thumbnail' => '.' . $submitted_thumbnail,
					'skill_id' => $skills,
					'total_user' => $total_user,
					'total_max' => $total_max,
					'results' => $this->Results_model->getResultsTable($student->id, $project_id),
					'comment' => $this->Comments_model->getCommentsByProjectIdAndUserId($project_id, $student->id),
					'self_assessments' => $this->Submit_model->getSelfAssessmentByProjectId($project_id, TRUE, $student->id)
				);
			}
		}
		return $data;
	}

	/*
	*  Generates PDF from project's results
	*  @todo Use model method to avoid repetition
	*
	*/
	public function pdf_student_report()
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
