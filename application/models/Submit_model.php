<?php
Class Submit_model extends CI_Model
{

    function __construct()
	{
        $this->load->library('upload');
        $this->load->model('Users_model','',TRUE);
    }

    public function getSubmitInformations($project_id)
    {
        if( ! $project_id) return FALSE;
            $sql = "SELECT project_name, class, number_of_files, extension, periode FROM projects WHERE id = ? LIMIT 1";
            $query = $this->db->query($sql, array($project_id));
            $row = $query->row();

            return $row;
    }

    public function getSubmittedInformations($project_id, $user_id = false)
    {
        if( ! $project_id) return FALSE;
            if (!$user_id) $user_id = $this->session->id;

            $sql = "SELECT CONCAT('/assets/', file_path, file_name) as file,
                                CONCAT('/assets/', file_path, 'thumb_', file_name) as thumbnail,
                                RIGHT(file_name, 3) as extension,
                                answers
                            FROM submitted WHERE user_id = ? AND project_id = ?";
            $query = $this->db->query($sql, array($user_id, $project_id));

            $files = $query->result();

            $submitted = $files;

            return $submitted;
    }

    public function getSubmittedProject($user_id, $project_id)
    {
        // format data language in french TODO set a config file
		$sql = "SET lc_time_names = 'fr_FR'";
		$this->db->query($sql);

        $sql = "SELECT file_name, file_path, answers, DATE_FORMAT(`time`, '%d %M %Y à %H:%m') as `time`,  RIGHT(file_name, 3) as extension,
                    CONCAT('/assets/', file_path, 'thumb_', file_name) as thumbnail
                FROM submitted
                WHERE user_id = ?
                    AND project_id = ?";

        $query = $this->db->query($sql, array($user_id, $project_id));
        return $query->result();
    }

    public function do_upload($config, $field_name)
    {

            // $this->load->library('upload', $config);
            $this->upload->initialize($config);
            if ( ! $this->upload->do_upload($field_name))
            {
                    $error = array('error' => $this->upload->display_errors());
                    return $error;
            }
            else
            {
                    $data = array('upload_data' => $this->upload->data());
                    return true;
            }
    }

    public function getAvatarConfig($user_id = FALSE)
    {
        if( ! $user_id) $user_id = $this->session->id;
        $user = $this->Users_model->getUserInformations($user_id);

        // rename file LASTNAME_Name_avatar
        $file_name = strtoupper(sanitize_name($user->last_name)) . '_' . $user->name . '_avatar';

        $config['file_name']            = $file_name;
        $config['overwrite']            = TRUE;
        $config['file_ext_tolower']      = TRUE;
        $config['upload_path']          = './assets/uploads/users/avatars/';
        $config['allowed_types']        = 'gif|jpg|png';
        $config['max_size']             = 100;
        $config['max_width']            = 1024;
        $config['max_height']           = 1024;

        return($config);
    }

    public function isDeadlineReached($project_id)
    {
        $this->db->where(array('id' => $project_id));
        $q = $this->db->get('projects', 1);

        $deadline = $q->row('deadline');
        $today = date('Y-m-d');

        if($today > $deadline)
            return TRUE;
        else
            return FALSE;
    }

    public function getSubmitConfig($project_id, $i = 1)
    {
        $config['file_name']            = $this->generateProjectFileName($project_id, $i);
        $config['overwrite']            = TRUE;
        $config['file_ext_tolower']      = TRUE;
        $config['upload_path']          = './assets/' . $this->generateProjectFilePath($project_id);
        $config['allowed_types']        = $this->generateAllowedFileType($project_id);;

        return($config);
    }

    private function generateProjectFilePath($project_id)
    {

        $this->load->helper('school');
        $this->load->helper('format');

        // get data
        $project_data = $this->getSubmitInformations($project_id);
        $sanitized_project_name = sanitize_name($project_data->project_name);

        // puts the file in ./upload/2015-2016/class/p_#
        $upload_dir = 'uploads/' . get_school_year() . '/' . $project_data->class . '/' . strtolower($project_data->periode) . '/' . $sanitized_project_name . '/';

        // create dir if doesn't exist
        if (!is_dir('assets/' . $upload_dir)) mkdir('assets/' . $upload_dir, 0777, TRUE);

        return $upload_dir;
    }

    private function generateProjectFileName($project_id, $i = 1)
    {

        $this->load->model('Users_model','',TRUE);
        $this->load->helper('school');
        $this->load->helper('format');

        // get data
        $project_data = $this->getSubmitInformations($project_id);
        $sanitized_user_name = sanitize_name(strtoupper($this->session->last_name) . "_" .  $this->session->name);
        $sanitized_project_name = sanitize_name($project_data->project_name);

        $file_name =  $sanitized_user_name . '_' . $sanitized_project_name . '_' . sprintf("%02d", $i) . '.' . $project_data->extension;

        return $file_name;
    }

    public function generateAllowedFileType($project_id, $formatted = FALSE)
    {
        return $this->getSubmitInformations($project_id)->extension;
    }

    public function submitProject($project_id, $file_name, $answers)
    {
        $data = array(
                'user_id' => $this->session->id,
                'project_id' => $project_id,
                'file_path' => $this->generateProjectFilePath($project_id),
                'file_name' => $file_name,
                'answers' => serialize($answers)
        );

        $where = array(
                'user_id' => $this->session->id,
                'project_id' => $project_id,
                'file_name' => $file_name
        );

        // checks if record exists
        $this->db->where($where);
        $q = $this->db->get_where('submitted', $where, 1);

        // if true, update
        if ($q->num_rows() > 0)
        {
            $this->db->where($where);
            $this->db->update('submitted', $data);
        }
        // else insert
        else
        {
            $this->db->insert('submitted', $data);
        }

        return TRUE;

    }

    public function makeThumbnail($image_full_path, $full_path)
    {
        $this->load->helper('path');

        $file_name = basename($image_full_path);

        $config['image_library'] = 'GD2';
        $config['source_image'] = $image_full_path;
        $config['new_image'] = $full_path . 'thumb_' . $file_name;
        $config['thumb_marker']  = '';
        $config['create_thumb'] = TRUE;
        $config['maintain_ratio'] = TRUE;
        $config['width']         = 500;
        $config['height']       = 500;

        $this->load->library('image_lib', $config);
        $this->image_lib->initialize($config);
        $this->image_lib->resize();
        //echo $this->image_lib->display_errors();
        return TRUE;
    }

    public function listNotGradedProjects()
	{
        $sql = "SELECT users.class, projects.periode, users.name, users.last_name, projects.project_name, users.id as user_id, projects.id as project_id
                FROM submitted, users, projects
                WHERE NOT EXISTS(SELECT NULL
                         FROM results
                         WHERE submitted.user_id = results.user_id
                             AND submitted.project_id = results.project_id)
                AND projects.id = submitted.project_id
                AND users.id = submitted.user_id
                ";

        $query = $this->db->query($sql);
        $results = $query->result();

        return $results;
    }

    public function getNLastSubmitted($n = 10)
    {
        $sql = "SELECT name, last_name, project_name, DATE_FORMAT(`time`, '%d-%m-%Y à %k:%i') as `time`
                FROM submitted
                LEFT JOIN users
                    ON user_id = users.id
                LEFT JOIN projects
                    ON project_id = projects.id
                GROUP BY name
                ORDER BY submitted.id DESC
                LIMIT $n";

            $query = $this->db->query($sql);
            $results = $query->result();

            return $results;

    }

}
?>
