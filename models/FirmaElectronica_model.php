<?php
defined('BASEPATH') or exit('No direct script access allowed');

class FirmaElectronica_model extends App_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function agregarFirma($id, $data)
    {
        $data['fk_retiro'] = $id;
        if ($this->db->insert('tblfirma_electronica_wti', $data)) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }

    public function agregarFirmaApoderado($rut_estudiante, $data)
    {
        $this->db->select('id, estatus');
        $this->db->where('rut_estudiante', $rut_estudiante);
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get('tblretiros_wti');
        if ($query->num_rows() > 0) {
            $retiro = $query->result();
            if ('no firmado' == $retiro[0]->estatus) {
                $data['fk_retiro'] = $retiro[0]->id;
                $this->db->insert('tblfirma_electronica_wti', $data);
                if ($this->db->error()['code']) {
                    // Error message
                    echo "Error al agregar firma apoderado: " . $this->db->error()['message'];
                    return false;
                }
                return true;
            } else {
                return false;
            }
        }
        return false;
    }

    public function searchSolRet($RUT)
    {
        $this->db->select('id');
        $this->db->where('rut_estudiante', $RUT);
        $this->db->where_not_in('estatus', array('anulado', 'formalizado', 'cerrado'));
        $this->db->where('ratifica_cambio IS NULL', null, false);
        
        $query = $this->db->get('tblretiros_wti');
        if ($query->num_rows() > 0) {
          return $query;
        }else{
          return false;  
        }
    }

    public function setSignValues($id, $valoresFirma)
    {
        $this->db->set('estatus', 'abierto');
        $this->db->set('fecha_ultima_actualizacion_estatus', date('Y-m-d H:i:s'));
        $this->db->set('firma_apoderado', $valoresFirma['firma_apoderado']);
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

    /* 
        añadido recientemente por ibe. 08/05/2024
        se necesitaba una función igual a la de arriba
        pero que no cambie el status del retiro a "abierto" sino a "pendiente"
        en este caso se usa para retiros a futuros (que no se pueden abrir todavía) 
    */
    public function setSignValues2($id, $valoresFirma)
    {
        $this->db->set('estatus', 'pendiente');
        $this->db->set('fecha_ultima_actualizacion_estatus', date('Y-m-d H:i:s'));
        $this->db->set('firma_apoderado', 1);
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

    public function setRatifica($id, $valoresRatifica)
    {
        $allowedFields = ['ratifica_cambio', 'futuro', 'fecha_retiro', 'estatus', 'motivo_anulacion'];
    
        $dataToUpdate = [];
    
        foreach ($allowedFields as $field) {
            if (array_key_exists($field, $valoresRatifica)) {
                $dataToUpdate[$field] = $valoresRatifica[$field];
            }
        }
    
        if (!empty($dataToUpdate)) {
            $this->db->set($dataToUpdate);
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
    
        // Si no se actualizó ningún campo, puedes manejarlo de la forma que desees
        return false;
    }

}
