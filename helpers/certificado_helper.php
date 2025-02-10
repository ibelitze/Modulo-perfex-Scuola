<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


if(!function_exists('certificado')) {
    function certificado($data, $logo, $firma) {
        $certificado = <<<EOF
            <style type="text/css">
                p {
                    font-size: 11px;
                    line-height: 16px;
                }
            </style>
            <div>
                <div>
                    <div class="row">
                        <div>
                            $logo
                        </div>
                    </div>
                    <h3 style="text-align: center;"><strong>Certificado de Traslado de Establecimiento
                    Educativo.</strong></h3>
                    <br>
                    <h3 style="text-align: center;">SCUOLA ITALIANA VITTORIO MONTIGLIO</h3>
                    <br>
                    <p style="text-align: justify;text-justify: inter-word;"> VISTO Se certifica que Don (a) $data->nombre asistió hasta $data->ultimoDia de manera regular a este Establecimiento Educacional, cursando $data->cursoCertf de Enseñanza $data->nivelCertf inscrito con el Nº $data->numMatricula del Registro Interno del año $data->anioAcd.
                    </p>
                    <br>
                    <p>Se entrega el presente certificado a petición de la interesada (o), para ser entregado en el nuevo establecimiento educativo, en el cual se matricula.</p>
                    <table style="width:100%; padding:0; Margin:0;">
                        <tr>
                            <td width="50%">
                                <p>LAS CONDES, $data->fechaEmsionCertf</p>
                            </td>
                            <td width="50%" style="text-align:center;">
                                <p>$firma</p>
                                <p>_________________________________</p>
                                <p>Nombre, Apellidos, Firma y Timbre</p>
                                <p>Director(a) del Establecimiento</p>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        EOF;

        return $certificado;
    }
}