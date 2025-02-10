<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Sample Controller
 */
class api_retiros_public extends ClientsController
{

    /**
     * Controler __construct function to initialize options
     */
    public function __construct()
    {
        parent::__construct();
        $this->CI = &get_instance();
        $this->load->model('Retiros_model');
        $this->load->model('Operations_model');
        $this->load->model('FirmaElectronica_model');
        $this->load->model('Segretaria_model');
        $this->load->model('Sec_academica_model');
        $this->CI->load->library('Wti_subir_archivos');
        $this->CI->load->library('Wti_notifications');
        $this->CI->load->library('Wti_mails_scuola');
        $this->CI->load->library('Wti_encrypt');
        $this->CI->load->library('Wti_helpers');
        $this->CI->load->library('Wti_stepowner');
    }

    public function ProbarRetiros()
    {
        $sqlQuery = "SELECT * FROM " . db_prefix() . "retiros_wti
                     LEFT JOIN " . db_prefix() . "clients ON " . db_prefix() . "clients.vat = " . db_prefix() . "retiros_wti.rut_estudiante";

        //PERFEX ADMIN
        if (staff_can('admin', 'retiros')) {
            $sqlQuery .= " WHERE retiros_wti.estatus != 'cerrado'";
        }

        $data = $this->db->query($sqlQuery);
        $temp = $data->result();

        echo json_encode($temp);
    }

    // public function ProbarCopiaEnRetiros()
    // {

    //     $alldata = $this->Operations_model->get_all_retirodata(169);

    //     $clientId = $this->Operations_model->get_client_id(169);
    //     $contacts = $this->Operations_model->get_contacts($clientId[0]->userid);

    //     $data = [
    //         'folio' => 163,
    //         'tipo' => $alldata[0]->tipo,
    //         'motivo' => $alldata[0]->motivo_retiro,
    //         'nombre_alumno' => $clientId[0]->company,
    //         'nombre_apoderado' => $contacts[0]->firstname . " " . $contacts[0]->lastname,
    //     ];
    //     if ($this->wti_mails_scuola->cierreRetiroMail('ibelitzeportafolio@gmail.com', $data, false)) {
    //         echo "success";
    //     }
    // }

    public function decryptHash()
    {
        $this->load->library('Wti_encrypt');
        $instance = new Wti_encrypt();
        $desencriptado = $instance->decrypt("a0NFamJLQ1duMU1BeHpLZlhpcFJmZz09");
        echo $desencriptado;
    }

    // public function probarCambioStatus()
    // {
    //     $this->Sec_academica_model->setNULLAporFirmar(181);
    // }

    /*public function ProbarDiasRetiro()
    {
        $rows_pendientes = $this->Operations_model->get_all_pendientes();

        foreach ($rows_pendientes as $row) {
            $clientId = $this->Operations_model->get_client_id($row->id);
            $contacts = $this->Operations_model->get_contacts($clientId[0]->userid);
            $retiroURI = "api_master/master/details/" . $row->id;

            $fechaDeInicio = $this->evaluarInicio($row->fecha_retiro);

            switch ($fechaDeInicio) {
                case 'waiting':
                    //NO ACTIONS
                    break;
                case '3':
                    // RATIFIQUE 1  ----  FALTAN TRES DÍAS ------
                    if ($this->wti_mails_scuola->daemon3Days($contacts[0]->firstname . " " . $contacts[0]->lastname, $contacts[0]->email, $row->id, $clientId[0]->company, $row, $this->wti_encrypt->encrypt($row->id))) {
                    }
                    break;
                case '2':
                    // RATIFIQUE 2  ----  FALTAN DOS DÍAS ------
                    if ($this->wti_mails_scuola->daemon2Days($contacts[0]->firstname . " " . $contacts[0]->lastname, $contacts[0]->email, $row->id, $clientId[0]->company, $row, $this->wti_encrypt->encrypt($row->id))) {
                    }
                    break;
                case '1':
                    // RATIFIQUE 3  ----  FALTAN TRES DÍAS ------
                    if ($this->wti_mails_scuola->daemon1Days($contacts[0]->firstname . " " . $contacts[0]->lastname, $contacts[0]->email, $row->id, $clientId[0]->company, $row, $this->wti_encrypt->encrypt($row->id))) {
                    }
                    break;
                case 'anular':
                    // CORRER ACCIONES DE ANULACIÓN
                    $doNullify = $this->Daemon_model->anulacionProcede($row->id);
                    if ($doNullify) {
                        if ($this->wti_mails_scuola->daemonAnulacion($contacts[0]->firstname . " " . $contacts[0]->lastname, $contacts[0]->email, $row->id, $clientId[0]->company, $row)) {
                            if ($this->wti_notifications->wti_log_retiro("CAMBIO DE ESTATUS A: ANULADO", "SISTEMA DE RETIROS - DUMMY", $row->id)) {
                            }
                        }
                    }
                    break;
            }

            unset($clientId);
            unset($contacts);
            unset($retiroURI);
        }

    }*/

    /*public function probarNullaOsta() 
    {
        $retiroData = $this->input->post();
        
        $retiroData = $this->Operations_model->get_all_retirodata(164);
        $vat = $retiroData[0]->rut_estudiante;
        
        $client = $this->CI->db->query("SELECT * FROM tblclients WHERE tblclients.vat = '{$vat}'");
        $clienteData = $client->result();

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

        // $curso = $this->wti_helpers->nulla_helper($retiroData[0]->curso);
        $nivel = $this->wti_helpers->devolverNivel($retiroData[0]->curso);
        $promocion = $this->wti_helpers->devolverPromocion($retiroData[0]->promocion_anticipada);

        $ultAsistencia = date_create(date($retiroData[0]->ultima_asistencia));
        $ultimoDia = date_format($ultAsistencia, "d-m-Y");

        // Certificado de traslado - poner aquí los datos reales
        $data1 = [
            'nombre' => $clienteData[0]->company,
            'ultimoDia' => $ultimoDia,
            'cursoCertf' => $curso,
            'nivelCertf' => $nivel,
            'numMatricula' => $retiroData[0]->matricula,
            'promocionCertf' => $promocion,
            'fechaEmsionCertf' => $retiroData[0]->fecha_ultima_actualizacion_estatus,
            'anioAcd' => "2023"
        ];

        // puse en un HELPER el html del certificado
        $this->load->helper('nulla');
        $certificado = nullaOsta((object) $data1, $logo, $firma);

        // Convertimos el contenido HTML en el PDF utilizando TCPDF
        $pdf->writeHTML($certificado, true, false, true, false, '');
        $complementName = date('YmdHis');

        // imprimimos
        $nombrePDF = "Certificado-" . $retiroData['idRetiro'] . "-" . $complementName;
        $temp_pdf_folder = dirname(__DIR__) . '/files/temp/';
        $fileObjectName = $temp_pdf_folder . $nombrePDF . ".pdf";

        $pdf->Output($temp_pdf_folder . $nombrePDF . '.pdf', 'F');

        $failedFail = false;

        $updfileCertificado = json_decode($this->wti_subir_archivos->tcpdf_a_bucket($fileObjectName));
        if (!$updfileCertificado->ok) {
            if (ENVIRONMENT == 'development') {
                $this->wti_log->log('ERROR - Cargando :: Nulla OSta- fileNullaOsta');
            }
            $failedFail = true;
        } else {
            $updfileCertificado = $updfileCertificado->url;
        }

        if (!$failedFail) {
            echo $updfileCertificado;
        }

    }*/


    /*public function probarCertificado() 
    {
        $retiroData = $this->input->post();
        
        $retiroData = $this->Operations_model->get_all_retirodata($retiroData['idRetiro']);
        $vat = $retiroData[0]->rut_estudiante;
        
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

        $curso = $this->wti_helpers->devolverCurso($retiroData[0]->curso);
        $nivel = $this->wti_helpers->devolverNivel($retiroData[0]->curso);
        $promocion = $this->wti_helpers->devolverPromocion($retiroData[0]->promocion_anticipada);

        $ultAsistencia = date_create(date($retiroData[0]->ultima_asistencia));
        $ultimoDia = date_format($ultAsistencia, "d-m-Y");

        // Certificado de traslado - poner aquí los datos reales
        $data1 = [
            'nombre' => $clienteData[0]->company,
            'ultimoDia' => $ultimoDia,
            'cursoCertf' => $curso,
            'nivelCertf' => $nivel,
            'numMatricula' => $retiroData[0]->matricula,
            'promocionCertf' => $promocion,
            'fechaEmsionCertf' => $retiroData[0]->fecha_ultima_actualizacion_estatus,
            'anioAcd' => "2023"
        ];

        // puse en un HELPER el html del certificado
        $this->load->helper('certificado');
        $certificado = certificado((object) $data1, $logo, $firma);

        // Convertimos el contenido HTML en el PDF utilizando TCPDF
        $pdf->writeHTML($certificado, true, false, true, false, '');
        $complementName = date('YmdHis');

        // imprimimos
        $nombrePDF = "Certificado-" . $retiroData['idRetiro'] . "-" . $complementName;
        $temp_pdf_folder = dirname(__DIR__) . '/files/temp/';
        $fileObjectName = $temp_pdf_folder . $nombrePDF . ".pdf";

        $pdf->Output($temp_pdf_folder . $nombrePDF . '.pdf', 'F');

        $failedFail = false;

        $updfileCertificado = json_decode($this->wti_subir_archivos->tcpdf_a_bucket($fileObjectName));
        if (!$updfileCertificado->ok) {
            if (ENVIRONMENT == 'development') {
                $this->wti_log->log('ERROR - Cargando :: Nulla OSta- fileNullaOsta');
            }
            $failedFail = true;
        } else {
            $updfileCertificado = $updfileCertificado->url;
        }

        if (!$failedFail) {
            echo $updfileCertificado;
        }

    }*/


    public function cambiarPromAnticipada()
    {
        if ('POST' === $_SERVER['REQUEST_METHOD'] && isset($_POST['id_retiro']) && isset($_POST['promocion_anticipada'])) {
            if ($this->CI) {
                $id = $_POST['id_retiro'];
                $promAntc = $_POST['promocion_anticipada'];

                if ($this->Retiros_model->cambiar_prom_anticipada($id, $promAntc)) {
                    echo "todo bien";
                } else {
                    echo false;
                }
            }
        }
    }

    public function cambiarColegioParitario()
    {
        if ('POST' === $_SERVER['REQUEST_METHOD'] && isset($_POST['id_retiro']) && isset($_POST['colegio_paritario'])) {
            if ($this->CI) {
                $id = $_POST['id_retiro'];
                $colegPar = $_POST['colegio_paritario'];

                if ($this->Retiros_model->cambiar_colegio_paritario($id, $colegPar)) {
                    echo "todo bien"; 
                } else {
                    echo false;
                }
            }
        }
    }

    public function cambiarFechaRetiro()
    {
        if ('POST' === $_SERVER['REQUEST_METHOD'] && isset($_POST['id_retiro']) && isset($_POST['fecha_retiro'])) {
            if ($this->CI) {
                $id = $_POST['id_retiro'];
                $fecha = $_POST['fecha_retiro'];
                $fechaNueva = date("Y-m-d H:i:s", strtotime($fecha));

                if ($this->Retiros_model->cambiar_fecha_retiro($id, $fechaNueva)) {
                    echo "todo bien"; 
                } else {
                    echo false;
                }
            }
        }
    }

    public function anularRetiro()
    {
        if ('POST' === $_SERVER['REQUEST_METHOD'] && isset($_POST['id_retiro'])) {
            if ($this->CI) {
                $id = $_POST['id_retiro'];

                if ($this->Retiros_model->anular_retiro($id)) {
                    echo "todo bien"; 
                } else {
                    echo false;
                }
            }
        }
    }


    // aquii
    public function correoManualConfirmacion()
    {

        if ('POST' === $_SERVER['REQUEST_METHOD'] && isset($_POST['id_retiro'])) {
            if ($this->CI) {
                $id = $_POST['id_retiro'];
                $cliente = $this->Operations_model->get_client_id($id);
                $apoderado = $this->Operations_model->get_solicitante($id);
                $retiro = $this->Operations_model->get_all_retirodata($id);

                $dataCliente = [
                    'company' => $cliente[0]->company,
                    'vat' => $cliente[0]->vat,
                    'country ' => 0,
                    'datecreated' => $cliente[0]->datecreated,
                    'active' => 1,
                ];

                $dataCliente2 = [
                    'alumno-nombre' => $cliente[0]->company,
                    'rut' => $cliente[0]->vat,
                    'alumno-curso ' => $retiro[0]->curso,
                    'datetimepicker1Input' => $retiro[0]->fecha_retiro,
                    'anoacademico' => $retiro[0]->año_academico,
                    'permanente-temporal' => $retiro[0]->tipo,
                ];

                $dataRetiro = [
                    'rut_estudiante' => $retiro[0]->rut_estudiante,
                    'matricula' => $retiro[0]->matricula,
                    'fecha_aviso' => $retiro[0]->fecha_aviso,
                    'fecha_retiro' => $retiro[0]->fecha_retiro,
                    'contador_fechas_establecidas' => 1,
                    'año_academico' => $retiro[0]->año_academico,
                    'tipo' => $retiro[0]->tipo,
                    'colegio_paritario' => $retiro[0]->colegio_paritario,
                    'curso' => $retiro[0]->curso,
                    'promocion_anticipada' => $retiro[0]->promocion_anticipada,
                    'motivo_retiro' => $retiro[0]->motivo_retiro,
                    'observaciones' => $retiro[0]->observaciones,
                    'fk_escuela' => $retiro[0]->fk_escuela,
                    'futuro' => 1,
                ];

                $dataApoderado = [
                    'es_apoderado' => $apoderado[0]->es_apoderado,
                    'nombre' => $apoderado[0]->nombre,
                    'correo' => $apoderado[0]->correo,
                    'telefono' => $apoderado[0]->telefono,
                    'rut' => $apoderado[0]->rut,
                    'fk_retiro' => $apoderado[0]->fk_retiro,
                ];

                $datosMail = array(
                    "dataCliente" => $dataCliente2,
                    "dataApoderado" => $dataApoderado,
                    "dataRetiro" => $dataRetiro,
                );

                if ($hashmail = $this->wti_encrypt->encrypt($retiro[0]->id)) {
                    if ($this->wti_mails_scuola->apoderadoFirma($datosMail, $hashmail)) {
                        echo "todo bien"; 
                    }
                }
            }
        }
    }

    // aquiii
    public function enviarCorreosCierre()
    {

        if ('POST' === $_SERVER['REQUEST_METHOD'] && isset($_POST['id_retiro'])) {
            if ($this->CI) {

                $id = $_POST['id_retiro'];
                $alldata = $this->Operations_model->get_all_retirodata($id);
                // $apoderado = $this->Operations_model->get_solicitante($id);
                $clientId = $this->Operations_model->get_client_id($id);
                $contacts = $this->Operations_model->get_contacts($clientId[0]->userid);

                $data = [
                    'folio' => $id,
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
    }


    public function correoManualFirmaApoderado()
    {
        if ('POST' === $_SERVER['REQUEST_METHOD'] && isset($_POST['id_retiro'])) {
            if ($this->CI) {

                $id = $_POST['id_retiro'];
                $cliente = $this->Operations_model->get_client_id($id);
                $apoderado = $this->Operations_model->get_solicitante($id);
                $retiro = $this->Operations_model->get_all_retirodata($id);

                $dataCliente = [
                    'company' => $cliente[0]->company,
                    'vat' => $cliente[0]->vat,
                    'country ' => 0,
                    'datecreated' => $cliente[0]->datecreated,
                    'active' => 1,
                ];

                $dataCliente2 = [
                    'alumno-nombre' => $cliente[0]->company,
                    'rut' => $cliente[0]->vat,
                    'alumno-curso ' => $retiro[0]->curso,
                    'datetimepicker1Input' => $retiro[0]->fecha_retiro,
                    'anoacademico' => $retiro[0]->año_academico,
                    'permanente-temporal' => $retiro[0]->tipo,
                ];

                $dataRetiro = [
                    'rut_estudiante' => $retiro[0]->rut_estudiante,
                    'matricula' => $retiro[0]->matricula,
                    'fecha_aviso' => $retiro[0]->fecha_aviso,
                    'fecha_retiro' => $retiro[0]->fecha_retiro,
                    'contador_fechas_establecidas' => 1,
                    'año_academico' => $retiro[0]->año_academico,
                    'tipo' => $retiro[0]->tipo,
                    'colegio_paritario' => $retiro[0]->colegio_paritario,
                    'curso' => $retiro[0]->curso,
                    'promocion_anticipada' => $retiro[0]->promocion_anticipada,
                    'motivo_retiro' => $retiro[0]->motivo_retiro,
                    'observaciones' => $retiro[0]->observaciones,
                    'fk_escuela' => $retiro[0]->fk_escuela,
                    'futuro' => 1,
                ];

                $dataApoderado = [
                    'es_apoderado' => $apoderado[0]->es_apoderado,
                    'nombre' => $apoderado[0]->nombre,
                    'correo' => $apoderado[0]->correo,
                    'telefono' => $apoderado[0]->telefono,
                    'rut' => $apoderado[0]->rut,
                    'fk_retiro' => $apoderado[0]->fk_retiro,
                ];

                $datosMail = array(
                    "dataCliente" => $dataCliente,
                    "dataApoderado" => $dataApoderado,
                    "dataRetiro" => $dataRetiro,
                );

                $datosMail2 = array(
                    "dataCliente" => $dataCliente2,
                    "dataApoderado" => $dataApoderado,
                    "dataRetiro" => $dataRetiro,
                );

                if ($hashmail = $this->wti_encrypt->encrypt($retiro[0]->id)) {
                    if ($this->wti_mails_scuola->daemonSoonDays($apoderado[0]->nombre, $apoderado[0]->correo, $retiro[0]->id, $cliente[0]->company, $retiro[0], $this->wti_encrypt->encrypt($retiro[0]->id))) {
                        echo "todo bien";
                    }
                }
            }
        }
    }


    public function createRetiro()
    {
        if ('POST' === $_SERVER['REQUEST_METHOD'] && isset($_POST['data'])) {

            if ($this->CI && $this->CI->db->table_exists('tblescuela_wti')) {

                $text = $_POST['data'];

                $str = str_replace('\\', '', $text);
                $arr = json_decode($str, true);
                $motivos = $arr['motivos'];
                if (isset($_POST['url']) && strlen($_POST['url']) > 0) {
                    $url = $_POST['url'];
                } else {
                    $url = '';
                }
                if (isset($_POST['url2']) && strlen($_POST['url2']) > 0) {
                    $url2 = $_POST['url2'];
                } else {
                    $url2 = '';
                }

                $hoy = strtotime("today");
                $paritario = false;
                $promAnticipada = false;
                $motivo = '';
                $nombreAdjunto = '';
                $verifyMotivos = count($motivos);
                if ($verifyMotivos > 0) {
                    if ($motivos['colegioParitario']) {
                        $paritario = true;
                    }

                    if ($motivos['promocionAnticipada']) {
                        $promAnticipada = true;
                    }

                    $motivo = $motivos['tipo'];

                    if (isset($motivos['nombreArchivo']) && strlen($motivos['nombreArchivo']) > 0) {
                        $nombreAdjunto = $motivos['nombreArchivo'];
                    }
                }

                $hoy = date_create(date("Y-m-d H:i:s"));
                $hoyFormat = date_format($hoy, "Y-m-d H:i:s");

                $timeStampRetiro = $arr['datetimepicker1Input'];

                $esApoderado = true;

                if ("tutor" === $arr["quien-retira"]) {
                    $esApoderado = false;
                }

                // REVISION DE RETIRO FUTURO
                $isFuture = 0;
                $pastvsfuture = $this->evaluarInicio($timeStampRetiro);
                if ("anular" === $pastvsfuture) {
                    // Pasado
                    $isFuture = 0;
                } elseif ("waiting" === $resultado) {
                    // > 3 dias habiles.
                    $isFuture = 1;
                } else {
                    // Dentro de los tres proximos días.
                    $isFuture = 1;
                }

                // convertir el rut en string
                // replace de posibles - con  nada ""
                $onlyNumbers = str_replace("-", "", strval($arr["rut"]));

                $dataRetiro = [
                    'rut_estudiante' => $onlyNumbers,
                    'matricula' => $arr['matricula'],
                    'fecha_aviso' => $hoyFormat,
                    'fecha_retiro' => $timeStampRetiro,
                    'contador_fechas_establecidas' => 1,
                    'año_academico' => $arr['anoacademico'],
                    'tipo' => $arr["permanente-temporal"],
                    'colegio_paritario' => $paritario,
                    'curso' => $arr['alumno-curso'],
                    'promocion_anticipada' => $promAnticipada,
                    'motivo_retiro' => $motivo,
                    'observaciones' => $arr["observaciones"],
                    'estatus' => "no firmado",
                    'fecha_ultima_actualizacion_estatus' => $hoyFormat,
                    'motivo_anulacion' => "sin anular",
                    'motivo_posible_reapertura' => "nada",
                    'fk_escuela' => $arr['area'],
                    'futuro' => $isFuture,
                ];

                // chequeamos que no exista ya un retiro con ese mismo RUT
                $retiroExiste = $this->Retiros_model->get_retiroRUT($onlyNumbers);

                if ($retiroExiste || count($retiroExiste) > 0) {
                    echo "ya existe retiro";
                } else {

                    $arr["rut"] = $onlyNumbers;
                    $sql = "SELECT * FROM tblclients WHERE tblclients.vat = '" . $arr["rut"] . "'";
                    $data = $this->db->query($sql);
                    $existe = $data->result();

                    // se inserta como cliente y se guarda el contacto (apoderado)
                    if (count($existe) <= 0) {

                        // guardando el cliente (alumno)
                        $dataCliente = [
                            'company' => $arr["alumno-nombre"],
                            'vat' => $arr["rut"],
                            'country ' => 0,
                            'datecreated' => $hoyFormat,
                            'active' => 1,
                            'default_currency' => 0,
                            'show_primary_contact' => 0,
                            'registration_confirmed' => 1,
                            'addedfrom' => 0,
                        ];
                        $this->CI->db->insert('tblclients', $dataCliente);
                        $insertedId = $this->CI->db->insert_id();

                        // guardando el contacto del cliente (apoderado)

                        // primero arreglamos el nombre
                        $cnt = str_word_count($arr['nombre-tutor-apoderado']);
                        $nombres = '';
                        $apellidos = '';
                        if (4 === $cnt) {
                            $piezas = explode(" ", $arr['nombre-tutor-apoderado']);
                            $nombres = $piezas[0] . ' ' . $piezas[1];
                            $apellidos = $piezas[2] . ' ' . $piezas[3];
                        } else if (2 === $cnt) {
                            $piezas = explode(" ", $arr['nombre-tutor-apoderado']);
                            $nombres = $piezas[0];
                            $apellidos = $piezas[1];
                        } else {
                            $piezas = explode(" ", $arr['nombre-tutor-apoderado']);
                            $nombres = $piezas[0];
                            $apellidos = array_pop($piezas);
                        }

                        $dataApoderado = [
                            'userid' => $insertedId,
                            'is_primary' => 1,
                            'firstname' => $nombres,
                            'lastname' => $apellidos,
                            'email' => $arr['mail-apoderado'],
                            'phonenumber' => $arr['telefono-apoderado'],
                            'datecreated' => $hoyFormat,
                            'active' => 1,
                            'invoice_emails' => 1,
                            'estimate_emails' => 1,
                            'credit_note_emails' => 1,
                            'contract_emails' => 1,
                            'task_emails' => 1,
                            'project_emails' => 1,
                            'ticket_emails' => 1,
                        ];
                        $this->CI->db->insert('tblcontacts', $dataApoderado);

                        if ($this->CI->db->insert('tblretiros_wti', $dataRetiro)) {

                            $lastId = $this->CI->db->insert_id();

                            if (strlen($nombreAdjunto) > 0 && strlen($url) > 0) {
                                $dataAdjunto = [
                                    'nombre' => $nombreAdjunto,
                                    'url' => $url,
                                    'fk_retiro' => $lastId,
                                ];

                                $this->CI->db->insert('tbladjuntos_retiro_wti', $dataAdjunto);
                            }
                            if (strlen($url2) > 0) {
                                $dataAdjunto2 = [
                                    'nombre' => 'Documento adicional',
                                    'url' => $url2,
                                    'fk_retiro' => $lastId,
                                ];

                                $this->CI->db->insert('tbladjuntos_retiro_wti', $dataAdjunto2);
                            }

                            $dataApoderado = [
                                'es_apoderado' => $esApoderado,
                                'nombre' => $arr['nombre-tutor-apoderado'],
                                'correo' => $arr['mail-apoderado'],
                                'telefono' => $arr['telefono-apoderado'],
                                'rut' => $arr['rut-apoderado'],
                                'fk_retiro' => $lastId,
                            ];

                            // todo ha salido bien, ahora guardamos al apoderado que hizo el retiro
                            if ($this->CI->db->insert('tblpersona_retira_wti', $dataApoderado)) {
                                if ($this->wti_notifications->wti_log_retiro("Solicitud de Retiro Generada", $dataApoderado["correo"], $dataApoderado["fk_retiro"])) {
                                    $datosMail = array(
                                        "dataCliente" => $arr,
                                        "dataApoderado" => $dataApoderado,
                                        "dataRetiro" => $dataRetiro,
                                    );
                                    if (isset($motivos['nombreArchivo']) && strlen($motivos['nombreArchivo']) > 0) {
                                        $datosMail["dataAdjunto"] = $dataAdjunto;
                                    }
                                    // aquii
                                    if ($hashmail = $this->wti_encrypt->encrypt($lastId)) {
                                        if ($this->wti_mails_scuola->apoderadoFirma($datosMail, $hashmail)) {
                                            echo "todo bien";
                                        }
                                    }

                                }

                            } else {
                                $error1 = $this->CI->db->error();
                                echo $error1;
                            }
                        } else {
                            $error = $this->CI->db->error();
                            echo $error;
                        }
                    } else {
                        // ya existe como cliente, solamente se crea el retiro
                        if ($this->CI->db->insert('tblretiros_wti', $dataRetiro)) {

                            $lastId = $this->CI->db->insert_id();

                            if (strlen($nombreAdjunto) > 0 && strlen($url) > 0) {
                                $dataAdjunto = [
                                    'nombre' => $nombreAdjunto,
                                    'url' => $url,
                                    'fk_retiro' => $lastId,
                                ];

                                $this->CI->db->insert('tbladjuntos_retiro_wti', $dataAdjunto);
                            }
                            if (strlen($url2) > 0) {
                                $dataAdjunto2 = [
                                    'nombre' => 'Documento adicional',
                                    'url' => $url2,
                                    'fk_retiro' => $lastId,
                                ];

                                $this->CI->db->insert('tbladjuntos_retiro_wti', $dataAdjunto2);
                            }

                            $dataApoderado = [
                                'es_apoderado' => $esApoderado,
                                'nombre' => $arr['nombre-tutor-apoderado'],
                                'correo' => $arr['mail-apoderado'],
                                'telefono' => $arr['telefono-apoderado'],
                                'rut' => $arr['rut-apoderado'],
                                'fk_retiro' => $lastId,
                            ];

                            // todo ha salido bien, ahora guardamos al apoderado que hizo el retiro
                            if ($this->CI->db->insert('tblpersona_retira_wti', $dataApoderado)) {
                                if ($this->wti_notifications->wti_log_retiro("Solicitud de Retiro Generada", $dataApoderado["correo"], $dataApoderado["fk_retiro"])) {
                                    $datosMail = array(
                                        "dataCliente" => $arr,
                                        "dataApoderado" => $dataApoderado,
                                        "dataRetiro" => $dataRetiro,
                                    );
                                    if (isset($motivos['nombreArchivo']) && strlen($motivos['nombreArchivo']) > 0) {
                                        $datosMail["dataAdjunto"] = $dataAdjunto;
                                    }
                                    // aquiii
                                    if ($hashmail = $this->wti_encrypt->encrypt($lastId)) {
                                        if ($this->wti_mails_scuola->apoderadoFirma($datosMail, $hashmail)) {
                                            echo "todo bien";
                                        }
                                    }
                                }

                            } else {
                                $error1 = $this->CI->db->error();
                                echo $error1;
                            }
                        } else {
                            $error = $this->CI->db->error();
                            echo $error;
                        }
                    }
                }
            }

        } else {
            echo "No post";
        }

    }

    public function encriptarData()
    {
        if ('POST' === $_SERVER['REQUEST_METHOD'] && isset($_POST['data'])) {

            $this->load->library('Wti_encrypt');
            $instance = new Wti_encrypt();
            $encriptado = $instance->encrypt($_POST['data']);
            echo $encriptado;
        } else {
            echo "no hay data";
        }
    }
    /*
    Esta es una función pública en el framework CodeIgniter que se encarga de agregar una firma electrónica a la tabla tblfirma_electronica_wti en la base de datos.

    La función recibe los siguientes parámetros mediante un POST:

    ip: la dirección IP del usuario que firma.
    hash: una cadena cifrada que contiene el RUT del estudiante.
    La función primero desencripta el RUT del estudiante usando la librería Wti_encrypt. Luego, crea un array $data que contiene los valores a insertar en la tabla tblfirma_electronica_wti. La fecha y hora actual se capturan usando date('Y-m-d H:i:s').

    Finalmente, la función llama al modelo FirmaElectronica_model para agregar la firma electrónica a la tabla, pasando el RUT desencriptado y el array $data como parámetros. Si la inserción se realiza correctamente, devuelve true. De lo contrario, devuelve false.

     */

    public function agregarFirmaConIP()
    {
        $alldata = $this->Operations_model->get_solicitante($_REQUEST['id']);
        $solicitud = $this->Operations_model->get_all_retirodata($_REQUEST['id']);
        $directores = $this->Operations_model->get_directores_area($solicitud[0]->fk_escuela); // ! DIRECTOR POR DIRECTORES
        // ? $staffData = $this->Operations_model->get_staff_data($director[0]["staff_id"]); // Eliminado
        $clientId = $this->Operations_model->get_client_id($_REQUEST['id']);
        $retiroURI = "api_master/master/details/" . $_REQUEST['id'];

        // DECRYPT HASH
        $this->load->library('Wti_encrypt');
        $instance = new Wti_encrypt();
        $desencriptado = $instance->decrypt($_REQUEST['hash']);

        if ($desencriptado) {
            // Construir el arreglo de datos para la base de datos
            $dataToInsert = array(
                'quien_firma' => 'apoderado',
                'name' => $alldata[0]->nombre . " " . $alldata[0]->correo,
                'fecha' => date('Y-m-d H:i:s'),
                'fk_retiro' => null,
                'fk_secretaria_traslado' => null,
                'IP' => $_REQUEST['ip'],
            );

            $mailsuccess = false;


            if ($this->FirmaElectronica_model->agregarFirmaApoderado($solicitud[0]->rut_estudiante, $dataToInsert)) {
                $mailsuccess = true;
                // aquiii
                $this->wti_mails_scuola->confirmacionAvisoDeRetiro($nombre, $correo, $folio, $nombreAlumno, $motivo);


                // ES UN RETIRO FUTURO
                if ($solicitud[0]->futuro > 0) {
                    $valoresFirma = array(
                        'firma_apoderado' => "1",
                    );
                    if ($this->FirmaElectronica_model->setSignValues2($_REQUEST['id'], $valoresFirma)) {
                        if (!empty($directores)) {
                            foreach ($directores as $director) {
                                $staffData = $this->Operations_model->get_staff_data($director["staffid"]);
                                // Verificar si se obtuvieron datos del personal
                                if (!empty($staffData)) {
                                    foreach ($staffData as $data) {
                                        if ($this->wti_mails_scuola->retiroApoderadoFirmaFuturo($data->email, $_REQUEST['id'])) {
                                            // si es futuro: no enviar notificaciones todavía
                                            // el apoderado debe ratificar primero
                                            if ($this->wti_notifications->wti_staff_notification("RETIRO PENDIENTE A FUTURO", $director->staffid, $clientId[0]->userid, $clientId[0]->company, $director["staffid"], $retiroURI)) {
                                                $mailsuccess = true;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        echo false;
                    }

                } else {
                    // NO ES FUTURO
                    $valoresFirma = array(
                        'firma_apoderado' => "1",
                    );
                    if ($this->FirmaElectronica_model->setSignValues($_REQUEST['id'], $valoresFirma)) {
                        $mailsuccess = true;
                        // ! [MULTIPLES MAILS] INICIO
                        if (!empty($directores)) {
                            foreach ($directores as $director) {
                                $staffData = $this->Operations_model->get_staff_data($director["staffid"]);
                                // Verificar si se obtuvieron datos del personal
                                if (!empty($staffData)) {
                                    foreach ($staffData as $data) {
                                        if ($this->wti_mails_scuola->retiroApoderadoFirma($data->email, $_REQUEST['id'])) {
                                            // el apoderado debe ratificar primero
                                            if ($this->wti_notifications->wti_staff_notification("RETIRO POR FIRMAR", $director->staffid, $clientId[0]->userid, $clientId[0]->company, $director["staffid"], $retiroURI)) {
                                                $mailsuccess = true;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        echo false;
                    }
                }

                if ($mailsuccess) {
                    echo true;
                } else {
                    echo false;
                }

            // no se guardó firma en BD
            } else {
                echo false;
            }

        } else {
            echo false;
        }

    }

    public function ratifica()
    {

        //DECRYPT HASH
        $this->load->library('Wti_encrypt');
        $instance = new Wti_encrypt();
        $desencriptado = $instance->decrypt($this->input->post('hash'));
        $IP = $this->input->post('ip');
        $tipoDec = $this->input->post('decision');
        $fechaNewRatifica = $this->input->post('fecha');

        $idSol = $this->Operations_model->get_all_retirodata($desencriptado);
        $apoderado = $this->Retiros_model->get_apoderadoID($idSol[0]->id);
        $alumno = $this->Operations_model->get_client_id($desencriptado);


        $directores = $this->Operations_model->get_directores_area($idSol[0]->fk_escuela); // ! FROM DIRECTOR TO DIRECTORES
        // ? $staffData = $this->Operations_model->get_staff_data($director[0]["staff_id"]);

        $retiroURI = "api_master/master/details/" . $idSol[0]->id;

        $dataRatifica;

        //Confirmar.
        if ("ratifica-retiro" == $tipoDec) {
            $dataRatifica = array(
                'ratifica_cambio' => 1,
                'estatus' => 'pendiente_ratificado',
                'futuro' => 0,
            );
        }

        //Cambio de fecha.
        if ("modifico-fecha" == $tipoDec) {

            // REVISION DE RETIRO FUTURO
            $isFuture = 0;
            $pastvsfuture = $this->evaluarInicio($fechaNewRatifica);
            if ("anular" === $pastvsfuture) {
                // Pasado
                $isFuture = 0;
            } elseif ("waiting" === $resultado) {
                // > 3 dias habiles.
                $isFuture = 1;
            } else {
                // Dentro de los tres proximos días.
                $isFuture = 1;
            }

            $dataRatifica = array(
                'ratifica_cambio' => 1,
                'futuro' => $isFuture,
                'estatus' => 'pendiente',
                'fecha_retiro' => $fechaNewRatifica,
            );
        }

        //Anular.
        if ("anular-retiro" == $tipoDec) {
            $dataRatifica = array(
                'estatus' => 'anulado',
                'motivo_anulacion' => 'Anulado por apoderado al momento de Ratificar',
            );
        }

        if ($setRatifica = $this->FirmaElectronica_model->setRatifica($idSol[0]->id, $dataRatifica)) {
            if ($this->wti_notifications->wti_log_retiro("PROCESO DE RATIFICACIÓN - " . $tipoDec, "SISTEMA", $idSol[0]->id)) {
                switch ($tipoDec) {
                    case "ratifica-retiro":
                        $this->wti_mails_scuola->ratificacionRatificaRetiro($apoderado[0]->nombre, $apoderado[0]->correo, $idSol[0]->id, $alumno[0]->company, $idSol[0]->motivo);
                        // ! [MULTIPLES MAILS] INICIO
                        $mailsuccess = false;
                        if (!empty($directores)) {
                            foreach ($directores as $director) {
                                $staffData = $this->Operations_model->get_staff_data($director["staffid"]);
                                if (!empty($staffData)) {
                                    foreach ($staffData as $data) {
                                        if ($this->wti_mails_scuola->retiroRatificado($data->email, $idSol[0]->id)) {
                                            if ($this->wti_notifications->wti_staff_notification("RETIRO RATIFICADO POR FIRMAR", $director["staffid"], $alumno[0]->userid, $alumno[0]->company, $director["staffid"], $retiroURI)) {
                                                $mailsuccess = true;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        if ($mailsuccess) {
                            echo "ok";
                        } else {
                            echo "error";
                        }
                        // ! [MULTIPLES MAILS] FIN
                        break;
                    case "modifico-fecha":
                        $this->wti_mails_scuola->ratificacionModificoFecha($apoderado[0]->nombre, $apoderado[0]->correo, $idSol[0]->id, $alumno[0]->company, $idSol[0]->motivo);
                        break;

                    case "anular-retiro":
                        $this->wti_mails_scuola->ratificacionAnularRetiro($apoderado[0]->nombre, $apoderado[0]->correo, $idSol[0]->id, $alumno[0]->company, $idSol[0]->motivo);
                        break;

                    default:
                        // Acción por defecto si $tipoDec no coincide con ninguno de los casos anteriores.
                        break;
                }

            }
        } else {
            echo "error";
        }
    }
    // aquii
    public function verDetalles()
    {
        if ('POST' === $_SERVER['REQUEST_METHOD'] && isset($_POST['data'])) {

            $hash = $_POST['data'];
            $this->load->library('Wti_encrypt');
            $instance = new Wti_encrypt();
            $desencriptado = $instance->decrypt($_POST['data']);

            if ($desencriptado) {
                $dataRetiro = $this->Operations_model->get_all_retirodata($desencriptado);
                $dataAlumno = $this->Operations_model->get_client_id($desencriptado);
                $dataApoderado = $this->Retiros_model->get_apoderadoID($dataRetiro[0]->id);
                $firmaApoderado = $this->Retiros_model->get_signs($dataRetiro[0]->id);

                if (count($firmaApoderado) > 0) {
                    $data = array('retiro' => $dataRetiro, 'cliente' => $dataAlumno, 'apoderado' => $dataApoderado, 'firma' => $firmaApoderado);
                    $jsonFinal = json_encode($data);
                    echo $jsonFinal;
                }
                else {
                    $data = array('retiro' => $dataRetiro, 'cliente' => $dataAlumno, 'apoderado' => $dataApoderado);
                    $jsonFinal = json_encode($data);
                    echo $jsonFinal;
                }

            } else {
                echo 'no existe';
            }

        } else {
            echo "no hay hash";
        }
    }

    /* -------------------------------------------------------------------- */
    /* ------- Para usar éste controlador hay que pasarle el pass: -------- */
    /* ------------------- wti-bucket-scuola-security --------------------- */
    /* ---------------------------- por POST ------------------------------ */
    /* -------------------------------------------------------------------- */
    public function subirArchivo()
    {
        $this->load->library('Wti_subir_archivos');
        if ('POST' === $_SERVER['REQUEST_METHOD'] && isset($_FILES['archivo']) && isset($_POST['pass'])) {
            if ("wti-bucket-scuola-security" === $_POST['pass']) {

                $instance = new Wti_subir_archivos();
                $subido = $instance->subir_a_bucket($_FILES['archivo']);
                if ($subido) {
                    $jsonDecoded = json_decode($subido);
                    echo $jsonDecoded->url;
                }
            } else {
                echo "la pass no llegó o está mal";
            }
        } else {
            echo "No hay file";
        }
    }

    public function verNullaOsta()
    {
        // GET DATA
        $sec_nacimiento = $_GET["nulla_fecha"];
        $sec_ciudad = $_GET["nulla_ciudad"];
        $sec_pais = $_GET["nulla_pais"];
        $sec_domicilio = $_GET["nulla_domicilio"];
        $sec_emision = $_GET["nulla_emision"];
        $sec_correlativo = $_GET["nulla_correlativo"];
        $sec_nombre = $_GET["nulla_nombre"];
        $sec_curso = $_GET["nulla_curso"];
        $sec_nivel = $_GET["nulla_nivel"];

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
/*
// Certificado de traslado - poner aquí los datos reales
$data2 = [
'nombreCertf' => "[NOMBRE COMPLETO ]",
'ultimoDia' => "[ÚLTIMO DÍA DE CLASES]",
'cursoCertf' => "[CURSO]",
'nivelCertf' => "[BÁSICA o MEDIA CIENTIFICO HUMANISTA]",
'numMatricula' => "[NÚMERO DE MATRICULA]",
'anioAcd' => "[AÑO ACADEMICO VIGENTE]",
'promocionCertf' => "[PROMOVIDO o NO PROMOVIDO]",
'fechaEmsionCertf' => "[FECHA DE EMISIÓN]",
'logo' => '<img src="' . base_url('uploads/company/2ded124a44f270240d200da5773c3c39.png') . '" style="width: 120px; height: 100px;" alt="Logo Scuola italiana">',
];

// el HTML - CERTIFICADO DE TRASLADO
$this->load->helper('certificado');
$nullaOsta = certificado((object) $data2, $logo);*/

        // creamos el QR
        $this->load->library('ciqrcode');
        /*----------------------------------------*/
        $params['data'] = 'LA DATA LA PONES AQUÍ';
        /*----------------------------------------*/
        $params['level'] = 'H';
        $params['size'] = 10;
        $params['savename'] = FCPATH . 'modules/api_master/files/temp/test.jpg';
        $this->ciqrcode->generate($params);

        // preparando la imagen
        $QR = '<img src="' . base_url('modules/api_master/files/temp/test.jpg') . '" style="width: 70px; height: 70px;" alt="QR retiro">';

        $logoPequenio = '<img src="' . base_url('uploads/company/2ded124a44f270240d200da5773c3c39.png') . '" style="width: 98px; height: 80px;" alt="Logo Scuola italiana">';

        // el HTML - DETALLES DE RETIRO
        $this->load->helper('detalles');
        $detalles = detalles($QR, $logoPequenio);

        // Convertimos el contenido HTML en el PDF utilizando TCPDF
        $pdf->writeHTML($nullaOsta, true, false, true, false, '');

        // imprimimos
        $nombrePDF = "detallesRetiro";
        $pdf->Output($nombrePDF . '.pdf', 'I');

        unlink($params['savename']);

    }

    // FUNCIÓN DE DETECCIÓN DE DÍAS RESTANTES

    // Función para verificar si un día es fin de semana.
    public function esFinDeSemana(DateTime $date)
    {
        $zonaHoraria = new DateTimeZone('America/Santiago');
        $fecha = new DateTime($date->format('Y-m-d'), $zonaHoraria);

        $weekDay = date('w', strtotime($fecha->format('Y-m-d')));

        if (0 == $weekDay || 6 == $weekDay) {
            // 0: Domingo, 6: Sábado.
            return true;
        } else {
            return false;
        }
    }

    public function evaluarInicio($date)
    {
        $year = date('Y');
        // Obtener la lista de feriados del JSON
        $feriados = json_decode(file_get_contents('https://apis.digital.gob.cl/fl/feriados/' . $year), true);

        // Calcular la cantidad de días faltantes
        $zonaHoraria = new DateTimeZone('America/Santiago');
        $fechaActual = new DateTime('now', $zonaHoraria);
        $fechaEvento = new DateTime($date, $zonaHoraria);
        if ($fechaActual == $fechaEvento) {
            return 0;
        }

        // Calcular la diferencia en días

        // 15-04-24   -   21-06-24
        //    ==     67  (menos fines de semana) = 67 - 18 = (49 días para el retiro futuro)
        $diferencia = $fechaEvento->getTimestamp() - $fechaActual->getTimestamp();
        $diasFaltantes = ceil($diferencia / 86400); // 86400 es la cantidad de segundos en un día
        $diasRestantes = $diasFaltantes;

        /* var_dump($zonaHoraria);
        echo "<hr>";
        var_dump($fechaActual);
        echo "<hr>";
        var_dump($fechaEvento);
        echo "<hr>";
        var_dump($diasFaltantes);
        echo "<hr>";
        die("---");*/

        if ($diasFaltantes < 1) {
            // ANULAR
            return "anular";
        } else {

            // Restar los días feriados y fines de semana

            for ($i = 0; $i < abs($diasFaltantes); $i++) {
                $fecha = $fechaActual->add(new DateInterval('P1D'))->format('Y-m-d');
                $diaSemana = date('w', strtotime($fecha));
                $esFeriado = false;
                foreach ($feriados as $feriado) {
                    if ($feriado['fecha'] == $fecha) {
                        $esFeriado = true;
                        break;
                    }
                }
                if (0 == $diaSemana || 6 == $diaSemana || $esFeriado) {
                    $diasRestantes--;
                }
            }

            if (3 < $diasRestantes) {
                return "waiting";
            } else {
                if (3 == $diasRestantes) {
                    return 3;
                } elseif (2 == $diasRestantes) {
                    return 2;
                } elseif (1 == $diasRestantes) {
                    return 1;
                }
            }

        }

    }

}