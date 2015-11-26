<?php

function unique_multidim_array($array, $key, $start, $end) {
    $suma_array = array();
    $key_array = array();
    $considerar;
    $i = 0;

    $start = strtotime($start); // inicio de grabacion
    $end = strtotime($end); // fin de grabacion

    foreach ($array as $val) {

        $startsesion = strtotime($val['date-created']); // inicio de sesion
        $endsesion = strtotime($val['date-end']); // fin de sesion

        if ($start >= $startsesion) {
            if ($end >= $endsesion) {
                //considerar
                $considerar = true;
            } else {
                // no considerar
                $considerar = false;
            }
        } elseif (($endsesion - 300) <= $end) {
            // considerar
            $considerar = true;
        } else {
            // no considerar
            $considerar = false;
        }

        if (!$considerar) {
            continue;
        }

        if (!in_array($val[$key], $key_array)) {
            $key_array[$i] = $val[$key];
            $suma_array[$val[$key]]['CN'] = (strtotime($val['date-end']) - strtotime($val['date-created']));
        } else {
            $suma_array[$val[$key]]['CN'] += (strtotime($val['date-end']) - strtotime($val['date-created']));
        }

        $suma_array[$val[$key]]['TCon'] = conversor_segundos($suma_array[$val[$key]]['CN']);

        $i++;
    }
    return $suma_array;
}

function conversor_segundos($seg_ini) {
    $horas = floor($seg_ini / 3600);
    $minutos = floor(($seg_ini - ($horas * 3600)) / 60);
    $segundos = $seg_ini - ($horas * 3600) - ($minutos * 60);

    if (strlen($horas) == 1) {
        $horas = '0' . $horas;
    }

    if (strlen($minutos) == 1) {
        $minutos = '0' . $minutos;
    }

    if (strlen($segundos) == 1) {
        $segundos = '0' . $segundos;
    }

    return $horas . ":" . $minutos . ":" . $segundos;
}

?>