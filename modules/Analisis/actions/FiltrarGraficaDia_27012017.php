<?php 

class Analisis_FiltrarGraficaDia_Action extends Vtiger_BasicAjax_Action{

	public function process(Vtiger_Request $request) {

		$uni = htmlspecialchars_decode($request->get('unidades'));
		$fec = htmlspecialchars_decode($request->get('fecha'));
		$acu = htmlspecialchars_decode($request->get('acumulado'));		
		$fam = htmlspecialchars_decode($request->get('familia'));
		$rub = htmlspecialchars_decode($request->get('rubro'));
		$loc = htmlspecialchars_decode($request->get('localizacion'));
/*M E*/		$nombreLocal = htmlspecialchars_decode($request->get('nombreLocal'));
		$adh = htmlspecialchars_decode($request->get('adherido'));
		$formaPago = htmlspecialchars_decode($request->get('formapago'));
		$per = htmlspecialchars_decode($request->get('perimetro'));	

		$db =  PearDatabase::getInstance();

		$unidad="TlkRVMontoNeto";

		$joinFormaPago="";
		$filtroFormaPago="";
		if($formaPago!=""){
			//$formapago = "AND TlkRV".$formaPago."Neto!=0";
			$unidad = "TlkRV".$formaPago."Neto";
			$unidad = "TlkFPMontoNeto";
			$joinFormaPago=" INNER JOIN lp_ventas_rubro_fp fp on fp.EmpId=lvr.EmpId
			and fp.TlkTransId= lvr.TlkTransId AND fp.TlkTransRefId=lvr.TlkTransRefId";

			$filtroFormaPago=" AND FPId=".$formaPago;
		}


		$unidades = "SUM(".$unidad.")";

		$ipc=false;

		$filtrar=false;

		if($uni == "true"){
			if ($formaPago!="") {
				$unidades = "SUM(IF(".$unidad." != 0 ,1,0))";
			}else{
				$unidades = "COUNT(*)";	
			}
			
		}

		if($uni == "ipc")
			$ipc = true;


		if($fam!=""){
			$familia = "AND lpfamilia LIKE '%$fam%'";
			$filtrar=true;
		}
		
		if($rub!=""){
			$rubro = "AND lprubro LIKE '%$rub%'";
			$filtrar=true;
		}
		
		if($loc!=""){
			$localizacion = "AND lplocalizacion='$loc'";
			$filtrar=true;
		}
		
		if($adh!=""){
			$adherido = "AND lpadherido=0";
			if ($adh=="Beneficios") {
				$adherido = "AND lpadherido=1";
			}
			$filtrar=true;
		}
		
/*M E*/		if($nombreLocal!=""){
/*M E*/			$local = "AND accountid=$nombreLocal";
/*M E*/			$filtrar=true;
/*M E*/		}

/*		$anio = date('Y');
		$mes = date('m');
		$dia = date('d', strtotime('-1 MONTH'));*/
		$join_accounts="";
		if($filtrar){
			$join_accounts="INNER join vtiger_account va on va.acnumerocontrato=lvr.TlkIDContrato";
		}
		$fecha = date('Y-m-d');		
		$fecha2 = date('Y-m-d',strtotime('-1 MONTH +1 DAY'));
		$arr = array();
		if($fec!="actual" && $fec!=""){
			$arr=split(',', $fec);
			$fecha = date('Y-m-d',strtotime($arr[1]));
			$fecha2 = date('Y-m-d',strtotime($arr[0]));
		}


		if($per=="true"){
			$_fecha = date('Y/m/d',strtotime('-12 MONTH', strtotime($fecha)));
			$_fecha2 = date('Y/m/d',strtotime('-12 MONTH',strtotime($fecha2)));
			$perimetro="INNER JOIN (SELECT DISTINCT lvr2.TlkIDContrato , MONTH(lvr2.TlkFecha) AS mes
						, DAY(lvr2.TlkFecha) AS dia
						FROM lp_ventas_rubro lvr2 
						WHERE  lvr2.TlkFecha >= '$_fecha2' AND lvr2.TlkFecha <= '$_fecha'
						GROUP BY lvr2.TlkIDContrato, DAY(lvr2.TlkFecha),MONTH(lvr2.TlkFecha),
						YEAR(lvr2.TlkFecha)
					) AS anterior ON anterior.TlkIDContrato=lvr.TlkIDContrato 
					AND anterior.dia = DAY(lvr.TlkFecha) AND anterior.mes = MONTH(lvr.TlkFecha)";
			$filtrar=true;
		}

		//BF si es IPC, obtengo solo los datos del mes desde
		$valor_mensual=' ,0 as ipcmensual';
		$join_ipc='';
		if($ipc){
			$valor_mensual=' ,ipcmensual';
			$join_ipc=' LEFT JOIN vtiger_ipc ipc ON ipc.ipcmes=MONTH(TlkFecha) AND ipc.ipcanio=YEAR(TlkFecha) ';
		}

		$periodoActual = date('d-m-Y', strtotime($fecha2))." al ".date('d-m-Y' ,strtotime($fecha));

		$query = "SELECT CONCAT(YEAR(TlkFecha),'-',LPAD(MONTH(TlkFecha), 2, '0'),'-',LPAD(DAY(TlkFecha), 2, '0')) as fecha, $unidades AS total $valor_mensual 
		FROM lp_ventas_rubro lvr
		$join_accounts $perimetro  $join_ipc $joinFormaPago
/*M E*/		WHERE TlkFecha <= '$fecha'  AND TlkFecha >= '$fecha2' $familia $rubro $localizacion $adherido $local $filtroFormaPago
		GROUP BY fecha";	

		$anio = $db->query($query);	

		foreach ($anio as $key) {
			$aux[$key["fecha"]] = $key["total"];
			$auxIpc[$key["fecha"]] = $key["ipcmensual"];
		}
		
		$datos = array();

		while (strtotime($fecha2) <= strtotime($fecha)) {

			if(!is_null($aux[$fecha2]))
				$total=$aux[$fecha2];
			else
				$total=0;

			$datos[0][] = array(date('d', strtotime($fecha2)),floatval($total));
			$fecha2 = date ("Y-m-d", strtotime("+1 day", strtotime($fecha2)));
		}

		if($fec!="actual" && $fec!=""){
			$fecha2 = date('Y-m-d',strtotime($arr[0]));	
		}		
		else{
			$fecha2 = date('Y-m-d',strtotime('-1 MONTH +1 DAY'));
		}

		$fecha = date('Y-m-d',strtotime('-12 MONTH', strtotime($fecha)));
		$fecha2 = date('Y-m-d',strtotime('-12 MONTH',strtotime($fecha2)));

		$periodoAnterior = date('d-m-Y', strtotime($fecha2))." al ".date('d-m-Y' ,strtotime($fecha));

		$query = "SELECT CONCAT(YEAR(TlkFecha),'-',LPAD(MONTH(TlkFecha), 2, '0'),'-',LPAD(DAY(TlkFecha), 2, '0')) as fecha, $unidades AS total $valor_mensual
		FROM lp_ventas_rubro lvr
		$join_accounts $join_ipc $joinFormaPago
/*M E*/		WHERE TlkFecha <= '$fecha'  AND TlkFecha >= '$fecha2' $familia $rubro $localizacion $adherido $local $filtroFormaPago
		GROUP BY fecha";
		$anterior = $db->query($query);
		foreach ($anterior as $key) {
			$aux2[$key["fecha"]] = $key["total"];
			$aux2Ipc[date('d', strtotime($key["fecha"]))] = $key["ipcmensual"];
		}

		$ipc_actual=0;
		while (strtotime($fecha2) <= strtotime($fecha)) {
			if(!is_null($aux2[$fecha2]))
				$total=$aux2[$fecha2];
			else
				$total=0;

			$datos[1][] = array(date('d', strtotime($fecha2)),floatval($total)); 
			$fecha2 = date ("Y-m-d", strtotime("+1 day", strtotime($fecha2)));

			if(isset($auxIpc[$mes]) && $auxIpc[$mes]!=0){
				$ipc_actual=$auxIpc[$mes];
			}	

		}
		if ($ipc){
			if($ipc_actual==0){
				$query="SELECT ipcmensual FROM vtiger_ipc
						ORDER BY ipcanio DESC, ipcmes DESC
						LIMIT 1";
				$result2 = $db->query($query);
				$ipc_actual =  $db->query_result($result2,0,'ipcmensual');
			}
			//echo "actual:".$ipc_actual;
			for ($i=0; $i < sizeof($datos[0]); $i++) {
				$ipcmes=$auxIpc[$datos[0][$i][0]];
				if(!isset($ipcmes))
					$ipcmes=$ipc_actual;
				$ipcmes2=$aux2Ipc[$datos[1][$i][0]];
				//echo "ipcmes2:".$ipcmes2;
				if(!isset($ipcmes2))
					$ipcmes2=$ipc_actual;

				$dif1=$ipc_actual-$ipcmes;
				$valor1=$datos[0][$i][1]*(1+$dif1/100);
				$datos[0][$i][1]=$valor1;
				$dif2=$ipc_actual-$ipcmes2;

				$valor2=$datos[1][$i][1]*(1+$dif2/100);
				if($valor2!=$datos[1][$i][1]){
					//echo $datos[1][$i][0]."/".$datos[1][$i][1]." - ".$valor2."/";
				}
				$datos[1][$i][1]=$valor2;
				//$datos[0][$i][1]=($datos[0][$i-1][1] * ($auxIpc[$datos[0][$i][0]]/100)) + $datos[0][$i-1][1];
				//$datos[1][$i][1]=($datos[1][$i-1][1] * ($aux2Ipc[$datos[1][$i][0]]/100)) + $datos[1][$i-1][1];
				
			}
			//$acu="false";
		}

		if ($acu=="true"){
			for ($i=0; $i < sizeof($datos[0]); $i++) {
				$datos[0][$i][1]=$datos[0][$i][1] + $datos[0][$i-1][1];
				$datos[1][$i][1]=$datos[1][$i][1] + $datos[1][$i-1][1];
			}
		}

	    echo json_encode(array($datos, $periodoActual, $periodoAnterior));
	    return;
	}

}