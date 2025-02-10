<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Wti_mails_scuola extends AdminController
{

    protected $CI;

    public function __construct($params)
    {
        $CI = &get_instance();
        $CI->load->model('Retiros_model');
    }
    // #############################################################################################
    // DAEMON - START
    // #############################################################################################
    public function daemonAnulacion($nombre, $correo, $folio, $nombreAlumno, $retiroData)
    {
        $text = "<p>Estimado(a) {$nombre}</p>";
        $text .= "<p>Le informamos que, dado que no hemos recibido respuesta ni ratificación al Aviso de retiro Folio Nro. {$folio} por su hijo {$nombreAlumno}, se ha procedido a anular dicho caso. Si usted aún requiere formalizar este retiro de nuestra institución, lo invitamos a realizar nuevamente el proceso directamente en nuestro sitio web.</p>";
        $text .= "<p>Atentamente,</p><p>Scuola Italiana Vittorio Montiglio</p>";
        if ($this->sent_smtp_wti("ANULADO - SOLICITUD DE RETIRO DE ALUMNO Nro. " . $folio, $correo, $text, $folio, false)) {
            return true;
        } else {
            return false;
        }
    }

    public function daemonSoonDays($nombre, $correo, $folio, $nombreAlumno, $retiroData, $hashrut)
    {
        $text = "<p>Estimado(a) {$nombre}</p>";
        $text .= "<p>Con fecha {$retiroData->fecha_aviso} usted nos informó que con fecha {$retiroData->fecha_retiro} su hijo {$nombreAlumno} será retirado de nuestra institución. <b>Dado que esa fecha se cumple muy pronto</b>, necesitamos que nos indique si:</p>";
        $text .= "<ul>";
        $text .= "<li>Ratifica retiro y fecha.</li>";
        $text .= "<li>Ratifica retiro, pero necesita modificar la fecha (esta acción sólo la puede realizar una vez).</li>";
        $text .= "<li>Anula el aviso de retiro.</li>";
        $text .= "</ul>";
        $text .= "<p>Para responder la opción elegida por usted, favor pinche <a href='https://www.scuola.cl/sivm/detalles-solicitud/?ratifica={$hashrut}'>Aquí</a></p>";
        $text .= "<p>Atentamente,</p><p>Scuola Italiana Vittorio Montiglio</p>";
        if ($this->sent_smtp_wti("FAVOR RATIFICAR O ANULAR SOLICITUD DE RETIRO DE ALUMNO Nro. " . $folio, $correo, $text, $folio, false)) {
            return true;
        } else {
            return false;
        }
    }

    public function daemon3Days($nombre, $correo, $folio, $nombreAlumno, $retiroData, $hashrut)
    {
        $text = "<p>Estimado(a) {$nombre}</p>";
        $text .= "<p>Con fecha {$retiroData->fecha_aviso} usted nos informó que con fecha {$retiroData->fecha_retiro} su hijo {$nombreAlumno} será retirado de nuestra institución. <b>Dado que esa fecha se cumple dentro de tres (03) días</b>, necesitamos que nos indique si:</p>";
        $text .= "<ul>";
        $text .= "<li>Ratifica retiro y fecha.</li>";
        $text .= "<li>Ratifica retiro, pero necesita modificar la fecha (esta acción sólo la puede realizar una vez).</li>";
        $text .= "<li>Anula el aviso de retiro.</li>";
        $text .= "</ul>";
        $text .= "<p>Para responder la opción elegida por usted, favor pinche <a href='https://www.scuola.cl/sivm/detalles-solicitud/?ratifica={$hashrut}'>Aquí</a></p>";
        $text .= "<p>Atentamente,</p><p>Scuola Italiana Vittorio Montiglio</p>";
        if ($this->sent_smtp_wti("FAVOR RATIFICAR O ANULAR SOLICITUD DE RETIRO DE ALUMNO Nro. " . $folio, $correo, $text, $folio, false)) {
            return true;
        } else {
            return false;
        }
    }

    public function daemon2Days($nombre, $correo, $folio, $nombreAlumno, $retiroData, $hashrut)
    {
        $text = "<p>Estimado(a) {$nombre}</p>";
        $text .= "<p>Nuevamente tomamos contacto con usted, ya que con fecha {$retiroData->fecha_aviso} usted nos informó que con fecha {$retiroData->fecha_retiro} su hijo {$nombreAlumno} será retirado de nuestra institución. <b>Dado que esa fecha se cumple dentro de dos (02) días</b>, necesitamos que nos indique si:</p>";
        $text .= "<ul>";
        $text .= "<li>Ratifica retiro y fecha.</li>";
        $text .= "<li>Ratifica retiro, pero necesita modificar la fecha (esta acción sólo la puede realizar una vez).</li>";
        $text .= "<li>Anula el aviso de retiro.</li>";
        $text .= "</ul>";
        $text .= "<p>Para responder la opción elegida por usted, favor pinche <a href='https://www.scuola.cl/sivm/detalles-solicitud/?ratifica={$hashrut}'>Aquí</a></p>";
        $text .= "<p>Atentamente,</p><p>Scuola Italiana Vittorio Montiglio</p>";
        if ($this->sent_smtp_wti("2DO AVISO - FAVOR RATIFICAR O ANULAR SOLICITUD DE RETIRO DE ALUMNO Nro. " . $folio, $correo, $text, $folio, false)) {
            return true;
        } else {
            return false;
        }
    }

    public function daemon1Days($nombre, $correo, $folio, $nombreAlumno, $retiroData, $hashrut)
    {
        $text = "<p>Estimado(a) {$nombre}</p>";
        $text .= "<p>Nuevamente tomamos contacto con usted, ya que con fecha {$retiroData->fecha_aviso} usted nos informó que con fecha {$retiroData->fecha_retiro} su hijo {$nombreAlumno} será retirado de nuestra institución. <b>Dado que esa fecha se cumple dentro de un (01) día</b>, necesitamos que nos indique si:</p>";
        $text .= "<ul>";
        $text .= "<li>Ratifica retiro y fecha.</li>";
        $text .= "<li>Ratifica retiro, pero necesita modificar la fecha (esta acción sólo la puede realizar una vez).</li>";
        $text .= "<li>Anula el aviso de retiro.</li>";
        $text .= "</ul>";
        $text .= "<p>Para responder la opción elegida por usted, favor pinche <a href='https://www.scuola.cl/sivm/detalles-solicitud/?ratifica={$hashrut}'>Aquí</a></p>";
        $text .= "<p>Cumplimos con informarle que de no recibir respuesta a este último correo, su aviso de retiro quedará sin efecto y por tanto será automáticamente ANULADO por el sistema por lo que no tendrá ningún efecto en nuestra institución, tanto en el área administrativa como en la académica.</p>";
        $text .= "<p>Atentamente,</p><p>Scuola Italiana Vittorio Montiglio</p>";
        if ($this->sent_smtp_wti("3ER AVISO -FAVOR RATIFICAR O ANULAR SOLICITUD DE RETIRO DE ALUMNO Nro. " . $folio, $correo, $text, $folio, false)) {
            return true;
        } else {
            return false;
        }
    }
    // #############################################################################################
    // DAEMON - END
    // #############################################################################################

    // #############################################################################################
    // STAFF
    // #############################################################################################

    public function msgSegretaria($correo, $folio, $mensaje, $correoSegretaria)
    {
        $text = "<p>Estimado(a)s</p>";
        $text .= "<p>La <b>Segretaria Scolastica</b> ha enviado el siguiente mensaje:</p>";
        $text .= $mensaje;
        $text .= "<p><b>Puede observar los detalles en el siguiente enlace:</p>";
        $text .= "<p><a href=" . base_url() . "admin/api_master/master/details/{$folio}>Folio Nro {$folio}</a></p>";
        $text .= "<p>Atentamente,</p><p>Scuola Italiana Vittorio Montiglio</p>";
        if ($this->sent_smtp_wti("Folio Nro. " . $folio . " Revisado por Segretaria Scolastica", $correo, $text, $folio, $correoSegretaria)) {
            return true;
        } else {
            return false;
        }
    }

    public function retiroFirmadoPorRectorSECA($correo, $folio)
    {
        $text = "<p>Estimado(a)s Secretaría Academica</p>";
        $text .= "<p>Le informamos que el Folio de retiro Nro. {$folio} ha sido firmado por <b>RECTORÍA</b>, tiene pendiente una revisión de su parte para poder continuar.</p>";
        $text .= "<p><a href=" . base_url() . "admin/api_master/master/details/{$folio}>Folio Nro {$folio}</a></p>";
        $text .= "<p>Atentamente,</p><p>Scuola Italiana Vittorio Montiglio</p>";
        if ($this->sent_smtp_wti("Folio Nro. " . $folio . " HA SIDO FIRMADO", $correo, $text, $folio, false)) {
            return true;
        } else {
            return false;
        }
    }

    public function retiroFirmadoPorRectorADM($correo, $folio)
    {
        $text = "<p>Estimado(a)s Administración</p>";
        $text .= "<p>Le informamos que el Folio de retiro Nro. {$folio} ha sido firmado por <b>RECTORÍA</b>, tiene pendiente una revisión de su parte para poder continuar.</p>";
        $text .= "<p><a href=" . base_url() . "admin/api_master/master/details/{$folio}>Folio Nro {$folio}</a></p>";
        $text .= "<p>Atentamente,</p><p>Scuola Italiana Vittorio Montiglio</p>";
        if ($this->sent_smtp_wti("Folio Nro. " . $folio . " HA SIDO FIRMADO", $correo, $text, $folio, false)) {
            return true;
        } else {
            return false;
        }
    }

    public function retiroFirmadoPorDirector($correo, $folio)
    {
        $text = "<p>Estimado(a) Rector(a)</p>";
        $text .= "<p>Le informamos que el Folio de retiro Nro. {$folio} ha sido firmado por <b>DIRECTOR DE ÁREA</b>, tiene pendiente una revisión de su parte para poder continuar.</p>";
        $text .= "<p><a href=" . base_url() . "admin/api_master/master/details/{$folio}>Folio Nro {$folio}</a></p>";
        $text .= "<p>Atentamente,</p><p>Scuola Italiana Vittorio Montiglio</p>";
        if ($this->sent_smtp_wti("Folio Nro. " . $folio . " HA SIDO FIRMADO", $correo, $text, $folio, false)) {
            return true;
        } else {
            return false;
        }
    }

    public function retiroEnProcesoDirector($correo, $folio)
    {
        $text = "<p>Estimado(a) Director(a)</p>";
        $text .= "<p>Le informamos que el Folio de retiro Nro. {$folio} fue marcado como <b>EN PROCESO</b>, tiene pendiente una revisión de su parte para poder continuar.</p>";
        $text .= "<p><a href=" . base_url() . "admin/api_master/master/details/{$folio}>Folio Nro {$folio}</a></p>";
        $text .= "<p>Atentamente,</p><p>Scuola Italiana Vittorio Montiglio</p>";
        if ($this->sent_smtp_wti("Folio Nro. " . $folio . " EN PROGRESO", $correo, $text, $folio, false)) {
            return true;
        } else {
            return false;
        }
    }
    
    
    public function retiroRatificadoEnProcesoDirector($correo, $folio)
    {
        $text = "<p>Estimado(a) Director(a)</p>";
        $text .= "<p>Le informamos que el Folio de retiro Nro. {$folio} fue marcado como <b>ABIERTO</b>, tiene pendiente una revisión de su parte para poder continuar.</p>";
        $text .= "<p><a href=" . base_url() . "admin/api_master/master/details/{$folio}>Folio Nro {$folio}</a></p>";
        $text .= "<p>Atentamente,</p><p>Scuola Italiana Vittorio Montiglio</p>";
        if ($this->sent_smtp_wti("Folio Nro. " . $folio . " ABIERTO", $correo, $text, $folio, false)) {
            return true;
        } else {
            return false;
        }
    }

    public function retiroApoderadoFirmaFuturo($correo, $folio)
    {
        $text = "<p>Estimado(a) Director(a)</p>";
        $text .= "<p>Le informamos que el Folio de retiro Nro. {$folio} fue firmado por el apoderado. Al ser un retiro futuro el sistema le avisará cuando el apoderado ratifique, así puede dar continuidad con el retiro.</p>";
        $text .= "<p>Atentamente,</p><p>Scuola Italiana Vittorio Montiglio</p>";
        if ($this->sent_smtp_wti("Folio Nro. " . $folio . " EN PROGRESO", $correo, $text, $folio, false)) {
            return true;
        } else {
            return false;
        }
    }

    public function retiroApoderadoFirma($correo, $folio)
    {
        $text = "<p>Estimado(a) Director(a)</p>";
        $text .= "<p>Le informamos que el Folio de retiro Nro. {$folio} fue firmado por el apoderado, tiene pendiente una revisión de su parte para poder continuar.</p>";
        $text .= "<p><a href=" . base_url() . "admin/api_master/master/details/{$folio}>Folio Nro {$folio}</a></p>";
        $text .= "<p>Atentamente,</p><p>Scuola Italiana Vittorio Montiglio</p>";
        if ($this->sent_smtp_wti("Folio Nro. " . $folio . " EN PROGRESO", $correo, $text, $folio, false)) {
            return true;
        } else {
            return false;
        }
    }

    public function retiroRatificado($correo, $folio)
    {
        $text = "<p>Estimado(a) Director(a)</p>";
        $text .= "<p>Le informamos que el Folio de retiro Nro. {$folio} fue ratificado por el apoderado, tiene pendiente una revisión de su parte para poder continuar.</p>";
        $text .= "<p><a href=" . base_url() . "admin/api_master/master/details/{$folio}>Folio Nro {$folio}</a></p>";
        $text .= "<p>Atentamente,</p><p>Scuola Italiana Vittorio Montiglio</p>";
        if ($this->sent_smtp_wti("Folio Nro. " . $folio . " HA SIDO RATIFICADO", $correo, $text, $folio, false)) {
            return true;
        } else {
            return false;
        }
    }

    public function retiroAnuladoEnRevision($correo, $folio, $motivo)
    {
        $text = "<p>Estimado(a) Director(a)</p>";
        $text .= "<p>Le informamos que el Folio de retiro Nro. {$folio} fue marcado como <b>ANULADO EN REVISIÓN</b>
        por motivo: <b>{$motivo}</b>, tiene pendiente un revisión de su parte para poder continuar.</p>";
        $text .= "<p><a href=" . base_url() . "admin/api_master/master/details/{$folio}>Folio Nro {$folio}</a></p>";
        $text .= "<p>Atentamente,</p><p>Scuola Italiana Vittorio Montiglio</p>";
        if ($this->sent_smtp_wti("Folio Nro. " . $folio . " ANULADO EN REVISIÓN", $correo, $text, $folio, false)) {
            return true;
        } else {
            return false;
        }
    }

    public function retiroNullaOstaGenerada($correo, $folio)
    {
        $text = "<p>Estimado(a) Rector(a)</p>";
        $text .= "<p>Le informamos que ha sido generado el documento <b>NULLA OSTA</b> para el Folio de retiro Nro. {$folio}, tiene pendiente un revisión de su parte para poder continuar.</p>";
        $text .= "<p><a href=" . base_url() . "admin/api_master/master/details/{$folio}>Folio Nro {$folio}</a></p>";
        $text .= "<p>Atentamente,</p><p>Scuola Italiana Vittorio Montiglio</p>";
        if ($this->sent_smtp_wti("Folio Nro. " . $folio . " FIRMA DE NULLA OSTA", $correo, $text, $folio, false)) {
            return true;
        } else {
            return false;
        }
    }

    public function nullaOstaFirmadoPorRectorSECA($correo, $folio)
    {
        $text = "<p>Estimado(a) Secretario(a)</p>";
        $text .= "<p>Le informamos que el Rector ha firmado el documento <b>NULLA OSTA</b> para el Folio de retiro Nro. {$folio}, tiene pendiente un revisión de su parte para poder continuar.</p>";
        $text .= "<p><a href=" . base_url() . "admin/api_master/master/details/{$folio}>Folio Nro {$folio}</a></p>";
        $text .= "<p>Atentamente,</p><p>Scuola Italiana Vittorio Montiglio</p>";
        if ($this->sent_smtp_wti("Folio Nro. " . $folio . " NULLA OSTA FIRMADA", $correo, $text, $folio, false)) {
            return true;
        } else {
            return false;
        }
    }

    public function retiroPosibleReaperturaSecretaria($correo, $folio, $motivo)
    {
        $text = "<p>Estimado(a) Secretario(a)</p>";
        $text .= "<p>Le informamos que el Folio de retiro Nro. {$folio} fue marcado como <b>POSIBLE REAPERTURA</b>
        por motivo: <b>{$motivo}</b>, tiene pendiente un revisión de su parte para poder continuar.</p>";
        $text .= "<p><a href=" . base_url() . "admin/api_master/master/details/{$folio}>Folio Nro {$folio}</a></p>";
        $text .= "<p>Atentamente,</p><p>Scuola Italiana Vittorio Montiglio</p>";
        if ($this->sent_smtp_wti("Folio Nro. " . $folio . " POSIBLE REAPERTURA", $correo, $text, $folio, false)) {
            return true;
        } else {
            return false;
        }
    }

    // #############################################################################################
    // APODERADOS
    // #############################################################################################

    public function cierreRetiroMail($correo, $data)
    {
        // Obtener el objeto CodeIgniter
        $CI = &get_instance();
        $adjuntos = $CI->Retiros_model->get_adjuntos($data['folio']);

        $text = "<p>Estimado Señor(a) {$data['nombre_apoderado']}</p>";

        $text .= "<p> Le informamos que el proceso de retiro realizado por usted, de su hijo(a) {$data['nombre_alumno']}, ha sido cerrado con éxito, con el siguiente detalle: </p>";

        $text .= "<p>Folio de Retiro: {$data['folio']}</p>";
        $text .= "<p>Tipo de Retiro: {$data['tipo']}</p>";
        $text .= "<p>Motivo de Retiro: {$data['motivo']}</p>";

        
        
        if ($adjuntos) {
            $text .= "<p>A continuación, encontrará un listado con los documentos relacionados a este proceso:</p><ul>";
            foreach ($adjuntos as $a) {
                $text .= "<li><a href='{$a->url}'>{$a->nombre}</a></li>";
            }
            $text .= "</ul>";
        }

        $text .= "<p>Recuerde que todos los procesos de reingreso a nuestro establecimiento, ya sea de retiros permanentes o temporales, se deben realizar a través del Departamento de Admisión en los plazos de postulación que existen para cada nivel y están constantemente publicados en nuestra página web, dado que como institución <span style='color:black;font-weight:600;'>no podemos reservar cupos.</span></p>";

        $text .= "<p>Les deseamos lo mejor en el nuevo camino que han decidido tomar como familia.</p>";
        $text .= "<p>Atentamente,</p><p> Scuola Italiana Vittorio Montiglio</p>";


        if ($this->sent_smtp_wti("Aviso de Retiro Finalizado", $correo, $text, $data['folio'], false)) {
            $anotherEmail = $this->sent_smtp_wti2("Aviso de Retiro Finalizado", 'administrador@admin.com', $text, $data['folio']);
            return true;
        } else {
            return false;
        }
    }

    public function confirmacionAnulacion($nombre, $correo, $folio, $nombreAlumno, $motivo)
    {
        $text .= "<p>Le informamos que se ha procedido a anular el Aviso de Retiro Folio Nro. {$folio} por su hijo(a) {$nombreAlumno}, 
        dado que el  Director de Área indicó que no procede procesarlo.</p>";
        $text .= "<p>El motivo indicado fue: <b>{$motivo}</b></p>";
        $text .= "<p>Si usted aún desea efectuar el Retiro de su hijo, favor realizar un nuevo Aviso
        de Retiro en nuestros sistemas.</p>";
        $text .= "<p>Atentamente,</p><p>Scuola Italiana Vittorio Montiglio</p>";
        if ($this->sent_smtp_wti("Aviso de Retiro Anulado", $correo, $text, $folio, false)) {
            return true;
        } else {
            return false;
        }
    }

    public function confirmacionAnulacionSecAca($nombre, $correo, $folio, $nombreAlumno, $motivo)
    {
        $text = "<p>Estimado Señor(a) {$nombre}</p>";
        $text .= "<p>Le informamos que se ha procedido a anular el Aviso de Retiro Folio Nro. {$folio} por su hijo(a) {$nombreAlumno}, 
        dado que la Secretaría Académica indicó que no procede procesarlo.</p>";
        $text .= "<p>El motivo indicado fue: <b>{$motivo}</b></p>";
        $text .= "<p>Si usted aún desea efectuar el Retiro de su hijo, favor realizar un nuevo Aviso
        de Retiro en nuestros sistemas.</p>";
        $text .= "<p>Atentamente,</p><p>Scuola Italiana Vittorio Montiglio</p>";
        if ($this->sent_smtp_wti("Aviso de Retiro Anulado", $correo, $text, $folio, false)) {
            return true;
        } else {
            return false;
        }
    }

    public function confirmacionAvisoDeRetiro($nombre, $correo, $folio, $nombreAlumno, $motivo)
    {
        $text = "<p>Estimado Señor(a) {$nombre}</p>";
        $text .= "<p>Le informamos que hemos recibido su Aviso de retiro Folio Nro. {$folio} por su hijo {$nombreAlumno},
        por motivo {$motivo}, el cual se procesará al interior de nuestra institución y se le dará aviso
        cuando dicho proceso haya concluído.</p>";
        $text .= "<p>Atentamente,</p><p>Scuola Italiana Vittorio Montiglio</p>";
        if ($this->sent_smtp_wti("Confirmación de Aviso de Retiro", $correo, $text, $folio, false)) {
            return true;
        } else {
            return false;
        }
    }

    public function confirmacionMotivoIntercambioEscolar($nombre, $correo, $folio, $nombreAlumno, $motivo)
    {
        $text = "<p>Estimado Señor(a) {$nombre}</p>";
        $text .= "<p>Le informamos que hemos recibido su Aviso de retiro Folio Nro. {$folio} por su hijo {$nombreAlumno},
        por motivo {$motivo}, el cual se procesará al interior de nuestra institución y se le dará aviso
        cuando dicho proceso haya concluído.</p>";

        $text .= "<p><a href='#'>Reglamento de Intercambio Scuola Italiana Vittorio Montiglio</a></p>";
        $text .= "<p>Dado que usted ha indicado que su hijo(a) se retira por un intercambio escolar, es
        importante que usted lea con detención el Reglamento de Intercambio que se anexa a
        este correo y comprenda que si una vez terminado el intercambio escolar, usted desea
        reincorporar a su hijo(a) a nuestra institución, debe solicitar la vacante directamente en
        Admisión. Desde ya, se le informa que no podemos asegurar vacantes para ningún nivel.</p>";
        $text .= "<p>Atentamente,</p><p>Scuola Italiana Vittorio Montiglio</p>";

        if ($this->sent_smtp_wti("Confirmación de Aviso de Retiro", $correo, $text, $folio, false)) {
            return true;
        } else {
            return false;
        }
    }

    public function ratificacionRatificaRetiro($nombre, $correo, $folio, $nombreAlumno, $motivo)
    {
        $text = "<p>Estimado Señor(a) {$nombre}</p>";
        $text .= "<p>Le informamos que hemos recibido su ratifcación al Aviso de retiro Folio Nro. {$folio} 
        por su hijo {$nombreAlumno}, por motivo {$motivo}, el cual se procesará en los próximos días al interior
         de nuestra institución y se le dará aviso cuando dicho proceso haya concluído. </p>";

        $text .= "<p>Atentamente,</p><p>Scuola Italiana Vittorio Montiglio</p>";

        if ($this->sent_smtp_wti("RETIRO ALUMNO RATIFICADO", $correo, $text, $folio, false)) {
            return true;
        } else {
            return false;
        }
    }

    public function ratificacionModificoFecha($nombre, $correo, $folio, $nombreAlumno, $motivo)
    {
        $text = "<p>Estimado Señor(a) {$nombre}</p>";
        $text .= "<p>Le informamos que hemos recibido su modificación de fechas al Aviso de retiro Folio Nro.{$folio} 
        por su hijo {$nombreAlumno}, por motivo {$motivo},Este caso se reactivará cuando falten 3 días para la fecha de 
        retiro de su hijo en caso de haber indicado una fecha futura o en los próximos días en caso de haber indicado 
        una fecha pasada, en cuyo caso se le dará aviso cuando dicho proceso haya concluído. </p>";

        $text .= "<p>Atentamente,</p><p>Scuola Italiana Vittorio Montiglio</p>";

        if ($this->sent_smtp_wti("RETIRO ALUMNO RATIFICADO CON CAMBIO DE FECHA", $correo, $text, $folio, false)) {
            return true;
        } else {
            return false;
        }
    }

    public function feDeErrata($nombre, $correo)
    {
        $text = "<p>Estimado Señor(a) {$nombre}</p>";
        $text .= "<p>Si usted ha recibido algún correo de nuestra institución que corresponde a algún proceso o paso que no corresponde a su caso, pedimos disculpas. Hemos estado haciendo pruebas y cambios internos en nuestro sistema para proveer un mejor servicio.</p>";

        $text .= "<p>Atentamente,</p><p>Scuola Italiana Vittorio Montiglio</p>";

        if ($this->sent_smtp_wti("FE DE ERRATA", $correo, $text, $folio, false)) {
            return true;
        } else {
            return false;
        }
    }


    public function ratificacionAnularRetiro($nombre, $correo, $folio, $nombreAlumno, $motivo)
    {
        $text = "<p>Estimado Señor(a) {$nombre}</p>";
        $text .= "<p>Le informamos que, dado que usted nos ha indicado que anula el Aviso de retiro Folio 
        Nro.{$folio} por su hijo {$nombreAlumno}, se ha procedido realizar dicha acción en nuestros sistemas. 
        Si usted aún requiere formalizar este retiro de nuestra institución, lo invitamos a realizar 
        nuevamente el proceso directamente en nuestro sitio web. </p>";

        $text .= "<p>Atentamente,</p><p>Scuola Italiana Vittorio Montiglio</p>";

        if ($this->sent_smtp_wti("RETIRO ANULADO - NO RATIFICADO", $correo, $text, $folio, false)) {
            return true;
        } else {
            return false;
        }
    }    

    public function sent_smtp_wti($wtititulo, $wticorreo, $wtitexto, $folio, $correoSegretaria)
    {
        $CI = &get_instance();
        $CI->load->config('email');
        // Simulate fake template to be parsed
        $template = new StdClass();
        $template->message = get_option('email_header') . $wtitexto;
        $template->fromname = get_option('companyname') != '' ? get_option('companyname') : 'TEST';
        $template->subject = $wtititulo;

        $template = parse_email_template($template);

        hooks()->do_action('before_send_test_smtp_email');
        $CI->email->initialize();
        if (get_option('mail_engine') == 'phpmailer') {
            $CI->email->set_debug_output(function ($err) {
                if (!isset($GLOBALS['debug'])) {
                    $GLOBALS['debug'] = '';
                }
                $GLOBALS['debug'] .= $err . '<br />';

                return $err;
            });
            $CI->email->set_smtp_debug(3);
        }

        $CI->email->set_newline(config_item('newline'));
        $CI->email->set_crlf(config_item('crlf'));

        $CI->email->from(get_option('smtp_email'), $template->fromname);
        $CI->email->to($wticorreo);

        $systemBCC = get_option('bcc_emails');

        if ('' != $systemBCC) {
            $CI->email->bcc($systemBCC);
        }

        $CI->email->subject($template->subject);
        $CI->email->message($template->message);
        if ($CI->email->send(true)) {

            if ($correoSegretaria) {
                if ($this->wti_log_retiro("Notificación Enviada: " . $wtititulo, $wticorreo, $folio, $correoSegretaria)) {
                    return true;
                }
            }
            else {
                if ($this->wti_log_retiro("Notificación Enviada: " . $wtititulo, $wticorreo, $folio, false)) {
                    return true;
                }
            }

        } else {
            return false;
            log_activity('Falla en la distribución de correo electrónico');
        }

    }
    
    public function sent_smtp_wti2($wtititulo, $wticorreo, $wtitexto, $folio)
    {
        $CI = &get_instance();
        $CI->load->config('email');
        // Simulate fake template to be parsed
        $template = new StdClass();
        $template->message = get_option('email_header') . $wtitexto;
        $template->fromname = get_option('companyname') != '' ? get_option('companyname') : 'TEST';
        $template->subject = $wtititulo;
        $template = parse_email_template($template);

        hooks()->do_action('before_send_test_smtp_email');
        $CI->email->initialize();
        if (get_option('mail_engine') == 'phpmailer') {
            $CI->email->set_debug_output(function ($err) {
                if (!isset($GLOBALS['debug'])) {
                    $GLOBALS['debug'] = '';
                }
                $GLOBALS['debug'] .= $err . '<br />';

                return $err;
            });
            $CI->email->set_smtp_debug(3);
        }

        $CI->email->set_newline(config_item('newline'));
        $CI->email->set_crlf(config_item('crlf'));
        $CI->email->from(get_option('smtp_email'), $template->fromname);
        $CI->email->to($wticorreo);
        $systemBCC = get_option('bcc_emails');

        if ('' != $systemBCC) {
            $CI->email->bcc($systemBCC);
        }
        $CI->email->subject($template->subject);
        $CI->email->message($template->message);
        if ($CI->email->send(true)) {
            return true;
        } else {
            return false;
            log_activity('Falla en la distribución de correo electrónico');
        }

    }

    public function wti_log($wtititulo, $wticorreo)
    {
        $logmsg = "<h4>" . $wtititulo;
        $logmsg .= "</h4>";
        $logmsg .= "User: " . $wticorreo;

        log_activity($logmsg);
        return true;
    }

    public function wti_log_retiro($wtititulo, $wticorreo, $id_retiro, $correoSegretaria)
    {
        if ($correoSegretaria) {
            $logmsg = "<h4>" . $wtititulo;
            $logmsg .= "</h4>";
            $logmsg .= "User: " . $correoSegretaria;
            $logmsg .= " <span>retiro_" . $id_retiro . "</span>";

            log_activity($logmsg);
            return true;
        }
        else {
            $logmsg = "<h4>" . $wtititulo;
            $logmsg .= "</h4>";
            $logmsg .= "User: " . $wticorreo;
            $logmsg .= " <span>retiro_" . $id_retiro . "</span>";

            log_activity($logmsg);
            return true;
        }

    }

    ###############################################################################################################
    // FIRMA INICIA APODERADO
    ###############################################################################################################

    public function apoderadoFirma($datosMail, $hashmail)
    {

        $bodyMail = "<table class='es-wrapper' width='100%' cellspacing='0' cellpadding='0' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;padding:0;Margin:0;width:100%;height:100%;background-repeat:repeat;background-position:center top;background-color:#F6F6F6'>
        <tr>
            <td valign='top' style='padding:0;Margin:0'>
                <table class='es-header' cellspacing='0' cellpadding='0' align='center' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%;background-color:transparent;background-repeat:repeat;background-position:center top'>
                    <tr>
                        <td align='center' style='padding:0;Margin:0'>
                            <table class='es-header-body' cellspacing='0' cellpadding='0' bgcolor='#ffffff' align='center' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#FFFFFF;width:600px'>
                                <tr>
                                    <td align='left' style='padding:0;Margin:0;padding-top:20px;padding-left:20px;padding-right:20px'>
                                        <!--[if mso]>
                                        <table style='width:560px' cellpadding='0'
                                            cellspacing='0'>
                                            <tr>
                                                <td style='width:180px' valign='top'>
                                                    <![endif]-->
                                                    <table class='es-left' cellspacing='0' cellpadding='0' align='left' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;float:left'>
                                                        <tr>
                                                            <td class='es-m-p0r es-m-p20b' valign='top' align='center' style='padding:0;Margin:0;width:180px'>
                                                                <table width='100%' cellspacing='0' cellpadding='0' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px'>
                                                                    <tr>
                                                                        <td style='padding:0;Margin:0;display:none' align='center'></td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <!--[if mso]>
                                                </td>
                                                <td style='width:20px'></td>
                                                <td style='width:360px' valign='top'>
                                                    <![endif]-->
                                                    <table class='es-right' cellspacing='0' cellpadding='0' align='right' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;float:right'>
                                                        <tr>
                                                            <td align='left' style='padding:0;Margin:0;width:360px'>
                                                                <table width='100%' cellspacing='0' cellpadding='0' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px'>
                                                                    <tr>
                                                                        <td align='center' style='padding:0;Margin:0;display:none'></td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <!--[if mso]>
                                                </td>
                                            </tr>
                                        </table>
                                        <![endif]-->
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                <table class='es-content' cellspacing='0' cellpadding='0' align='center' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%'>
                    <tr>
                        <td align='center' style='padding:0;Margin:0'>
                            <table class='es-content-body' cellspacing='0' cellpadding='0' bgcolor='#ffffff' align='center' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#FFFFFF;width:600px'>
                                <tr>
                                    <td align='left' style='padding:0;Margin:0;padding-top:20px;padding-left:20px;padding-right:20px'>
                                        <table width='100%' cellspacing='0' cellpadding='0' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px'>
                                            <tr>
                                                <td valign='top' align='center' style='padding:0;Margin:0;width:560px'>
                                                    <table width='100%' cellspacing='0' cellpadding='0' role='presentation' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px'>
                                                        <tr>
                                                            <td align='left' style='padding:0;Margin:0'>
                                                                <p style='text-align: center;Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, helvetica, sans-serif;line-height:24px;color:#0dab0d;font-size:16px'><strong>Solicitud de retiro - Firma electrónica</strong></p>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                            </tr>
                                <tr>
                                    <td align='left' style='padding:0;Margin:0;padding-top:20px;padding-left:20px;padding-right:20px'>
                                        <table cellpadding='0' cellspacing='0' width='100%' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px'>
                                            <tr>
                                                <td align='center' valign='top' style='padding:0;Margin:0;width:560px'>
                                                    <table cellpadding='0' cellspacing='0' width='100%' role='presentation' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px'>
                                                        <tr>
                                                            <td align='left' style='padding:0;Margin:0'>
                                                                <p style='Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, helvetica, sans-serif;line-height:28px;color:#0dab0d;font-size:14px'><strong>Datos básicos:</strong></p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td align='left' style='padding:0;Margin:0'>
                                                                <p style='Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, helvetica, sans-serif;line-height:21px;color:#686666;font-size:14px'><strong>Fecha:&nbsp;</strong> <span style='Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, helvetica, sans-serif;line-height:21px;color:#686666;font-size:14px'>{$datosMail['dataRetiro']['fecha_aviso']}</span></p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td align='left' style='padding:0;Margin:0'>
                                                                <p style='Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, helvetica, sans-serif;line-height:21px;color:#686666;font-size:14px'><strong>Alumno:&nbsp;</strong><span style='Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, helvetica, sans-serif;line-height:21px;color:#686666;font-size:14px'>{$datosMail['dataCliente']['alumno-nombre']}</span></p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td align='left' style='padding:0;Margin:0'>
                                                                <p style='Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, helvetica, sans-serif;line-height:21px;color:#686666;font-size:14px'><strong>RUT:&nbsp;</strong><span style='Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, helvetica, sans-serif;line-height:21px;color:#686666;font-size:14px'>{$datosMail['dataCliente']['rut']}</span></p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td align='left' style='padding:0;Margin:0'>
                                                                <p style='Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, helvetica, sans-serif;line-height:21px;color:#686666;font-size:14px'><strong>Curso:&nbsp;</strong><span style='Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, helvetica, sans-serif;line-height:21px;color:#686666;font-size:14px'>{$datosMail['dataCliente']['alumno-curso']}</span></p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td align='left' style='padding:0;Margin:0'>
                                                                <p style='Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, helvetica, sans-serif;line-height:21px;color:#686666;font-size:14px'><strong>Fecha de retiro:&nbsp;</strong><span style='Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, helvetica, sans-serif;line-height:21px;color:#686666;font-size:14px'>{$datosMail['dataCliente']['datetimepicker1Input']}</span></p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td align='left' style='padding:0;Margin:0'>
                                                                <p style='Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, helvetica, sans-serif;line-height:21px;color:#686666;font-size:14px'><strong>Año académico de retiro:&nbsp;</strong><span style='Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, helvetica, sans-serif;line-height:21px;color:#686666;font-size:14px'>{$datosMail['dataCliente']['anoacademico']}</span></p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td align='left' style='padding:0;Margin:0'>
                                                                <p style='Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, helvetica, sans-serif;line-height:21px;color:#686666;font-size:14px'><strong>Tipo de retiro:&nbsp;</strong><span style='Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, helvetica, sans-serif;line-height:21px;color:#686666;font-size:14px'>{$datosMail['dataCliente']['permanente-temporal']}</span></p>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td align='left' style='padding:0;Margin:0;padding-top:20px;padding-left:20px;padding-right:20px'>
                                        <table cellpadding='0' cellspacing='0' width='100%' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px'>
                                            <tr>
                                                <td align='center' valign='top' style='padding:0;Margin:0;width:560px'>
                                                    <table cellpadding='0' cellspacing='0' width='100%' role='presentation' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px'>
                                                        <tr>
                                                            <td align='left' style='padding:0;Margin:0'>
                                                                <p style='Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, helvetica, sans-serif;line-height:28px;color:#0dab0d;font-size:14px'><strong>Datos de quien retira:</strong></p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td align='left' style='padding:0;Margin:0'>
                                                                <p style='Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, helvetica, sans-serif;line-height:21px;color:#686666;font-size:14px'><strong>Quien retira:&nbsp;</strong><span style='Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, helvetica, sans-serif;line-height:21px;color:#686666;font-size:14px'>{$datosMail['dataApoderado']['es_apoderado']}</span></p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td align='left' style='padding:0;Margin:0'>
                                                                <p style='Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, helvetica, sans-serif;line-height:21px;color:#686666;font-size:14px'><strong>Nombre completo:&nbsp;</strong><span style='Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, helvetica, sans-serif;line-height:21px;color:#686666;font-size:14px'>{$datosMail['dataApoderado']['nombre']}</span></p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td align='left' style='padding:0;Margin:0'>
                                                                <p style='Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, helvetica, sans-serif;line-height:21px;color:#686666;font-size:14px'><strong>RUT:&nbsp;</strong><span style='Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, helvetica, sans-serif;line-height:21px;color:#686666;font-size:14px'>{$datosMail['dataApoderado']['rut']}</span></p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td align='left' style='padding:0;Margin:0'>
                                                                <p style='Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, helvetica, sans-serif;line-height:21px;color:#686666;font-size:14px'><strong>Mail:&nbsp;</strong><span style='Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, helvetica, sans-serif;line-height:21px;color:#686666;font-size:14px'>{$datosMail['dataApoderado']['correo']}</span></p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td align='left' style='padding:0;Margin:0'>
                                                                <p style='Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, helvetica, sans-serif;line-height:21px;color:#686666;font-size:14px'><strong>Teléfono:&nbsp;</strong><span style='Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, helvetica, sans-serif;line-height:21px;color:#686666;font-size:14px'>{$datosMail['dataApoderado']['telefono']}</span></p>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align='center' valign='top' style='padding:0;Margin:0;width:560px'>
                                                    <table cellpadding='0' cellspacing='0' width='100%' role='presentation' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px'>
                                                        <tr>
                                                            <td align='left' style='padding:0;Margin:0'>
                                                                <p style='Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, helvetica, sans-serif;line-height:28px;color:#0dab0d;font-size:14px'><strong>Motivo del retiro:</strong><span style='Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, helvetica, sans-serif;line-height:21px;color:#686666;font-size:14px'></span></p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td align='left' style='padding:0;Margin:0'>
                                                                <p style='Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, helvetica, sans-serif;line-height:21px;color:#686666;font-size:14px'><strong>Motivo:&nbsp;</strong><span style='Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, helvetica, sans-serif;line-height:21px;color:#686666;font-size:14px'>{$datosMail['dataRetiro']['motivo_retiro']}</span></p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td align='left' style='padding:0;Margin:0'>
                                                                <p style='Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, helvetica, sans-serif;line-height:21px;color:#686666;font-size:14px'><strong>Irá a colegio paritario:&nbsp;</strong><span style='Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, helvetica, sans-serif;line-height:21px;color:#686666;font-size:14px'>{$datosMail['dataRetiro']['colegio_paritario']}</span></p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td align='left' style='padding:0;Margin:0'>
                                                                <p style='Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, helvetica, sans-serif;line-height:21px;color:#686666;font-size:14px'><strong>Observaciones:&nbsp;</strong><span style='Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, helvetica, sans-serif;line-height:21px;color:#686666;font-size:14px'>{$datosMail['dataRetiro']['observaciones']}</span></p>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td align='left' style='padding:0;Margin:0;padding-top:20px;padding-left:20px;padding-right:20px'>
                                        <table width='100%' cellspacing='0' cellpadding='0' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px'>
                                            <tr>
                                                <td valign='top' align='center' style='padding:0;Margin:0;width:560px'>
                                                    <table width='100%' cellspacing='0' cellpadding='0' role='presentation' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px'>
                                                        <tr>
                                                            <td align='center' style='padding:0;Margin:0'>
                                                                <a style='font-weight:bold;width:60%;height:17px;border-radius:0.6rem;font-size:14px;text-decoration:none;display:inline-block;background:#198754;color:#fff;padding:5px;text-align:center;' href='" . URL_BASE_WORDPRESS . "detalles-solicitud/?retiro={$hashmail}'>Pinche aquí para ir a firmar electrónicamente</a>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>";

        if ($this->sent_smtp_wti("Retiro de Alumno Firma Electrónica - Folio Nro. {$datosMail['dataApoderado']['fk_retiro']}", $datosMail['dataApoderado']['correo'], $bodyMail, $datosMail['dataApoderado']['fk_retiro'], false)) {
            return true;
        } else {
            return false;
        }
    }
}