<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Sample Controller
 */
class master extends AdminController
{

    /**
     * Controler __construct function to initialize options
     */
    public function __construct()
    {
        parent::__construct();
        $this->CI = &get_instance();
        $this->load->model('Auth_model');
        $this->load->model('Retiros_model');
        $this->CI->load->library('Wti_statuscolor');
    }

    /**
     * Go to home page
     * @return view
     */

    public function get_roles()
    {
        $roles = $this->Auth_model->get_roles();
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($roles));

    }

    public function index()
    {

        $retiros_header = $this->Retiros_model->get_retiros_header();
        $retiros = $this->Retiros_model->get_retiros();

        $abiertos = [];
        $pendientes = [];
        $anulados = [];
        $formalizados = [];
        $cerrados = [];

        for ($i = 0; $i < count($retiros_header); $i++) {
            $actual = $retiros_header[$i];

            $resp = $this->Retiros_model->get_clienteRUT($actual->rut_estudiante);
            $actual->cliente = $resp[0];

            if ("abierto" === $actual->estatus) {
                array_push($abiertos, $actual);
            } else if ("pendiente" === $actual->estatus || "pendiente_ratificado" === $actual->estatus) {
                array_push($pendientes, $actual);
            } else if ("anulado" === $actual->estatus || "anulado_en_revision" === $actual->estatus) {
                array_push($anulados, $actual);
            } else if ("formalizado" === $actual->estatus) {
                array_push($formalizados, $actual);
            } else if ("cerrado" === $actual->estatus) {
                array_push($cerrados, $actual);
            }
        }

        foreach ($retiros as $key => $value) {
            $newColor = $this->wti_statuscolor->set_color_status($retiros[$key]->fecha_ultima_actualizacion_estatus);
            $retiros[$key]->colorStats = $newColor;
        }

        $data = [
            'retiros' => $retiros,
            'abiertos' => $abiertos,
            'pendientes' => $pendientes,
            'anulados' => $anulados,
            'formalizados' => $formalizados,
            'cerrados' => $cerrados,
        ];
        $this->load->view('common/header');
        $this->load->view('navigation/index', $data);
        $this->load->view('common/footer');
    }

    public function devuelvoCharQR($rut)
    {
        $this->load->library('Wti_encrypt');

        // encriptado del rut
        $instance = new Wti_encrypt();
        $encriptado = $instance->encrypt($rut);
        $urlChart = "https://chart.googleapis.com/chart?chs=150x150&chld=L|2&cht=qr&chl=";
        $urlWordpress = "https://url-aqui.com/detalles-solicitud/?retiro=";

        $final = $urlChart . $urlWordpress . $encriptado;
        return $final;
    }

    public function details($id)
    {
        $idFinal = intval($id);

        $retiro = $this->Retiros_model->get_retiroID($idFinal);

        $apoderado = $this->Retiros_model->get_apoderadoID($idFinal);
        $adjuntos = $this->Retiros_model->get_adjuntos($idFinal);
        $chart = $this->devuelvoCharQR($retiro[0]->rut_estudiante);
        $logs = $this->Retiros_model->get_logs($idFinal);
        $signs = $this->Retiros_model->get_signs($idFinal);
        $admin = $this->Retiros_model->get_admin($idFinal);
        $secre = $this->Retiros_model->get_secre($idFinal);

        if (count($adjuntos) > 0) {
            $data = [
                "retiro" => $retiro,
                "apoderado" => $apoderado,
                'adjuntos' => $adjuntos,
                'chart' => $chart,
                'logs' => $logs,
                'signs' => $signs,
                'admin' => $admin,
                'secre' => $secre,
            ];
        } else {
            $data = [
                "retiro" => $retiro,
                "apoderado" => $apoderado,
                'chart' => $chart,
                'logs' => $logs,
                'signs' => $signs,
                'admin' => $admin,
                'secre' => $secre,
            ];
        }

        $this->load->view('common/header');
        $this->load->view('navigation/details', $data);
        $this->load->view('common/footer');
    }

}
