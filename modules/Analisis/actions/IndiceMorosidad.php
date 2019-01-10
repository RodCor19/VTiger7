<?php 
/*ini_set("display_errors", 1);
error_reporting(E_ALL & ~E_NOTICE);
*/
class Analisis_IndiceMorosidad_Action extends Vtiger_BasicAjax_Action{


	public function process(Vtiger_Request $request) {		
		$vista = $request->get('vista');
		
		$fp = fopen("consultas.txt","a");
		fwrite($fp,$query.PHP_EOL);
		fclose($fp);
		
		$query_aux = "";
		
		$json_string=$this->procesarRespuesta("","",$request,$query_aux);
		;
		echo $json_string;
		return ;
	}


	public function procesarRespuesta($query,$vista,$request,$query_aux){
		global $log;
		$adb = PearDatabase::getInstance();
/*		ini_set("display_errors", 1);
		error_reporting(E_ALL & ~E_NOTICE);*/
		
		$json_string = "[[";
		$json_string .= "\"Fecha\",\"Deuda Vencida\",\"% Variacion\",\"Total Deuda\",
			\"% Morosidad\",\"Variacion Morosidad (pp)\"],";

		$fechasResult = $adb->query("SELECT DISTINCT YEAR(fec_venc) as anio, MONTH(fec_venc) as mes
							FROM lp_saldoscontables WHERE cod_cta=11310
							ORDER BY anio, mes
							LIMIT 1");
		$primerAnio = $adb->query_result($fechasResult, 0, 'anio');
		$primerMes = $adb->query_result($fechasResult, 0, 'mes');

		$fechaTemporal = $primerAnio."-".$primerMes."-1";
		$aux = date('Y-m-d', strtotime("{$fechaTemporal} + 1 month"));
		$fechaTemporal = date("Y-m-d", strtotime("{$aux} - 1 day"));
		
		$aux2 = date("Y")."-".date("n")."-1";
		$aux = date('Y-m-d', strtotime("{$aux2} + 1 month"));
		$fechaActual = date('Y-m-d', strtotime("{$aux} - 1 day"));

		$deuda_vencida_anterior = 0;
		$morosidad_anterior = 0;
		$variacion = 0;
		$variacion_morosidad = 0;
		do{
			$result = $adb->query("SELECT 
								SUM(saldobco) AS deuda_total, 
								SUM(IF(fec_venc<='$fechaTemporal',saldobco,0)) AS deuda_vencida
								FROM lp_saldoscontables s 
								WHERE cod_cta=11310 AND fec_venc <= '$fechaTemporal' ");
			
			while($row = $adb->fetch_array($result)){
				$morosidad = 0;

				if( $row["deuda_total"] != 0 ){
					$morosidad = round( ($row["deuda_vencida"] / $row["deuda_total"]) * 100 );	
				}
				
				if( $morosidad_anterior == 0 ){
					$variacion_morosidad = 0;
					$morosidad_anterior = $morosidad;
				} else {
					$variacion_morosidad = $morosidad - $morosidad_anterior;

					$morosidad_anterior = $morosidad;
				}
				
				if($deuda_vencida_anterior == 0){
					$variacion = 0;
					$deuda_vencida_anterior = $row["deuda_vencida"];
				} else {
					$variacion = round((($row["deuda_vencida"] - $deuda_vencida_anterior) / $deuda_vencida_anterior) * 100); 
					
					$deuda_vencida_anterior = $row["deuda_vencida"];
				}


				$json_string .= "[";
				$json_string .= "\"".$fechaTemporal."\",
				\"".$row["deuda_vencida"]."\",
				\"".$variacion."\",
				\"".$row["deuda_total"]."\",
				\"".$morosidad."\",
				\"".$variacion_morosidad."\"
				],";	
				
			}

			//aumentar en un mes la fecha temporal
			//le sumamos un dia, un mes y le restamos un dia para quedarnos con el ultimo dia del mes siguiente
			$aux = date('Y-m-d', strtotime("{$fechaTemporal} + 1 day"));
			$aux = date('Y-m-d', strtotime("{$aux} + 1 month"));
			$fechaTemporal = date("Y-m-d", strtotime("{$aux} - 1 day"));
		}while ($fechaTemporal != $fechaActual);
				
		$json_string = rtrim($json_string, ",");
		$json_string .= "]";
			
		return $json_string;
	}
	
}