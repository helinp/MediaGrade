<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__) . '/tcpdf/tcpdf_import.php';

class Pdf_import extends TCPDF
{
    function __construct()
    {
        parent::__construct();
    }
}
