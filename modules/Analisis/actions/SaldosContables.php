<?php 
/*ini_set("display_errors", 1);
error_reporting(E_ALL & ~E_NOTICE);
*/
class Analisis_SaldosContables_Action extends Vtiger_BasicAjax_Action{


	public function process(Vtiger_Request $request) {		
		$vista = $request->get('vista');
		$query = $this->getQuery($request);
		$fp = fopen("consultas.txt","a");
		fwrite($fp,$query.PHP_EOL);
		fclose($fp);
		
		$query_aux = "";
		
		$json_string=$this->procesarRespuesta($query,"",$request,$query_aux);
		;
		echo $json_string;
		return ;
	}


	public function obtenerFiltrosLocales(Vtiger_Request $request){
		$filtros_locales = "";
		$nombreLocal = htmlspecialchars_decode($request->get('nombreLocal'));
		
		if($nombreLocal != ""){
			$rangos = explode(",", $nombreLocal);
			$filtros_locales .= " AND (";
			foreach($rangos as $id){
				$filtros_locales .= " accountid = ".$id." OR";
			}	
			$filtros_locales = rtrim($filtros_locales,'OR');
			$filtros_locales .= " )";
		}
		
		return $filtros_locales;
	}


	public function getQuery(Vtiger_Request $request){		
		$fecha_desde = "";
		$fecha_hasta = "";

		$fecha_hasta = date('Y-m-d');
		$fecha2 = date('Y-m-01');
		$date = strtotime( date('Y-m-01')." -11 months");
		$date = strtotime( date('Y-m-01')." -11 months");
		$fecha_desde = date("Y-m-d", $date);
		$fecha1 = date("Y-m-d", $date);

		$createdTime = $request->get('createdtime');
		if(!empty($createdTime)) {
			$fecha_desde = DateTimeField::__convertToDBFormat($createdTime['start'],'dd-mm-yyyy');
			$fecha_hasta = DateTimeField::__convertToDBFormat($createdTime['end'],'dd-mm-yyyy');		
		}	
		
		$filtros_locales=$this->obtenerFiltrosLocales($request);

		$query = "";

		if ($fecha_desde != ""){
			$filtro_fechas .= " AND fec_venc >= '".$fecha_desde."'";
		}
		if ($fecha_hasta != ""){
			$filtro_fechas .= " AND fec_venc <= '".$fecha_hasta."' ";
		}

		$query="SELECT DISTINCT num_Cont,
				SUM(saldobco) AS deuda_total, 
				SUM(IF(fec_venc<NOW(),saldobco,0)) AS deuda_vencida, 
				SUM(IF(fec_venc>NOW(),saldobco,0)) AS deuda_vencer,
				ROUND(SUM(IF(fec_venc<NOW(),saldobco,0)) * 100 / t.s) AS deuda_vencida_porc,
				ac.accountid,ac.accountname
				FROM lp_saldoscontables s inner join vtiger_account ac on s.num_Cont=ac.acnumerocontrato
				CROSS JOIN (SELECT SUM(IF(fec_venc<NOW(),saldobco,0)) AS s FROM lp_saldoscontables WHERE cod_cta=11310 ) t
				WHERE cod_cta=11310 
				";

		$query .= $filtros_locales.$filtro_fechas;
		$query .= " GROUP BY num_cont ORDER BY deuda_vencida_porc DESC";		

		return $query;
	}

	public function getDatosAnioAnterior($numeroContrato, Vtiger_Request $request){
		global $log;
		$fecha_desde = "";
		$fecha_hasta = "";

		$fecha_hasta = date('Y-m-d');
		$fecha2 = date('Y-m-01');
		$date = strtotime( date('Y-m-01')." -11 months");
		$date = strtotime( date('Y-m-01')." -11 months");
		$fecha_desde = date("Y-m-d", $date);
		$fecha1 = date("Y-m-d", $date);
		
		$fechaAnterior = $request->get('fechaAnterior');
		if(!empty($fechaAnterior)) {
			$fecha_desde = DateTimeField::__convertToDBFormat($fechaAnterior['start'],'dd-mm-yyyy');
			$fecha_hasta = DateTimeField::__convertToDBFormat($fechaAnterior['end'],'dd-mm-yyyy');		
		}
		
		$filtros_locales = $this->obtenerFiltrosLocales($request);

		$query = "";

		if ($fecha_desde != ""){
			$filtro_fechas .= " AND fec_venc >= '".$fecha_desde."'";
		}
		if ($fecha_hasta != ""){
			$filtro_fechas .= " AND fec_venc <= '".$fecha_hasta."' ";
		}

		$query="SELECT DISTINCT num_Cont,
				SUM(saldobco) AS deuda_total, 
				SUM(IF(fec_venc<NOW(),saldobco,0)) AS deuda_vencida, 
				SUM(IF(fec_venc>NOW(),saldobco,0)) AS deuda_vencer,
				ROUND(SUM(IF(fec_venc<NOW(),saldobco,0)) * 100 / t.s) AS deuda_vencida_porc,
				ac.accountid,ac.accountname
				FROM lp_saldoscontables s inner join vtiger_account ac on s.num_Cont=ac.acnumerocontrato
				CROSS JOIN (SELECT SUM(IF(fec_venc<NOW(),saldobco,0)) AS s FROM lp_saldoscontables WHERE cod_cta=11310 ) t
				WHERE num_Cont = $numeroContrato AND  cod_cta=11310 
				";
			//para qúe el  cod_cta=11310 AND???????

		$query .= $filtros_locales.$filtro_fechas;
		$query .= " GROUP BY num_cont ORDER BY deuda_vencida_porc DESC";

		return $query;		
	}

	public function procesarRespuesta($query,$vista,$request,$query_aux){
		global $log;
		$adb = PearDatabase::getInstance();
/*		ini_set("display_errors", 1);
		error_reporting(E_ALL & ~E_NOTICE);*/
		$result = $adb->query($query);
		$result2 = NULL;
		$localesvalores = array();
		$indice = "";
		

		$json_string = "[[";
		$json_string .= "\"Nro\",\"Nombre Fantasía\",\"Contrato\",\"Deuda a Vencer\",
			\"Deuda Vencida\",\"Total Deuda\",\"% Morosidad\",\"Deuda a Vencer (Mes Año Anterior)\",\"Deuda Vencida (Mes Año Anterior)\", \"Total Deuda (Mes Año Anterior)\", \"% Morosidad (Mes Año Anterior)\",\"Deuda a Vencer (Variacion)\",\"Deuda Vencida (Variacion)\", \"Total Deuda (Variacion)\", \"% Morosidad (Variacion)\"],";
	
		$total = 0;

		$nro = 1; $morosidad = 0;
		while ($row = $adb->fetch_array($result)){
			$url = "<a href='index.php?module=Accounts&view=Detail&record=".$row['accountid']."'>".$row['accountname']."</a>";
			$morosidad = 0;

			if($row["deuda_total"] > 0){
				$morosidad = round( ($row["deuda_vencida"] / $row["deuda_total"]) * 100 );	
			}

			$datosAnioAnterior = $adb->query($this->getDatosAnioAnterior($row['num_cont'], $request));

			$deuda_vencerAA = $adb->query_result($datosAnioAnterior, 0, 'deuda_vencer');
			$deuda_vencidaAA = $adb->query_result($datosAnioAnterior, 0, 'deuda_vencida');
			$deuda_totalAA = $adb->query_result($datosAnioAnterior, 0, 'deuda_total');
			$morosidadAA = 0;

			if($deuda_totalAA > 0)
				$morosidadAA = round(($deuda_vencidaAA/$deuda_totalAA)*100);

			$deuda_vencerV = $row['deuda_vencer'] - $deuda_vencerAA;
			$deuda_vencidaV = $row['deuda_vencida'] - $deuda_vencidaAA;
			$deuda_totalV = $row['deuda_total'] - $deuda_totalAA;
			$morosidadV = $morosidad - $morosidadAA;

			$json_string .= "[";
			$json_string .= "\"".$nro."\",
			\"".$url."\",
			\"".$row['num_cont']."\",
			\"".$row['deuda_vencer']."\",
			\"".$row["deuda_vencida"]."\",
			\"".$row["deuda_total"]."\",
			\"".$morosidad."\",
			\"".$deuda_vencerAA."\",
			\"".$deuda_vencidaAA."\",
			\"".$deuda_totalAA."\",
			\"".$morosidadAA."\",
			\"".$deuda_vencerV."\",
			\"".$deuda_vencidaV."\",
			\"".$deuda_totalV."\",
			\"".$morosidadV."\"
			],";	
			
			$nro++;
		}		
		
		$json_string = rtrim($json_string, ",");
		$json_string .= "]";
			
		return $json_string;
	}
	
	
	function convertirmes($dato, $sep){
		require_once 'include/utils/utils.php';
		$arr = split('-', $dato);
		if (intval($arr[0])<10){
			$arr[0]="0".$arr[0];
		}
		return nombremes($arr[0]).$sep.$arr[1];
		//return $arr[0]."-".$arr[1];
 	}
	function convertirmesInvertido($dato, $sep){
		require_once 'include/utils/utils.php';
		$Anio=substr($dato, 0,4);
		$mes=substr($dato, 4,2);
		if (intval($mes)<10){
			$mes="0".$mes;
		}
		//echo $mes;
		return nombremes($mes).$sep.$Anio;
		//return $arr[0]."-".$arr[1];
 	}
}