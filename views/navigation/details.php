<?php if ($retiro[0]->estatus != "pendiente" || $retiro[0]->estatus == "pendiente_ratificado" || staff_can('administracion', 'retiros')): ?>
<div class="wti-retiro-alumnos">
    <div class="container">
        <!--  VISTA HTML  -->
        <div class="row wti-row">
            <div class="wti-retiro-form col-md-9">

                <div class="row wti-row">

                    <div class="col-md-9 col-xs-12">
                        <h2 class="wti-title-h2">Solicitud de retiro - Número de folio
                            <span><?php echo $retiro[0]->id; ?></span>
                        </h2>
                        <div class="row">
                            <div class="col-md-12 col-xs-12">
                                <h3 class="wti-title-h3">Estado: </h3>
                                <div style="text-transform: uppercase;" class="wti-estado-retiro
                                <?php

echo "retiro-" . $retiro[0]->estatus;
?>">
                                    <?php echo $retiro[0]->estatus; ?>
                                </div>
                                <div class="wti_motivos">
                                    <?php

if ("anulado en revision" == $retiro[0]->estatus) {
    echo "<p><b>Motivo: " . $retiro[0]->motivo_anulacion . "</b></p>";
}
if ("posible reapertura" == $retiro[0]->estatus) {
    echo "<p><b>Motivo: " . $retiro[0]->motivo_posible_reapertura . "</b></p>";
}
?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-xs-12">
                                <h3 class="wti-title-h3">Datos básicos</h3>

                                <p class="wti-p-label">Fecha: <span><?php echo date('Y-m-d', strtotime($retiro[0]->fecha_aviso)); ?></span></p>
                                <p class="wti-p-label">Alumno: <span><?php echo $retiro[0]->nombre; ?></span></p>
                                <p class="wti-p-label">RUT: <span><?php echo $retiro[0]->rut_estudiante; ?></span></p>
                                <p class="wti-p-label">Curso: <span><?php echo $retiro[0]->curso; ?></span>
                                </p>
                                <p class="wti-p-label">Nivel: <span><?php echo $retiro[0]->nivel; ?></span>
                                </p>
                                <p class="wti-p-label">Fecha de retiro:
                                    <span><?php echo date('Y-m-d', strtotime($retiro[0]->fecha_retiro)); ?></span>
                                </p>
                                <p class="wti-p-label">Año académico de retiro:
                                    <span><?php echo $retiro[0]->año_academico; ?></span>
                                </p>
                                <p class="wti-p-label">Tipo de retiro: <span><?php echo $retiro[0]->tipo; ?></span></p>


                                <br>
                                <h3 class="wti-title-h3">Datos de quien retira</h3>
                                <p class="wti-p-label">Quien retira: <span>Apoderado académico</span></p>
                                <p class="wti-p-label">Nombre completo:
                                    <span><?php echo $apoderado[0]->nombre; ?></span>
                                </p>
                                <p class="wti-p-label">RUT: <span><?php echo $apoderado[0]->rut; ?></span></p>
                                <p class="wti-p-label">Mail: <span><?php echo $apoderado[0]->correo; ?></span></p>
                                <p class="wti-p-label">Teléfono: <span><?php echo $apoderado[0]->telefono; ?></span></p>

                                <h3 class="wti-title-h3">Motivo del retiro</h3>

                                <p class="wti-p-label">Motivo: <span><?php echo $retiro[0]->motivo_retiro; ?></span>
                                </p>
                                <p class="wti-p-label">Irá a colegio paritario:
                                    <span><?php echo (1 == $retiro[0]->colegio_paritario) ? "SI" : "NO"; ?></span>
                                </p>
                                <p class="wti-p-label">Observaciones:</p>
                                <p id="wti-p-observaciones" class="wti-p-observaciones">
                                    <?php echo $retiro[0]->observaciones; ?></p>
                            </div>

                            <div class="col-md-6 col-xs-12">
                                <!-- INICIO DATOS CARGADOS POR EL DIRECTOR -->
                                <?php if (isset($retiro[0]->firma_director) && !empty($retiro[0]->firma_director) && 1 == $retiro[0]->firma_director): ?>
                                <h3 class="wti-title-h3">Director(a):</h3>
                                <?php if (isset($retiro[0]->ultima_asistencia) && !empty($retiro[0]->ultima_asistencia)): ?>
                                <p class="wti-p-label">Fecha de última asistencia:
                                    <span><?php echo date('Y-m-d', strtotime($retiro[0]->ultima_asistencia)); ?></span>
                                </p>
                                <?php endif;?>
                                <?php if (isset($retiro[0]->authpromoanti) && !empty($retiro[0]->authpromoanti)): ?>
                                <p class="wti-p-label">Se ha autorizado y/o aplica una promoción anticipada:
                                    <span><?php if (1 == $retiro[0]->authpromoanti) {echo "SI";} else {echo "NO";}?></span>
                                </p>
                                <?php endif;?>
                                <?php endif;?>
                                <!-- INICIO DATOS CARGADOS POR ADMINISTRACION -->
                                <?php if (isset($retiro[0]->adminitracion_proc) && !empty($retiro[0]->adminitracion_proc) && 1 == $retiro[0]->adminitracion_proc): ?>
                                <h3 class="wti-title-h3">Administración:</h3>
                                <?php
// Verificar la columna 'anio_retiro'
if (isset($admin[0]->anio_retiro)) {
    // El valor de la columna 'anio_retiro' existe
    echo '<p class="wti-p-label">Año del cual es retirado el alumno:
          <span>';
    echo $admin[0]->anio_retiro;
    echo '</span></p>';
}

// Verificar la columna 'observaciones_director_area'
if (isset($retiro[0]->observaciones_director_area)) {
    // El valor de la columna 'observaciones_director_area' existe
    echo '<p class="wti-p-label">Observaciones del director de área:
          <span>';
    echo $retiro[0]->observaciones_director_area;
    echo '</span></p>';
}

// Verificar la columna 'anualidades'
if (isset($admin[0]->anualidades)) {
    // El valor de la columna 'anualidades' existe
    echo '<p class="wti-p-label">¿Anualidades emitidas para próximo año?:
    <span>';
    echo $admin[0]->anualidades;
    echo '</span></p>';
}

// Verificar la columna 'sin_efecto_contable'
if (isset($admin[0]->sin_efecto_contable)) {
    // El valor de la columna 'sin_efecto_contable' existe
    if ($admin[0]->sin_efecto_contable > 0) {
        echo '<p class="wti-p-label">Efecto Contable:
        <span>';
        echo "con efecto";
        echo '</span></p>';
    }
} else {
    // El valor de la columna 'sin_efecto_contable' no existe
}

// Verificar la columna 'pago_colegiaturas'
if (isset($admin[0]->pago_colegiaturas)) {
    // El valor de la columna 'pago_colegiaturas' existe
    echo '<p class="wti-p-label">Forma de pago de las Colegiaturas:
    <span>';
    echo $admin[0]->pago_colegiaturas;
    echo '</span></p>';
} else {
    // El valor de la columna 'pago_colegiaturas' no existe
}

// Verificar la columna 'calculo_proporcional'
if (isset($admin[0]->calculo_proporcional)) {
    // El valor de la columna 'calculo_proporcional' existe
    if ($admin[0]->calculo_proporcional > 0) {
        echo '<p class="wti-p-label">Cálculo Proporcional:
              <span>';
        echo "Si";
        echo '</span></p>';
    }
} else {
    // El valor de la columna 'calculo_proporcional' no existe
}

// Verificar la columna 'formulario_devolucion'
if (isset($admin[0]->formulario_devolucion)) {
    // El valor de la columna 'formulario_devolucion' existe
    if ($admin[0]->formulario_devolucion > 0) {
        echo '<p class="wti-p-label">Se genera número de folio del “Formulario de Devolución”:
              <span>';
        echo "Si";
        echo '</span></p>';
    }
} else {
    // El valor de la columna 'formulario_devolucion' no existe
}

// Verificar la columna 'nota_credito'
if (isset($admin[0]->nota_credito)) {
    // El valor de la columna 'nota_credito' existe
    if ($admin[0]->nota_credito > 0) {
        echo '<p class="wti-p-label">Se genera número de “Nota de crédito”:
              <span>';
        echo "Si";
        echo '</span></p>';
    }
} else {
    // El valor de la columna 'nota_credito' no existe
}

// Verificar la columna 'egreso_devolucion'
if (isset($admin[0]->egreso_devolucion)) {
    // El valor de la columna 'egreso_devolucion' existe
    if ($admin[0]->egreso_devolucion > 0) {
        echo '<p class="wti-p-label">Se genera número de “Egreso de la devolución”
              <span>';
        echo "Si";
        echo '</span></p>';
    }
} else {
    // El valor de la columna 'egreso_devolucion' no existe
}

// Verificar la columna 'rebaja_sistema'
if (isset($admin[0]->rebaja_sistema)) {
    // El valor de la columna 'rebaja_sistema' existe
    if ($admin[0]->rebaja_sistema > 0) {
        echo '<p class="wti-p-label">Se genera número de Id de la “Rebaja en sistema”:
              <span>';
        echo "Si";
        echo '</span></p>';
    }
} else {
    // El valor de la columna 'rebaja_sistema' no existe
}

// Verificar la columna 'numero_egreso_devolucion'
if (isset($admin[0]->numero_egreso_devolucion)) {
    // El valor de la columna 'numero_egreso_devolucion' existe
    echo '<p class="wti-p-label">N° de egreso de la devolución:
    <span>';
    echo $admin[0]->numero_egreso_devolucion;
    echo '</span></p>';
} else {
    // El valor de la columna 'numero_egreso_devolucion' no existe
}

// Verificar fecha_egreso_devolucion
if (!empty($admin[0]->fecha_egreso_devolucion)) {
    $fecha_valida = preg_match('/^\d{4}[-\/]\d{2}[-\/]\d{2}$/', $admin[0]->fecha_egreso_devolucion);
    if (!$fecha_valida) {
        // La fecha no es válida
    } else {
        echo '<p class="wti-p-label">Fecha de devolución:
        <span>';
        echo $admin[0]->fecha_egreso_devolucion;
        echo '</span></p>';
    }
}

// Verificar observaciones
if (!empty($admin[0]->observaciones)) {
    $observaciones_validas = substr($admin[0]->observaciones, 0, 600);
    echo '<p class="wti-p-label">Observaciones:
    <span>';
    echo $observaciones_validas;
    echo '</span></p>';
}

?>

                                <?php endif;?>

                                <?php if (isset($retiro[0]->secretaria_proc) && !empty($retiro[0]->secretaria_proc) && 1 == $retiro[0]->secretaria_proc): ?>
                                <h3 class="wti-title-h3">Secretaria Academica</h3>
                                <?php
if (isset($secre[0]->observaciones_anticipada_autorizada)) {
    // El valor de la columna 'rebaja_sistema' existe
    if ($secre[0]->observaciones_anticipada_autorizada > 0) {
        echo '<p class="wti-p-label">Observaciones Promoción anticipada:
              <span>';
        echo $secre[0]->observaciones_anticipada_autorizada;
        echo '</span></p>';
    }
} else {
    // El valor de la columna 'rebaja_sistema' no existe
}

if (isset($secre[0]->certificado_estudios_gen)) {
    // El valor de la columna 'rebaja_sistema' existe
    if ($secre[0]->certificado_estudios_gen > 0) {
        echo '<p class="wti-p-label">Certificado de Estudios Generado:
              <span>';
        echo "Si";
        echo '</span></p>';
    }
} else {
    // El valor de la columna 'rebaja_sistema' no existe
}

if (isset($secre[0]->certificado_estudios_sign)) {
    // El valor de la columna 'rebaja_sistema' existe
    if ($secre[0]->certificado_estudios_sign > 0) {
        echo '<p class="wti-p-label">Certificado de Estudios Firmado:
              <span>';
        echo "Si";
        echo '</span></p>';
    }
} else {
    // El valor de la columna 'rebaja_sistema' no existe
}

if (isset($secre[0]->obs_paritario_no_autorizada)) {
    // El valor de la columna 'rebaja_sistema' existe
    if ($secre[0]->obs_paritario_no_autorizada > 0) {
        echo '<p class="wti-p-label">Promoción Anticipada no autorizada:
              <span>';
        echo $secre[0]->obs_paritario_no_autorizada;
        echo '</span></p>';
    }
} else {
    // El valor de la columna 'rebaja_sistema' no existe
}

if (isset($secre[0]->obs_paritario_autorizada)) {
    // El valor de la columna 'rebaja_sistema' existe
    if ($secre[0]->obs_paritario_autorizada > 0) {
        echo '<p class="wti-p-label">Traslado a colegio Paritario Autorizado:
              <span>';
        echo $secre[0]->obs_paritario_autorizada;
        echo '</span></p>';
    }
} else {
    // El valor de la columna 'rebaja_sistema' no existe
}

if (isset($secre[0]->secre_pagella_gen)) {
    // El valor de la columna 'rebaja_sistema' existe
    if ($secre[0]->secre_pagella_gen > 0) {
        echo '<p class="wti-p-label">Certificado de Pagella Generado:
              <span>';
        echo "Si";
        echo '</span></p>';
    }
} else {
    // El valor de la columna 'rebaja_sistema' no existe
}

if (isset($secre[0]->secre_pagella_sign)) {
    // El valor de la columna 'rebaja_sistema' existe
    if ($secre[0]->secre_pagella_sign > 0) {
        echo '<p class="wti-p-label">Certificado de Pagella Firmado:
              <span>';
        echo "Si";
        echo '</span></p>';
    }
} else {
    // El valor de la columna 'rebaja_sistema' no existe
}

if (isset($secre[0]->secre_modifica_colegium)) {
    // El valor de la columna 'rebaja_sistema' existe
    if ($secre[0]->secre_modifica_colegium > 0) {
        echo '<p class="wti-p-label">Modificado en sistema unificado de alumnos:
              <span>';
        echo "Si";
        echo '</span></p>';
    }
} else {
    // El valor de la columna 'rebaja_sistema' no existe
}

?>

                                <?php endif;?>



                                <?php

if (isset($adjuntos)) {
    ?>
                                <h3 class="wti-title-h3">Adjuntos</h3>
                                <?php

    foreach ($adjuntos as $adj) {?>
                                <p> <a target="_blank" href="<?php echo $adj->url; ?>">
                                        <?php echo $adj->nombre; ?>
                                    </a>
                                </p>
                                <?php
}
    ?>

                                <?php
}
?>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-xs-12">
                        <div class="wti-qr">
                            <div><img src="<?php echo $chart; ?>" />
                            </div>
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div id="wti-retiro-enviado" class="wti-retiro-enviado row justify-content-center">
                        <h3 class="wti-title-h3">Firmas</h3>
                        <?php
foreach ($signs as $sign) {?>
                        <div class="col-md-12 col-xs-12 wti-jumbotron2">

                            <p><span class="wti_firmante"><?php echo $sign->quien_firma; ?></span> Solicitud de retiro
                                - <span><?php echo 'Numero de folio ' . $retiro[0]->id; ?></span>- Firmado
                                electrónicamente a
                                través
                                del correo -<span><?php echo $sign->name; ?></span>- desde la dirección ip
                                <span><?php echo $sign->IP; ?></span> el <span><?php echo $sign->fecha; ?></span>
                            </p>
                        </div>
                        <?php
}
?>
                    </div>
                </div>

            </div>
            <div class="col-md-3 wti-panel-sivmforms">
                <div class="wti-blur-base">
                    <div class="wti-sivmforms-workspace">
                        <?php include "subdetails/roles/admin.php";?>
                        <?php include "subdetails/roles/director.php";?>
                        <?php include "subdetails/roles/secretariaAcademica.php";?>
                        <?php include "subdetails/roles/rector.php";?>
                        <?php include "subdetails/roles/administracion.php";?>
                        <?php include "subdetails/roles/scolastica.php";?>
                    </div>
                </div>
                <div class="row wti-row">
                    <div class="timeline-container col-md-12">
                        <h3 class="wti-title-h3">Actividad de la solicitud</h3>
                        <div class="activity-feed">
                            <?php

foreach ($logs as $log) {?>
                            <div class="feed-item">
                                <div class="date"> <span class="text-has-action" data-toggle="tooltip"
                                        data-title="<?php echo $log->date; ?>" data-original-title=""
                                        title=""><?php echo tiempo_transcurrido($log->date); ?></span>
                                </div>
                                <div class="text"> <?php echo $log->description; ?> </div>
                            </div>
                            <?php
}
?>

                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>

</div>
<?php endif; ?>
<?php if ($retiro[0]->estatus == "pendiente" && !staff_can('administracion', 'retiros')): ?>
<div class="wti-retiro-alumnos">
    <div class="container">
        <div class="row wti-row">
            <p>Este retiro no ha sido ratificado todavía. No está permitido visualizarlo</p>
        </div>
    </div>
</div>
<?php endif; ?>

<?php
function tiempo_transcurrido($fecha)
{
    $fecha_actual = new DateTime();
    $fecha_hora = new DateTime($fecha);
    $diferencia = $fecha_actual->diff($fecha_hora);

    if ($diferencia->days > 0) {
        return "Hace " . $diferencia->days . " días";
    } elseif ($diferencia->h > 0) {
        return "Hace " . $diferencia->h . " horas";
    } else {
        return "Hace " . $diferencia->i . " minutos";
    }
}
?>