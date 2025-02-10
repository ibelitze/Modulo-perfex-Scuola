<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wti_validar_types extends AdminController {
    
    protected $CI;
    
    public function __construct($params){
        $CI =& get_instance();
    }

    public function validar_string($cadena , $min , $max){
        if ( (gettype($cadena) == 'string') && (strlen($cadena)>=$min) && (strlen($cadena)<=$max) ){
            return true;
        }
        return false;
    }

    public function validar_numero($numero){
        if( is_numeric($numero) ){
            return true;
        }
        return false;
    }

    public function validar_booleano($booleano){
        if( is_bool($booleano) || $booleano=='true' || $booleano=='false' || $booleano==0 || $booleano==1 ){
            return true;
        }
        return false;
    }    

}