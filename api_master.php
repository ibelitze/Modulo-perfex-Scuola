<?php

/**
 * Ensures that the module init file can't be accessed directly, only within the application.
 */
defined('BASEPATH') or exit('No direct script access allowed');
require_once __DIR__ . '/constants.php';
/*
Module Name: API Master.
Description: M&oacute;dulo para la gesti&oacute;n de retiros.
Author: Webtiginoso.
Version: 1.0
Requires at least: 1.0.*
 */

// ------------------------------------------------------------------
// ----------------------------------------------     S T E P   1
// ------------------------------------------------------------------

//MODULE URL BASE AND INSTANCE NAME
define('API_MASTER', 'api_master');

// ------------------------------------------------------------------
// ----------------------------------------------     S T E P   2
// ------------------------------------------------------------------

//RUNNING WHEN INSTALL  (ACTIVATE, Running when press activate Module)
register_activation_hook(API_MASTER, 'app_activation_hook');
function app_activation_hook()
{
    $CI = &get_instance();
    require_once __DIR__ . '/install.php';
}

// ------------------------------------------------------------------
// ----------------------------------------------     S T E P   3
// ------------------------------------------------------------------
//RUNNING WHEN DEACTIVATE
register_deactivation_hook(API_MASTER, 'app_deactivation_hook');
function app_deactivation_hook()
{
    $CI = &get_instance();
    require_once __DIR__ . '/deactivate.php';
}

// ------------------------------------------------------------------
// ----------------------------------------------     S T E P   4
// ------------------------------------------------------------------
//RUNNING WHEN UNINSTALL
register_uninstall_hook(API_MASTER, 'app_uninstall_hook');
function app_uninstall_hook()
{
    // $CI = &get_instance();
    // require_once(__DIR__ . '/uninstall.php');
}

// ------------------------------------------------------------------
// ----------------------------------------------     S T E P   5
// ------------------------------------------------------------------

// IN ADMIN AREA
hooks()->add_action('admin_init', 'master_init_menu_items');
function master_init_menu_items()
{
    $CI = &get_instance();

    $CI->app_menu->add_sidebar_menu_item('modulo-retiros', [
        'name' => 'RETIROS', // The name if the item
        'href' => admin_url('api_master/master'), // URL of the item
        'position' => 6, // The menu position, see below for default positions.
        'icon' => 'fa fa-user', // Font awesome icon
    ]);

}

// ------------------------------------------------------------------
// ----------------------------------------------     S T E P   6
// ------------------------------------------------------------------

// STAFF CAPABILITIES AREA
hooks()->add_action('admin_init', 'scuola_permissions');

function scuola_permissions($permissions)
{
    $config = [];

    $config['capabilities'] = [
        'director' => 'Director',
        'dir_nido' => 'Nido',
        'dir_materna' => 'Materna',
        'dir_elementare' => 'Elementare',
        'dir_inferior' => 'Inferior',
        'dir_superior' => 'Superior',
        'rectoria' => 'Rectoría',
        'administracion' => 'Administración',
        'sec_academica' => 'Sec. Academica',
        'sec_escolastica' => 'Sec. Escolastica',
    ];

    register_staff_capabilities(
        'retiros',
        $config,
        'Retiros'
    );
}

/*
if(staff_can('director', 'retiros')) {
// Do directors things here
}
 */

hooks()->add_action('after_cron_run', 'wti_chek_dates');

function wti_chek_dates()
{
    $CI = &get_instance();
    $url_base = APP_BASE_URL;
    $url = $url_base . 'api_master/daemon/runactions';

    // Inicializar sesión cURL
    $curl = curl_init();

    // Configurar opciones de la petición
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($curl, CURLOPT_TIMEOUT, 60);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

    // Ejecutar petición cURL
    $response = curl_exec($curl);

    // Verificar si ocurrieron errores
    if (curl_errno($curl)) {
        $error = curl_error($curl);
        curl_close($curl);
        throw new Exception('Error en la petición cURL: ' . $error);
    }

    // Obtener información de la petición
    $info = curl_getinfo($curl);

    // Cerrar sesión cURL
    curl_close($curl);

    // Verificar el código de respuesta HTTP
    if (200 !== $info['http_code']) {
        throw new Exception('Error en la petición HTTP: ' . $info['http_code']);
    } else {
        return $response;
    }
}
