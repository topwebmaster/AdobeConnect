<?php
	function unique_multidim_array($array, $key){
	    $temp_array = array();
	    $i = 0;
	    $key_array = array();
	    
	    foreach($array as $val){
	        if(!in_array($val[$key],$key_array)){
	            $key_array[$i] = $val[$key];
	            $temp_array[$i] = $val;
	        }
	        $i++;
	    }
	    return $temp_array;
	}

	function conversor_segundos($seg_ini) {
		$horas = floor($seg_ini/3600);
		$minutos = floor(($seg_ini-($horas*3600))/60);
		$segundos = $seg_ini-($horas*3600)-($minutos*60);

		if(strlen($horas) == 1){
			$horas = '0' . $horas;
		}

		if(strlen($minutos) == 1){
			$minutos = '0' . $minutos;
		}

		if(strlen($segundos) == 1){
			$segundos = '0' . $segundos;
		}

		return $horas.":".$minutos.":".$segundos;
	}
?>