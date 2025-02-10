<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Sample Controller
 */
class Administracion extends AdminController
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

    /*public function submit_form()
    {
    //HEADERS
    if (isset($_FILES['adm_anticipado_1'])) {

    $fileHere = $_FILES['adm_anticipado_1'];

    //CALCULO PROPORCIONAL
    $adm_anticipado_1 = $this->wti_subir_archivos->subir_a_bucket($fileHere);
    if ($adm_anticipado_1) {
    echo $adm_anticipado_1;
    } else {
    echo "Error cargando archivo";
    }
    }
    }*/
    #######################################################################################################################
    # START - A C T U A L   A N T I C I P A D O
    #######################################################################################################################
    public function actual_anticipado()
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
        $updCalculoProporcional = "";
        $updFormulariodedevolucion = "";
        $updfileNotadeCredito = "";

        //FILE PROCESSED
        //fileCalculoProporcional
        if (isset($_FILES['fileCalculoProporcional'])) {
            $fileCalculoProporcional = $_FILES['fileCalculoProporcional'];
            //fileCalculoProporcional
            $updCalculoProporcional = json_decode($this->wti_subir_archivos->subir_a_bucket($fileCalculoProporcional));
            if (!$updCalculoProporcional->ok) {
                $this->wti_log->log('ERROR - Cargando ::actual_anticipado- fileCalculoProporcional');
                $failedFail = true;
            } else {
                $updCalculoProporcional = $updCalculoProporcional->url;
            }
        }

//fileFormulariodedevolucion
        if (isset($_FILES['fileFormulariodedevolucion'])) {
            $fileFormulariodedevolucion = $_FILES['fileFormulariodedevolucion'];
//fileFormulariodedevolucion
            $updFormulariodedevolucion = json_decode($this->wti_subir_archivos->subir_a_bucket($fileFormulariodedevolucion));
            if (!$updFormulariodedevolucion->ok) {
                    $this->wti_log->log('ERROR - Cargando ::actual_anticipado- fileFormulariodedevolucion');
                $failedFail = true;
            } else {
                $updFormulariodedevolucion = $updFormulariodedevolucion->url;
            }
        }

//fileNotadeCredito
        if (isset($_FILES['fileNotadeCredito'])) {
            $fileNotadeCredito = $_FILES['fileNotadeCredito'];
//fileNotadeCredito
            $updfileNotadeCredito = json_decode($this->wti_subir_archivos->subir_a_bucket($fileNotadeCredito));
            if (!$updfileNotadeCredito->ok) {
                $this->wti_log->log('ERROR - Cargando ::actual_anticipado- fileNotadeCredito');
                $failedFail = true;
            } else {
                $updfileNotadeCredito = $updfileNotadeCredito->url;
            }
        }

        if (!$failedFail) {
            $valoresProcesado = array(
                'adminitracion_proc' => "1",
            );
            if (1 == $alldata[0]->secretaria_proc) {
                $valoresProcesado["estatus"] = "cerrado";
            }
            if ($iniciarProceso = $this->Administracion_model->setSignAdminValues($retiroData['idRetiro'], $valoresProcesado)) {

                // aquí va el error
                $valoresRetiro = array(
                    'anio_retiro' => $retiroData["anioRetiro"],
                    'sin_efecto_contable' => 0,
                    'pago_colegiaturas' => $retiroData["admin_forma_de_pago"],
                    'calculo_proporcional' => 1,
                    'formulario_devolucion' => 1,
                    'nota_credito' => 1,
                    'egreso_devolucion' => 1,
                    'rebaja_sistema' => 0,
                    'numero_egreso_devolucion' => $retiroData["admin_numero_egreso_devolucion"],
                    'fecha_egreso_devolucion' => $retiroData["admin_fecha_egreso_devolucion"],
                    'observaciones' => $retiroData["observaciones_anual_anticipado"],
                    'id_retiro' => $retiroData['idRetiro'],
                );

                if ($gestionarRetiro = $this->Administracion_model->insertRetirosAdminWti($valoresRetiro)) {
                    $datosCalculoProporcional = array(
                        'nombre' => "Administración - Cálculo proporcional",
                        'url' => $updCalculoProporcional,
                        'tipo' => null,
                        'fk_retiro' => $retiroData['idRetiro'],
                    );
                    $set1 = $this->Operations_model->addAttached($datosCalculoProporcional);
                    $datosFileFormulariodedevolucion = array(
                        'nombre' => "Administración - Formulario de devolución",
                        'url' => $updFormulariodedevolucion,
                        'tipo' => null,
                        'fk_retiro' => $retiroData['idRetiro'],
                    );
                    $set2 = $this->Operations_model->addAttached($datosFileFormulariodedevolucion);
                    $datosNotadeCredito = array(
                        'nombre' => "Administración - Nota de Crédito",
                        'url' => $updfileNotadeCredito,
                        'tipo' => null,
                        'fk_retiro' => $retiroData['idRetiro'],
                    );
                    $set3 = $this->Operations_model->addAttached($datosNotadeCredito);

                    if ($set1 && $set2 && $set3) {
                        if ($this->wti_notifications->wti_log_retiro("PROCESADO POR ADMINISTRACIÓN", $staffData[0]->email, $retiroData['idRetiro'])) {
                            if (ENVIRONMENT == 'development') {
                                $this->wti_log->log('SUCCESS - ::actual_anticipado - Proceso finalizado con éxito');
                            }
                            if ($valoresProcesado["estatus"] == "cerrado") {


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
                                        'nombre' => "Administración - Certificado de traslado de establecimiento",
                                        'url' => $updfileCertificado,
                                        'tipo' => null,
                                        'fk_retiro' => $alldata[0]->id,
                                    );
                                    $set4 = $this->Operations_model->addAttached($datosCertificadoTraslado);

                                    if ($set4) {

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
                $this->wti_log->log('ERROR - con alguno de los archivos ::actual_anticipado -');
            }
            echo "failed";
        }
    }
    #######################################################################################################################
    # END - A C T U A L   A N T I C I P A D O
    #######################################################################################################################

    #######################################################################################################################
    # START - P R O X I M O   S I N   E F E C T O   C O N T A B L E
    #######################################################################################################################
    public function proximo_sin_efecto_contable()
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
            'adminitracion_proc' => "1",
        );
        if (1 == $alldata[0]->secretaria_proc) {
            $valoresProcesado["estatus"] = "cerrado";
        }
        if ($iniciarProceso = $this->Administracion_model->setSignAdminValues($retiroData['idRetiro'], $valoresProcesado)) {
            $valoresRetiro = array(
                'anio_retiro' => $retiroData["anioRetiro"],
                'anualidades' => $retiroData["admin_anualidades"],
                'sin_efecto_contable' => 1,
                'id_retiro' => $retiroData['idRetiro'],
            );
            if ($gestionarRetiro = $this->Administracion_model->insertRetirosAdminWti($valoresRetiro)) {
                if ($this->wti_notifications->wti_log_retiro("PROCESADO POR ADMINISTRACIÓN", $staffData[0]->email, $retiroData['idRetiro'])) {
                    if (ENVIRONMENT == 'development') {
                        $this->wti_log->log('SUCCESS - ::proximo_sin_efecto_contable - Proceso finalizado con éxito');
                    }
                            if ($valoresProcesado["estatus"] == "cerrado") {

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
                                        'nombre' => "Administración - Certificado de traslado de establecimiento",
                                        'url' => $updfileCertificado,
                                        'tipo' => null,
                                        'fk_retiro' => $alldata[0]->id,
                                    );
                                    $set4 = $this->Operations_model->addAttached($datosCertificadoTraslado);

                                    if ($set4) {

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
        } else {
            if (ENVIRONMENT == 'development') {
                $this->wti_log->log('ERROR - con alguno de los archivos ::proximo_sin_efecto_contable -');
            }
            echo "failed";
        }

    }
    #######################################################################################################################
    # END - P R O X I M O   S I N   E F E C T O   C O N T A B L E
    #######################################################################################################################

    #######################################################################################################################
    # START - P R O X I M O   E M I T I D O   A N T I C I P A D O
    #######################################################################################################################

    public function proximo_emitido_anticipado()
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
        $updCalculoProporcional = "";
        $updFormulariodedevolucion = "";
        $updfileNotadeCredito = "";

        //FILE PROCESSED
        //fileCalculoProporcional
        if (isset($_FILES['fileCalculoProporcional'])) {
            $fileCalculoProporcional = $_FILES['fileCalculoProporcional'];
            //fileCalculoProporcional
            $updCalculoProporcional = json_decode($this->wti_subir_archivos->subir_a_bucket($fileCalculoProporcional));
            if (!$updCalculoProporcional->ok) {
                if (ENVIRONMENT == 'development') {
                    $this->wti_log->log('ERROR - Cargando ::proximo_emitido_anticipado- fileCalculoProporcional');
                }
                $failedFail = true;
            } else {
                $updCalculoProporcional = $updCalculoProporcional->url;
            }
        }

//fileFormulariodedevolucion
        if (isset($_FILES['fileFormulariodedevolucion'])) {
            $fileFormulariodedevolucion = $_FILES['fileFormulariodedevolucion'];
//fileFormulariodedevolucion
            $updFormulariodedevolucion = json_decode($this->wti_subir_archivos->subir_a_bucket($fileFormulariodedevolucion));
            if (!$updFormulariodedevolucion->ok) {
                if (ENVIRONMENT == 'development') {
                    $this->wti_log->log('ERROR - Cargando ::proximo_emitido_anticipado - fileFormulariodedevolucion');
                }
                $failedFail = true;
            } else {
                $updFormulariodedevolucion = $updFormulariodedevolucion->url;
            }
        }

//fileNotadeCredito
        if (isset($_FILES['fileNotadeCredito'])) {
            $fileNotadeCredito = $_FILES['fileNotadeCredito'];
//fileNotadeCredito
            $updfileNotadeCredito = json_decode($this->wti_subir_archivos->subir_a_bucket($fileNotadeCredito));
            if (!$updfileNotadeCredito->ok) {
                if (ENVIRONMENT == 'development') {
                    $this->wti_log->log('ERROR - Cargando ::proximo_emitido_anticipado - fileNotadeCredito');
                }
                $failedFail = true;
            } else {
                $updfileNotadeCredito = $updfileNotadeCredito->url;
            }
        }

        if (!$failedFail) {
            $valoresProcesado = array(
                'adminitracion_proc' => "1",
            );
        if (1 == $alldata[0]->secretaria_proc) {
            $valoresProcesado["estatus"] = "cerrado";
        }
            if ($iniciarProceso = $this->Administracion_model->setSignAdminValues($retiroData['idRetiro'], $valoresProcesado)) {
                $valoresRetiro = array(
                    'anio_retiro' => $retiroData["anioRetiro"],
                    'anualidades' => $retiroData["admin_anualidades"],
                    'sin_efecto_contable' => 0,
                    'pago_colegiaturas' => $retiroData["admin_forma_de_pago"],
                    'calculo_proporcional' => 1,
                    'formulario_devolucion' => 1,
                    'nota_credito' => 1,
                    'egreso_devolucion' => 1,
                    'rebaja_sistema' => 0,
                    'numero_egreso_devolucion' => $retiroData["admin_numero_egreso_devolucion"],
                    'fecha_egreso_devolucion' => $retiroData["admin_fecha_egreso_devolucion"],
                    'observaciones' => $retiroData["observaciones_anual_anticipado"],
                    'id_retiro' => $retiroData['idRetiro'],
                );
                if ($gestionarRetiro = $this->Administracion_model->insertRetirosAdminWti($valoresRetiro)) {
                    $datosCalculoProporcional = array(
                        'nombre' => "Administración - Cálculo proporcional",
                        'url' => $updCalculoProporcional,
                        'tipo' => null,
                        'fk_retiro' => $retiroData['idRetiro'],
                    );
                    $set1 = $this->Operations_model->addAttached($datosCalculoProporcional);
                    $datosFileFormulariodedevolucion = array(
                        'nombre' => "Administración - Formulario de devolución",
                        'url' => $updFormulariodedevolucion,
                        'tipo' => null,
                        'fk_retiro' => $retiroData['idRetiro'],
                    );
                    $set2 = $this->Operations_model->addAttached($datosFileFormulariodedevolucion);
                    $datosNotadeCredito = array(
                        'nombre' => "Administración - Nota de Crédito",
                        'url' => $updfileNotadeCredito,
                        'tipo' => null,
                        'fk_retiro' => $retiroData['idRetiro'],
                    );
                    $set3 = $this->Operations_model->addAttached($datosNotadeCredito);

                    if ($set1 && $set2 && $set3) {
                        if ($this->wti_notifications->wti_log_retiro("PROCESADO POR ADMINISTRACIÓN", $staffData[0]->email, $retiroData['idRetiro'])) {
                            if (ENVIRONMENT == 'development') {
                                $this->wti_log->log('SUCCESS - ::proximo_emitido_anticipado - Proceso finalizado con éxito');
                            }
                            if ($valoresProcesado["estatus"] == "cerrado") {

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
                                        'nombre' => "Administración - Certificado de traslado de establecimiento",
                                        'url' => $updfileCertificado,
                                        'tipo' => null,
                                        'fk_retiro' => $alldata[0]->id,
                                    );
                                    $set4 = $this->Operations_model->addAttached($datosCertificadoTraslado);

                                    if ($set4) {

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
                $this->wti_log->log('ERROR - con alguno de los archivos ::proximo_emitido_anticipado -');
            }
            echo "failed";
        }
    }
    #######################################################################################################################
    # END - P R O X I M O   E M I T I D O  A N T I C I P A D O
    #######################################################################################################################

    #######################################################################################################################
    # START - P R O X I M O   E M I T I D O   M E N S U A L
    #######################################################################################################################
    public function proximo_emitido_mensual()
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
        $updCalculoProporcional = "";
        $updRebajaEnSistema = "";

        //FILE PROCESSED
        //fileCalculoProporcional
        if (isset($_FILES['fileCalculoProporcional'])) {
            $fileCalculoProporcional = $_FILES['fileCalculoProporcional'];
            //fileCalculoProporcional
            $updCalculoProporcional = json_decode($this->wti_subir_archivos->subir_a_bucket($fileCalculoProporcional));
            if (!$updCalculoProporcional->ok) {
                if (ENVIRONMENT == 'development') {
                    $this->wti_log->log('ERROR - Cargando ::proximo_emitido_mensual- fileCalculoProporcional');
                }
                $failedFail = true;
            } else {
                $updCalculoProporcional = $updCalculoProporcional->url;
            }
        }

//fileFormulariodedevolucion
        if (isset($_FILES['fileRebajaEnSistema'])) {
            $fileRebajaEnSistema = $_FILES['fileRebajaEnSistema'];
//fileFormulariodedevolucion
            $updRebajaEnSistema = json_decode($this->wti_subir_archivos->subir_a_bucket($fileRebajaEnSistema));
            if (!$updRebajaEnSistema->ok) {
                if (ENVIRONMENT == 'development') {
                    $this->wti_log->log('ERROR - Cargando ::proximo_emitido_mensual - fileFormulariodedevolucion');
                }
                $failedFail = true;
            } else {
                $updRebajaEnSistema = $updRebajaEnSistema->url;
            }
        }

        if (!$failedFail) {
            $valoresProcesado = array(
                'adminitracion_proc' => "1",
            );
        if (1 == $alldata[0]->secretaria_proc) {
            $valoresProcesado["estatus"] = "cerrado";
        }
            if ($iniciarProceso = $this->Administracion_model->setSignAdminValues($retiroData['idRetiro'], $valoresProcesado)) {
                $valoresRetiro = array(
                    'anio_retiro' => $retiroData["anioRetiro"],
                    'anualidades' => $retiroData["admin_anualidades"],
                    'sin_efecto_contable' => 0,
                    'pago_colegiaturas' => $retiroData["admin_forma_de_pago"],
                    'calculo_proporcional' => 1,
                    'formulario_devolucion' => 0,
                    'nota_credito' => 0,
                    'egreso_devolucion' => 0,
                    'rebaja_sistema' => 1,
                    'id_retiro' => $retiroData['idRetiro'],
                );
                if ($gestionarRetiro = $this->Administracion_model->insertRetirosAdminWti($valoresRetiro)) {
                    $datosCalculoProporcional = array(
                        'nombre' => "Administración - Cálculo proporcional",
                        'url' => $updCalculoProporcional,
                        'tipo' => null,
                        'fk_retiro' => $retiroData['idRetiro'],
                    );
                    $set1 = $this->Operations_model->addAttached($datosCalculoProporcional);
                    $datosFileRebajaEnSistema = array(
                        'nombre' => "Administración - Rebaja en Sistema",
                        'url' => $updRebajaEnSistema,
                        'tipo' => null,
                        'fk_retiro' => $retiroData['idRetiro'],
                    );
                    $set2 = $this->Operations_model->addAttached($datosFileRebajaEnSistema);

                    if ($set1 && $set2) {
                        if ($this->wti_notifications->wti_log_retiro("PROCESADO POR ADMINISTRACIÓN", $staffData[0]->email, $retiroData['idRetiro'])) {
                            if (ENVIRONMENT == 'development') {
                                $this->wti_log->log('SUCCESS - ::proximo_emitido_mensual - Proceso finalizado con éxito');
                            }
                            if ($valoresProcesado["estatus"] == "cerrado") {

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
                                        'nombre' => "Administración - Certificado de traslado de establecimiento",
                                        'url' => $updfileCertificado,
                                        'tipo' => null,
                                        'fk_retiro' => $alldata[0]->id,
                                    );
                                    $set3 = $this->Operations_model->addAttached($datosCertificadoTraslado);

                                    if ($set3) {

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
    #######################################################################################################################
    # END - P R O X I M O   E M I T I D O   M E N S U A L
    #######################################################################################################################
    #######################################################################################################################
    # START - A C T U A L   M E N S U A L
    #######################################################################################################################
    public function actual_mensual()
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
        $updCalculoProporcional = "";
        $updRebajaEnSistema = "";

        //FILE PROCESSED
        //fileCalculoProporcional
        if (isset($_FILES['fileCalculoProporcional'])) {
            $fileCalculoProporcional = $_FILES['fileCalculoProporcional'];
            //fileCalculoProporcional
            $updCalculoProporcional = json_decode($this->wti_subir_archivos->subir_a_bucket($fileCalculoProporcional));
            if (!$updCalculoProporcional->ok) {
                if (ENVIRONMENT == 'development') {
                    $this->wti_log->log('ERROR - Cargando ::actual_mensual- fileCalculoProporcional');
                }
                $failedFail = true;
            } else {
                $updCalculoProporcional = $updCalculoProporcional->url;
            }
        }

//fileFormulariodedevolucion
        if (isset($_FILES['fileRebajaEnSistema'])) {
            $fileRebajaEnSistema = $_FILES['fileRebajaEnSistema'];
//fileFormulariodedevolucion
            $updRebajaEnSistema = json_decode($this->wti_subir_archivos->subir_a_bucket($fileRebajaEnSistema));
            if (!$updRebajaEnSistema->ok) {
                if (ENVIRONMENT == 'development') {
                    $this->wti_log->log('ERROR - Cargando ::actual_mensual - fileFormulariodedevolucion');
                }
                $failedFail = true;
            } else {
                $updRebajaEnSistema = $updRebajaEnSistema->url;
            }
        }

        if (!$failedFail) {
            $valoresProcesado = array(
                'adminitracion_proc' => "1",
            );
        if (1 == $alldata[0]->secretaria_proc) {
            $valoresProcesado["estatus"] = "cerrado";
        }
            if ($iniciarProceso = $this->Administracion_model->setSignAdminValues($retiroData['idRetiro'], $valoresProcesado)) {
                $valoresRetiro = array(
                    'anio_retiro' => $retiroData["anioRetiro"],
                    'sin_efecto_contable' => 0,
                    'pago_colegiaturas' => $retiroData["admin_forma_de_pago"],
                    'calculo_proporcional' => 1,
                    'formulario_devolucion' => 0,
                    'nota_credito' => 0,
                    'egreso_devolucion' => 0,
                    'rebaja_sistema' => 1,
                    'id_retiro' => $retiroData['idRetiro'],
                );
                if ($gestionarRetiro = $this->Administracion_model->insertRetirosAdminWti($valoresRetiro)) {
                    $datosCalculoProporcional = array(
                        'nombre' => "Administración - Cálculo proporcional",
                        'url' => $updCalculoProporcional,
                        'tipo' => null,
                        'fk_retiro' => $retiroData['idRetiro'],
                    );
                    $set1 = $this->Operations_model->addAttached($datosCalculoProporcional);
                    $datosFileRebajaEnSistema = array(
                        'nombre' => "Administración - Rebaja en Sistema",
                        'url' => $updRebajaEnSistema,
                        'tipo' => null,
                        'fk_retiro' => $retiroData['idRetiro'],
                    );
                    $set2 = $this->Operations_model->addAttached($datosFileRebajaEnSistema);

                    if ($set1 && $set2) {
                        if ($this->wti_notifications->wti_log_retiro("PROCESADO POR ADMINISTRACIÓN", $staffData[0]->email, $retiroData['idRetiro'])) {
                            if (ENVIRONMENT == 'development') {
                                $this->wti_log->log('SUCCESS - ::actual_mensual - Proceso finalizado con éxito');
                            }
                            if ($valoresProcesado["estatus"] == "cerrado") {


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
                                        'nombre' => "Administración - Certificado de traslado de establecimiento",
                                        'url' => $updfileCertificado,
                                        'tipo' => null,
                                        'fk_retiro' => $alldata[0]->id,
                                    );
                                    $set3 = $this->Operations_model->addAttached($datosCertificadoTraslado);

                                    if ($set3) {

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

        } else {
            if (ENVIRONMENT == 'development') {
                $this->wti_log->log('ERROR - con alguno de los archivos ::actual_mensual -');
            }
            echo "failed";
        }
    }
    #######################################################################################################################
    # END - A C T U A L   M E N S U A L
    #######################################################################################################################


    #######################################################################################################################
    # START - CAMBIO NÚMERO DE EGRESO
    #######################################################################################################################

    public function cambio_numero_egreso()
    {
        $post = $this->input->post();
        if ($this->Administracion_model->saveNumeroEgreso($post)){
            echo "success";
        }
        else {
            echo "failed";
        }
    }


    #######################################################################################################################
    # END - CAMBIO NÚMERO DE EGRESO
    #######################################################################################################################


    #######################################################################################################################
    # START -  E X T R A S 
    #######################################################################################################################


    public function eliminar_retiro()
    {
        $retiroData = $this->input->post();
        $id = $retiroData['id'];

        // secretaría académica table
        $this->db->where('id_retiro', $id);
        $this->db->delete('tblretiros_secretariaaca_wti');

        // eliminar persona retira
        $this->db->where('fk_retiro', $id);
        $this->db->delete('tblpersona_retira_wti');

        // eliminar mensajes segretaria
        $this->db->where('fk_retiro', $id);
        $this->db->delete('tblmensajes_segre_wti');

        // eliminar firmas
        $this->db->where('fk_retiro', $id);
        $this->db->delete('tblfirma_electronica_wti');

        // eliminar adjuntos
        $this->db->where('fk_retiro', $id);
        $this->db->delete('tbladjuntos_retiro_wti');

        // eliminar admin table
        $this->db->where('id_retiro', $id);
        $this->db->delete('tblretiros_admin_wti');

        // eliminar retiro
        $this->db->where('id', $id);
        $this->db->delete('tblretiros_wti');

        if ($this->db->affected_rows() > 0) {
            // El cambio se realizó correctamente
            return true;
        } else {
            // Hubo un error en la consulta o no se actualizó ninguna fila
            return false;
        }
    }

    public function cambiar_correo_apoderado()
    {
        $retiroData = $this->input->post();
        $email = $retiroData['email'];
        $id = $retiroData['id'];

        $this->db->set('correo', $email);
        $this->db->where('fk_retiro', $id);
        $this->db->update('tblpersona_retira_wti');

        if ($this->db->affected_rows() > 0) {
            // El cambio se realizó correctamente
            return true;
        } else {
            // Hubo un error en la consulta o no se actualizó ninguna fila
            return false;
        }
    }



    #######################################################################################################################
    # END -  E X T R A S 
    #######################################################################################################################
}
