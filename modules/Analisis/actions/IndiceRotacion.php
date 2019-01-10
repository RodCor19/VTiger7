<?php 
/*ini_set("display_errors", 1);
error_reporting(E_ALL & ~E_NOTICE);
*/
class Analisis_IndiceRotacion_Action extends Vtiger_BasicAjax_Action{


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
		$json_string .= "\"Fecha\",\"Total Deuda\",
			\"Facturacion Mensual Prom.\",\"Rotacion (meses)\"],";

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

		
		do{
			$result = $adb->query("SELECT 
								SUM(saldobco) AS deuda_total
								FROM lp_saldoscontables s 
								WHERE cod_cta=11310 AND fec_venc <= '$fechaTemporal' ");
			
			while($row = $adb->fetch_array($result)){
				$deudaTotal = $row["deuda_total"];

				//calcular ventas ultimos 6 meses
				$fecha_desde = date("Ym", strtotime("{$fechaTemporal} - 5 months"));
				$fecha_hasta = date("Ym", strtotime($fechaTemporal));
			
				$r = $adb->query("SELECT SUM(VentasSIva) AS suma 
									FROM lp_ventas_contratos
									WHERE Aniomes >= '$fecha_desde' AND Aniomes <= '$fecha_hasta'
									");
				$ventasUltimosMeses = $adb->query_result($r, 0, 'suma');
				$facturacionPromedio = $ventasUltimosMeses / 6;
				$rotacionMeses = 0;

				if($facturacionPromedio != 0)
					$rotacionMeses = $deudaTotal / $facturacionPromedio;
			

				$json_string .= "[";
				$json_string .= "\"".$fechaTemporal."\",
				\"".$deudaTotal."\",
				\"".$facturacionPromedio."\",
				\"".$rotacionMeses."\"
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