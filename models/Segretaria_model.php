<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Segretaria_model extends App_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function saveMessage($data)
    {
        if ($this->db->insert('tblmensajes_segre_wti', $data)) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }

}
