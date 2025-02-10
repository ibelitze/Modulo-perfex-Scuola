<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


if(!function_exists('detalles')) {
    function detalles($QR, $logo) {
        $detalles = <<<EOF
        <table class="es-wrapper" width="100%" cellspacing="0" cellpadding="0" style="border-spacing:0px;padding:0;Margin:0;width:100%;height:100%;background-position:center top;background-color:#fff">
            <tr>
                <td valign="top" style="padding:0;Margin:0">
                    <table class="es-content" cellspacing="0" cellpadding="0" align="center" style=";border-spacing:0px;table-layout:fixed !important;width:100%">
                        <tr>
                                    <td align="center" style="padding:20px;Margin:0">
                                        <table class="es-content-body" cellspacing="0" cellpadding="0" bgcolor="#ffffff" align="center" style="border-collapse:collapse;border-spacing:0px;background-color:#FFFFFF;width:100%;">
                                            <tr>
                                                <td align="left" style="padding:0;Margin:0;padding-top:20px;padding-left:20px;padding-right:20px">
                                                <table width="100%" cellspacing="0" cellpadding="0" style="border-collapse:collapse;border-spacing:0px">
                                                    <tr>
                                                        <td>
                                                            $logo
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                    <td valign="top" align="center" style="padding:0;Margin:0;width:100%;">
                                                        <table width="100%" cellspacing="0" cellpadding="0" role="presentation" style="border-spacing:0px">
                                                        <tr>
                                                        <td width="100%" valign="bottom" align="center" style="padding:0;Margin:0"><p style="text-align: center;Margin:0;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:24px;color:#0dab0d;font-size:15px"><strong>Solicitud de retiro - Número de folio 87</strong></p></td>
                                                        </tr>
                                                        </table>
                                                    </td>
                                                    </tr>
                                                </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="50%" align="left" style="padding:0;Margin:0;padding-left:20px;padding-right:20px">
                                                    <table width="100%" cellspacing="0" cellpadding="0" style="border-spacing:0px">
                                                    <tr>
                                                    <td align="center" style="padding:0;Margin:0;width:100%;">
                                                    <table width="100%" cellspacing="0" cellpadding="0" role="presentation" style="border-spacing:0px">
                                                    <tr>
                                                        <td align="left" style="padding:0;Margin:0"><p style="Margin:0;font-family:arial,'helvetica neue', helvetica, sans-serif;color:#0dab0d;font-size:9px"><strong>Estado:</strong></p></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" style="padding:0;Margin:0">
                                                            <p style="font-weight:bold;width:50%;height:34px;border-radius:0.7rem;font-size:9px;text-decoration:none;display:inline-block;background-color:#bfbd3f;color:#fff;padding:5px;text-align:center;">FORMALIZADO</p>
                                                        </td>
                                                    </tr>
                                                    </table></td>
                                                    </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="left" width="50%" style="padding:0;Margin:0;padding-top:20px;padding-left:20px;padding-right:20px">
                                                    <table cellpadding="0" cellspacing="0" width="100%" style="border-spacing:0px">
                                                    <tr>
                                                    <td align="center" valign="top" style="padding:0;Margin:0;width:50%;">
                                                    <table cellpadding="0" cellspacing="0" width="100%" role="presentation" style="border-collapse:collapse;border-spacing:0px">
                                                    <tr>
                                                    <td align="left" style="padding:0;Margin:0"><p style="Margin:0;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:28px;color:#0dab0d;font-size:9px"><strong>Datos básicos:</strong></p></td>
                                                    </tr>
                                                    <tr>
                                                    <td align="left" style="padding:0;Margin:0"><p style="Margin:0;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#686666;font-size:9px"><strong>Fecha:&nbsp;</strong> <span style="Margin:0;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#686666;font-size:9px"></span></p></td>
                                                    </tr>
                                                    <tr>
                                                    <td align="left" style="padding:0;Margin:0"><p style="Margin:0;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#686666;font-size:9px"><strong>Alumno:&nbsp;</strong><span style="Margin:0;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#686666;font-size:9px"></span></p></td>
                                                    </tr>
                                                    <tr>
                                                    <td align="left" style="padding:0;Margin:0"><p style="Margin:0;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#686666;font-size:9px"><strong>RUT:&nbsp;</strong><span style="Margin:0;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#686666;font-size:9px"></span></p></td>
                                                    </tr>
                                                    <tr>
                                                    <td align="left" style="padding:0;Margin:0"><p style="Margin:0;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#686666;font-size:9px"><strong>Curso:&nbsp;</strong><span style="Margin:0;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#686666;font-size:9px"></span></p></td>
                                                    </tr>
                                                    <tr>
                                                    <td align="left" style="padding:0;Margin:0"><p style="Margin:0;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#686666;font-size:9px"><strong>Fecha de retiro:&nbsp;</strong><span style="Margin:0;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#686666;font-size:9px"></span></p></td>
                                                    </tr>
                                                    <tr>
                                                    <td align="left" style="padding:0;Margin:0"><p style="Margin:0;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#686666;font-size:9px"><strong>Año académico de retiro:&nbsp;</strong><span style="Margin:0;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#686666;font-size:9px"></span></p></td>
                                                    </tr>
                                                    <tr>
                                                    <td align="left" style="padding:0;Margin:0"><p style="Margin:0;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#686666;font-size:9px"><strong>Tipo de retiro:&nbsp;</strong><span style="Margin:0;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#686666;font-size:9px"></span></p></td>
                                                    </tr>
                                                    </table></td>
                                                    </tr>
                                                    </table>
                                                </td>
                                                <td align="left" width="50%" style="vertical-align:top;padding:0;Margin:0;padding-left:20px;padding-right:20px;padding-top:20px;">
                                                    <table cellpadding="0" cellspacing="0" width="100%" style="border-spacing:0px">
                                                    <tr>
                                                    <td align="center" valign="top" style="padding:0;Margin:0;width:100%;">
                                                    <table cellpadding="0" cellspacing="0" width="100%" role="presentation" style="border-spacing:0px">
                                                    <tr>
                                                    <td align="left" style="padding:0;Margin:0"><p style="Margin:0;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:28px;color:#0dab0d;font-size:9px"><strong>Motivo del retiro:</strong><span style="Margin:0;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#686666;font-size:9px"></span></p></td>
                                                    </tr>
                                                    <tr>
                                                    <td align="left" style="padding:0;Margin:0"><p style="Margin:0;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#686666;font-size:9px"><strong>Motivo:&nbsp;</strong><span style="Margin:0;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#686666;font-size:9px"></span></p></td>
                                                    </tr>
                                                    <tr>
                                                    <td align="left" style="padding:0;Margin:0"><p style="Margin:0;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#686666;font-size:9px"><strong>Irá a colegio paritario:&nbsp;</strong><span style="Margin:0;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#686666;font-size:9px"></span></p></td>
                                                    </tr>
                                                    <tr>
                                                    <td align="left" style="padding:0;Margin:0"><p style="Margin:0;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#686666;font-size:9px"><strong>Observaciones:&nbsp;</strong><span style="Margin:0;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#686666;font-size:9px"></span></p></td>
                                                    </tr>
                                                    </table></td>
                                                    </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="left" style="padding:0;Margin:0;padding-top:20px;padding-left:20px;padding-right:20px">
                                                    <table cellpadding="0" cellspacing="0" width="100%" style="border-spacing:0px">
                                                    <tr>
                                                    <td align="center" valign="top" style="padding:0;Margin:0;width:560px">
                                                    <table cellpadding="0" cellspacing="0" width="100%" role="presentation" style="border-spacing:0px">
                                                    <tr>
                                                    <td align="left" style="padding:0;Margin:0"><p style="Margin:0;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:28px;color:#0dab0d;font-size:9px"><strong>Datos de quien retira:</strong></p></td>
                                                    </tr>
                                                    <tr>
                                                    <td align="left" style="padding:0;Margin:0"><p style="Margin:0;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#686666;font-size:9px"><strong>Quien retira:&nbsp;</strong><span style="Margin:0;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#686666;font-size:9px"></span></p></td>
                                                    </tr>
                                                    <tr>
                                                    <td align="left" style="padding:0;Margin:0"><p style="Margin:0;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#686666;font-size:9px"><strong>Nombre completo:&nbsp;</strong><span style="Margin:0;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#686666;font-size:9px"></span></p></td>
                                                    </tr>
                                                    <tr>
                                                    <td align="left" style="padding:0;Margin:0"><p style="Margin:0;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#686666;font-size:9px"><strong>RUT:&nbsp;</strong><span style="Margin:0;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#686666;font-size:9px"></span></p></td>
                                                    </tr>
                                                    <tr>
                                                    <td align="left" style="padding:0;Margin:0"><p style="Margin:0;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#686666;font-size:9px"><strong>Mail:&nbsp;</strong><span style="Margin:0;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#686666;font-size:9px"></span></p></td>
                                                    </tr>
                                                    <tr>
                                                    <td align="left" style="padding:0;Margin:0"><p style="Margin:0;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#686666;font-size:9px"><strong>Teléfono:&nbsp;</strong><span style="Margin:0;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#686666;font-size:9px"></span></p></td>
                                                    </tr>
                                                    </table></td>
                                                    </tr>
                                                    </table>
                                                </td>
                                                <td align="left" style="vertical-align:top;padding:0;Margin:0;padding-top:20px;padding-left:20px;padding-right:20px">
                                                    <table cellpadding="0" cellspacing="0" width="100%" style="border-spacing:0px">
                                                    <tr>
                                                    <td align="center" valign="top" style="padding:0;Margin:0;width:560px">
                                                    <table cellpadding="0" cellspacing="0" width="100%" role="presentation" style="border-spacing:0px">
                                                    <tr>
                                                    <td align="left" style="padding:0;Margin:0"><p style="Margin:0;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:28px;color:#0dab0d;font-size:9px"><strong>Director(a):</strong><span style="Margin:0;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#686666;font-size:9px"></span></p></td>
                                                    </tr>
                                                    <tr>
                                                    <td align="left" style="padding:0;Margin:0"><p style="Margin:0;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#686666;font-size:9px"><strong>fecha de última asistencia:</strong><span style="Margin:0;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#686666;font-size:9px"></span></p></td>
                                                    </tr>
                                                    <tr>
                                                    <td align="left" style="padding:0;Margin:0"><p style="Margin:0;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#686666;font-size:9px"><strong>Se ha autorizado y/o aplica una promoción anticipada:</strong><span style="Margin:0;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#686666;font-size:9px"></span></p></td>
                                                    </tr>
                                                    </table></td>
                                                    </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="left" style="vertical-align:top;padding:0;Margin:0;padding-top:20px;padding-left:20px;padding-right:20px">
                                                    <table cellpadding="0" cellspacing="0" width="100%" style="border-spacing:0px">
                                                    <tr>
                                                    <td align="center" valign="top" style="padding:0;Margin:0;">
                                                    <table cellpadding="0" cellspacing="0" width="100%" role="presentation" style="border-spacing:0px">
                                                    <tr>
                                                    <td align="left" style="padding:0;Margin:0"><p style="Margin:0;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:28px;color:#0dab0d;font-size:9px"><strong>Firmas:</strong><span style="Margin:0;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#686666;font-size:9px"></span></p></td>
                                                    </tr>
                                                    </table></td>
                                                    </tr>
                                                    </table>
                                                </td>
                                            </tr>
                            </table></td>
                        </tr>
                        <tr>
                            <td width="50%">
                                <table width="100%" style="padding:0;Margin:0;">
                                    <tr>
                                        <td valign="middle" align="center"style="padding:0px;Margin:0;">
                                            <p style="font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:12px;color:#1a5a3c;font-size:9px">Solicitud de retiro -Número de folio 32353- Firmado electrónicamente a través del correo -apoderado@clientes.com- desde la dirección ip 192.168.0.1 el 03/01/2023 12:25pm</p>
                                        </td>
                                    </tr>
                                    <tr><td></td></tr>
                                    <tr>
                                        <td valign="middle" align="center"style="padding:0px;Margin:0;">
                                            <p style="font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:12px;color:#1a5a3c;font-size:9px">Solicitud de retiro -Número de folio 32353- Firmado electrónicamente a través del correo -apoderado@clientes.com- desde la dirección ip 192.168.0.1 el 03/01/2023 12:25pm</p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td width="50%">
                                $QR
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        EOF;


        return $detalles;
    }
}