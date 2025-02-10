<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sec_academica_model extends App_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function setNULLAporFirmar($id)
    {
        $this->db->set('estatus', 'por_firmar_NULLA');
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

    public function insertRetirosSecreWti($valoresRetiro)
    {
        $this->db->insert('tblretiros_secretariaaca_wti', $valoresRetiro);

        if ($this->db->affected_rows() > 0) {
            // La inserción se realizó correctamente
            return true;
        } else {
            // Hubo un error en la consulta o no se insertó ninguna fila
            return false;
        }
    }
    
    public function setSignNullaValues($id, $valoresProcesado)
    {
        $this->db->set('fecha_ultima_actualizacion_estatus', date('Y-m-d H:i:s'));
        $this->db->set('nulla_send', $valoresProcesado['nulla_send']);
        if (array_key_exists('estatus', $valoresProcesado) && null !== $valoresProcesado['estatus']) {
            $this->db->set('estatus', $valoresProcesado['estatus']);
        }
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

    public function setSignSecreValues($id, $valoresProcesado)
    {
        $this->db->set('fecha_ultima_actualizacion_estatus', date('Y-m-d H:i:s'));
        $this->db->set('secretaria_proc', $valoresProcesado['secretaria_proc']);
        if (array_key_exists('estatus', $valoresProcesado) && null !== $valoresProcesado['estatus']) {
            $this->db->set('estatus', $valoresProcesado['estatus']);
        }
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
