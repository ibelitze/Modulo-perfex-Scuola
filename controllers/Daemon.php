<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Sample Controller
 */
class Daemon extends ClientsController
{

    /**
     * Controler __construct function to initialize options
     */

    public function __construct()
    {
        parent::__construct();
        $this->CI = &get_instance();
        $this->CI->load->library('session');
        $this->CI->load->library('Wti_encrypt');
        $this->CI->load->library('Wti_notifications');
        $this->CI->load->library('Wti_mails_scuola');
        $this->CI->load->library('Wti_log');
        $this->load->model('Operations_model');
        $this->load->model('Daemon_model');

    }

    /**
     * Go to home page
     * @return view
     */

    public function index()
    {
        show_404('Página no encontrada');
    }

    public function runactions()
    {
        //HEADERS
        $rows_pendientes = $this->Operations_model->get_all_pendientes2();

        // -- P R O C E S O    A ----------------------------------------------------------
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
                case '0':
                    $hacerPendiente = $this->Daemon_model->pendienteProcede($row->id);
                    if ($hacerPendiente) {
                        // enviar las notificaciones e emails correspondientes
                        if ($this->wti_notifications->wti_log_retiro("CAMBIO DE ESTATUS A: ABIERTO", "SISTEMA DE RETIROS - DUMMY", $row->id)) {

                            // buscar acá el email del director
                            $directores = $this->Operations_model->get_directores_area($row->fk_escuela);

                            if (!empty($directores)) {

                                // iterando para conseguir todos los directores
                                // y enviarles mail a todos
                                foreach ($directores as $director) {
                                    $staffData = $this->Operations_model->get_staff_data($director["staffid"]);
                                    // Verificar si se obtuvieron datos del personal
                                    if (!empty($staffData)) {

                                        foreach ($staffData as $data) {
                                            $correoAdirector = $this->wti_mails_scuola->retiroRatificadoEnProcesoDirector($data->email, $row->id);
                                        }
                                    }
                                }
                            }
                        }
                    }
                    break;

            }

            unset($clientId);
            unset($contacts);
            unset($retiroURI);
        }

        // -- P R O C E S O    B ----------------------------------------------------------

        if (ENVIRONMENT == 'development') {
            $this->wti_log->log('SUCCESS - ::Running log from CRON');
        }

    }

    public function actualizar_fecha()
    {
        $this->Daemon_model->actualizar_fecha_ultima_actualizacion_estatus();
        echo 'Fecha actualizada correctamente';
    }

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

    public function evaluarInicio($date, $tipo)
    {
        $year = date('Y');
        // Obtener la lista de feriados del JSON
        $feriados = json_decode(file_get_contents('https://apis.digital.gob.cl/fl/feriados/' . $year), true);

        // Calcular la cantidad de días faltantes
        $zonaHoraria = new DateTimeZone('America/Santiago');
        $fechaActual = new DateTime('now', $zonaHoraria);
        $fechaEvento = new DateTime($date, $zonaHoraria);

        // aquii
        if ($fechaActual == $fechaEvento && $tipo == "pendiente_ratificado") {
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

    public function anuladoDaemon()
    {

    }
}
