<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Wti_Log
{
    public function log($message)
    {
        // Código para crear el archivo de registro y agregar el mensaje
        $file = debug_backtrace()[0]['file'];
        $line = debug_backtrace()[0]['line'];
        $log_file = dirname(__DIR__) . '/logs/' . date('Y-m-d') . '.log';
        $log_message = '[' . date('Y-m-d H:i:s') . '] ' . $message . ' - ' . $file . ':' . $line . PHP_EOL;
        file_put_contents($log_file, $log_message, FILE_APPEND);
    }
}
