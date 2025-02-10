<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wti_helpers extends ClientsController {
    
        protected $CI;
    
        public function __construct()
        {
           $CI =& get_instance();
        }

        
        public function devolverCurso($curso) {
            if (str_contains($curso, 'SC1')) {return "Sala Cuna Menor";}
            else if (str_contains($curso, 'SC2')) {return "Sala Cuna Mayor";}
            else if (str_contains($curso, 'MM1')) {return "Nivel Medio Menor";}
            else if (str_contains($curso, 'PPK')) {return "I Sezione";}
            else if (str_contains($curso, 'PK')) {return "II Sezione";}
            else if (str_contains($curso, 'K')) {return "III Sezione";}
            else if (str_contains($curso, '1EB')) {return "1° Básico";}
            else if (str_contains($curso, '2EB')) {return "2° Básico";}
            else if (str_contains($curso, '3EB')) {return "3° Básico";}
            else if (str_contains($curso, '4EB')) {return "4° Básico";}
            else if (str_contains($curso, '5EB')) {return "5° Básico";}
            else if (str_contains($curso, '6EB')) {return "6° Básico";}
            else if (str_contains($curso, '7EB')) {return "7° Básico";}
            else if (str_contains($curso, '8EB')) {return "8° Básico";}
            else if (str_contains($curso, '1EM')) {return "1° Medio";}
            else if (str_contains($curso, '2EM')) {return "2° Medio";}
            else if (str_contains($curso, '3EM')) {return "3° Medio";}
            else if (str_contains($curso, '4EM')) {return "4° Medio";}
            else {return "-Sin Curso-";}
        }

        public function devolverNivel($curso) {
            if (str_contains($curso, 'SC1')) {return "Scuola Nido";}
            else if (str_contains($curso, 'SC2')) {return "Scuola Nido";}
            else if (str_contains($curso, 'MM1')) {return "Scuola Nido";}
            else if (str_contains($curso, 'PPK')) {return "Materna";}
            else if (str_contains($curso, 'PK')) {return "Materna";}
            else if (str_contains($curso, 'K')) {return "Materna";}
            else if (str_contains($curso, '1EB')) {return "Elementare";}
            else if (str_contains($curso, '2EB')) {return "Elementare";}
            else if (str_contains($curso, '3EB')) {return "Elementare";}
            else if (str_contains($curso, '4EB')) {return "Elementare";}
            else if (str_contains($curso, '5EB')) {return "Media Inferior";}
            else if (str_contains($curso, '6EB')) {return "Media Inferior";}
            else if (str_contains($curso, '7EB')) {return "Media Inferior";}
            else if (str_contains($curso, '8EB')) {return "Media Inferior";}
            else if (str_contains($curso, '1EM')) {return "Media Superior";}
            else if (str_contains($curso, '2EM')) {return "Media Superior";}
            else if (str_contains($curso, '3EM')) {return "Media Superior";}
            else if (str_contains($curso, '4EM')) {return "Media Superior";}
            else {return "-Sin nivel-";}
        }

        public function devolverPromocion($data) {
            if (intval($data) <= 0) {
               return "NO PROMOVIDO";
            }
            else {
               return "PROMOVIDO";
            }
        }
}