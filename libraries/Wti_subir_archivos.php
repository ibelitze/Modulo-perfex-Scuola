<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Wti_subir_archivos extends ClientsController
{
    protected $CI;

    public function __construct()
    {
        $CI = &get_instance();
    }

    public function subir_a_bucket($fileHere)
    {
        $path = dirname(__DIR__) . '/files/temp/';
        $nombre_archivo = date('YmdHis') . '_' . $fileHere['name'];
        $fichero_subido = $path . $nombre_archivo;
        if (move_uploaded_file($fileHere['tmp_name'], $fichero_subido)) {
            $fp = fopen($fichero_subido, "r");
            $contents = fread($fp, filesize($fichero_subido));
            fclose($fp);

            $url = "url-bucket-aqui";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);

            //If the function curl_file_create exists
            if (function_exists('curl_file_create')) {
                $filePath = curl_file_create($fichero_subido);
            } else {
                $filePath = '@' . realpath($fichero_subido);
                curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false);
            }
            $post = array('ldt' => 'retiros', 'image' => $filePath);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

            $result = curl_exec($ch);
            curl_close($ch);

            if ($result) {
                unlink($fichero_subido);
                return $result;
            } else {
                echo 'Curl error: ' . curl_error($ch);
            }
        } else {
            echo "Error al subir el archivo";
        }
    }

    public function tcpdf_a_bucket($fileLocation)
    {
        $fichero_subido = $fileLocation;

        $url = "url-bucket-aqui";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);

        //If the function curl_file_create exists
        if (function_exists('curl_file_create')) {
            $filePath = curl_file_create($fichero_subido);
        } else {
            $filePath = '@' . realpath($fichero_subido);
            curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false);
        }
        $post = array('ldt' => 'retiros', 'image' => $filePath);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

        $result = curl_exec($ch);
        curl_close($ch);

        if ($result) {
            unlink($fichero_subido);
            return $result;
        } else {
            echo 'Curl error: ' . curl_error($ch);
        }

    }

}
