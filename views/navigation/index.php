<div class="wti-retiro-alumnos">
    <div class="container">


        <!--  VISTA HTML  -->

        <body class="app admin  clients user-id-1 chrome">
            <div id="wrapper">
                <div class="content">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel_s">
                                <div class="panel-body">
                                    <div class="clearfix"></div>
                                    <hr class="hr-panel-heading" />
                                    <div class="row mbot15">
                                        <div class="col-md-12">
                                            <h4 class="no-margin">Resumen de retiros</h4>
                                            <!--OTHER -->
                                        </div>
                                        <div class="col-md-2 col-xs-6 border-right">
                                            <h3 class="bold"><?php echo count($abiertos); ?></h3>
                                            <span class="text-dark">Abiertos</span>
                                        </div>
                                        <div class="col-md-2 col-xs-6 border-right">
                                            <h3 class="bold"><?php echo count($pendientes); ?></h3>
                                            <span class="text-info">Pendientes</span>
                                        </div>
                                        <div class="col-md-2 col-xs-6 border-right">
                                            <h3 class="bold"><?php echo count($pendientes); ?></h3>
                                            <span class="text-success">En proceso</span>
                                        </div>
                                        <div class="col-md-2 col-xs-6 border-right">
                                            <h3 class="bold"><?php echo count($anulados); ?></h3>
                                            <span class="text-muted">
                                                <span>Anulados</span>
                                            </span>
                                        </div>
                                        <div class="col-md-2  col-xs-6 border-right">
                                            <h3 class="bold"><?php echo count($formalizados); ?></h3>
                                            <span class="text-success">Formalizados</span>
                                        </div>
                                        <div class="col-md-2 col-xs-6">
                                            <h3 class="bold"><?php echo count($cerrados); ?></h3>
                                            <span class="text-muted">
                                                <span>Cerrados</span>
                                            </span>
                                        </div>
                                    </div>
                                    <hr class="hr-panel-heading" />
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <button class="btn btn-success" onclick="table_to_excel()">Descargar Excel</button> 
                        </div>
                    </div>

                </div>
            </div>

            <div id="retiros-table">
                <div class="content">
                    <div class="row">
                        <div class="col-md-12">
                            <table id="retiros-table1" class="hover" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>RUT estudiante</th>
                                        <th>Nombre</th>
                                        <th>Grado escolar</th>
                                        <th>Fecha aviso</th>
                                        <th>Fecha retiro</th>
                                        <th>Motivo retiro</th>
                                        <th>Año</th>
                                        <th>Tipo</th>
                                        <th>Estatus</th>
                                        <th>Espera</th>
                                        <th>Ver detalles</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
if (count($retiros) > 0) {
    for ($i = 0; $i < count($retiros); $i++) {
        $actual = $retiros[$i];
        ?>
<?php
    $fechaAviso = new DateTime($actual->fecha_aviso);
    $fechaUltimaActualizacion = new DateTime($actual->fecha_ultima_actualizacion_estatus);
    $diferencia = $fechaAviso->diff($fechaUltimaActualizacion);
    $diasDiferencia = $diferencia->days;
    
    $fechaRetiro = new DateTime($actual->fecha_retiro);
    $hoy = new DateTime();
    $faltantesFuturo = $hoy->diff($fechaRetiro);
    $diasDifFuturo = $faltantesFuturo->days;
    $yaImprimi = false;
    

if ($actual->estatus !== 'cerrado') {

        // imprimiento el color status (o de ser futuro: ponerle la etiqueta)
    if($actual->futuro > 0){
        if( strtotime('now') > strtotime($actual->fecha_retiro) ) {
            if ($actual->estatus == "formalizado" && staff_can('administracion', 'retiros')) {
                echo '<tr class="wti-verde">';
            }
            else {
                echo '<tr class="' . $actual->colorStats . '">';
            }
        }
        else if (strtotime($actual->fecha_retiro) > strtotime('now') && $diasDifFuturo <= 3) {
            echo '<tr class="' . $actual->colorStats . '">';
        }
        else {
            echo '<tr class="' . $actual->colorStats . ' wti-force-futuro">';
        }
    } else {
        // está formalizado y se le dan 15 días extras a administración para procesar
        if ($actual->estatus == "formalizado" && strtotime('now') > strtotime($actual->fecha_retiro)
            && staff_can('administracion', 'retiros')) {
            echo '<tr>';
        } else {
            echo '<tr class="' . $actual->colorStats . '">';
        }
    }

    echo '<td>' . $actual->id . '</td>';
    echo '<td>' . $actual->rut_estudiante . '</td>';
    echo '<td>' . $actual->company . '</td>';
    echo '<td>' . $actual->curso . '</td>';
    echo '<td>' . $actual->fecha_aviso . '</td>';
    echo '<td>' . $actual->fecha_retiro . '</td>';
    echo '<td>' . $actual->motivo_retiro . '</td>';
    echo '<td>' . $actual->año_academico . '</td>';
    echo '<td>' . $actual->tipo . '</td>';
    echo '<td>' . $actual->estatus . '</td>';
    
    // calculando los días que le restan al retiro, dependiendo de:
    // si es futuro, si ya pasó la fecha, si está cerrado, etc.
    
    // si el retiro está pendiente_formalizado: 
    // los directores deberían tener más días para procesarlo
    if ($actual->estatus == "pendiente_ratificado" && strtotime('now') > strtotime($actual->fecha_retiro) && staff_can('director', 'retiros')) {

        $fechaRetiro3dias = new DateTime($actual->fecha_retiro . ' +3 day');
        $faltantesFuturo = $hoy->diff($fechaRetiro3dias);
        $mas3dias = $faltantesFuturo->days;
        if ($fechaRetiro3dias > $hoy && $fechaRetiro3dias > 0) {
            echo '<td>' . $mas3dias . ' días</td>';
        } else {
            echo '<td>0 días</td>';
        }
        $yaImprimi = true;
    }

    // si el retiro está formalizado y ya pasó la fecha del retiro:
    // se agregan 15 días a la fecha de ese retiro, para ser procesado con calma
    if ($actual->estatus == "formalizado" && strtotime('now') > strtotime($actual->fecha_retiro) 
        && $actual->futuro < 1 && staff_can('administracion', 'retiros')) {

        $fechaRetiro15dias = new DateTime($actual->fecha_retiro . ' +15 day');
        $faltantesFuturo = $hoy->diff($fechaRetiro15dias);
        $mas15dias = $faltantesFuturo->days;
        if ($fechaRetiro15dias > $hoy && $fechaRetiro15dias > 0) {
            echo '<td>' . $mas15dias . ' días</td>';
        } else {
            echo '<td>0 días</td>';
        }
        $yaImprimi = true;
    }

    // retiros futuros que se formalizaron. Les damos más tiempo para procesarlos
    else if ($actual->futuro > 0 && $actual->estatus == "formalizado" 
        && strtotime('now') > strtotime($actual->fecha_retiro) && staff_can('administracion', 'retiros')) {

        $fechaRetiro15dias = new DateTime($actual->fecha_retiro . ' +15 day');
        $faltantesFuturo = $hoy->diff($fechaRetiro15dias);
        $mas15dias = $faltantesFuturo->days;
        if ($fechaRetiro15dias > $hoy && $fechaRetiro15dias > 0) {
            echo '<td>' . $mas15dias . ' días</td>';
        } else {
            echo '<td>0 días</td>';
        }
        $yaImprimi = true;
    }

    // el retiro es futuro y todavía no llega la fecha a que se formalice
    else if ($actual->futuro > 0 && $actual->estatus != "formalizado") {
        if( strtotime('now') > strtotime($actual->fecha_retiro) ) {
            echo '<td>0 días</td>';
        }
        else {
            echo '<td>' . $diasDifFuturo . ' días</td>';
        }
        $yaImprimi = true;
    }
    else if ($actual->estatus == "por_firmar_NULLA") {
        echo '<td>Urgente</td>';
        $yaImprimi = true;
    }

    else {
        if (!$yaImprimi) {
            switch ($actual->colorStats) {
                case 'wti-verde':
                    echo '<td>En tiempo</td>';
                    break;
                case 'wti-amarillo':
                    echo '<td>Alerta</td>';
                    break;
                case 'wti-rojo':
                    echo '<td>Urgente</td>';
                    break;
                case 'wti-gray':
                    echo '<td>Retrasada</td>';
                    break;
            }
        }
    }

    echo '<td><button class="btn btn-success ver-detalles ir-' . $actual->id . '">Ver detalles</button></td>';
    
    echo '</tr>';
} else {
    echo '<tr>';
    echo '<td>' . $actual->id . '</td>';
    echo '<td>' . $actual->rut_estudiante . '</td>';
    echo '<td>' . $actual->company . '</td>';
    echo '<td>' . $actual->curso . '</td>';
    echo '<td>' . $actual->fecha_aviso . '</td>';
    echo '<td>' . $actual->fecha_retiro . '</td>';
    echo '<td>' . $actual->motivo_retiro . '</td>';
    echo '<td>' . $actual->año_academico . '</td>';
    echo '<td>' . $actual->tipo . '</td>';
    echo '<td>' . $actual->estatus . '</td>';
    echo '<td>' . $diasDiferencia . ' días</td>'; // Empty cell for the ninth column
    echo '<td><button class="btn btn-success ver-detalles ir-' . $actual->id . '">Ver detalles</button></td>';
    echo '</tr>';
}
?>

                                    <?php }
}
?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>ID</th>
                                        <th>RUT estudiante</th>
                                        <th>Nombre</th>
                                        <th>Grado escolar</th>
                                        <th>Fecha aviso</th>
                                        <th>Fecha retiro</th>
                                        <th>Motivo retiro</th>
                                        <th>Año</th>
                                        <th>Tipo</th>
                                        <th>Estatus</th>
                                        <th>Espera</th>
                                        <th>Ver detalles</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </body>


        <script src="https://code.jquery.com/jquery-3.6.3.min.js"
            integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
        <script type="text/javascript" id="datatables-js"
            src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" id="datatables-js"
            src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>
            <script defer src="//cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script>
        <script type="text/javascript" id="google-js" src="https://apis.google.com/js/api.js?onload=onGoogleApiLoad"
            defer></script>
        <script>
        $('.ver-detalles').on('click', (e) => {
            let classList = $(e.target).attr('class');
            let arrClasses = classList.split(' ');
            let id = arrClasses[3].split('-').pop();

            // redireccionar
            let domain = window.location.href;
            location.href = domain + '/details/' + id;
        });
        </script>
        <script>
            function table_to_excel() {
                $('#retiros-table1').table2excel({
                    name: 'Scuola Italiana - retiros',
                    filename: 'Export_Scuola_retiros.xls', // do include extension
                    preserveColors: true // set to true if you want background colors and font colors preserved
                });
            }
        </script>
        <script>
        $(document).ready(function() {
            $('#retiros-table1').DataTable({
                "ordering": true,
                "language": {
                    "lengthMenu": "Mostrar _MENU_ retiros por página",
                    "zeroRecords": "No encontrado - lo sentimos",
                    "info": "Mostrando _PAGE_ de _PAGES_ retiros",
                    "infoEmpty": "No hay retiros abiertos",
                    "infoFiltered": "(Filtrado de un total de _MAX_ retiros)",
                    "search": "Buscar: ",
                    "paginate": {
                        "first": "Primero",
                        "last": "Último",
                        "next": "Próximo",
                        "previous": "Anterior"
                    },
                    "aria": {
                        "sortAscending": ": Activado el filtro de columna ascendente",
                        "sortDescending": ": Activado el filtro de columna descendente"
                    }
                }
            });
        });
        </script>


    </div>

</div>