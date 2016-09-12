<?php
Class System_model extends CI_Model
{

    function __construct()
	{
        $this->load->helper(array('format_bytes'));
    }

    public function getUsedDiskSpace()
    {

        $disk_space['b_free'] = disk_free_space('/');
        $disk_space['b_total'] = disk_total_space('/');
        $disk_space['free'] = format_bytes($disk_space['b_free']);
        $disk_space['total'] = format_bytes($disk_space['b_total']);
        $disk_space['used'] = format_bytes($disk_space['b_total'] - $disk_space['b_free']);

        $disk_space['per_used'] =   100 - (int) ($disk_space['b_free'] / $disk_space['b_total'] * 100);

        return $disk_space;
    }
    
    public function getFolderPerms($path)
    {
        $perms = substr(sprintf('%o', fileperms($_SERVER["DOCUMENT_ROOT"] . $path)), -4);
        return $perms;
    }
}
