<?php

    /**
    *  Generate formatted data for highcharts
    *
    */
    function graph_results($results, $delimiter = "'")
    {
        $string = '';
        $tmp_objective = '';

        foreach ($results as $row)
        {
            if ($tmp_objective !== js_special_chars($row->skills_group))
            {
                if ( ! empty($tmp_objective))
                {
                    $string = substr("$string", 0, -2);
                    $string .= "]}, {";
                }

                $tmp_objective = js_special_chars($row->skills_group);
                $string .= "type: 'column'," . $delimiter . 'name' . $delimiter . ':' . $delimiter . $tmp_objective . $delimiter . ',' . $delimiter . 'data' . $delimiter . ':[';
            }

            $string .= $row->user_percentage;
            $string .= ', ';
        }

        $string = substr("$string", 0, -2);
        $string .= ']';

        return($string);
    }


    function graph_skills_groups_results($results, $delimiter = "'")
    {
        $string = '';

        foreach ($results as $skills_group => $results)
        {
            $string .= "{type: 'column',"
					. $delimiter . 'name' . $delimiter . ':' . $delimiter . js_special_chars($skills_group) . $delimiter . ','
					. $delimiter . 'data' . $delimiter . ':['
					. implode(', ', $results) . ']}, ';
        }

        $string = substr("$string", 0, -2);
        $string .= '';

        return($string);
    }

    /**
    *  Generate x axis names for highcharts
    *
    */
     function graph_projects($projects, $delimiter = "'")
    {
        $string = '';

        foreach ($projects as $project)
        {
            $string .= $delimiter . js_special_chars($project->term_name) . ' / ' . js_special_chars($project->project_name ). $delimiter;
            $string .= ', ';
        }

        $string = substr("$string", 0, -2);

        return($string);
    }

    function js_special_chars($string = '')
    {
        $string = preg_replace("/\r*\n/","\\n",$string);
        $string = preg_replace("/\//","\\\/",$string);
        $string = preg_replace("/\"/","\\\"",$string);
        $string = preg_replace("/'/","\\'",$string);

        return $string;
    }

    function gauss($data, $field = FALSE)
    {
		if( ! $data) return FALSE;

        if($field)
        {
            // tranforms results to unidimensional array
            $gauss = array();

            foreach($data as $row)
            {
                array_push($gauss, $row->$field);
            }
        }
        else
        {
            $gauss = $data;
        }

        // count frequency of values
        $gauss = array_count_values($gauss);

        // fills values gaps
        for($i = 0 ; $i <= 10 ; $i++)
        {
            if ( ! isset($gauss[$i]))
                $gauss[$i] = 0;
            else $gauss[$i] = $gauss[$i];
        }

        // sorts array by key
        ksort($gauss);

        return $gauss;
    }
?>
