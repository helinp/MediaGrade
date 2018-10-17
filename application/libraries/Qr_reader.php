<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__) . '/qr_code_reader/qr_reader.php';

class QR_reader extends QrReader
{
    function __construct()
    {
        parent::__construct();
    }
}
