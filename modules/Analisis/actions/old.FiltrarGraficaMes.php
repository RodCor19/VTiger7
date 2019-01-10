<?php 

class Analisis_FiltrarGraficaMes_Action extends Vtiger_BasicAjax_Action{

	public function process(Vtiger_Request $request) {

		$uni = htmlspecialchars_decode($request->get('unidades'));
		$fec = htmlspecialchars_decode($request->get('fecha'));
		$acu = htmlspecialchars_decode($request->get('acumulado'));		
		$fam = htmlspecialchars_decode($request->get('familia'));
		$rub = htmlspecialchars_decode($request->get('rubro'));
		$loc = htmlspecialchars_decode($request->get('localizacion'));
/*M E*/		$nombreLocal = htmlspecialchars_decode($request->get('nombreLocal'));
		$adh = htmlspecialchars_decode($request->get('adherido'));

		$db =  PearDatabase::getInstance();

		$unidades = "SUM(TlkRVMontoNeto)";

		$filtrar=false;

		if($uni == "true")
			$unidades = "COUNT(TlkRVMontoNeto)";

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
/*M E*/			$local = "AND LocNombre='$nombreLocal'";
/*M E*/			$filtrar=true;
/*M E*/		}

		$join_accounts="";
		if($filtrar){
			$join_accounts="INNER join vtiger_account va on va.accountname=lvr.LocNombre";
		}

/*		$anio = date('Y');
		$mes = date('m');
		$dia = date('d', strtotime('-1 MONTH'));*/

		$fecha = date('Y-m-d');		
		$fecha2 = date('Y-m-d',strtotime('-12 MONTH +1 DAY'));
		$arr = array();
		if($fec!="actual" && $fec!=""){
			$arr=split(',', $fec);
			$fecha = date('Y-m-d',strtotime($arr[1]));
			$fecha2 = date('Y-m-d',strtotime($arr[0]));
		}

		$periodoActual = date('d-m-Y', strtotime($fecha2))." al ".date('d-m-Y' ,strtotime($fecha));


		$query = "SELECT CONCAT(MONTH(TlkFecha),'-',YEAR(TlkFecha)) as fecha, $unidades AS total 
		FROM lp_ventas_rubro lvr
		$join_accounts
/*M E*/		WHERE TlkFecha <= '$fecha'  AND TlkFecha > '$fecha2' $familia $rubro $localizacion $adherido $local
		GROUP BY fecha";	
		
		$anio = $db->query($query);	

		foreach ($anio as $key) {
			$a = $this->convertirmes($key["fecha"]);
			$aux[$a] = $key["total"];
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
			$fecha2 = date('Y-m-d',strtotime($arr[0]));	
		}		
		else{
			$fecha2 = date('Y-m-d',strtotime('-12 MONTH +1 DAY'));
		}

		$fecha = date('Y-m-d',strtotime('-12 MONTH', strtotime($fecha)));
		$fecha2 = date('Y-m-d',strtotime('-12 MONTH',strtotime($fecha2)));

		$periodoAnterior = date('d-m-Y', strtotime($fecha2))." al ".date('d-m-Y' ,strtotime($fecha));

		$query = "SELECT CONCAT(MONTH(TlkFecha),'-',YEAR(TlkFecha)) as fecha, $unidades AS total 
		FROM lp_ventas_rubro lvr
		$join_accounts
/*M E*/		WHERE TlkFecha <= '$fecha'  AND TlkFecha > '$fecha2' $familia $rubro $localizacion $adherido $local
		GROUP BY fecha";	
		$anterior = $db->query($query);

		foreach ($anterior as $key) {
			$a = $this->convertirmes($key["fecha"]);
			$aux2[$a] = $key["total"];
		}


		while (strtotime($fecha2) <= strtotime($fecha)) {
			$mes = $this->convertirmes(date('m-Y',strtotime($fecha2)));
			if(!is_null($aux2[$mes]))
				$total=$aux2[$mes];
			else
				$total=0;

			$datos[1][] = array($this->convertirmes(date('m-Y', strtotime("+12 MONTH", strtotime($fecha2)))),floatval($total)); 
			$fecha2 = date ("Y-m-d", strtotime("+1 MONTH", strtotime($fecha2)));
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