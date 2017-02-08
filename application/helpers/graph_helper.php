<?php

    /**
    *  Generate formatted data for highcharts
    *
    */
    function graph_results($results)
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
                $string .= "name:'$tmp_objective',\ndata:[";
            }

            $string .= $row->user_percentage;
            $string .= ', ';
        }

        $string = substr("$string", 0, -2);
        $string .= "]";

        return($string);
    }

    /**
    *  Generate x axis names for highcharts
    *
    */
     function graph_projects($projects)
    {
        $string = '';

        foreach ($projects as $project)
        {
            $string .= "'" . js_special_chars($project->term) . ' / ' . js_special_chars($project->project_name ). "'";
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
