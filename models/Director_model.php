<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Director_model extends App_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function setSignValues($id, $valoresFirma)
    {
        $this->db->set('estatus', 'en proceso');
        $this->db->set('fecha_ultima_actualizacion_estatus', date('Y-m-d H:i:s'));
        $this->db->set('ultima_asistencia', $valoresFirma['ultima_asistencia']);
        $this->db->set('authpromoanti', $valoresFirma['authpromoanti']);
        $this->db->set('firma_director', $valoresFirma['firma_director']);
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
    /*  actualización 09-04-2024: 
        agregadas las observaciones del director del área en retiros 
    */
    public function retiroProcede($id, $observacion)
    {
        $this->db->set('estatus', 'en proceso');
        $this->db->set('fecha_ultima_actualizacion_estatus', date('Y-m-d H:i:s'));
        if (strlen($observacion) > 0) {
            $this->db->set('observaciones_director_area', $observacion);
        }
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

    public function anulacionNoProcede($id, $motivo)
    {
        $this->db->set('estatus', 'posible reapertura');
        $this->db->set('fecha_ultima_actualizacion_estatus', date('Y-m-d H:i:s'));
        $this->db->set('motivo_posible_reapertura', $motivo);
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

    public function preAnularProceso($id, $motivo)
    {
        $this->db->set('estatus', 'anulado en revision');
        $this->db->set('fecha_ultima_actualizacion_estatus', date('Y-m-d H:i:s'));
        $this->db->set('motivo_anulacion', $motivo);
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