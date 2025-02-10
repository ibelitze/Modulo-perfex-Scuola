 <!-- ############################################################################################################ -->
 <!-- DIRECTOR START -->
 <!-- ############################################################################################################ -->
 <?php if (staff_can('director', 'retiros')): ?>
 <!-- RETIRO ABIERTO -->
     <?php if ($retiro[0]->estatus == "abierto" && $retiro[0]->estatus != "pendiente"): ?>
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
             <h3 class="wti-title-h3">Acciones del Director</h3>
             <h5>¿Procede el retiro?</h5>
             <div class="radio radio-info">
                 <input type="radio" id="procede" name="procede" value="1">
                 <label for="procede">Procede</label>
             </div>
             <div class="radio radio-info">
                 <input type="radio" id="no_procede" name="procede" value="0">
                 <label for="no_procede">No procede</label>
             </div>
             <div class="form-group" app-field-wrapper="director_no_procede" style=display:none>
                 <label for="director_no_procede" class="control-label">
                     <small class="req text-danger">* </small>Validar si procede o no el retiro</label>
                 <select id="director_no_procede" name="director_no_procede" class="form-control">
                     <option value="Alumno sigue en clases, por lo que fecha de retiro no procede">Alumno
                         sigue en clases, por lo que fecha de retiro no procede</option>
                     <option value="Padres indican a Director de área que han desistido del retiro">Padres
                         indican a Director de área que han desistido del retiro</option>
                     <option value="0">Otros</option>
                 </select>
             </div>
             <div class="form-group" app-field-wrapper="no_procede_otros" style=display:none>
                 <label for="no_procede_otros" class="control-label">
                     <small class="req text-danger">* </small>Especifique:</label>
                 <textarea name="no_procede_otros" id="no_procede_otros" rows="3" cols="30" required
                     style=display:none></textarea>
             </div>
             <?php /* NUEVO RECUADRO DE OBSERVACIONES: SI PROCEDE  */ ?>
             <div class="form-group" app-field-wrapper="procede_motivos" style=display:none>
                 <label for="procede_motivos" class="control-label">
                     <small class="req text-danger">* </small>Observaciones:</label>
                 <textarea name="procede_motivos" id="procede_motivos" rows="3" cols="30"
                     style=display:none></textarea>
             </div>
             <input type="hidden" name="id_retiro" id="id_retiro" value="<?php echo $retiro[0]->id; ?>" />
             <input type="hidden" name="fk_escuela" id="fk_escuela" value="<?php echo $retiro[0]->fk_escuela; ?>" />
             <button class="btn btn-info" id="director_form_procede">Continuar</button>
         </div>
     </div>
     <?php endif;?>
     <!-- RETIRO ANULACION EN REVISION -->
     <?php if ("anulado en revision" == $retiro[0]->estatus): ?>
     <div class="row wti-row scuola-director">
         <div class="col-md-12">
             <h3 class="wti-title-h3">Acciones del Director</h3>
             <h5>¿La anulación es correcta?</h5>
             <div class="radio radio-info">
                 <input type="radio" id="void_procede" name="void_procede" value="1">
                 <label for="void_procede">Anulación Procede</label>
             </div>
             <div class="radio radio-info">
                 <input type="radio" id="void_no_procede" name="void_procede" value="0">
                 <label for="void_no_procede">Anulación No procede</label>
             </div>
             <div class="form-group" app-field-wrapper="no_procede_otros" style=display:none>
                 <label for="no_procede_otros" class="control-label">
                     <small class="req text-danger">* </small>Motivo de Posible Reapertura:</label>
                 <textarea name="void_no_procede_motivo" id="void_no_procede_motivo" rows="3" cols="30" required
                     style=display:none></textarea>
             </div>
             <input type="hidden" name="id_retiro" id="id_retiro" value="<?php echo $retiro[0]->id; ?>" />
             <input type="hidden" name="fk_escuela" id="fk_escuela" value="<?php echo $retiro[0]->fk_escuela; ?>" />
             <button class="btn btn-info" id="director_form_void_procede">Continuar</button>
         </div>
     </div>
     <?php endif;?>
     <!-- RETIRO EN PROCESO POR FIRMAR-->
     <?php if (($retiro[0]->estatus == "en proceso" || $retiro[0]->estatus == "pendiente_ratificado") && $retiro[0]->firma_director == 0): ?>
     <div class="row wti-row scuola-director">
         <div class="col-md-12">
             <h3 class="wti-title-h3">Acciones del Director(a)</h3>
             <div class="form-group" app-field-wrapper="startdate"><label for="startdate" class="control-label"> <small
                         class="req text-danger">* </small>Fecha de última asistencia según libro de clases</label>
                 <div class="input-group date"><input type="text" id="directorUltimaAsistencia"
                         name="directorUltimaAsistencia" class="form-control datepicker" value="" autocomplete="off">
                     <div class="input-group-addon">
                         <i class="fa fa-calendar calendar-icon"></i>
                     </div>
                 </div>
             </div>
             <div class="form-group">
                <label>
                    <input type="radio" value="si" name="directorAuthPromoAnticipada" id="directorAuthPromoAnticipada">
                    Se autoriza una promoción anticipada
                </label>

                <label>
                 <input type="radio" value="no" name="directorAuthPromoAnticipada" id="directorAuthPromoAnticipada">
                    NO se autoriza una promoción anticipada
                </label>
             </div>

             <input type="hidden" name="id_retiro" id="id_retiro" value="<?php echo $retiro[0]->id; ?>" />
             <input type="hidden" name="fk_escuela" id="fk_escuela" value="<?php echo $retiro[0]->fk_escuela; ?>" />
             <button class="btn btn-info" id="director_form_firma_fomalizado">Firmar</button>
         </div>
     </div>
     <?php endif;?>

 <?php endif;?>
 <!-- ############################################################################################################ -->
 <!-- DIRECTOR END -->
 <!-- ############################################################################################################ -->