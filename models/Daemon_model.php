<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Daemon_model extends App_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('email');
    }

    public function actualizar_fecha_ultima_actualizacion_estatus()
    {
        $this->db->set('fecha_ultima_actualizacion_estatus', 'NOW()', false);
        $this->db->update('tblretiros_wti');
    }

    public function anulacionProcede($id)
    {
        $this->db->set('estatus', 'anulado');
        $this->db->set('fecha_ultima_actualizacion_estatus', date('Y-m-d H:i:s'));
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'retiros_wti');

        if ($this->db->affected_rows() > 0) {
            // El cambio se realizó correctamente
            return true;
        } else {
            // Hubo un error en la consulta o no se actualizó ninguna fila
            return false;
        }
    }

    public function pendienteProcede($id)
    {
        $this->db->set('estatus', 'abierto');
        $this->db->set('fecha_ultima_actualizacion_estatus', date('Y-m-d H:i:s'));
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'retiros_wti');

        if ($this->db->affected_rows() > 0) {
            // El cambio se realizó correctamente
            return true;
        } else {
            // Hubo un error en la consulta o no se actualizó ninguna fila
            return false;
        }
    } 

}
