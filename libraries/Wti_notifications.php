<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Wti_notifications extends AdminController
{

    protected $CI;

    public function __construct($params)
    {
        $this->CI = &get_instance();
        $CI = &get_instance();
    }

    public function sent_smtp_wti($wtititulo, $wticorreo, $wtitexto)
    {
        $wticorreo = 'contacto@webtiginoso.com';
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

            if ($this->wti_log($wtititulo, $wticorreo)) {
                return true;
            }

        } else {
            return false;
            log_activity('Falla en la distribución de correo electrónico');
        }

    }

    public function wti_staff_notification($descripcion, $senderid, $clientid, $clientname, $receiver, $link)
    {
        $CI = &get_instance();
        $CI->load->model('Notify_model');
        $creatingNotification = $CI->Notify_model->createStaffNotification($descripcion, $senderid, $clientid, $clientname, $receiver, $link);
        if ($creatingNotification) {
            return true;
        } else {
            return false;
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

    public function wti_log_retiro($wtititulo, $wticorreo, $id_retiro)
    {
        $logmsg = "<h4>" . $wtititulo;
        $logmsg .= "</h4>";
        $logmsg .= "User: " . $wticorreo;
        $logmsg .= " <span>retiro_" . $id_retiro . "</span>";

        log_activity($logmsg);
        return true;
    }

}
