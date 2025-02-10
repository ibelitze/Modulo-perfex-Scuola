<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Wti_statuscolor extends ClientsController
{
    protected $CI;

    public function __construct()
    {
        $CI = &get_instance();
    }

    public function set_color_status($lastDate)
    {
        $color = $this->tiempoTranscurrido($lastDate);
        return $color;
    }

    public function tiempoTranscurrido($fecha)
    {
        $calculoDays = $this->evaluarPasado($fecha);
        if (0 == $calculoDays) {
            return 'wti-verde';
        } elseif (-1 == $calculoDays) {
            return 'wti-amarillo';
        } elseif (-1 > $calculoDays && $calculoDays >= -4) {
            return 'wti-rojo';
        } elseif ($calculoDays <= -5) {
            return 'wti-gray';
        }

        return false; // si no se cumple ninguna de las condiciones anteriores.
    }

    public function evaluarPasado($date)
    {
        $year = date('Y');
        // Obtener la lista de feriados del JSON
        $feriados = json_decode(file_get_contents('https://apis.digital.gob.cl/fl/feriados/' . $year), true);

        // Calcular la cantidad de días pasados
        $zonaHoraria = new DateTimeZone('America/Santiago');
        $fechaActual = new DateTime('now', $zonaHoraria);
        $fechaEvento = new DateTime($date, $zonaHoraria);

        if ($fechaActual == $fechaEvento) {
            return 0;
        }

        $diferencia = $fechaActual->diff($fechaEvento);
        $diasPasados = -$diferencia->days;
        $diasHabilesPasados = $diasPasados;

        // Sumar los días feriados y fines de semana
        for ($i = 0; $i < abs($diasPasados); $i++) {
            $fecha = $fechaActual->sub(new DateInterval('P1D'))->format('Y-m-d');
            $diaSemana = date('w', strtotime($fecha));
            $esFeriado = false;
            foreach ($feriados as $feriado) {
                if ($feriado['fecha'] == $fecha) {
                    $esFeriado = true;
                    break;
                }
            }
            if (0 == $diaSemana || 6 == $diaSemana || $esFeriado) {
                $diasHabilesPasados++;
            }
            unset($fecha);
            unset($diaSemana);
            unset($esFeriado);
        }

        return $diasHabilesPasados;
    }

    // Función para verificar si un día es fin de semana.
    public function esFinDeSemana(DateTime $date)
    {
        $zonaHoraria = new DateTimeZone('America/Santiago');
        $fecha = new DateTime($date->format('Y-m-d'), $zonaHoraria);

        $weekDay = date('w', strtotime($fecha->format('Y-m-d')));

        if (0 == $weekDay || 6 == $weekDay) {
            // 0: Domingo, 6: Sábado.

            return true;

        } else {

            return false;

        }
    }

}
