<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Sample Controller
 */
class segretaria extends AdminController
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
        $this->CI->load->library('Wti_stepowner');
        $this->load->model('Operations_model');
        $this->load->model('Segretaria_model');
    }

    /**
     * Go to home page
     * @return view
     */

    public function index()
    {
        show_404('Página no encontrada');
    }

    public function enviar_mensaje()
    {
        //HEADERS
        $retiroData = $this->input->post();
        $alldata = $this->Operations_model->get_all_retirodata($retiroData['idRetiro']);


        //SEGRETARIA
        $id_staff = $this->session->userdata('staff_user_id');
        $staffData = $this->Operations_model->get_staff_data($id_staff);

        //RECTOR
        $rectorRequest = $this->Operations_model->get_rectores();
        $rector = $this->Operations_model->get_staff_data($rectorRequest[0]["staffid"]);

        //SECRETARIA
        $secretariaRequest = $this->Operations_model->get_secretaria_academica();
        $secretaria = $this->Operations_model->get_staff_data($secretariaRequest[0]["staffid"]);

        //ADMINISTRACION
        $administracionRequest = $this->Operations_model->get_administracion();
        $administracion = $this->Operations_model->get_staff_data($administracionRequest[0]["staffid"]);

        //DIRECTOR
        $directorRequest = $this->Operations_model->get_directores_area($retiroData['fkEscuela']);
        $director = $this->Operations_model->get_staff_data($directorRequest[0]["staffid"]);

        $clientId = $this->Operations_model->get_client_id($retiroData['idRetiro']);
        $retiroURI = "api_master/master/details/" . $retiroData['idRetiro'];


        //EJECUTOR ACTUAL
        $requestStaff = $this->wti_stepowner->assigned_to($alldata[0]->estatus, $alldata);
        $StaffUnique = array_unique($requestStaff);

        $data = array(
            'message' => $retiroData["segre_msg_scolastica"],
            'msg_date' => date('Y-m-d H:i:s'),
            'fk_retiro' => $retiroData['idRetiro'],
        );
        $savingMessage = $this->Segretaria_model->saveMessage($data);

        if ($savingMessage) {

            foreach ($StaffUnique as $staff) {

                $haProcesado;

                switch ($staff) {
                    case "Director":
                        $assigned_to = $director[0];
                        break;

                    case "Rector":
                        $assigned_to = $rector[0];
                        if (!$alldata[0]->firma_rector_1) {
                            $haProcesado = false;
                        }
                        if ($alldata[0]->colegio_paritario > 0 && !$alldata[0]->firma_rector_2) {
                            $haProcesado = false;
                        }
                        break;

                    case "Secretaria Academica":
                        $assigned_to = $secretaria[0];
                        if (!$alldata[0]->secretaria_proc) {
                            $haProcesado = false;
                        }
                        break;

                    case "Administracion":
                        $assigned_to = $administracion[0];
                        if (!$alldata[0]->adminitracion_proc) {
                            $haProcesado = false;
                        }
                        break;
                }

                if (!$haProcesado) {
                    // wti_log
                    $this->wti_mails_scuola->msgSegretaria($assigned_to->email, $retiroData['idRetiro'], $retiroData['segre_msg_scolastica'], $staffData[0]->email);
                    $this->wti_notifications->wti_staff_notification("RECORDATORIO SEGRETARIA", $id_staff, $clientId[0]->userid, $clientId[0]->company, $assigned_to->staffid, $retiroURI);

                    $this->wti_notifications->wti_log_retiro("MENSAJE ENVIADO POR SEGRETARIA SCOLASTICA", $assigned_to->email, $retiroData['idRetiro']);
                }

            }
            unset($assigned_to);
            echo "success";

        } else {
            echo "failed";
        }
    }


}
