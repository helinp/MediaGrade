<?php
Class Highcharts_model extends CI_Model
{


    function __construct()
    {
        $this->load->helper('school');
        $this->current_school_year = get_school_year();
    }

	/**
	 * Generates img from Highcharts and PhantomJS server
	 *
	 * @param 	string		$content 	// js parameters
	 * @param 	string		$type = png
	 * @param 	integer		$scale		// scale of outputed img
	 * @return	image
	 */
	function renderHighchart($content, $type = 'png', $scale = 2)
	{
		$params = array();
		$params['infile'] = preg_replace("/\t+/", '', $content);
		$params['scale'] = $scale;
		$params['type'] = $type;

		$json = json_encode($params);

		// use key 'http' even if you send the request to https://...
		$options = array(
			'http' => array(
				'header'  => "Content-type: application/json\r\n",
				'method'  => 'POST',
				'content' => $json
			)
		);
		$context  = stream_context_create($options);
		$result = file_get_contents(PHANTOMJS_SERVER_ADRESS, false, $context);

		return base64_decode($result);
	}

	/**
	 * Generates gaussian bell curve of student's votes
	 *
	 * @param 	string		$class
	 * @param 	string		$school_year
	 * @param 	boolean		$admin_id
	 * @return	array
	 */
	function generateSkillsReport($student_id, $skills_groups, $projects)
 	{
 		$graph = '{
 									"title": {
 										"text": "",
 										"x": -20
 									},
 									"chart": {
 										"type": "spline"
 									},
 									"colors": ["#29CD86", "#F3EB00", "#972EE7", "#E72E7E", "#2E7EE7"],
 									"xAxis": {
 										"title": {
 											"text": "Projets"
 										},
 										"categories": ['. graph_projects($projects, '"') . '],

 									},
 									"yAxis": {
 										"title": {
 											"text": "' . _('Pourcentage') . '"
 										},
 										"min": 0,
 										"max": 100,
 										"tickInterval": 20
 									},
 									"plotOptions": {
 										"series": {
 											"connectNulls": true
 										},
 										"spline": {
 											"marker": {
 												"symbol": "circle"
 											}
 										}
 									},
 									"tooltip": {
 										"valueSuffix": "%"
 									},
 									"legend": {
 										"enabled": true,
 								        "floating": false,
 								        "verticalAlign": "bottom",
 								        "align":"center"
 									},
 									"series": [{' . graph_results($this->Results_model->getUserOverallResults($skills_groups, $projects, $student_id), '"') . '},
 									{
 									   "name": "' . _('Moyenne pondérée') . '",
 									   "data": [' . implode(', ', $this->Results_model->getUserOverallResults(FALSE, $projects, $student_id)) . '],
 										"type": "areaspline",
 										"lineWidth": 0,
 										"color": Highcharts.getOptions().colors[0],
 										"fillOpacity": "0.3",
 										"zIndex": 0,
 										"marker": [{
 											"enabled": false
 										}],
 									}],
 									"credits": {
 										"enabled": false
 									}
 								}';

 		return $graph;
 	}

 	function generateCriteriaReport($criterion_results)
 	{
 		$graph = '						{
 											"chart": {
 									            "polar": true,
 									            "type": "line"
 									        },

 									        "title": {
 									            "text": "",
 									            "x": -80
 									        },

 									        "pane": {
 									            "size": "80%"
 									        },

 									        "xAxis": {
 									            "categories": ["' . implode("\", \"", array_column((array) $criterion_results, 'conca')) . '"],
 									            "tickmarkPlacement": "on",
 									            "lineWidth": 0
 									        },

 									        yAxis: {
 									            "gridLineInterpolation": "polygon",
 									            "lineWidth": 0,
 									            "min": 0,
 												"max": 100,
 												"tickInterval": 20
 									        },
 											legend: {
 												"enabled": false
 											},

 									        "series": [{
 												"type": "area",
 												"fillOpacity": "0.3",
 									            "name": "Moyenne",
 									            "data": [' . implode(', ', array_column((array) $criterion_results, 'average')) . '],
 									            "pointPlacement": "on"
 									        }],
 											"credits": {
 												"enabled": false
 											}
 									    }';
 		return $graph;
 	}

}
?>
