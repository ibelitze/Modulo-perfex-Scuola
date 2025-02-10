 <!-- ############################################################################################################ -->
 <!-- ADMIN START -->
 <!-- ############################################################################################################ -->
 <?php if (staff_can('admin', 'retiros')): ?>

    <div class="row wti-row scuola-director">
         <!-- <div class="open-panel">
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
         </div> -->
         <div class="col-md-12">
             <h3 class="wti-title-h3">Acciones del Admin</h3>
             <h5>Reenviar correo a tutor/apoderado</h5>
             <div class="radio radio-info">
                 <input type="radio" id="firma" name="mail_admin" value="firma">
                 <label for="procede">Correo para firma electrónica</label>
             </div>
             <div class="radio radio-info">
                 <input type="radio" id="ratifica" name="mail_admin" value="ratifica">
                 <label for="no_procede">Correo para ratificar</label>
             </div>
             <div class="radio radio-info">
                 <input type="radio" id="cierre" name="mail_admin" value="cierre">
                 <label for="no_procede">Correo de retiro cerrado</label>
             </div>
             <input type="hidden" name="id_retiro" id="id_retiro" value="<?php echo $retiro[0]->id; ?>" />
             <button class="btn btn-info" id="admin_form_enviar_correo">Enviar</button>
         </div>
         <div class="col-md-12" style="margin-top: 30px;">
             <h5>Anular retiro</h5>
             <button class="btn btn-info" id="admin_form_anular_retiro">Anular</button>
         </div>
         <div class="col-md-12" style="margin-top: 30px;">
             <h5>Cambiar fecha de retiro</h5>
             <div class="form-group" app-field-wrapper="startdate"><label for="startdate" class="control-label"> <small
                         class="req text-danger">* </small>Indique nueva fecha:</label>
                 <div class="input-group date"><input type="text" id="adminCambioFecha"
                         name="adminCambioFecha" class="form-control datepicker" value="" autocomplete="off">
                     <div class="input-group-addon">
                         <i class="fa fa-calendar calendar-icon"></i>
                     </div>
                 </div>
             </div>
             <button class="btn btn-info" id="admin_form_cambiar_fecha_retiro">Cambiar</button>
         </div>
         <div class="col-md-12" style="margin-top: 30px;">
             <h5>Cambiar colegio paritario:</h5>
             <input type="hidden" name="form_colegio_paritario" id="form_colegio_paritario" value="<?php echo $retiro[0]->colegio_paritario; ?>" />
             <button class="btn btn-info" id="admin_form_colegio_paritario">Cambiar</button>
         </div>
         <div class="col-md-12" style="margin-top: 30px;">
             <h5>Cambiar promocion anticipada:</h5>
             <input type="hidden" name="form_promocion_anticipada" id="form_promocion_anticipada" value="<?php echo $retiro[0]->promocion_anticipada; ?>" />
             <button class="btn btn-info" id="admin_form_prom_anticipada">Cambiar</button>
         </div>
    </div>

    

 <?php endif;?>
 <!-- ############################################################################################################ -->
 <!-- ADMIN END -->
 <!-- ############################################################################################################ -->