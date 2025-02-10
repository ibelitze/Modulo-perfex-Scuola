 <!-- ############################################################################################################ -->
 <!-- RECTOR START -->
 <!-- ############################################################################################################ -->
 <?php if (staff_can('rectoria', 'retiros')): ?>
 <!-- RETIRO EN PROCESO POR FIRMAR -->
 <?php if ("en proceso" == $retiro[0]->estatus): ?>
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
     <div class="col-md-12">
         <h3 class="wti-title-h3">Acciones del Rector(a)</h3>
         <h5>Firmar Procesamiento de Retiro</h5>
         <input type="hidden" name="id_retiro" id="id_retiro" value="<?php echo $retiro[0]->id; ?>" />
         <input type="hidden" name="fk_escuela" id="fk_escuela" value="<?php echo $retiro[0]->fk_escuela; ?>" />
         <button class="btn btn-info" id="rector_form_firma_fomalizado">Firmar</button>
     </div>
 </div>
 <?php endif;?>
 <?php if ($retiro[0]->estatus == "por_firmar_NULLA" && $retiro[0]->firma_rector_2 == 0 && 1 == $retiro[0]->colegio_paritario && 1 == $retiro[0]->nulla_send ): ?>
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
     <div class="col-md-12">
         <h3 class="wti-title-h3">Acciones del Rector(a)</h3>
         <!-- INICIO formulario NULLA OSTA -->
         <div class="wti-form-wrapper paritario-autorizado">
             <h5>Firma certificado Nulla Osta</h5>
             <div class="form-group" app-field-wrapper="nulla_nombre">
                 <label for="nulla_nombre" class="control-label">Nombre Alumno:</label>
                 <input type="text" name="nulla_nombre" id="nulla_nombre" value="<?php echo $retiro[0]->nombre; ?>"
                     disabled />
             </div>
             <div class="form-group" app-field-wrapper="nulla_nombre">
                 <label for="nulla_curso" class="control-label">Curso:</label>
                 <input type="text" name="nulla_curso" id="nulla_curso" value="<?php echo $retiro[0]->curso; ?>"
                     disabled />
             </div>
             <div class="form-group" app-field-wrapper="nulla_nombre">
                 <label for="nulla_nivel" class="control-label">Nivel:</label>
                 <input type="text" name="nulla_nivel" id="nulla_nivel" value="<?php echo $retiro[0]->nivel; ?>"
                     disabled />
             </div>
             <div class="form-group" app-field-wrapper="nulla_ciudad">
                 <label for="nulla_ciudad" class="control-label">Ciudad de nacimiento:</label>
                 <input type="text" name="nulla_ciudad" id="nulla_ciudad" value="<?php echo $secre[0]->nulla_ciudad; ?>"
                     disabled />
             </div>
             <div class="form-group" app-field-wrapper="nulla_pais">
                 <label for="nulla_ciudad" class="control-label">Pa铆s de nacimiento:</label>
                 <input type="text" name="nulla_pais" id="nulla_pais" value="<?php echo $secre[0]->nulla_pais; ?>"
                     disabled />
             </div>
             <div class="form-group" app-field-wrapper="nulla_fecha"><label for="nulla_fecha"
                     class="control-label">Fecha de nacimiento</label>
                 <div class="input-group date"><input type="text" id="nulla_fecha" name="nulla_fecha"
                         class="form-control datepicker" autocomplete="off"
                         value="<?php echo $secre[0]->nulla_fecha; ?>" disabled>
                     <div class="input-group-addon">
                         <i class="fa fa-calendar calendar-icon"></i>
                     </div>
                 </div>
             </div>
             <div class="form-group" app-field-wrapper="nulla_domicilio">
                 <label for="nulla_domicilio" class="control-label">Domicilio del alumno:</label>
                 <input type="text" name="nulla_domicilio" id="nulla_domicilio"
                     value="<?php echo $secre[0]->nulla_domicilio; ?>" disabled />
             </div>
             <div class="form-group" app-field-wrapper="nulla_correlativo">
                 <label for="nulla_correlativo" class="control-label">Correlativo:</label>
                 <input type="text" name="nulla_correlativo" id="nulla_correlativo"
                     value="<?php echo $secre[0]->nulla_correlativo; ?>" disabled />
             </div>
             <div class="form-group" app-field-wrapper="nulla_fecha"><label for="nulla_emision"
                     class="control-label">Fecha de emisión</label>
                 <div class="input-group date"><input type="text" id="nulla_emision" name="nulla_emision"
                         class="form-control datepicker" autocomplete="off"
                         value="<?php echo $secre[0]->nulla_emision; ?>" disabled>
                     <div class="input-group-addon">
                         <i class="fa fa-calendar calendar-icon"></i>
                     </div>
                 </div>
             </div>
             <div class="btn-container sec_preview_null">
                 <a class="btn btn-default" id="sec_preview_null">Previsualizar Nulla
                     Osta</a>
             </div>
             <hr>
         </div>
         <input type="hidden" name="id_retiro" id="id_retiro" value="<?php echo $retiro[0]->id; ?>" />
         <input type="hidden" name="fk_escuela" id="fk_escuela" value="<?php echo $retiro[0]->fk_escuela; ?>" />
         <div class="btn-container btn_rector_submit_firma_nulla_osta">
             <button class="btn btn-info btn-def" id="rector_submit_firma_nulla_osta">FIRMAR</button>
         </div>
         <!-- fin formulario NULLA OSTA -->
     </div>
 </div>
 <?php endif;?>



 <?php endif;?>
 <!-- ############################################################################################################ -->
 <!-- DIRECTOR END -->
 <!-- ############################################################################################################ -->