<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Sample Controller
 */
class rector extends AdminController
{

    /**
     * Controler __construct function to initialize options
     */

    public function __construct()
    {
        parent::__construct();
        $this->CI = &get_instance();
        $this->CI->load->library('session');
        $this->CI->load->library('Wti_notifications');
        $this->CI->load->library('Wti_mails_scuola');
        $this->CI->load->library('Wti_subir_archivos');
        $this->load->model('Operations_model');
        $this->load->model('Rector_model');
        $this->load->model('FirmaElectronica_model');
        $this->load->model('Retiros_model');
    }

    /**
     * Go to home page
     * @return view
     */

    public function index()
    {
        show_404('Página no encontrada');
    }

    public function firmaRector_Uno()
    {
        //HEADERS
        $retiroData = $this->input->post();
        $alldata = $this->Operations_model->get_all_retirodata($retiroData['idRetiro']);
        $id_staff = $this->session->userdata('staff_user_id');
        $SecretariasAcademicas = $this->Operations_model->get_secretaria_academica(); // ! FROM SecretariaAcademicaRequest TO  SecretariasAcademicas
        // ? $secAcademica = $this->Operations_model->get_staff_data($secAcademicaRequest[0]["staff_id"]);
        $administradores = $this->Operations_model->get_administracion(); // ! FROM $administracionRequest TO $administradores
        // ? $administracion = $this->Operations_model->get_staff_data($administracionRequest[0]["staff_id"]);
        $staffData = $this->Operations_model->get_staff_data($id_staff);
        $clientId = $this->Operations_model->get_client_id($retiroData['idRetiro']);
        $retiroURI = "api_master/master/details/" . $retiroData['idRetiro'];

        //OPERATIONS

        $valoresFirma = array(
            'firma_rector_1' => "1",
        );
        if ($iniciarProceso = $this->Rector_model->setSignValues($retiroData['idRetiro'], $valoresFirma)) {
            $data = array(
                'quien_firma' => 'rector',
                'name' => $staffData[0]->firstname . " " . $staffData[0]->lastname,
                'fecha' => date('Y-m-d H:i:s'),
                'fk_retiro' => null,
                'fk_secretaria_traslado' => null,
                'IP' => $this->input->ip_address(),
            );
            $firmarRetiro = $this->FirmaElectronica_model->agregarFirma($retiroData['idRetiro'], $data);
            if ($firmarRetiro) {
                if ($this->wti_notifications->wti_log_retiro("FIRMADO POR RECTOR", $staffData[0]->email, $retiroData['idRetiro'])) {
                    // ! [MULTIPLES MAILS] INICIO
                    $mailsuccess = false;
                    if (!empty($SecretariasAcademicas)) {
                        foreach ($SecretariasAcademicas as $SecretariaAcademica) {
                            $SecretariaArea = $this->Operations_model->get_staff_data($SecretariaAcademica["staffid"]);
                            if (!empty($SecretariaArea)) {
                                foreach ($SecretariaArea as $data) {
                                    if ($this->wti_mails_scuola->retiroFirmadoPorRectorSECA($data->email, $retiroData['idRetiro'])) {
                                        if ($this->wti_notifications->wti_staff_notification("RETIRO POR PROCESAR", $id_staff, $clientId[0]->userid, $clientId[0]->company, $secAcademica["staffid"], $retiroURI)) {
                                            $mailsuccess = true;
                                        }
                                    }
                                }
                            }
                        }
                    }
                    if (!empty($administradores)) {
                        foreach ($administradores as $administrador) {
                            $administradordata = $this->Operations_model->get_staff_data($administrador["staffid"]);
                            if (!empty($administradordata)) {
                                foreach ($administradordata as $data) {
                                    if ($this->wti_mails_scuola->retiroFirmadoPorRectorADM($data->email, $retiroData['idRetiro'])) {
                                        if ($this->wti_notifications->wti_staff_notification("RETIRO POR PROCESAR", $id_staff, $clientId[0]->userid, $clientId[0]->company, $administrador["staffid"], $retiroURI)) {
                                            $mailsuccess = true;
                                        }
                                    }
                                }
                            }
                        }
                    }
                    if ($mailsuccess) {
                        return true;
                    } else {
                        return false;
                    }
                    // ! [MULTIPLES MAILS] FIN
                }
            } else {
                return false;
            }
        }
    }

    public function firmaNullaOsta()
    {
        //HEADERS
        $retiroData = $this->input->post();
        $alldata = $this->Operations_model->get_all_retirodata($retiroData['idRetiro']);
        $id_staff = $this->session->userdata('staff_user_id');
        $SecretariasAcademicas = $this->Operations_model->get_secretaria_academica(); // ! FROM SecretariaAcademicaRequest TO  SecretariasAcademicas
        // ? $secAcademica = $this->Operations_model->get_staff_data($secAcademicaRequest[0]["staff_id"]);
        $staffData = $this->Operations_model->get_staff_data($id_staff);
        $clientId = $this->Operations_model->get_client_id($retiroData['idRetiro']);
        $retiroURI = "api_master/master/details/" . $retiroData['idRetiro'];

        //OPERATIONS

        $uploadNullaOsta = $this->crearNullaOsta($retiroData['idRetiro']);
        if ($uploadNullaOsta) {
            $datosNullaOsta = array(
                'nombre' => "Secretaría académica - NULLA OSTA",
                'url' => $uploadNullaOsta,
                'tipo' => null,
                'fk_retiro' => $retiroData['idRetiro'],
            );
            $set1 = $this->Operations_model->addAttached($datosNullaOsta);
            if ($set1) {
                $valoresFirma = array(
                    'firma_rector_2' => "1",
                    'firma_rector_2_date' => date("Y-m-d"),
                );
                if ($this->Rector_model->setSignNullaOstaValues($retiroData['idRetiro'], $valoresFirma)) {

                    $data = array(
                        'quien_firma' => 'rector',
                        'name' => $staffData[0]->firstname . " " . $staffData[0]->lastname,
                        'fecha' => date('Y-m-d H:i:s'),
                        'fk_retiro' => null,
                        'fk_secretaria_traslado' => null,
                        'IP' => $this->input->ip_address(),
                    );
                    $firmarRetiro2 = $this->FirmaElectronica_model->agregarFirma($retiroData['idRetiro'], $data);


                    if ($this->wti_notifications->wti_log_retiro("NULLA OSTA FIRMADA POR RECTOR", $staffData[0]->email, $retiroData['idRetiro'])) {
                        // ! [MULTIPLES MAILS] INICIO
                        $mailsuccess = false;
                        if (!empty($SecretariasAcademicas)) {
                            foreach ($SecretariasAcademicas as $SecretariaAcademica) {
                                $SecretariaArea = $this->Operations_model->get_staff_data($SecretariaAcademica["staffid"]);
                                if (!empty($SecretariaArea)) {
                                    foreach ($SecretariaArea as $data) {
                                        if ($this->wti_mails_scuola->nullaOstaFirmadoPorRectorSECA($data->email, $retiroData['idRetiro'])) {
                                            if ($this->wti_notifications->wti_staff_notification("NULLA OSTA FIRMADA POR RECTOR", $id_staff, $clientId[0]->userid, $clientId[0]->company, $secAcademica["staffid"], $retiroURI)) {
                                                $mailsuccess = true;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        if ($mailsuccess) {
                            echo "success";
                        } else {
                            echo "error";
                        }
                        // ! [MULTIPLES MAILS] FIN
                    }
                } else {
                    return false;
                }
            } else {
                if (ENVIRONMENT == 'development') {
                    $this->wti_log->log('ERROR - con alguno de los archivos :: NULLA OSTA - ');
                }
                echo "failed";
            }
        }
    }

    public function crearNullaOsta($idRetiro)
    {
        $retiro = $this->Retiros_model->get_retiroID($idRetiro);
        $secre = $this->Retiros_model->get_secre($idRetiro);
        $clientId = $this->Operations_model->get_client_id($idRetiro);
        // aquiii

        // GET DATA

        $sec_nacimiento = $secre[0]->nulla_fecha;
        $sec_ciudad = $secre[0]->nulla_ciudad;
        $sec_pais = $secre[0]->nulla_pais;
        $sec_domicilio = $secre[0]->nulla_domicilio;
        $sec_emision = $secre[0]->nulla_emision;
        $sec_correlativo = $secre[0]->nulla_correlativo;
        $sec_nombre = $clientId[0]->company;
        $sec_curso = $retiro[0]->curso;
        $sec_nivel = $retiro[0]->nivel;

        // Cargando librería
        $this->load->library('pdf/tcpdf');

        // Seteando cosas básicas del pdf (cambiar ésto dependiendo del archivo que se va a generar)
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetAuthor('Scuola Italiana Vittorio Montiglio');
        $pdf->SetTitle('Nulla Osta - Scuola Italiana');
        $pdf->SetSubject('Nulla Osta');
        $pdf->SetKeywords('Scuola, Nulla Osta');

        // Agregamos una página
        $pdf->AddPage();

        $logo = '<img src="' . base_url('uploads/company/2ded124a44f270240d200da5773c3c39.png') . '" style="width: 120px; height: 100px;" alt="Logo Scuola italiana">';
        $firma = '<img src="' . base_url('uploads/company/firma-presi.jpg') . '" style="width: 144px; height: 130px;" alt="Firma Presidente Scuola">';

        // NULLA OSTA - poner aquí los datos reales
        $data1 = [
            'nombre' => $sec_nombre,
            'ciudad' => $sec_ciudad,
            'pais' => $sec_pais,
            'diamesanio' => $sec_nacimiento,
            'domicilio' => $sec_domicilio,
            'curso' => $sec_curso,
            'nivel' => $sec_nivel,
            'fechaHoy' => $sec_emision,
            'correlativo' => $sec_correlativo,
        ];

        // puse en un HELPER el html de NULLA OSTA
        $this->load->helper('nulla');
        $nullaOsta = nullaOsta((object) $data1, $logo, $firma);
        // Convertimos el contenido HTML en el PDF utilizando TCPDF
        $pdf->writeHTML($nullaOsta, true, false, true, false, '');
        $complementName = date('YmdHis');
        // imprimimos
        $nombrePDF = "NullaOsta" . $complementName;
        $temp_pdf_folder = dirname(__DIR__) . '/files/temp/';
        $fileObjectName = $temp_pdf_folder . $nombrePDF . ".pdf";
        //$pdf->Output($nombrePDF . '.pdf', 'I');
        $pdf->Output($temp_pdf_folder . $nombrePDF . '.pdf', 'F');

        $failedFail = false;
        //fileNullaOsta
        $updfileNullaOsta = json_decode($this->wti_subir_archivos->tcpdf_a_bucket($fileObjectName));
        if (!$updfileNullaOsta->ok) {
            if (ENVIRONMENT == 'development') {
                $this->wti_log->log('ERROR - Cargando :: Nulla OSta- fileNullaOsta');
            }
            $failedFail = true;
        } else {
            $updfileNullaOsta = $updfileNullaOsta->url;
        }

        if (!$failedFail) {
            return $updfileNullaOsta;
        }

    }
}