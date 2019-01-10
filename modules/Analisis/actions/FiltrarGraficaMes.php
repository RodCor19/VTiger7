<?php 

class Analisis_FiltrarGraficaMes_Action extends Vtiger_BasicAjax_Action{

	public function process(Vtiger_Request $request) {

		$uni = htmlspecialchars_decode($request->get('unidades'));
		$fec = htmlspecialchars_decode($request->get('fecha'));
		$acu = htmlspecialchars_decode($request->get('acumulado'));	
		$per = htmlspecialchars_decode($request->get('perimetro'));		
		$fam = htmlspecialchars_decode($request->get('familia'));
		$rub = htmlspecialchars_decode($request->get('rubro'));
		$loc = htmlspecialchars_decode($request->get('localizacion'));
		$nombreLocal = htmlspecialchars_decode($request->get('nombreLocal'));
		$formaPago = htmlspecialchars_decode($request->get('formapago'));
		$adh = htmlspecialchars_decode($request->get('adherido'));

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

		$filtrar=false;

		$ipc=false;

		if($uni == "unidades")
			$unidades = "COUNT(".$unidad.")";


		
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
		

		$fecha = date('Y/m/d');		
		//$fecha2 = date('Y-m-d',strtotime('-11 MONTH '));
		$date = strtotime( date('Y-m-01')." -11 months");
		$fecha2=date("Y/m/d", $date);
		$arr = array();
		if($fec!="actual" && $fec!=""){
			$arr=split(',', $fec);
			$fecha = date('Y/m/d',strtotime($arr[1]));
			$fecha2 = date('Y/m/d',strtotime($arr[0]));
		}


		if($per=="true"){
			$perimetro = "AND acnumerocontrato IN (
						SELECT distinct TlkIDContrato 
						FROM lp_ventas_rubro lvr2 
						WHERE MONTH(lvr2.TlkFecha) = MONTH('$fecha2') 
						AND YEAR(lvr2.TlkFecha) = YEAR('$fecha2'))";
		$_fecha = date('Y/m/d',strtotime('-12 MONTH', strtotime($fecha)));
		$_fecha2 = date('Y/m/d',strtotime('-12 MONTH',strtotime($fecha2)));
			$perimetro="INNER JOIN (SELECT DISTINCT lvr2.TlkIDContrato , MONTH(lvr2.TlkFecha) AS mes
						FROM lp_ventas_rubro lvr2 
						WHERE  lvr2.TlkFecha >= '$_fecha2' AND lvr2.TlkFecha <= '$_fecha'
						GROUP BY lvr2.TlkIDContrato, MONTH(lvr2.TlkFecha),YEAR(lvr2.TlkFecha)
					) AS anterior ON anterior.TlkIDContrato=lvr.TlkIDContrato AND anterior.mes = MONTH(lvr.TlkFecha)";
			$filtrar=true;
		}

		$join_accounts="";
		if($filtrar){
			$join_accounts="INNER join vtiger_account va on va.acnumerocontrato=lvr.TlkIDContrato";
		}



		$periodoActual = date('d-m-Y', strtotime($fecha2))." al ".date('d-m-Y' ,strtotime($fecha));


		//BF si es IPC, obtengo solo los datos del mes desde
		$valor_mensual=' ,0 as ipcmensual';
		$join_ipc='';
		if($ipc){
			$valor_mensual=' ,ipcmensual';
			$join_ipc=' LEFT JOIN vtiger_ipc ipc ON ipc.ipcmes=MONTH(TlkFecha) AND ipc.ipcanio=YEAR(TlkFecha) ';
		}

		$query = "SELECT CONCAT(MONTH(TlkFecha),'-',YEAR(TlkFecha)) as fecha, $unidades AS total $valor_mensual 
		FROM lp_ventas_rubro lvr
		$join_accounts $join_ipc $perimetro $joinFormaPago
/*M E*/		WHERE TlkFecha <= '$fecha'  AND TlkFecha > '$fecha2' $familia $rubro $localizacion $adherido $local $filtroFormaPago
		GROUP BY fecha
		ORDER BY YEAR(TlkFecha),MONTH(TlkFecha) ";	
		//echo $query;
		$anio = $db->query($query);	

		foreach ($anio as $key) {
			$a = $this->convertirmes($key["fecha"]);
			$aux[$a] = $key["total"];
			$auxIpc[$a] = $key["ipcmensual"];
		}

/*	    echo json_encode($aux);
	    return;*/

		$datos = array();

		while (strtotime($fecha2) <= strtotime($fecha)) {
			$mes = $this->convertirmes(date('m-Y',strtotime($fecha2)));
			if(!is_null($aux[$mes]))
				$total=$aux[$mes];
			else
				$total=0;

			$datos[0][] = array($this->convertirmes(date('m-Y', strtotime($fecha2))),floatval($total)); 
			$fecha2 = date ("Y-m-d", strtotime("+1 MONTH", strtotime($fecha2)));
		}
		


		if($fec!="actual" && $fec!=""){
			$fecha2 = date('Y/m/d',strtotime($arr[0]));	
		}		
		else{
			$date = strtotime( date('Y-m-01')." -11 months");
			$fecha2=date("Y/m/d", $date);
		}

		$fecha = date('Y/m/d',strtotime('-12 MONTH', strtotime($fecha)));
		$fecha2 = date('Y/m/d',strtotime('-12 MONTH',strtotime($fecha2)));

		if($per=="true"){
			$perimetro = "AND acnumerocontrato IN (
						SELECT distinct TlkIDContrato 
						FROM lp_ventas_rubro lvr2 
						WHERE MONTH(lvr2.TlkFecha) = MONTH('$fecha2') 
						AND YEAR(lvr2.TlkFecha) = YEAR('$fecha2'))";
			$filtrar=true;
		}

		$periodoAnterior = date('d-m-Y', strtotime($fecha2))." al ".date('d-m-Y' ,strtotime($fecha));

		$query = "SELECT CONCAT(MONTH(TlkFecha),'-',YEAR(TlkFecha)) as fecha, $unidades AS total $valor_mensual
		FROM lp_ventas_rubro lvr
		$join_accounts $join_ipc $joinFormaPago
/*M E*/		WHERE TlkFecha <= '$fecha'  AND TlkFecha > '$fecha2' $familia $rubro $localizacion $adherido $local $filtroFormaPago
		GROUP BY fecha ORDER BY YEAR(TlkFecha),MONTH(TlkFecha) ";	
		$anterior = $db->query($query);
		//echo $query;
		foreach ($anterior as $key) {
			$a = $this->convertirmes($key["fecha"]);
			$aux2[$a] = $key["total"];
			$aux2Ipc[$a] = $key["ipcmensual"];
		}

		$ipc_actual=0;
		while (strtotime($fecha2) <= strtotime($fecha)) {
			$mes = $this->convertirmes(date('m-Y',strtotime($fecha2)));
			if(!is_null($aux2[$mes]))
				$total=$aux2[$mes];
			else
				$total=0;

			$datos[1][] = array($this->convertirmes(date('m-Y', strtotime("+12 MONTH", strtotime($fecha2)))),floatval($total)); 
			$fecha2 = date ("Y-m-d", strtotime("+1 MONTH", strtotime($fecha2)));

			if(isset($auxIpc[$mes]) && $auxIpc[$mes]!=0){
				$ipc_actual=$auxIpc[$mes];
			}	
		}
		//var_dump($auxIpc);
		if ($ipc){
			for ($i=0; $i < sizeof($datos[0]); $i++) {
				$ipcmes=$auxIpc[$datos[0][$i][0]];
				if(!isset($ipcmes))
					$ipcmes=$ipc_actual;
				$ipcmes2=$aux2Ipc[$datos[1][$i][0]];
				if(!isset($ipcmes2))
					$ipcmes2=$ipc_actual;

				$dif1=$ipc_actual/$ipcmes;
				$valor1=$datos[0][$i][1]*($dif1);
				$datos[0][$i][1]=$valor1;
				$dif2=$ipc_actual/$ipcmes2;
				$valor2=$datos[1][$i][1]*($dif2);
				$datos[1][$i][1]=$valor2;
				//$datos[0][$i][1]=($datos[0][$i-1][1] * ($auxIpc[$datos[0][$i][0]]/100)) + $datos[0][$i-1][1];
				//$datos[1][$i][1]=($datos[1][$i-1][1] * ($aux2Ipc[$datos[1][$i][0]]/100)) + $datos[1][$i-1][1];
				
			}
			//$acu="false";
		}
		if ($acu=="true"){
			for ($i=1; $i < sizeof($datos[0]); $i++) {
				$datos[0][$i][1]=$datos[0][$i][1] + $datos[0][$i-1][1];
				$datos[1][$i][1]=$datos[1][$i][1] + $datos[1][$i-1][1];
			}
		}

	    echo json_encode(array($datos, $periodoActual, $periodoAnterior));
	    return;
	}

	function convertirmes($dato){
		  require_once 'include/utils/utils.php';
		$arr = split('-', $dato);
		if (intval($arr[0])<10){
			$arr[0]="0".$arr[0];
		}
		return nombremes($arr[0]);
		//return $arr[0]."-".$arr[1];
 	}

}