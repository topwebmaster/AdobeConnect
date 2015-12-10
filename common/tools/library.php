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

        if ($startsesion < $start) {
            if ($endsesion < $start || ($start - $startsesion) > 1800) {
                continue;
            }
            $startsesion = $start;
        } else if ($startsesion > $end) {
            continue;
        }

        if ($endsesion > $end) {
            $endsesion = $end;
        }

        if (!in_array($val[$key], $key_array)) {
            $key_array[$i] = $val[$key];
            $suma_array[$val[$key]]['CN'] = ($endsesion - $startsesion);
        } else {
            $suma_array[$val[$key]]['CN'] += ($endsesion - $startsesion);
        }

        $suma_array[$val[$key]]['TCon'] = conversor_segundos($suma_array[$val[$key]]['CN']);

        $i++;
    }
    unset($val);
    return (!empty($suma_array)) ? $suma_array : null;
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