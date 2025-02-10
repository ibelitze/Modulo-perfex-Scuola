<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Retiros_model extends App_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('email');
    }
    public function get_retiros_header()
    {
        $sqlQuery = "SELECT * FROM " . db_prefix() . "retiros_wti";
        $data = $this->db->query($sqlQuery);
        return $data->result();
    }

    public function get_retiros()
    {
        $sqlQuery = "SELECT * FROM " . db_prefix() . "retiros_wti
                     LEFT JOIN " . db_prefix() . "clients ON " . db_prefix() . "clients.vat = " . db_prefix() . "retiros_wti.rut_estudiante";

        //PERFEX ADMIN
        if (staff_can('admin', 'retiros')) {
            $sqlQuery .= " WHERE estatus != 'cerrado'";
            // WHERE 1
        } 
        else if (staff_can('supervisor', 'retiros')) {
            $sqlQuery .= " WHERE estatus != 'cerrado'";
        } 
        else {

            //RECTOR
            if (staff_can('rectoria', 'retiros')) {
                $sqlQuery .= " WHERE (estatus = 'formalizado' OR estatus = 'por_firmar_NULLA' OR estatus = 'en proceso')";
            }
            //DIRECTOR
            if (staff_can('director', 'retiros')) {
                $sqlQuery .= " WHERE (estatus != 'pendiente' OR estatus != 'anulado' OR estatus != 'formalizado')";

                //EJECUCIÓN DE VISTA POR TIPO DE ESCUELA
                //Scuola Nido  #1
                if (staff_can('dir_nido', 'retiros')) {
                    $sqlQuery .= " AND fk_escuela = 1";
                }
                //Scuola Materna #2
                if (staff_can('dir_materna', 'retiros')) {
                    $sqlQuery .= " AND fk_escuela = 2";
                }
                //Elementare #3
                if (staff_can('dir_elementare', 'retiros')) {
                    $sqlQuery .= " AND fk_escuela = 3";
                }
                //Media Inferior #4
                if (staff_can('dir_inferior', 'retiros')) {
                    $sqlQuery .= " AND fk_escuela = 4";
                }
                //Media Superior #5
                if (staff_can('dir_superior', 'retiros')) {
                    $sqlQuery .= " AND fk_escuela = 5";
                }

            }
            //ADMINISTRACION
            if (staff_can('administracion', 'retiros')) {
                $sqlQuery .= " WHERE (estatus = 'formalizado' OR estatus = 'por_firmar_NULLA' OR estatus = 'cerrado') AND adminitracion_proc IS NULL";
                // AND adminitracion_proc IS NOT NULL
            }
            //SECRETARIA
            if (staff_can('sec_academica', 'retiros')) {
                $sqlQuery .= " WHERE (estatus = 'posible reapertura' OR estatus = 'por_firmar_NULLA' OR estatus = 'formalizado') AND secretaria_proc IS NULL";
            }
        }

        $data = $this->db->query($sqlQuery);
        $temp = $data->result();

        // si es para directores debemos filtrar los retiros 
        /*
            El filtro debe devolver todos los retiros de arriba + filtrar los pendientes ratificados que ya hayan pasado la fecha de retiro establecido por el apoderado
        */
        if (staff_can('director', 'retiros') && !staff_can('administracion', 'retiros')) {
            $temp2 = [];
            foreach ($temp as $retiro) {
                if ($retiro->estatus !== "pendiente_ratificado" && $retiro->estatus !== "cerrado") {
                    array_push($temp2, $retiro);
                }
                if ($retiro->estatus == "pendiente_ratificado" && strtotime('now') > strtotime($retiro->fecha_retiro) ) {
                    array_push($temp2, $retiro);
                }
            }

            return $temp2;
        }


        return $temp;
    }

    public function get_retiroID($id)
    {
        $sql1 = "SELECT * FROM tblretiros_wti WHERE tblretiros_wti.id = '" . intval($id) . "'";
        $data = $this->db->query($sql1);
        $retiro = $data->result();

        // buscando los datos básicos del alumno (client, según el perfex)
        if (count($retiro) > 0) {
            $vat = $retiro[0]->rut_estudiante;
            $sql2 = "SELECT * FROM tblclients WHERE tblclients.vat = '" . $vat . "'";
            $data2 = $this->db->query($sql2);
            $results = $data2->result();
            $retiro[0]->nombre = $results[0]->company;
            $retiro[0]->idclient = $results[0]->userid;
            if (count($results) > 0) {
                $sql3 = "SELECT `nombre` FROM `tblescuela_wti` WHERE `id` = {$retiro[0]->fk_escuela}";
                $data3 = $this->db->query($sql3);
                $escuela = $data3->result();
                $retiro[0]->nivel = $escuela[0]->nombre;
            }
        }
        return $retiro;
    }
    public function get_retiroRUT($rut)
    {
        $sql = "SELECT * FROM tblretiros_wti WHERE tblretiros_wti.rut_estudiante = '" . $rut . "' AND estatus NOT IN ('anulado')";
        $data = $this->db->query($sql);
        $retiro = $data->result();
        return $retiro;
    }
    public function get_clienteRUT($rut)
    {
        $sql = "SELECT * FROM tblclients WHERE tblclients.vat = '" . $rut . "'";
        $data = $this->db->query($sql);
        $cliente = $data->result();
        return $cliente;
    }
    public function get_apoderadoID($id)
    {
        $sql = "SELECT * FROM tblpersona_retira_wti WHERE tblpersona_retira_wti.fk_retiro = '" . intval($id) . "'";
        $data = $this->db->query($sql);
        return $data->result();
    }
    public function get_adjuntos($id)
    {
        $sql = "SELECT * FROM tbladjuntos_retiro_wti WHERE tbladjuntos_retiro_wti.fk_retiro = '" . intval($id) . "'";
        $data = $this->db->query($sql);
        return $data->result();
    }

    public function get_logs($id)
    {
        /* get logs */
        $sql = "SELECT * FROM tblactivity_log WHERE tblactivity_log.description LIKE '%retiro_{$id}%' ORDER BY id DESC";
        $data = $this->db->query($sql);
        return $data->result();
    }

    public function get_signs($id)
    {
        $sql = "SELECT * FROM tblfirma_electronica_wti WHERE tblfirma_electronica_wti.fk_retiro = '{$id}' ORDER BY id ASC";
        $data = $this->db->query($sql);
        return $data->result();
    }

    public function get_admin($id)
    {
        $sql = "SELECT * FROM tblretiros_admin_wti WHERE tblretiros_admin_wti.id_retiro = '{$id}'";
        $data = $this->db->query($sql);
        return $data->result();
    }

    public function get_secre($id)
    {
        $sql = "SELECT * FROM tblretiros_secretariaaca_wti WHERE tblretiros_secretariaaca_wti.id_retiro = '{$id}'";
        $data = $this->db->query($sql);
        return $data->result();
    }

    public function anular_retiro($id)
    {
        $this->db->set('estatus', 'anulado');
        $this->db->set('fecha_ultima_actualizacion_estatus', date('Y-m-d H:i:s'));
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

    public function cambiar_fecha_retiro($id, $fecha)
    {
        $this->db->set('fecha_retiro', $fecha);
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

    public function cambiar_colegio_paritario($id, $colegPar)
    {
        $this->db->set('colegio_paritario', $colegPar);
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

    public function cambiar_prom_anticipada($id, $promAntc)
    {
        $this->db->set('promocion_anticipada', $promAntc);
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
