<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth_model extends App_Model {

	public function __construct() {
		parent::__construct();
	}

    public function get_roles(){
		$data = $this->db->query(' SELECT * FROM ' . db_prefix() . 'roles');
		return $data->result();
    }

}

?>