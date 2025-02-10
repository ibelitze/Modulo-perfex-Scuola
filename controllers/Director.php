<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Sample Controller
 */
class director extends AdminController
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
        $this->load->model('Operations_model');
        $this->load->model('Director_model');
        $this->load->model('FirmaElectronica_model');

    }

    /**
     * Go to home page
     * @return view
     */

    public function index()
    {
        show_404('Página no encontrada');
    }

    public function firmaDirector()
    {
        //HEADERS
        $retiroData = $this->input->post();
        $alldata = $this->Operations_model->get_all_retirodata($retiroData['idRetiro']);
        $id_staff = $this->session->userdata('staff_user_id');
        $rectores = $this->Operations_model->get_rectores(); // ! FROM $rectorRequest TO $rectores
        // ?$rector = $this->Operations_model->get_staff_data($rectorRequest[0]["staff_id"]);
        $staffData = $this->Operations_model->get_staff_data($id_staff);
        $clientId = $this->Operations_model->get_client_id($retiroData['idRetiro']);
        $retiroURI = "api_master/master/details/" . $retiroData['idRetiro'];

        //OPERATIONS
        $date = $retiroData['ultimaAsistencia'];
        $ultimaAsistencia = date("Y-m-d H:i:s", strtotime($date));
        $greatest_date = max($ultimaAsistencia, $alldata[0]->fecha_retiro);

        $valoresFirma = array(
            'ultima_asistencia' => $greatest_date,
            'authpromoanti' => $retiroData['authpromo'],
            'firma_director' => "1",
        );
        if ($iniciarProceso = $this->Director_model->setSignValues($retiroData['idRetiro'], $valoresFirma)) {
            $data = array(
                'quien_firma' => 'director',
                'name' => $staffData[0]->firstname . " " . $staffData[0]->lastname,
                'fecha' => date('Y-m-d H:i:s'),
                'fk_retiro' => $retiroData['idRetiro'],
                'fk_secretaria_traslado' => null,
                'IP' => $this->input->ip_address(),
            );
            $firmarRetiro = $this->FirmaElectronica_model->agregarFirma($retiroData['idRetiro'], $data);
            if ($firmarRetiro) {
                if ($this->wti_notifications->wti_log_retiro("FIRMADO POR DIRECTOR", $staffData[0]->email, $retiroData['idRetiro'])) {
                    // ! [MULTIPLES MAILS] INICIO
                    $mailsuccess = false;
                    if (!empty($rectores)) {
                        foreach ($rectores as $rector) {
                            $rectordata = $this->Operations_model->get_staff_data($rector["staffid"]);
                            if (!empty($rectordata)) {
                                foreach ($rectordata as $data) {
                                    if ($this->wti_mails_scuola->retiroFirmadoPorDirector($data->email, $retiroData['idRetiro'])) {
                                        if ($this->wti_notifications->wti_staff_notification("RETIRO POR FIRMAR", $id_staff, $clientId[0]->userid, $clientId[0]->company, $rector["staffid"], $retiroURI)) {
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

    public function directorProcede()
    {
        $retiroData = $this->input->post();
        $id_staff = $this->session->userdata('staff_user_id');
        $directores = $this->Operations_model->get_directores_area($retiroData['fkEscuela']); // ! FROM directorAreaRequest TO directores
        // ? $directorArea = $this->Operations_model->get_staff_data($directorAreaRequest[0]["staff_id"]);
        $staffData = $this->Operations_model->get_staff_data($id_staff);
        $clientId = $this->Operations_model->get_client_id($retiroData['idRetiro']);
        $retiroURI = "api_master/master/details/" . $retiroData['idRetiro'];

        if (1 == $retiroData['procede']) {
            //Procede
            $iniciarProceso = $this->Director_model->retiroProcede($retiroData['idRetiro'], $retiroData['observaciones']);

            if ($iniciarProceso) {
                if ($this->wti_notifications->wti_log_retiro("CAMBIO DE ESTATUS A: EN PROCESO", $staffData[0]->email, $retiroData['idRetiro'])) {
                    return true;
                }
            } else {
                return false;
            }

        } else {
            //No procede
            $directorProcedMotivoMail = "";
            if (0 == $retiroData['directorNoProcede']) {
                $preAnularProceso = $this->Director_model->preAnularProceso($retiroData['idRetiro'], $retiroData['noProcedeOtros']);
                $directorProcedMotivoMail = $retiroData['noProcedeOtros'];
            } elseif ($retiroData['directorNoProcede']) {
                $preAnularProceso = $this->Director_model->preAnularProceso($retiroData['idRetiro'], $retiroData['directorNoProcede']);
                $directorProcedMotivoMail = $retiroData['directorNoProcede'];
            } else {
                die("ERROR: Motivo no insertado");
            }

            if ($preAnularProceso) {
                if ($this->wti_notifications->wti_log_retiro("CAMBIO DE ESTATUS A: ANULADO EN REVISION", $staffData[0]->email, $retiroData['idRetiro'])) {
                    // ! [MULTIPLES MAILS] INICIO
                    $mailsuccess = false;
                    if ($this->wti_notifications->wti_staff_notification("ANULACIÓN POR REVISAR", $id_staff, $clientId[0]->userid, $clientId[0]->company, $id_staff, $retiroURI)) {
                        if (!empty($directores)) {
                            foreach ($directores as $director) {
                                $directorArea = $this->Operations_model->get_staff_data($director["staffid"]);
                                if (!empty($directorArea)) {
                                    foreach ($directorArea as $data) {
                                        if ($this->wti_mails_scuola->retiroAnuladoEnRevision($data->email, $retiroData['idRetiro'], $directorProcedMotivoMail)) {
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

    public function directorAnulacionProcede()
    {
        $retiroData = $this->input->post();
        $id_staff = $this->session->userdata('staff_user_id');
        $alldata = $this->Operations_model->get_all_retirodata($retiroData['idRetiro']);
        $SecretariasAcademicas = $this->Operations_model->get_secretaria_academica(); // ! FROM SecretariaAcademicaRequest TO  SecretariasAcademicas
        // ? $SecretariaArea = $this->Operations_model->get_staff_data($SecretariaAcademicaRequest[0]["staff_id"]);
        $staffData = $this->Operations_model->get_staff_data($id_staff);
        $clientId = $this->Operations_model->get_client_id($retiroData['idRetiro']);
        $contacts = $this->Operations_model->get_contacts($clientId[0]->userid);
        $retiroURI = "api_master/master/details/" . $retiroData['idRetiro'];

        if (0 == $retiroData['procede']) {
            //No Procede
            $iniciarProceso = $this->Director_model->anulacionNoProcede($retiroData['idRetiro'], $retiroData['directorNoProcede']);

            if ($iniciarProceso) {
                // ! [MULTIPLES MAILS] INICIO
                $mailsuccess = false;
                if (!empty($SecretariasAcademicas)) {
                    if ($this->wti_notifications->wti_log_retiro("CAMBIO DE ESTATUS A: POSIBLE REAPERTURA", $staffData[0]->email, $retiroData['idRetiro'])) {
                        foreach ($SecretariasAcademicas as $SecretariaAcademica) {
                            $SecretariaArea = $this->Operations_model->get_staff_data($SecretariaAcademica["staffid"]);
                            if (!empty($SecretariaArea)) {
                                foreach ($SecretariaArea as $data) {
                                    if ($this->wti_mails_scuola->retiroPosibleReaperturaSecretaria($data->email, $retiroData['idRetiro'], $retiroData['directorNoProcede'])) {
                                        if ($this->wti_notifications->wti_staff_notification("ANULACIÓN POR REVISAR", $id_staff, $clientId[0]->userid, $clientId[0]->company, $SecretariaArea["staffid"], $retiroURI)) {
                                            $mailsuccess = true;
                                        }
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
            } else {
                return false;
            }

        } else {
            $directorProcedMotivoMail = "";
            $preAnularProceso = $this->Director_model->anulacionProcede($retiroData['idRetiro']);

            // $directorProcedMotivoMail = $retiroData['directorNoProcede'];

            $directorProcedMotivoMail = $alldata[0]->motivo_anulacion;

            if ($preAnularProceso) {
                if ($this->wti_notifications->wti_log_retiro("CAMBIO DE ESTATUS A: ANULADO", $staffData[0]->email, $retiroData['idRetiro'])) {
                    if ($this->wti_mails_scuola->confirmacionAnulacion($contacts[0]->firstname . " " . $contacts[0]->lastname, $contacts[0]->email, $retiroData['idRetiro'], $clientId[0]->company, $directorProcedMotivoMail)) {
                        return true;
                    }
                }
            } else {
                return false;
            }
        }
    }

}