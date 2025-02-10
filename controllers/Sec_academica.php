<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Sample Controller
 */
class Sec_academica extends AdminController
{

    /**
     * Controler __construct function to initialize options
     */

    public function __construct()
    {
        parent::__construct();
        $this->CI = &get_instance();
        $this->CI->load->library('session');
        $this->CI->load->library('Wti_log');
        $this->CI->load->library('Wti_notifications');
        $this->CI->load->library('Wti_mails_scuola');
        $this->CI->load->library('Wti_subir_archivos');
        $this->CI->load->library('Wti_helpers');
        $this->load->model('Operations_model');
        $this->load->model('Sec_academica_model');
        $this->load->model('Director_model');
        $this->load->model('Administracion_model');

    }

    /**
     * Go to home page
     * @return view
     */

    public function index()
    {
        show_404('Página no encontrada');
    }
    // -- A N T I C I P A D O -------------------------------------------------------------------------------------  START
    public function anticipado()
    {
        //HEADERS
        $retiroData = $this->input->post();
        $alldata = $this->Operations_model->get_all_retirodata($retiroData['idRetiro']);
        $id_staff = $this->session->userdata('staff_user_id');
        $staffData = $this->Operations_model->get_staff_data($id_staff);
        $clientId = $this->Operations_model->get_client_id($retiroData['idRetiro']);
        $contacts = $this->Operations_model->get_contacts($clientId[0]->userid);
        $retiroURI = "api_master/master/details/" . $retiroData['idRetiro'];

        $failedFail = false;
        $updCertificadoEstudios = "";

        //FILE PROCESSED
        //fileCertificadoEstudios
        if (isset($_FILES['fileCertificadoEstudios'])) {
            $fileCertificadoEstudios = $_FILES['fileCertificadoEstudios'];
            //fileCertificadoEstudios
            $updCertificadoEstudios = json_decode($this->wti_subir_archivos->subir_a_bucket($fileCertificadoEstudios));
            if (!$updCertificadoEstudios->ok) {
                if (ENVIRONMENT == 'development') {
                    $this->wti_log->log('ERROR - Cargando :: fileCertificadoEstudios');
                }
                $failedFail = true;
            } else {
                $updCertificadoEstudios = $updCertificadoEstudios->url;
            }
        }

        if (!$failedFail) {
            $valoresProcesado = array(
                'secretaria_proc' => "1",
            );
            if (1 == $alldata[0]->adminitracion_proc) {
                $valoresProcesado["estatus"] = "cerrado";
            }
            if ($iniciarProceso = $this->Sec_academica_model->setSignSecreValues($retiroData['idRetiro'], $valoresProcesado)) {
                $valoresRetiro = array(
                    'tipo_secre' => 'anticipado',
                    'observaciones_anticipada_autorizada' => $retiroData["observaciones_anticipada_autorizada"],
                    'certificado_estudios_gen' => 1,
                    'certificado_estudios_sign' => 1,
                    'obs_paritario_no_autorizada' => $retiroData["obs_paritario_no_autorizada"],
                    'secre_modifica_colegium' => 1,
                    'id_retiro' => $retiroData['idRetiro'],
                );
                if ($gestionarRetiro = $this->Sec_academica_model->insertRetirosSecreWti($valoresRetiro)) {
                    $datosCertificadoEstudios = array(
                        'nombre' => "Secretaria Académica - Certificado Anual de Estudios",
                        'url' => $updCertificadoEstudios,
                        'tipo' => null,
                        'fk_retiro' => $retiroData['idRetiro'],
                    );
                    $set1 = $this->Operations_model->addAttached($datosCertificadoEstudios);

                    if ($set1) {
                        if ($this->wti_notifications->wti_log_retiro("PROCESADO POR SECRETARIA ACADEMICA", $staffData[0]->email, $retiroData['idRetiro'])) {
                            if (ENVIRONMENT == 'development') {
                                $this->wti_log->log('SUCCESS - :: anticipado - Proceso finalizado con éxito');
                            }
                            if ("cerrado" == $valoresProcesado["estatus"]) {

                                /* ---- CERTIFICADO DE TRASLADO - SCUOLA ---- */
                                /* ---- CERTIFICADO DE TRASLADO - SCUOLA ---- */
                                /* ---- CERTIFICADO DE TRASLADO - SCUOLA ---- */

                                $vat = $alldata[0]->rut_estudiante;

                                $client = $this->CI->db->query("SELECT * FROM tblclients WHERE tblclients.vat = '{$vat}'");
                                $clienteData = $client->result();

                                // Cargando librería
                                $this->load->library('pdf/tcpdf');

                                // Seteando cosas básicas del pdf (cambiar ésto dependiendo del archivo que se va a generar)
                                $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
                                $pdf->SetAuthor('Scuola Italiana Vittorio Montiglio');
                                $pdf->SetTitle('Certificado de traslado de establecimiento - Scuola Italiana');
                                $pdf->SetSubject('Ciertificado de traslado de establecimiento');
                                $pdf->SetKeywords('Scuola, Certificado, traslado');

                                // Agregamos una página
                                $pdf->AddPage();

                                $logo = '<img src="' . base_url('uploads/company/2ded124a44f270240d200da5773c3c39.png') . '" style="width: 120px; height: 100px;" alt="Logo Scuola italiana">';
                                $firma = '<img src="' . base_url('uploads/company/firma-presi.jpg') . '" style="width: 144px; height: 130px;" alt="Firma Presidente Scuola">';

                                $nivel = $this->wti_helpers->devolverNivel($alldata[0]->curso);
                                $promocion = $this->wti_helpers->devolverPromocion($alldata[0]->promocion_anticipada);

                                $ultAsistencia = date_create(date($alldata[0]->ultima_asistencia));
                                $ultimoDia = date_format($ultAsistencia, "d-m-Y");

                                // Certificado de traslado - poner aquí los datos reales
                                $data1 = [
                                    'nombre' => $clienteData[0]->company,
                                    'ultimoDia' => $ultimoDia,
                                    'cursoCertf' => $alldata[0]->curso,
                                    'nivelCertf' => $nivel,
                                    'numMatricula' => $alldata[0]->matricula,
                                    'promocionCertf' => $promocion,
                                    'fechaEmsionCertf' => $alldata[0]->fecha_ultima_actualizacion_estatus,
                                    'anioAcd' => $alldata[0]->año_academico
                                ];

                                // puse en un HELPER el html del certificado
                                $this->load->helper('certificado');
                                $certificado = certificado((object) $data1, $logo, $firma);

                                // Convertimos el contenido HTML en el PDF utilizando TCPDF
                                $pdf->writeHTML($certificado, true, false, true, false, '');
                                $complementName = date('YmdHis');

                                // imprimimos
                                $nombrePDF = "Certificado-" . $alldata[0]->id . "-" . $complementName;
                                $temp_pdf_folder = dirname(__DIR__) . '/files/temp/';
                                $fileObjectName = $temp_pdf_folder . $nombrePDF . ".pdf";

                                $pdf->Output($temp_pdf_folder . $nombrePDF . '.pdf', 'F');

                                $failedFail = false;

                                $updfileCertificado = json_decode($this->wti_subir_archivos->tcpdf_a_bucket($fileObjectName));
                                if (!$updfileCertificado->ok) {
                                    if (ENVIRONMENT == 'development') {
                                        $this->wti_log->log('ERROR - Cargando :: Certificado de traslado - CertificadoFile');
                                    }
                                    $failedFail = true;
                                } else {
                                    $updfileCertificado = $updfileCertificado->url;
                                }

                                if (!$failedFail) {

                                    /* GUARDANDO EL NUEVO CERTIFICADO */
                                    $datosCertificadoTraslado = array(
                                        'nombre' => "Secretaria Académica - Certificado de traslado de establecimiento",
                                        'url' => $updfileCertificado,
                                        'tipo' => null,
                                        'fk_retiro' => $alldata[0]->id,
                                    );
                                    $set2 = $this->Operations_model->addAttached($datosCertificadoTraslado);

                                    if ($set2) {

                                        $data = [
                                            'folio' => $retiroData['idRetiro'],
                                            'tipo' => $alldata[0]->tipo,
                                            'motivo' => $alldata[0]->motivo_retiro,
                                            'nombre_alumno' => $clientId[0]->company,
                                            'nombre_apoderado' => $contacts[0]->firstname . " " . $contacts[0]->lastname,
                                        ];

                                        if ($this->wti_mails_scuola->cierreRetiroMail($contacts[0]->email, $data)) {
                                            echo "success";
                                        }

                                    }
                                }

                            } else {
                                echo "success";
                            }
                        }
                    }
                }
            }

        } else {
            if (ENVIRONMENT == 'development') {
                $this->wti_log->log('ERROR - con alguno de los archivos ::proximo_emitido_mensual -');
            }
            echo "failed";
        }
    }
    // -- A N T I C I P A D O -------------------------------------------------------------------------------------  END

    // -- P A R I T A R I O -------------------------------------------------------------------------------------  START
    public function paritario()
    {
        //HEADERS
        $retiroData = $this->input->post();
        $alldata = $this->Operations_model->get_all_retirodata($retiroData['idRetiro']);
        $id_staff = $this->session->userdata('staff_user_id');
        $rectores = $this->Operations_model->get_rectores(); // ! FROM $rectorRequest TO $rectores
        // ? $rector = $this->Operations_model->get_staff_data($rectorRequest[0]["staff_id"]);
        $staffData = $this->Operations_model->get_staff_data($id_staff);
        $clientId = $this->Operations_model->get_client_id($retiroData['idRetiro']);
        $contacts = $this->Operations_model->get_contacts($clientId[0]->userid);
        $retiroURI = "api_master/master/details/" . $retiroData['idRetiro'];

        $failedFail = false;
        $updCertificadoEstudios = "";

        //FILE PROCESSED
        //fileCertificadoPagella
        if (isset($_FILES['fileCertificadoPagella'])) {
            $fileCertificadoPagella = $_FILES['fileCertificadoPagella'];
            //fileCertificadoEstudios
            $updCertificadoPagella = json_decode($this->wti_subir_archivos->subir_a_bucket($fileCertificadoPagella));
            if (!$updCertificadoPagella->ok) {
                if (ENVIRONMENT == 'development') {
                    $this->wti_log->log('ERROR - Cargando :: fileCertificadoPagella');
                }
                $failedFail = true;
            } else {
                $updCertificadoPagella = $updCertificadoPagella->url;
            }
        }

        if (!$failedFail) {
            $valoresProcesado = array(
                'nulla_send' => 1,
                'secretaria_proc' => "1",
            );
            if ($iniciarProceso = $this->Sec_academica_model->setSignNullaValues($retiroData['idRetiro'], $valoresProcesado)) {
                $valoresRetiro = array(
                    'tipo_secre' => 'paritario',
                    'observaciones_anticipada_no_autorizada' => $retiroData["observaciones_anticipada_no_autorizada"],
                    'obs_paritario_autorizada' => $retiroData["obs_paritario_autorizada"],
                    'secre_pagella_gen' => 1,
                    'secre_pagella_sign' => 1,
                    'secre_modifica_colegium' => 1,
                    'nulla_ciudad' => $retiroData["nulla_ciudad"],
                    'nulla_pais' => $retiroData["nulla_pais"],
                    'nulla_fecha' => $retiroData["nulla_fecha"],
                    'nulla_domicilio' => $retiroData["nulla_domicilio"],
                    'nulla_correlativo' => $retiroData["nulla_correlativo"],
                    'nulla_emision' => $retiroData["nulla_emision"],
                    'id_retiro' => $retiroData['idRetiro'],
                );
                if ($gestionarRetiro = $this->Sec_academica_model->insertRetirosSecreWti($valoresRetiro)) {
                    $datosCertificadoPagella = array(
                        'nombre' => "Secretaria Académica - Certificado de Pagella",
                        'url' => $updCertificadoPagella,
                        'tipo' => null,
                        'fk_retiro' => $retiroData['idRetiro'],
                    );
                    $set1 = $this->Operations_model->addAttached($datosCertificadoPagella);

                    if ($set1) {
                        if ($this->wti_notifications->wti_log_retiro("EN ESPERA FIRMA DE RECTOR", $staffData[0]->email, $retiroData['idRetiro'])) {
                            if (ENVIRONMENT == 'development') {
                                $this->wti_log->log('SUCCESS - :: Anticipado/Paritario - Proceso finalizado con éxito');
                            }
                            // ! [MULTIPLES MAILS] INICIO
                            $mailsuccess = false;

                            if (!empty($rectores)) {
                                // cambiar a estatus: por_firmar_NULLA
                                $nuevoStatus = $this->Sec_academica_model->setNULLAporFirmar($retiroData['idRetiro']);

                                if ($nuevoStatus) {
                                    // ahora a enviar los correos
                                    foreach ($rectores as $rector) {
                                        $rectordata = $this->Operations_model->get_staff_data($rector["staffid"]);
                                        if (!empty($rectordata)) {
                                            foreach ($rectordata as $data) {
                                                if ($this->wti_mails_scuola->retiroNullaOstaGenerada($data->email, $retiroData['idRetiro'])) {
                                                    if ($this->wti_notifications->wti_staff_notification("NULLA OSTA POR FIRMAR", $id_staff, $clientId[0]->userid, $clientId[0]->company, $rector["staffid"], $retiroURI)) {
                                                        $mailsuccess = true;
                                                    }
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
                    }
                }
            }

        } else {
            if (ENVIRONMENT == 'development') {
                $this->wti_log->log('ERROR - con alguno de los archivos :: Anticipado - Paritario -');
            }
            echo "failed";
        }
    }
    // -- P A R I T A R I O -------------------------------------------------------------------------------------  END

    // -- P A R I T A R I O   F I R M A D O -------------------------------------------------------------------------------------  START
    public function paritario_firmado()
    {
        //HEADERS
        $retiroData = $this->input->post();
        $alldata = $this->Operations_model->get_all_retirodata($retiroData['idRetiro']);
        $id_staff = $this->session->userdata('staff_user_id');
        $staffData = $this->Operations_model->get_staff_data($id_staff);
        $clientId = $this->Operations_model->get_client_id($retiroData['idRetiro']);
        $contacts = $this->Operations_model->get_contacts($clientId[0]->userid);
        $retiroURI = "api_master/master/details/" . $retiroData['idRetiro'];

        $valoresProcesado = array(
            'secretaria_proc' => "1",
        );
        if (1 == $alldata[0]->adminitracion_proc) {
            $valoresProcesado["estatus"] = "cerrado";
        }

        if ($iniciarProceso = $this->Sec_academica_model->setSignSecreValues($retiroData['idRetiro'], $valoresProcesado)) {
            if ($this->wti_notifications->wti_log_retiro("CAMBIO DE ESTATUS A: CERRADO", $staffData[0]->email, $retiroData['idRetiro'])) {
                if (ENVIRONMENT == 'development') {
                    $this->wti_log->log('SUCCESS - :: Cambio de status a cerrado');
                }
                if ("cerrado" == $valoresProcesado["estatus"]) {

                    /* ---- CERTIFICADO DE TRASLADO - SCUOLA ---- */
                    /* ---- CERTIFICADO DE TRASLADO - SCUOLA ---- */
                    /* ---- CERTIFICADO DE TRASLADO - SCUOLA ---- */

                    $vat = $alldata[0]->rut_estudiante;

                    $client = $this->CI->db->query("SELECT * FROM tblclients WHERE tblclients.vat = '{$vat}'");
                    $clienteData = $client->result();

                    // Cargando librería
                    $this->load->library('pdf/tcpdf');

                    $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
                    $pdf->SetAuthor('Scuola Italiana Vittorio Montiglio');
                    $pdf->SetTitle('Certificado de traslado de establecimiento - Scuola Italiana');
                    $pdf->SetSubject('Ciertificado de traslado de establecimiento');
                    $pdf->SetKeywords('Scuola, Certificado, traslado');

                    // Agregamos una página
                    $pdf->AddPage();

                    $logo = '<img src="' . base_url('uploads/company/2ded124a44f270240d200da5773c3c39.png') . '" style="width: 120px; height: 100px;" alt="Logo Scuola italiana">';
                    $firma = '<img src="' . base_url('uploads/company/firma-presi.jpg') . '" style="width: 144px; height: 130px;" alt="Firma Presidente Scuola">';

                    $nivel = $this->wti_helpers->devolverNivel($alldata[0]->curso);
                    $promocion = $this->wti_helpers->devolverPromocion($alldata[0]->promocion_anticipada);

                    $ultAsistencia = date_create(date($alldata[0]->ultima_asistencia));
                    $ultimoDia = date_format($ultAsistencia, "d-m-Y");

                    // Certificado de traslado - data
                    $data1 = [
                        'nombre' => $clienteData[0]->company,
                        'ultimoDia' => $ultimoDia,
                        'cursoCertf' => $alldata[0]->curso,
                        'nivelCertf' => $nivel,
                        'numMatricula' => $alldata[0]->matricula,
                        'promocionCertf' => $promocion,
                        'fechaEmsionCertf' => $alldata[0]->fecha_ultima_actualizacion_estatus,
                        'anioAcd' => $alldata[0]->año_academico
                    ];

                    // puse en un HELPER el html del certificado
                    $this->load->helper('certificado');
                    $certificado = certificado((object) $data1, $logo, $firma);

                    // Convertimos el contenido HTML en el PDF utilizando TCPDF
                    $pdf->writeHTML($certificado, true, false, true, false, '');
                    $complementName = date('YmdHis');

                    // imprimimos
                    $nombrePDF = "Certificado-" . $alldata[0]->id . "-" . $complementName;
                    $temp_pdf_folder = dirname(__DIR__) . '/files/temp/';
                    $fileObjectName = $temp_pdf_folder . $nombrePDF . ".pdf";

                    $pdf->Output($temp_pdf_folder . $nombrePDF . '.pdf', 'F');

                    $failedFail = false;

                    $updfileCertificado = json_decode($this->wti_subir_archivos->tcpdf_a_bucket($fileObjectName));
                    if (!$updfileCertificado->ok) {
                        if (ENVIRONMENT == 'development') {
                            $this->wti_log->log('ERROR - Cargando :: Certificado de traslado - CertificadoFile');
                        }
                        $failedFail = true;
                    } else {
                        $updfileCertificado = $updfileCertificado->url;
                    }

                    if (!$failedFail) {

                        /* GUARDANDO EL NUEVO CERTIFICADO */
                        $datosCertificadoTraslado = array(
                            'nombre' => "Secretaria Académica - Certificado de traslado de establecimiento",
                            'url' => $updfileCertificado,
                            'tipo' => null,
                            'fk_retiro' => $alldata[0]->id,
                        );
                        $set2 = $this->Operations_model->addAttached($datosCertificadoTraslado);

                        if ($set2) {

                            $data = [
                                'folio' => $retiroData['idRetiro'],
                                'tipo' => $alldata[0]->tipo,
                                'motivo' => $alldata[0]->motivo_retiro,
                                'nombre_alumno' => $clientId[0]->company,
                                'nombre_apoderado' => $contacts[0]->firstname . " " . $contacts[0]->lastname,
                            ];

                            if ($this->wti_mails_scuola->cierreRetiroMail($contacts[0]->email, $data, false)) {
                                echo "success";
                            }

                        }
                    }

                } else {
                    echo "success";
                }
            }
        }
    }
    // -- P A R I T A R I O   F I R M A D O -------------------------------------------------------------------------------------  END

    // -- A N T I C I P A D O  /  P A R I T A R I O -------------------------------------------------------------------------------------  START
    public function anticipado_paritario()
    {
        //HEADERS
        $retiroData = $this->input->post();
        $alldata = $this->Operations_model->get_all_retirodata($retiroData['idRetiro']);
        $id_staff = $this->session->userdata('staff_user_id');
        $rectores = $this->Operations_model->get_rectores(); // ! FROM $rectorRequest TO $rectores
        // ? $rector = $this->Operations_model->get_staff_data($rectorRequest[0]["staff_id"]);
        $staffData = $this->Operations_model->get_staff_data($id_staff);
        $clientId = $this->Operations_model->get_client_id($retiroData['idRetiro']);
        $contacts = $this->Operations_model->get_contacts($clientId[0]->userid);
        $retiroURI = "api_master/master/details/" . $retiroData['idRetiro'];

        $failedFail = false;
        $updCertificadoEstudios = "";

        //FILE PROCESSED
        //fileCertificadoEstudios
        if (isset($_FILES['fileCertificadoEstudios'])) {
            $fileCertificadoEstudios = $_FILES['fileCertificadoEstudios'];
            //fileCertificadoEstudios
            $updCertificadoEstudios = json_decode($this->wti_subir_archivos->subir_a_bucket($fileCertificadoEstudios));
            if (!$updCertificadoEstudios->ok) {
                if (ENVIRONMENT == 'development') {
                    $this->wti_log->log('ERROR - Cargando :: fileCertificadoEstudios');
                }
                $failedFail = true;
            } else {
                $updCertificadoEstudios = $updCertificadoEstudios->url;
            }
        }

        //fileCertificadoPagella
        if (isset($_FILES['fileCertificadoPagella'])) {
            $fileCertificadoPagella = $_FILES['fileCertificadoPagella'];
            //fileCertificadoEstudios
            $updCertificadoPagella = json_decode($this->wti_subir_archivos->subir_a_bucket($fileCertificadoPagella));
            if (!$updCertificadoPagella->ok) {
                if (ENVIRONMENT == 'development') {
                    $this->wti_log->log('ERROR - Cargando :: fileCertificadoPagella');
                }
                $failedFail = true;
            } else {
                $updCertificadoPagella = $updCertificadoPagella->url;
            }
        }

        if (!$failedFail) {
            $valoresProcesado = array(
                'nulla_send' => 1,
                'secretaria_proc' => "1",
            );
            if ($iniciarProceso = $this->Sec_academica_model->setSignNullaValues($retiroData['idRetiro'], $valoresProcesado)) {
                $valoresRetiro = array(
                    'tipo_secre' => 'anticipado_paritario',
                    'observaciones_anticipada_autorizada' => $retiroData["observaciones_anticipada_autorizada"],
                    'obs_paritario_autorizada' => $retiroData["obs_paritario_autorizada"],
                    'certificado_estudios_gen' => 1,
                    'certificado_estudios_sign' => 1,
                    'secre_pagella_gen' => 1,
                    'secre_pagella_sign' => 1,
                    'secre_modifica_colegium' => 1,
                    'nulla_ciudad' => $retiroData["nulla_ciudad"],
                    'nulla_pais' => $retiroData["nulla_pais"],
                    'nulla_fecha' => $retiroData["nulla_fecha"],
                    'nulla_domicilio' => $retiroData["nulla_domicilio"],
                    'nulla_correlativo' => $retiroData["nulla_correlativo"],
                    'nulla_emision' => $retiroData["nulla_emision"],
                    'id_retiro' => $retiroData['idRetiro'],
                );

                if ($gestionarRetiro = $this->Sec_academica_model->insertRetirosSecreWti($valoresRetiro)) {
                    $datosCertificadoEstudios = array(
                        'nombre' => "Secretaria Académica - Certificado Anual de Estudios",
                        'url' => $updCertificadoEstudios,
                        'tipo' => null,
                        'fk_retiro' => $retiroData['idRetiro'],
                    );
                    $set1 = $this->Operations_model->addAttached($datosCertificadoEstudios);

                    $datosCertificadoPagella = array(
                        'nombre' => "Secretaria Académica - Certificado de Pagella",
                        'url' => $updCertificadoPagella,
                        'tipo' => null,
                        'fk_retiro' => $retiroData['idRetiro'],
                    );
                    $set2 = $this->Operations_model->addAttached($datosCertificadoPagella);

                    if ($set1 && $set2) {
                        if ($this->wti_notifications->wti_log_retiro("EN ESPERA FIRMA DE RECTOR", $staffData[0]->email, $retiroData['idRetiro'])) {
                            if (ENVIRONMENT == 'development') {
                                $this->wti_log->log('SUCCESS - :: Anticipado/Paritario - Proceso finalizado con éxito');
                            }
                            // ! [MULTIPLES MAILS] INICIO
                            $mailsuccess = false;
                            if (!empty($rectores)) {

                                $nuevoStatus = $this->Sec_academica_model->setNULLAporFirmar($retiroData['idRetiro']);

                                if ($nuevoStatus) {
                                    foreach ($rectores as $rector) {
                                        $rectordata = $this->Operations_model->get_staff_data($rector["staffid"]);
                                        if (!empty($rectordata)) {
                                            foreach ($rectordata as $data) {
                                                if ($this->wti_mails_scuola->retiroNullaOstaGenerada($data->email, $retiroData['idRetiro'])) {
                                                    if ($this->wti_notifications->wti_staff_notification("NULLA OSTA POR FIRMAR", $id_staff, $clientId[0]->userid, $clientId[0]->company, $rector["staffid"], $retiroURI)) {
                                                        $mailsuccess = true;
                                                    }
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
                    }
                }
            }

        } else {
            if (ENVIRONMENT == 'development') {
                $this->wti_log->log('ERROR - con alguno de los archivos :: Anticipado - Paritario -');
            }
            echo "failed";
        }
    }
    // -- A N T I C I P A D O  /  P A R I T A R I O -------------------------------------------------------------------------------------  END

    // -- N O   A N T I C I P A D O  /  N O   P A R I T A R I O -------------------------------------------------------------------------------------  START
    public function no_anticipado_no_paritario()
    {
        //HEADERS
        $retiroData = $this->input->post();
        $alldata = $this->Operations_model->get_all_retirodata($retiroData['idRetiro']);
        $id_staff = $this->session->userdata('staff_user_id');
        $staffData = $this->Operations_model->get_staff_data($id_staff);
        $clientId = $this->Operations_model->get_client_id($retiroData['idRetiro']);
        $contacts = $this->Operations_model->get_contacts($clientId[0]->userid);
        $retiroURI = "api_master/master/details/" . $retiroData['idRetiro'];

        $valoresProcesado = array(
            'secretaria_proc' => "1",
        );
        if (1 == $alldata[0]->adminitracion_proc) {
            $valoresProcesado["estatus"] = "cerrado";
        }
        if ($iniciarProceso = $this->Sec_academica_model->setSignSecreValues($retiroData['idRetiro'], $valoresProcesado)) {
            $valoresRetiro = array(
                'tipo_secre' => 'no_anticipado_no_paritario',
                'observaciones_anticipada_no_autorizada' => $retiroData["observaciones_anticipada_no_autorizada"],
                'obs_paritario_no_autorizada' => $retiroData["obs_paritario_no_autorizada"],
                'id_retiro' => $retiroData['idRetiro'],
            );
            if ($gestionarRetiro = $this->Sec_academica_model->insertRetirosSecreWti($valoresRetiro)) {
                if ($this->wti_notifications->wti_log_retiro("PROCESADO POR SECRETARIA ACADEMICA", $staffData[0]->email, $retiroData['idRetiro'])) {
                    if (ENVIRONMENT == 'development') {
                        $this->wti_log->log('SUCCESS - :: No Anticipado/ No Paritario - Proceso finalizado con éxito');
                    }

                    if ("cerrado" == $valoresProcesado["estatus"]) {

                        /* ---- CERTIFICADO DE TRASLADO - SCUOLA ---- */
                        /* ---- CERTIFICADO DE TRASLADO - SCUOLA ---- */
                        /* ---- CERTIFICADO DE TRASLADO - SCUOLA ---- */

                        $vat = $alldata[0]->rut_estudiante;

                        $client = $this->CI->db->query("SELECT * FROM tblclients WHERE tblclients.vat = '{$vat}'");
                        $clienteData = $client->result();

                        // Cargando librería
                        $this->load->library('pdf/tcpdf');

                        // Seteando cosas básicas del pdf (cambiar ésto dependiendo del archivo que se va a generar)
                        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
                        $pdf->SetAuthor('Scuola Italiana Vittorio Montiglio');
                        $pdf->SetTitle('Certificado de traslado de establecimiento - Scuola Italiana');
                        $pdf->SetSubject('Ciertificado de traslado de establecimiento');
                        $pdf->SetKeywords('Scuola, Certificado, traslado');

                        // Agregamos una página
                        $pdf->AddPage();

                        $logo = '<img src="' . base_url('uploads/company/2ded124a44f270240d200da5773c3c39.png') . '" style="width: 120px; height: 100px;" alt="Logo Scuola italiana">';
                        $firma = '<img src="' . base_url('uploads/company/firma-presi.jpg') . '" style="width: 144px; height: 130px;" alt="Firma Presidente Scuola">';

                        $nivel = $this->wti_helpers->devolverNivel($alldata[0]->curso);
                        $promocion = $this->wti_helpers->devolverPromocion($alldata[0]->promocion_anticipada);

                        $ultAsistencia = date_create(date($alldata[0]->ultima_asistencia));
                        $ultimoDia = date_format($ultAsistencia, "d-m-Y");

                        // Certificado de traslado - poner aquí los datos reales
                        $data1 = [
                            'nombre' => $clienteData[0]->company,
                            'ultimoDia' => $ultimoDia,
                            'cursoCertf' => $alldata[0]->curso,
                            'nivelCertf' => $nivel,
                            'numMatricula' => $alldata[0]->matricula,
                            'promocionCertf' => $promocion,
                            'fechaEmsionCertf' => $alldata[0]->fecha_ultima_actualizacion_estatus,
                            'anioAcd' => $alldata[0]->año_academico
                        ];

                        // puse en un HELPER el html del certificado
                        $this->load->helper('certificado');
                        $certificado = certificado((object) $data1, $logo, $firma);

                        // Convertimos el contenido HTML en el PDF utilizando TCPDF
                        $pdf->writeHTML($certificado, true, false, true, false, '');
                        $complementName = date('YmdHis');

                        // imprimimos
                        $nombrePDF = "Certificado-" . $alldata[0]->id . "-" . $complementName;
                        $temp_pdf_folder = dirname(__DIR__) . '/files/temp/';
                        $fileObjectName = $temp_pdf_folder . $nombrePDF . ".pdf";

                        $pdf->Output($temp_pdf_folder . $nombrePDF . '.pdf', 'F');

                        $failedFail = false;

                        $updfileCertificado = json_decode($this->wti_subir_archivos->tcpdf_a_bucket($fileObjectName));
                        if (!$updfileCertificado->ok) {
                            if (ENVIRONMENT == 'development') {
                                $this->wti_log->log('ERROR - Cargando :: Certificado de traslado - CertificadoFile');
                            }
                            $failedFail = true;
                        } else {
                            $updfileCertificado = $updfileCertificado->url;
                        }

                        if (!$failedFail) {

                            /* GUARDANDO EL NUEVO CERTIFICADO */
                            $datosCertificadoTraslado = array(
                                'nombre' => "Secretaria Académica - Certificado de traslado de establecimiento",
                                'url' => $updfileCertificado,
                                'tipo' => null,
                                'fk_retiro' => $alldata[0]->id,
                            );
                            $set2 = $this->Operations_model->addAttached($datosCertificadoTraslado);

                            if ($set2) {

                                $data = [
                                    'folio' => $retiroData['idRetiro'],
                                    'tipo' => $alldata[0]->tipo,
                                    'motivo' => $alldata[0]->motivo_retiro,
                                    'nombre_alumno' => $clientId[0]->company,
                                    'nombre_apoderado' => $contacts[0]->firstname . " " . $contacts[0]->lastname,
                                ];

                                if ($this->wti_mails_scuola->cierreRetiroMail($contacts[0]->email, $data, false)) {
                                    echo "success";
                                }

                            }
                        }

                    } else {
                        echo "success";
                    }

                }

            }
        }

    }
    // -- N O   A N T I C I P A D O  /  N O   P A R I T A R I O -------------------------------------------------------------------------------------  END

    // -------------------------------------------------------------------------------------------------------------------------------------------
    // 1RA ITERACION

    public function reabrirAnular()
    {
        $retiroData = $this->input->post();
        $id_staff = $this->session->userdata('staff_user_id');
        $staffData = $this->Operations_model->get_staff_data($id_staff);
        $directores = $this->Operations_model->get_directores_area($retiroData['fkEscuela']); // ! FROM  $directorAreaRequest  TO $directores
        // ? $directorArea = $this->Operations_model->get_staff_data($directorAreaRequest[0]["staff_id"]);
        $clientId = $this->Operations_model->get_client_id($retiroData['idRetiro']);
        $alldata = $this->Operations_model->get_all_retirodata($retiroData['idRetiro']);
        $contacts = $this->Operations_model->get_contacts($clientId[0]->userid);
        $retiroURI = "api_master/master/details/" . $retiroData['idRetiro'];

        if ("reabrir" == $retiroData['datos']) {
            $iniciarEnProceso = $this->Director_model->retiroProcede($retiroData['idRetiro'], '');

            if ($iniciarEnProceso) {
                if ($this->wti_notifications->wti_log_retiro("CAMBIO DE ESTATUS A: EN PROCESO", $staffData[0]->email, $retiroData['idRetiro'])) {
                    // ! [MULTIPLES MAILS] INICIO
                    $mailsuccess = false;
                    if ($this->wti_notifications->wti_staff_notification("EN PROCESO", $id_staff, $clientId[0]->userid, $clientId[0]->company, $id_staff, $retiroURI)) {
                        if (!empty($directores)) {
                            foreach ($directores as $director) {
                                $directorArea = $this->Operations_model->get_staff_data($director["staffid"]);
                                if (!empty($directorArea)) {
                                    foreach ($directorArea as $data) {
                                        if ($this->wti_mails_scuola->retiroEnProcesoDirector($data->email, $retiroData['idRetiro'])) {
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
                echo "failed";
            }

        } elseif ("anular" == $retiroData['datos']) {
            //procede
            $directorProcedMotivoMail = "";
            $preAnularProceso = $this->Sec_academica_model->anulacionProcede($retiroData['idRetiro']);
            $directorProcedMotivoMail = $alldata[0]->motivo_anulacion;

            if ($preAnularProceso) {
                if ($this->wti_notifications->wti_log_retiro("CAMBIO DE ESTATUS A: ANULADO", $staffData[0]->email, $retiroData['idRetiro'])) {
                    if ($this->wti_mails_scuola->confirmacionAnulacionSecAca($contacts[0]->firstname . " " . $contacts[0]->lastname, $contacts[0]->email, $retiroData['idRetiro'], $clientId[0]->company, $directorProcedMotivoMail)) {
                        return true;
                    }
                }
            } else {
                return false;
            }

        }
    }

}