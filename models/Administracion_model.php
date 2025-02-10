<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Administracion_model extends App_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function setSignAdminValues($id, $valoresProcesado)
    {
        $this->db->set('fecha_ultima_actualizacion_estatus', date('Y-m-d H:i:s'));
        $this->db->set('adminitracion_proc', $valoresProcesado['adminitracion_proc']);
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

    public function insertRetirosAdminWti($valoresRetiro)
    {
        $this->db->insert('retiros_admin_wti', $valoresRetiro);

        if ($this->db->affected_rows() > 0) {
            // La inserción se realizó correctamente
            return true;
        } else {
            // Hubo un error en la consulta o no se insertó ninguna fila
            return false;
        }
    }

    public function saveNumeroEgreso($post)
    {
        $sql = "SELECT * FROM tblretiros_admin_wti WHERE tblretiros_admin_wti.id_retiro = '" . $post['idRetiro'] . "'";
        $data = $this->db->query($sql);
        $retiro = $data->result();

        if ($retiro[0]->numero_egreso_cambiado >= 3) {
            return false;
        }
        else {
            $veces = $retiro[0]->numero_egreso_cambiado + 1;
            $this->db->set('numero_egreso_devolucion', $post['numero_egreso']);
            $this->db->set('numero_egreso_cambiado', $veces);

            $this->db->where('id_retiro', $post['idRetiro']);
            $this->db->update('tblretiros_admin_wti');

            if ($this->db->affected_rows() > 0) {
                // El cambio se realizó correctamente
                return true;
            } else {
                // Hubo un error en la consulta o no se actualizó ninguna fila
                return false;
            }
        }
    }


}
