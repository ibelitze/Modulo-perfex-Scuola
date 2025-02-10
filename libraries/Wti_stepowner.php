<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Wti_stepowner
{
    public function assigned_to($estatus, $retiro)
    {
        $usuarios_asignados = array(); // inicializa el array vacío

        switch ($estatus) {
            case "formalizado":
                // Administración
                if (0 == $retiro[0]->adminitracion_proc) {
                    $usuarios_asignados[] = "Administracion";
                }
                // Secretaria Académica
                if (0 == $retiro[0]->secretaria_proc) {
                    $usuarios_asignados[] = "Secretaria Academica";
                }
                // Rector
                if (1 == $retiro[0]->secretaria_proc && 0 == $retiro[0]->firma_rector_2) {
                    $usuarios_asignados[] = "Rector";
                }
                else {
                    $usuarios_asignados[] = "Administracion";
                }
                break;

            case "posible reapertura":
                //SECRETARIA ACADEMICA
                $usuarios_asignados[] = "Secretaria Academica";
                break;

            case "en proceso":
                // rector
                if (0 == $retiro[0]->firma_rector_1) {
                    $usuarios_asignados[] = "Rector";
                }
                // director
                if (0 == $retiro[0]->firma_director) {
                    $usuarios_asignados[] = "Director";
                }
                break;

            case "anulado en revision":
                // Director
                $usuarios_asignados[] = "Director";
                break;

            case "abierto":
                // Director
                $usuarios_asignados[] = "Director";
                break;

            case "pendiente_ratificado":
                // Director
                $usuarios_asignados[] = "Director";
                break;

            case default:
                $usuarios_asignados[] = "Administracion";
                break;
        }

        return $usuarios_asignados; // devolver el array de usuarios asignados
    }
}
