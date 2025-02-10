<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

if (!function_exists('nullaOsta')) {
    function nullaOsta($data, $logo, $firma)
    {
        $nulla = <<<EOF
            <style type="text/css">
                p {
                    font-size: 11px;
                }
            </style>
            <div class="wti-retiro-alumnos">
                <div class="container">
                    <div class="row">
                        <div class="col-md-4 col-xs-12">
                            $logo
                        </div>
                    </div>
                    <h3 style="text-align: center;"><strong>II Dirigente Scolastico</strong></h3>
                    <br>
                    <p><strong>VISTA</strong> la domanda dell'interessato..(a)</p>
                    <p><strong>VISTI</strong> gli atti di Ufficio:</p>
                    <p>VISTO che, <strong>$data->nombre</strong>, nato a <strong>$data->ciudad</strong>, <strong>$data->pais</strong> il <strong>$data->diamesanio</strong> e domiciliato(a) in Via <strong>$data->domicilio </strong>.</p>
                    <p>Ha frequentato il <strong>$data->curso</strong>, ottenendo l’ammissione al <strong>$data->curso</strong>. Di Scuola <strong>$data->nivel</strong>.</p>
                    <p>Ritenuti validi i motivi addotti, concede il</p>
                    <h3 style="text-align: center;"><strong>NULLA OSTA</strong></h3>
                    <p>Al trasferimento dell’alunno..(a) presso un’altro Istituto.</p>
                    <p>Si rilascia a richiesta dell’interessato in carta semplice per uso consentito</p>
                    <br>
                    <p>$data->fechaHoy</p>
                    <p>Ref.: $data->correlativo</p>
                    <div style="text-align:right">
                        $firma
                    </div>
                </div>
            </div>
        EOF;

        return $nulla;
    }
}
