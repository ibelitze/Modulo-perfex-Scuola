<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wti_encrypt extends ClientsController {
    
        protected $CI;
    
        public function __construct()
        {
           $CI =& get_instance();
           $this->method = 'aes-256-cbc';
           $this->key = '';
           $this->iv = base64_decode("");
        }

        
        public function encrypt($texto) {
            $data = openssl_encrypt($texto, $this->method, $this->key, false, $this->iv);
            return base64_encode($data);
        }

        public function decrypt($texto) {
            $data = base64_decode($texto);
            return openssl_decrypt($data, $this->method, $this->key, false, $this->iv);       
        }

}