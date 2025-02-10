<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Notify_model extends App_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function createStaffNotification($descripcion, $senderid, $clientid, $clientname, $receiver, $link)
    {
        $mydate = date('Y-m-d H:i:s');
        $this->db->insert('tblnotifications', array(
            'date' => $mydate,
            'description' => $descripcion,
            'fromuserid' => $senderid,
            'fromclientid' => $clientid,
            'from_fullname' => $clientname,
            'touserid' => $receiver,
            'link' => $link,
        ));

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

}
