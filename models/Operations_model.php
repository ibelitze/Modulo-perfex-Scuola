<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Operations_model extends App_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('email');
    }

    public function addAttached($valoresAdjunto)
    {
        $this->db->insert('tbladjuntos_retiro_wti', $valoresAdjunto);

        if ($this->db->affected_rows() > 0) {
            // La inserción se realizó correctamente
            return true;
        } else {
            // Hubo un error en la consulta o no se insertó ninguna fila
            return false;
        }
    }

    public function get_staff_data($staff_id)
    {
        $data = $this->db->query("SELECT * FROM " . db_prefix() . "staff  WHERE tblstaff.staffid = {$staff_id}");
        return $data->result();
    }

    public function get_client_id($retiro)
    {
        $data = $this->db->query("SELECT * FROM " . db_prefix() . "retiros_wti  WHERE tblretiros_wti.id = {$retiro}");
        $results = $data->result();
        $vat = $results[0]->rut_estudiante;
        $client = $this->db->query("SELECT * FROM " . db_prefix() . "clients  WHERE tblclients.vat = '{$vat}'");
        return $client->result();
    }

    public function get_all_retirodata($retiro)
    {
        $data = $this->db->query("SELECT * FROM " . db_prefix() . "retiros_wti  WHERE tblretiros_wti.id = {$retiro}");
        return $data->result();
    }

    public function get_all_pendientes()
    {
        $data = $this->db->query("SELECT * FROM " . db_prefix() . "retiros_wti  WHERE tblretiros_wti.estatus = 'pendiente' AND tblretiros_wti.futuro = 1 AND (tblretiros_wti.ratifica_cambio != 1 OR tblretiros_wti.ratifica_cambio IS NULL)");
        return $data->result();
    }

    public function get_all_pendientes2()
    {
        $data = $this->db->query("SELECT * FROM " . db_prefix() . "retiros_wti  WHERE tblretiros_wti.estatus = 'pendiente' OR tblretiros_wti.estatus = 'pendiente_ratificado' AND tblretiros_wti.futuro = 1 AND (tblretiros_wti.ratifica_cambio != 1 OR tblretiros_wti.ratifica_cambio IS NULL)");
        return $data->result();
    }

    public function get_solicitante($retiro)
    {
        $data = $this->db->query("SELECT * FROM " . db_prefix() . "persona_retira_wti  WHERE tblpersona_retira_wti.fk_retiro = {$retiro}");
        return $data->result();
    }

    public function get_contacts($id)
    {
        $data = $this->db->query("SELECT * FROM " . db_prefix() . "contacts  WHERE tblcontacts.userid = {$id}");
        $results = $data->result();
        return $data->result();
    }

    public function get_directores_area($fkEscuela)
    {

        $dirlevel = null;

        switch ($fkEscuela) {
            case 1:
                $dirlevel = "dir_nido";
                break;
            case 2:
                $dirlevel = "dir_materna";
                break;
            case 3:
                $dirlevel = "dir_elementare";
                break;
            case 4:
                $dirlevel = "dir_inferior";
                break;
            case 5:
                $dirlevel = "dir_superior";
                break;
        }

        $this->db->select('tblstaff.staffid');
        $this->db->from('tblstaff_permissions');
        $this->db->join('tblstaff', 'tblstaff_permissions.staff_id = tblstaff.staffid');
        $this->db->where_in('capability', array('director', $dirlevel));
        $this->db->where('tblstaff.active', 1);
        $this->db->group_by('tblstaff.staffid');
        $this->db->having('COUNT(DISTINCT capability) = 2');
        $query = $this->db->get();
        $cleanData = $this->eliminarDuplicados($query->result_array());

        return $cleanData;
    }

    public function get_secretaria_academica()
    {
        $this->db->select('tblstaff.staffid');
        $this->db->from('tblstaff_permissions');
        $this->db->join('tblstaff', 'tblstaff_permissions.staff_id = tblstaff.staffid');
        $this->db->where_in('capability', 'sec_academica');
        $this->db->where('tblstaff.active', 1);
        $this->db->group_by('tblstaff.staffid');
        $this->db->having('COUNT(DISTINCT capability) = 1');
        $query = $this->db->get();
        $cleanData = $this->eliminarDuplicados($query->result_array());

        return $cleanData;
    }

    public function get_administracion()
    {
        $this->db->select('tblstaff.staffid');
        $this->db->from('tblstaff_permissions');
        $this->db->join('tblstaff', 'tblstaff_permissions.staff_id = tblstaff.staffid');
        $this->db->where_in('capability', 'administracion');
        $this->db->where('tblstaff.active', 1);
        $this->db->group_by('tblstaff.staffid');
        $this->db->having('COUNT(DISTINCT capability) = 1');
        $query = $this->db->get();
        $cleanData = $this->eliminarDuplicados($query->result_array());

        return $cleanData;
    }

    public function get_rectores()
    {
        $this->db->select('tblstaff.staffid');
        $this->db->from('tblstaff_permissions');
        $this->db->join('tblstaff', 'tblstaff_permissions.staff_id = tblstaff.staffid');
        $this->db->where_in('capability', 'rectoria');
        $this->db->where('tblstaff.active', 1);
        $this->db->group_by('tblstaff.staffid');
        $this->db->having('COUNT(DISTINCT capability) = 1');
        $query = $this->db->get();
        $cleanData = $this->eliminarDuplicados($query->result_array());

        return $cleanData;
    }

    public function eliminarDuplicados($array)
    {
        $uniqueArray = array_unique($array, SORT_REGULAR);
        return $uniqueArray;
    }

}