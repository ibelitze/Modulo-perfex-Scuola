<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Rector_model extends App_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function setSignValues($id, $valoresFirma)
    {
        $this->db->set('estatus', 'formalizado');
        $this->db->set('fecha_ultima_actualizacion_estatus', date('Y-m-d H:i:s'));
        $this->db->set('firma_rector_1', $valoresFirma['firma_rector_1']);
        $this->db->where('id', $id);
        $this->db->update('tblretiros_wti');

        if ($this->db->affected_rows() > 0) {
            // El cambio se realizó correctamente
            return true;
        } else {
            // Hubo un error en la consulta o no se actualizó ninguna fila
            return false;
        }
    }

    public function setSignNullaOstaValues($id, $valoresFirma)
    {
        $this->db->set('fecha_ultima_actualizacion_estatus', date('Y-m-d H:i:s'));
        $this->db->set('firma_rector_2', $valoresFirma['firma_rector_2']);
        $this->db->set('firma_rector_2_date', $valoresFirma['firma_rector_2_date']);
        $this->db->set('estatus', 'formalizado');
        $this->db->where('id', $id);
        $this->db->update('tblretiros_wti');

        if ($this->db->affected_rows() > 0) {
            // El cambio se realizó correctamente
            return true;
        } else {
            // Hubo un error en la consulta o no se actualizó ninguna fila
            return false;
        }
    }
}