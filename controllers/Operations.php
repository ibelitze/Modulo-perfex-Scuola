<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Sample Controller
 */
class operations extends AdminController
{

    /**
     * Controler __construct function to initialize options
     */

    public function __construct()
    {
        parent::__construct();
        $this->CI = &get_instance();
        $this->CI->load->library('session');
        $this->CI->load->library('Wti_notifications');
        $this->CI->load->library('Wti_mails_scuola');
        $this->load->model('Operations_model');
        $this->load->model('Director_model');

    }

    /**
     * Go to home page
     * @return view
     */

    public function index()
    {
        show_404('Página no encontrada');
    }

}
