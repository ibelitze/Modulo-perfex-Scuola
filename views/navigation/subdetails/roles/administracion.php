 <!-- ############################################################################################################ -->
 <!-- DIRECTOR START -->
 <!-- ############################################################################################################ -->



 <?php if (staff_can('administracion', 'retiros')): ?>
     <!-- RETIRO EN PROCESO POR FIRMAR -->
     <?php if ("formalizado" == $retiro[0]->estatus): ?>

        <?php if (0 == $retiro[0]->adminitracion_proc): ?>

 <div class="row wti-row scuola-director">
     <div class="open-panel">
         <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
             <path
                 d="M18 13V19C18 19.5304 17.7893 20.0391 17.4142 20.4142C17.0391 20.7893 16.5304 21 16 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V8C3 7.46957 3.21071 6.96086 3.58579 6.58579C3.96086 6.21071 4.46957 6 5 6H11"
                 stroke="#259251" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
             <path d="M15 3H21V9" stroke="#259251" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
             <path d="M10 14L21 3" stroke="#259251" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
         </svg>
     </div>
     <div class="close-panel">
         <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
             <path
                 d="M6 1V4C6 4.53043 5.78929 5.03914 5.41421 5.41421C5.03914 5.78929 4.53043 6 4 6H1M19 6H16C15.4696 6 14.9609 5.78929 14.5858 5.41421C14.2107 5.03914 14 4.53043 14 4V1M14 19V16C14 15.4696 14.2107 14.9609 14.5858 14.5858C14.9609 14.2107 15.4696 14 16 14H19M1 14H4C4.53043 14 5.03914 14.2107 5.41421 14.5858C5.78929 14.9609 6 15.4696 6 16V19"
                 stroke="#259251" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
         </svg>
     </div>
     <form id="wti_admin">
         <div class="col-md-12">
             <h3 class="wti-title-h3">Acciones Administración</h3>
             <h5>Completar datos del retiro</h5>
             <div class="wti-form-wrapper">
                 <div class="form-group" app-field-wrapper="admin_ano_de_retiro">
                     <label for="director_no_procede" class="control-label">
                         <small class="req text-danger">* </small>Año del cual es retirado el alumno</label>
                     <select id="admin_ano_de_retiro" name="admin_ano_de_retiro"
                         class="form-control wti-admin-select-control required">
                         <option value="0">Seleccione</option>
                         <option value="actual">Actual</option>
                         <option value="proximo">Próximo</option>
                     </select>
                     <em class="help-block"></em>
                 </div>
             </div>
             <div class="wti-admin-proximo container_admin_anualidades">
                 <div class="wti-form-wrapper">
                     <div class="form-group" app-field-wrapper="admin_anualidades">
                         <label for="admin_anualidades" class="control-label">
                             <small class="req text-danger">* </small>¿Anualidades emitidas para próximo año?</label>
                         <select id="admin_anualidades" name="admin_anualidades"
                             class="form-control wti-admin-select-control required">
                             <option value="">Seleccionar</option>
                             <option value="no">No, sin emitir</option>
                             <option value="si">Sí, emitidas</option>
                         </select>
                         <em class="help-block"></em>
                     </div>
                 </div>
             </div>
             <div class="wti-admin-actual wti-efecto-contable">
                 <div class="wti-form-wrapper">
                     <div class="checkbox checkbox-info mbot20 no-mtop form-group">
                         <input type="checkbox" name="noTieneEfectoContable" value="false" id="noTieneEfectoContable">
                         <label for="noTieneEfectoContable">No tiene efecto contable</label>
                     </div>
                 </div>
             </div>
             <div class="wti-admin-actual wti-pago-colegiatura-container">
                 <div class="wti-form-wrapper">
                     <div class="form-group" app-field-wrapper="admin_forma_de_pago">
                         <label for="admin_forma_de_pago" class="control-label">
                             <small class="req text-danger">* </small>Forma de
                             pago de las Colegiaturas”:</label>
                         <select id="admin_forma_de_pago" name="admin_forma_de_pago"
                             class="form-control wti-admin-select-control required">
                             <option value="">Seleccionar</option>
                             <option value="anticipado">Anticipado</option>
                             <option value="mensual">Mensual</option>
                         </select>
                         <em class="help-block"></em>
                     </div>
                 </div>
             </div>
             <div class="wti-pago-colegiaturas wti-pago-colegiaturas-opciones">
                 <div class="wti-form-wrapper admin_checkbox_panticipado">
                     <h4>Confirmar operaciones de pago anticipado:</h4>
                     <div class="checkbox checkbox-info mbot20 no-mtop form-group">
                         <input type="checkbox" name="adminPayAnt_A" value="1" id="adminPayAnt_A">
                         <label for="adminPayAnt_A">Se hizo el cálculo proporcional</label>

                         <em class="help-block"></em>
                     </div>
                     <div class="checkbox checkbox-info mbot20 no-mtop form-group">
                         <input type="checkbox" name="adminPayAnt_B" value="1" id="adminPayAnt_B">
                         <label for="adminPayAnt_B">Se genera número de folio del “Formulario de Devolución”</label>
                         <em class="help-block"></em>
                     </div>
                     <div class="checkbox checkbox-info mbot20 no-mtop form-group">
                         <input type="checkbox" name="adminPayAnt_C" value="1" id="adminPayAnt_C">
                         <label for="adminPayAnt_C">Se genera número de “Nota de crédito”</label>
                         <em class="help-block"></em>
                     </div>
                     <div class="checkbox checkbox-info mbot20 no-mtop form-group">
                         <input type="checkbox" name="adminPayAnt_D" value="1" id="adminPayAnt_D">
                         <label for="adminPayAnt_D">Se genera número de “Egreso de la devolución”</label>
                         <em class="help-block"></em>
                     </div>
                     <div class="form-group" app-field-wrapper="observaciones_anual_anticipado">
                         <label for="no_procede_otros" class="control-label">Observaciones:</label>
                         <textarea name="observaciones_anual_anticipado" id="observaciones_anual_anticipado" rows="3"
                             cols="30"></textarea>
                     </div>
                 </div>
                 <div class="wti-form-wrapper admin_checkbox_pmensual">
                     <h4>Confirmar operaciones de pago mensual:</h4>
                     <div class="checkbox checkbox-info mbot20 no-mtop form-group">
                         <input type="checkbox" name="adminPayMen_A" value="" id="adminPayMen_A">
                         <label for="adminPayMen_A">Se hizo el cálculo proporcional para hacer
                             rebajas</label>
                         <em class="help-block"></em>
                     </div>
                     <div class="checkbox checkbox-info mbot20 no-mtop form-group">
                         <input type="checkbox" name="adminPayMen_B" value="" id="adminPayMen_B">
                         <label for="adminPayMen_B">Se genera número de Id de la “Rebaja en sistema”;</label>
                         <em class="help-block"></em>
                     </div>
                 </div>
                 <div class="wti-form-wrapper admin_checkbox_files_anticipado">
                     <h5>Carga de comprobantes pago anticipado</h5>
                     <label class="file-input-wrapper form-group">
                         <h5>Cálculo proporcional</h5>
                         <input type="file" id="adm_anticipado_1" name="fileCalculoProporcional"
                             accept=".pdf,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,.xls,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,.ppt,application/vnd.ms-powerpoint,application/vnd.openxmlformats-officedocument.presentationml.presentation,.jpg,image/jpeg,.png,image/png">
                     </label>
                     <label class="file-input-wrapper form-group">
                         <h5>Formulario de devolución</h5>
                         <input type="file" id="adm_anticipado_2" name="fileFormulariodedevolucion"
                             accept=".pdf,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,.xls,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,.ppt,application/vnd.ms-powerpoint,application/vnd.openxmlformats-officedocument.presentationml.presentation,.jpg,image/jpeg,.png,image/png">
                     </label>
                     <label class="file-input-wrapper form-group">
                         <h5>Nota de Crédito</h5>
                         <input type="file" id="adm_anticipado_3" name="fileNotadeCredito"
                             accept=".pdf,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,.xls,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,.ppt,application/vnd.ms-powerpoint,application/vnd.openxmlformats-officedocument.presentationml.presentation,.jpg,image/jpeg,.png,image/png">
                     </label>
                     <div class="form-group" app-field-wrapper="numero_egreso_devolucion">
                         <label for="numero_egreso_devolucion" class="control-label">Número de egreso de
                             devolución:</label>
                         <input type="text" name="numero_egreso_devolucion" id="numero_egreso_devolucion" />
                     </div>
                     <div class="form-group" app-field-wrapper="fecha_egreso_devolucion"><label
                             for="fecha_egreso_devolucion" class="control-label">Fecha egreso de la devolución</label>
                         <div class="input-group date"><input type="text" id="fecha_egreso_devolucion"
                                 name="fecha_egreso_devolucion" class="form-control datepicker" value=""
                                 autocomplete="off">
                             <div class="input-group-addon">
                                 <i class="fa fa-calendar calendar-icon"></i>
                             </div>
                         </div>
                     </div>
                 </div>
                 <div class="wti-form-wrapper admin_checkbox_files_mensual">
                     <h5>Carga de comprobantes pago mensual</h5>
                     <label class="file-input-wrapper form-group">
                         <h5>Cálculo cálculo proporcional para hacer rebajas</h5>
                         <input type="file" id="adm_mensual_1" name="fileCalculoProporcional"
                             accept=".pdf,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,.xls,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,.ppt,application/vnd.ms-powerpoint,application/vnd.openxmlformats-officedocument.presentationml.presentation,.jpg,image/jpeg,.png,image/png">
                     </label>
                     <label class="file-input-wrapper form-group">
                         <h5>Rebaja en sistema</h5>
                         <input type="file" id="adm_mensual_2" name="fileRebajaEnSistema"
                             accept=".pdf,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,.xls,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,.ppt,application/vnd.ms-powerpoint,application/vnd.openxmlformats-officedocument.presentationml.presentation,.jpg,image/jpeg,.png,image/png">
                     </label>
                 </div>
             </div>
             <input type="hidden" name="id_retiro" id="id_retiro" value="<?php echo $retiro[0]->id; ?>" />
             <input type="hidden" name="fk_escuela" id="fk_escuela" value="<?php echo $retiro[0]->fk_escuela; ?>" />
             <div class="btn-container btn_admin_submit_proximo_sin_efecto">
                 <button class="btn btn-info" id="admin_submit_proximo_sin_efecto">Cierre Administrativo</button>
             </div>
             <div class="btn-container btn_admin_submit_proximo_emitido_anticipado">
                 <button class="btn btn-info" id="admin_submit_proximo_emitido_anticipado">Cierre
                     Administrativo</button>
             </div>
             <div class="btn-container btn_admin_submit_proximo_emitido_mensual">
                 <button class="btn btn-info" id="admin_submit_proximo_emitido_mensual">Cierre
                     Administrativo</button>
             </div>
             <div class="btn-container btn_admin_submit_actual_anticipado">
                 <button class="btn btn-info" id="admin_submit_actual_anticipado">Cierre
                     Administrativo</button>
             </div>
             <div class="btn-container btn_admin_submit_actual_mensual">
                 <button class="btn btn-info" id="admin_submit_actual_mensual">Cierre
                     Administrativo</button>
             </div>
         </div>
     </form>
 </div>
        <?php endif;?> <!-- cierre [si administración procede] -->

    <?php endif;?> <!-- cierre [status formalizado] -->



    <!-- [ status cerrado, modificación de número de egreso ] -->
    <!-- [ status cerrado, modificación de número de egreso ] -->
    <!-- [ status cerrado, modificación de número de egreso ] -->

    <?php if ("cerrado" == $retiro[0]->estatus || "formalizado" == $retiro[0]->estatus): ?>

<div class="row wti-row scuola-director">
     <div class="open-panel">
         <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
             <path
                 d="M18 13V19C18 19.5304 17.7893 20.0391 17.4142 20.4142C17.0391 20.7893 16.5304 21 16 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V8C3 7.46957 3.21071 6.96086 3.58579 6.58579C3.96086 6.21071 4.46957 6 5 6H11"
                 stroke="#259251" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
             <path d="M15 3H21V9" stroke="#259251" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
             <path d="M10 14L21 3" stroke="#259251" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
         </svg>
     </div>
     <div class="close-panel">
         <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
             <path
                 d="M6 1V4C6 4.53043 5.78929 5.03914 5.41421 5.41421C5.03914 5.78929 4.53043 6 4 6H1M19 6H16C15.4696 6 14.9609 5.78929 14.5858 5.41421C14.2107 5.03914 14 4.53043 14 4V1M14 19V16C14 15.4696 14.2107 14.9609 14.5858 14.5858C14.9609 14.2107 15.4696 14 16 14H19M1 14H4C4.53043 14 5.03914 14.2107 5.41421 14.5858C5.78929 14.9609 6 15.4696 6 16V19"
                 stroke="#259251" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
         </svg>
     </div>
     <div id="wti_admin_numero_egreso">
         <div class="col-md-12">
             <h3 class="wti-title-h3">Modificar Número de egreso</h3>
             <h5>Modificar datos del número de egreso del retiro: <?php echo $retiro[0]->id; ?></h5>
             <div class="wti-form-wrapper">
                 <div class="form-group" app-field-wrapper="numero-egreso">
                    <label for="input-numero-egreso">Escriba el nuevo número:</label>
                    <input type="text" name="numero-egreso" id="input-numero-egreso" autocomplete="off">
                 </div>
             </div>

             <input type="hidden" name="id_retiro2" id="id_retiro2" value="<?php echo $retiro[0]->id; ?>" />
             <input type="hidden" name="fk_escuela2" id="fk_escuela2" value="<?php echo $retiro[0]->fk_escuela; ?>" />
             <div class="btn-container btn_admin_submit_numero_egreso">
                 <button class="btn btn-info" id="admin_submit_numero_egreso">Modificar</button>
             </div>
         </div>
     </div>
 </div>


    <?php endif;?> <!-- cierre [status cerrado] -->




<?php endif;?> <!-- cierre [si es administrador] -->


 <!-- ############################################################################################################ -->
 <!-- DIRECTOR END -->
 <!-- ############################################################################################################ -->