<?php if (staff_can('sec_academica', 'retiros')): ?>
                <!-- ############################################################################################################ -->
                <!-- SECRETARIA ACADEMICA START -->
                <!-- ############################################################################################################ -->

                <!-- RETIRO REAPERTURA ANULADO -->
                <?php if ($retiro[0]->estatus == "anulado"): ?>
                <div class="row wti-row scuola-director">
                    <div class="open-panel">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M18 13V19C18 19.5304 17.7893 20.0391 17.4142 20.4142C17.0391 20.7893 16.5304 21 16 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V8C3 7.46957 3.21071 6.96086 3.58579 6.58579C3.96086 6.21071 4.46957 6 5 6H11"
                                stroke="#259251" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M15 3H21V9" stroke="#259251" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M10 14L21 3" stroke="#259251" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
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
                        <h3 class="wti-title-h3">Acciones Sec. Académica</h3>
                        <h5>¿Reabrir retiro anulado?</h5>
                        <input type="hidden" name="id_retiro" id="id_retiro" value="<?php echo $retiro[0]->id; ?>" />
                        <input type="hidden" name="fk_escuela" id="fk_escuela"
                            value="<?php echo $retiro[0]->fk_escuela; ?>" />
                        <button class="btn btn-success" id="secretaria_reabrir_retiro_anulado">
                            REABRIR</button>
                    </div>
                </div>
                <?php endif;?>

                <!-- RETIRO POSIBLE REAPERTURA -->
                <?php if ("posible_reapertura" == $retiro[0]->estatus): ?>
                <div class="row wti-row scuola-director">
                    <div class="open-panel">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M18 13V19C18 19.5304 17.7893 20.0391 17.4142 20.4142C17.0391 20.7893 16.5304 21 16 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V8C3 7.46957 3.21071 6.96086 3.58579 6.58579C3.96086 6.21071 4.46957 6 5 6H11"
                                stroke="#259251" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M15 3H21V9" stroke="#259251" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M10 14L21 3" stroke="#259251" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
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
                        <h3 class="wti-title-h3">Acciones Sec. Académica</h3>
                        <h5>¿Reabrir Retiro?</h5>
                        <input type="hidden" name="id_retiro" id="id_retiro" value="<?php echo $retiro[0]->id; ?>" />
                        <input type="hidden" name="fk_escuela" id="fk_escuela"
                            value="<?php echo $retiro[0]->fk_escuela; ?>" />
                        <button class="btn btn-success" id="secretaria_reabrir_retiro">
                            REABRIR</button>
                        <button class="btn btn-danger" id="secretaria_anular_retiro">
                            NO APLICA</button>
                    </div>
                </div>
                <?php endif;?>

                <!-- RETIRO FORMALIZADO -->
                <?php if ("formalizado" == $retiro[0]->estatus && 0 == $retiro[0]->secretaria_proc): ?>
                <div class="row wti-row scuola-director">
                    <div class="open-panel">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M18 13V19C18 19.5304 17.7893 20.0391 17.4142 20.4142C17.0391 20.7893 16.5304 21 16 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V8C3 7.46957 3.21071 6.96086 3.58579 6.58579C3.96086 6.21071 4.46957 6 5 6H11"
                                stroke="#259251" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M15 3H21V9" stroke="#259251" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M10 14L21 3" stroke="#259251" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </div>
                    <div class="close-panel">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M6 1V4C6 4.53043 5.78929 5.03914 5.41421 5.41421C5.03914 5.78929 4.53043 6 4 6H1M19 6H16C15.4696 6 14.9609 5.78929 14.5858 5.41421C14.2107 5.03914 14 4.53043 14 4V1M14 19V16C14 15.4696 14.2107 14.9609 14.5858 14.5858C14.9609 14.2107 15.4696 14 16 14H19M1 14H4C4.53043 14 5.03914 14.2107 5.41421 14.5858C5.78929 14.9609 6 15.4696 6 16V19"
                                stroke="#259251" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                    <form id="wti_secre">
                        <div class="col-md-12">
                            <h3 class="wti-title-h3">Acciones Sec. Académica</h3>
                            <!-- -------------------------------------------- -->
                            <?php if (0 == $retiro[0]->authpromoanti && 0 == $retiro[0]->secretaria_proc && 0 == $retiro[0]->nulla_send): ?>
                            <h4>Promoción Anticipada "NO" Autorizada</h4>
                            <div class="wti-form-wrapper anticipada-no-autorizada">
                                <div class="form-group" app-field-wrapper="observaciones_anticipada_no_autorizada">
                                    <label for="observaciones_anticipada_no_autorizada"
                                        class="control-label">Observaciones:</label>
                                    <textarea name="observaciones_anticipada_no_autorizada"
                                        id="observaciones_anticipada_no_autorizada" rows="3" cols="30"></textarea>
                                </div>
                            </div>

                            <?php endif;?>
                            <!-- -------------------------------------------- -->
                            <?php if (1 == $retiro[0]->authpromoanti && 0 == $retiro[0]->secretaria_proc && 0 == $retiro[0]->nulla_send): ?>
                            <h4>Promoción Anticipada Autorizada</h4>
                            <div class="wti-form-wrapper anticipada-autorizada">
                                <div class="form-group" app-field-wrapper="observaciones_anticipada_autorizada">
                                    <label for="observaciones_anticipada_autorizada"
                                        class="control-label">Observaciones:</label>
                                    <textarea name="observaciones_anticipada_autorizada"
                                        id="observaciones_anticipada_autorizada" rows="3" cols="30"></textarea>
                                </div>
                                <div class="checkbox checkbox-info mbot20 no-mtop form-group">
                                    <input type="checkbox" name="certificado_estudios_gen" value="1"
                                        id="certificado_estudios_gen">
                                    <label for="certificado_estudios_gen">Certificado Anual de Estudios generado</label>
                                    <em class="help-block"></em>
                                </div>
                                <div class="checkbox checkbox-info mbot20 no-mtop form-group">
                                    <input type="checkbox" name="certificado_estudios_sign" value="1"
                                        id="certificado_estudios_sign">
                                    <label for="certificado_estudios_sign">Certificado Anual de Estudios firmado</label>
                                    <em class="help-block"></em>
                                </div>
                            </div>
                            <div class="wti-form-wrapper anticipada-autorizada">
                                <h5>Carga de archivos - Promoción Anticipada</h5>
                                <label class="file-input-wrapper form-group">
                                    <h5>Certificado Anual de Estudios</h5>
                                    <input type="file" id="sec_certifcado_estudios" name="fileCertificadoEstudios"
                                        accept=".pdf,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,.xls,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,.ppt,application/vnd.ms-powerpoint,application/vnd.openxmlformats-officedocument.presentationml.presentation,.jpg,image/jpeg,.png,image/png">
                                </label>
                            </div>
                            <?php endif;?>
                            <!-- -------------------------------------------- -->
                            <!-- -------------------------------------------- -->
                            <?php if (0 == $retiro[0]->colegio_paritario && 0 == $retiro[0]->secretaria_proc && 0 == $retiro[0]->nulla_send): ?>
                            <h4>No aplica Traslado a colegio Paritario</h4>
                            <div class="wti-form-wrapper paritario-no-autorizado">
                                <div class="form-group" app-field-wrapper="obs_paritario_no_autorizada">
                                    <label for="obs_paritario_no_autorizada"
                                        class="control-label">Observaciones:</label>
                                    <textarea name="obs_paritario_no_autorizada" id="obs_paritario_no_autorizada"
                                        rows="3" cols="30"></textarea>
                                </div>
                            </div>
                            <?php endif;?>
                            <!-- -------------------------------------------- -->
                            <?php if (1 == $retiro[0]->colegio_paritario && 0 == $retiro[0]->secretaria_proc && 0 == $retiro[0]->nulla_send): ?>
                            <h4>Aplica Traslado a colegio Paritario</h4>
                            <div class="wti-form-wrapper paritario-autorizado">
                                <div class="form-group" app-field-wrapper="obs_paritario_autorizada">
                                    <label for="obs_paritario_autorizada" class="control-label">Observaciones:</label>
                                    <textarea name="obs_paritario_autorizada" id="obs_paritario_autorizada" rows="3"
                                        cols="30"></textarea>
                                </div>
                                <div class="checkbox checkbox-info mbot20 no-mtop form-group">
                                    <input type="checkbox" name="secre_pagella_gen" value="1" id="secre_pagella_gen">
                                    <label for="secre_pagella_gen">Certificado de “Pagella” generado</label>
                                    <em class="help-block"></em>
                                </div>
                                <div class="checkbox checkbox-info mbot20 no-mtop form-group">
                                    <input type="checkbox" name="secre_pagella_sign" value="1" id="secre_pagella_sign">
                                    <label for="secre_pagella_sign">Certificado de “Pagella” firmado</label>
                                    <em class="help-block"></em>
                                </div>
                            </div>
                            <div class="wti-form-wrapper paritario-autorizado">
                                <h5>Carga de archivos - Colegio Paritario</h5>
                                <label class="file-input-wrapper form-group">
                                    <h5>Certificado de “Pagella”</h5>
                                    <input type="file" id="sec_certifcado_pagella" name="fileCertificadoPagella"
                                        accept=".pdf,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,.xls,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,.ppt,application/vnd.ms-powerpoint,application/vnd.openxmlformats-officedocument.presentationml.presentation,.jpg,image/jpeg,.png,image/png">
                                </label>
                            </div>
                            <div class="wti-form-wrapper paritario-autorizado">
                                <h5>Generación certificado Nulla Osta</h5>
                                <div class="form-group" app-field-wrapper="nulla_nombre">
                                    <label for="nulla_nombre" class="control-label">Nombre Alumno:</label>
                                    <input type="text" name="nulla_nombre" id="nulla_nombre"
                                        value="<?php echo $retiro[0]->nombre; ?>" disabled />
                                </div>
                                <div class="form-group" app-field-wrapper="nulla_nombre">
                                    <label for="nulla_curso" class="control-label">Curso:</label>
                                    <input type="text" name="nulla_curso" id="nulla_curso"
                                        value="<?php echo $retiro[0]->curso; ?>" disabled />
                                </div>
                                <div class="form-group" app-field-wrapper="nulla_nombre">
                                    <label for="nulla_nivel" class="control-label">Nivel:</label>
                                    <input type="text" name="nulla_nivel" id="nulla_nivel"
                                        value="<?php echo $retiro[0]->nivel; ?>" disabled />
                                </div>
                                <div class="form-group" app-field-wrapper="nulla_ciudad">
                                    <label for="nulla_ciudad" class="control-label">Ciudad de nacimiento:</label>
                                    <input type="text" name="nulla_ciudad" id="nulla_ciudad" />
                                </div>
                                <div class="form-group" app-field-wrapper="nulla_pais">
                                    <label for="nulla_ciudad" class="control-label">País de nacimiento:</label>
                                    <input type="text" name="nulla_pais" id="nulla_pais" />
                                </div>
                                <div class="form-group" app-field-wrapper="nulla_fecha"><label for="nulla_fecha"
                                        class="control-label">Fecha de nacimiento</label>
                                    <div class="input-group date"><input type="text" id="nulla_fecha" name="nulla_fecha"
                                            class="form-control datepicker" value="" autocomplete="off">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar calendar-icon"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group" app-field-wrapper="nulla_domicilio">
                                    <label for="nulla_domicilio" class="control-label">Domicilio del alumno:</label>
                                    <input type="text" name="nulla_domicilio" id="nulla_domicilio" />
                                </div>
                                <div class="form-group" app-field-wrapper="nulla_correlativo">
                                    <label for="nulla_correlativo" class="control-label">Correlativo:</label>
                                    <input type="text" name="nulla_correlativo" id="nulla_correlativo" />
                                </div>
                                <div class="form-group" app-field-wrapper="nulla_fecha"><label for="nulla_emision"
                                        class="control-label">Fecha de emisión</label>
                                    <div class="input-group date"><input type="text" id="nulla_emision"
                                            name="nulla_emision" class="form-control datepicker" value=""
                                            autocomplete="off">
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
                            <?php endif;?>
                            <?php if (0 == $retiro[0]->secretaria_proc && 0 == $retiro[0]->nulla_send): ?>
                            <div class="wti-form-wrapper">
                                <div class="checkbox checkbox-info mbot20 no-mtop form-group">
                                    <input type="checkbox" name="secre_modifica_colegium" value="1"
                                        id="secre_modifica_colegium">
                                    <label for="secre_modifica_colegium">Se modifica estado en sistema unificado de
                                        alumnos</label>
                                    <em class="help-block"></em>
                                </div>
                            </div>
                            <?php endif;?>
                            <input type="hidden" name="id_retiro" id="id_retiro"
                                value="<?php echo $retiro[0]->id; ?>" />
                            <input type="hidden" name="fk_escuela" id="fk_escuela"
                                value="<?php echo $retiro[0]->fk_escuela; ?>" />

                            <!-- P A R I T A R I O  --  A N T I C I P A D O  -->
                            <?php if (1 == $retiro[0]->authpromoanti && 1 == $retiro[0]->colegio_paritario && 0 == $retiro[0]->firma_rector_2 && 0 == $retiro[0]->nulla_send): ?>
                            <div class="btn-container btn_secre_submit_paritario_anticipado">
                                <button class="btn btn-info btn-def" id="secre_submit_paritario_anticipado"
                                    data-exec="paritario_anticipado">LISTA PARA FIRMAR</button>
                            </div>
                            <?php endif;?>
                            <!-- N O   A N T I C I P A D O  --  N O   P A R I T A R I O -->
                            <?php if (0 == $retiro[0]->authpromoanti && 0 == $retiro[0]->colegio_paritario && 0 == $retiro[0]->secretaria_proc): ?>
                            <div class="btn-container btn_secre_submit_no_anticipado_no_paritario">
                                <button class="btn btn-info btn-def" id="secre_submit_no_anticipado_no_paritario"
                                    data-exec="noparitario_noanticipado">Cierre
                                    Académico</button>
                            </div>
                            <?php endif;?>
                            <!-- S O L O  P A R I T A R I O -->
                            <?php if (0 == $retiro[0]->authpromoanti && 1 == $retiro[0]->colegio_paritario && 0 == $retiro[0]->firma_rector_2 && 0 == $retiro[0]->nulla_send): ?>
                            <div class="btn-container btn_secre_submit_paritario">
                                <button class="btn btn-info btn-def" id="secre_submit_paritario"
                                    data-exec="paritario">LISTA PARA FIRMAR</button>
                            </div>
                            <?php endif;?>
                            <!-- S O L O   A N T I C I P A D O -->
                            <?php if (1 == $retiro[0]->authpromoanti && 0 == $retiro[0]->colegio_paritario && 0 == $retiro[0]->secretaria_proc): ?>
                            <div class="btn-container btn_secre_submit_anticipado">
                                <button class="btn btn-info btn-def" id="secre_submit_anticipado"
                                    data-exec="anticipado">Cierre
                                    Académico</button>
                            </div>
                            <?php endif;?>
                            <!--  P A R I T A R I O  F I R M A D O  -->
                            <?php if (1 == $retiro[0]->colegio_paritario && 1 == $retiro[0]->firma_rector_2 && 1 == $retiro[0]->nulla_send): ?>
                            <div class="btn-container btn_secre_submit_paritario_firmado">
                                <h4>¿Desea realizar el cierre académico?</h4>
                            </div>
                            <div class="btn-container btn_secre_submit_paritario_firmado">
                                <button class="btn btn-info btn-def" id="secre_submit_paritario_firmado"
                                    data-exec="paritario_firmado">Cierre
                                    Académico</button>
                            </div>
                            <?php endif;?>
                        </div>
                    </form>
                </div>
                <?php endif;?>
                <!-- ############################################################################################################ -->
                <!-- SECRETARIA ACADEMICA END -->
                <!-- ############################################################################################################ -->
                <?php endif;?>