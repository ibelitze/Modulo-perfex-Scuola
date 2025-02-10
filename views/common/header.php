<?php defined('BASEPATH') or exit('No direct script access allowed');?>

<?php init_head();?>
<!-- styles -->
<link rel="stylesheet" type="text/css" href="<?php echo base_url('modules/api_master/assets/css/compiled.css'); ?>">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/keytable/2.8.0/css/keyTable.bootstrap5.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">

<style type="text/css">
.dt-table-loading.table,
.table-loading .dataTables_filter,
.table-loading .dataTables_length,
.table-loading .dt-buttons,
.table-loading table tbody tr,
.table-loading table thead th {
    opacity: 1 !important;
}

#retiros-table1_wrapper {
    background: #fff;
}
</style>

<!-- spinner de carga -->
<div id="wti-loader">
    <div class="wti-loader-inner"></div>
</div>

<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body" style="background: #fff">
                        <!-- aqui va el contenido -->