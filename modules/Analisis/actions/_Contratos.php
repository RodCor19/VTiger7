<?php 
ini_set("display_errors", 1);
error_reporting(E_ALL & ~E_NOTICE);

class Analisis_Contratos_Action extends Vtiger_BasicAjax_Action{

	public function process(Vtiger_Request $request) {

		
		$vista = $request->get('vista');


		
		
		$query=$this->getQuery($request);
		
		
		$json_string=$this->procesarRespuesta($query,$vista,$request);
		echo $json_string;
		return ;



	}
	public function obtenerFiltrosLocales(Vtiger_Request $request){
		$filtros_locales="";
		$fam = htmlspecialchars_decode($request->get('familia'));
		$rub = htmlspecialchars_decode($request->get('rubro'));
		$loc = htmlspecialchars_decode($request->get('localizacion'));
		$nombreLocal = htmlspecialchars_decode($request->get('nombreLocal'));
		if($fam!=""){
			$filtros_locales .= "AND lpfamilia LIKE '%$fam%'";
		}

		
		if($rub!=""){
			$filtros_locales .= "AND lprubro LIKE '%$rub%'";
		}		
		if($loc!=""){
			$filtros_locales .= "AND lplocalizacion='$loc'";
		}
		
		if($loc!=""){
			$filtros_locales .= "AND lplocalizacion='$loc'";
		}
		
		
		if($nombreLocal!=""){
			$filtros_locales .= "AND accountid=$nombreLocal";
		}
		return $filtros_locales;
	}
	public function getQuery(Vtiger_Request $request){
		$vista = $request->get('vista');

		$fecha_desde="";
		$fecha_hasta="";

		$fecha_hasta=date('Ym');
		$fecha2=date('Y-m-01');
		$date = strtotime( date('Y-m-01')." -11 months");
		$date = strtotime( date('Y-m-01')." -11 months");
		$fecha_desde=date("Ym", $date);
		$fecha1=date("Y-m-d", $date);
		
		$date = strtotime( date('Y-m-01')." -12 months");
		$fecha_hasta_anterior=date('Ym', $date);
		$date = strtotime( date('Y-m-01')." -23 months");
		$fecha_desde_anterior=date("Ym", $date);
		
		
		$createdTime = $request->get('createdtime');
		if(!empty($createdTime)) {
			$fecha_desde = DateTimeField::__convertToDBFormat($createdTime['start'],'dd-mm-yyyy');
			$fecha_hasta = DateTimeField::__convertToDBFormat($createdTime['end'],'dd-mm-yyyy');
			
			list($d, $m, $y) = explode('-', $createdTime['start']);
			$fecha_desde =$y.$m;
			$fecha1= strtotime($y."/".$m."/".$d);
			//$fecha1 =;
			$fecha_desde_anterior =($y-1).$m;
			list($d, $m, $y) = explode('-', $createdTime['end']);
			$fecha_hasta =$y.$m;
			$fecha2=strtotime($y."/".$m."/".$d);
			$fecha_hasta_anterior =($y-1).$m;
		}
		
		$numberOfMonths = abs(
			(date('Y', $fecha2) - date('Y', $fecha1))*12 + (date('m', $fecha2) - date('m', $fecha1)))+1;

		$filtros_locales=$this->obtenerFiltrosLocales($request);

		$query="";

		if($vista!="detalle"){

			//query común a actual y resumen
			$query=" SELECT ac.accountname, acmetros,
			SUM(if(vc.Aniomes>='".$fecha_desde."' AND vc.Aniomes <= '".$fecha_hasta."',vc.VentasSIva,0)) AS ventas,
			(select SUM(if(ar.Aniomes>='".$fecha_desde."' AND ar.Aniomes <= '".$fecha_hasta."',ar.ImpSIva,0))FROM lp_arrendamientos ar WHERE ar.numcont_lin = ac.acnumerocontrato AND ar.Aniomes >= '".$fecha_desde_anterior."' AND ar.Aniomes <= '".$fecha_hasta."' AND (cod_grupocon='ARRPORCENTUAL' OR cod_grupocon='ARRMINIMO'  )) AS arr,
				(select SUM(if(cod_grupocon='ARRPORCENTUAL' AND ar.Aniomes>='".$fecha_desde."' AND ar.Aniomes <= '".$fecha_hasta."',ar.ImpSIva,0))FROM lp_arrendamientos ar WHERE ar.numcont_lin = ac.acnumerocontrato AND ar.Aniomes >= '".$fecha_desde_anterior."' AND ar.Aniomes <= '".$fecha_hasta."' ) AS arr_porc,
			(
			SELECT SUM(ImpSIva) 
			FROM lp_arrendamientos ar 
			INNER JOIN (
			  select Max(AnioMes) as AnioMes, numcont_lin from lp_arrendamientos 
			  where cod_grupocon='ARRMINIMO' AND Aniomes >= '".$fecha_desde."'  AND Aniomes <= '".$fecha_hasta."'
			  group by numcont_lin
			  ) ultimafecha on ultimafecha.AnioMes=ar.AnioMes and ar.numcont_lin=ultimafecha.numcont_lin
			WHERE  ar.Aniomes >= '".$fecha_desde."' AND ar.Aniomes <= '".$fecha_hasta."' AND cod_grupocon='ARRMINIMO' and ar.numcont_lin=ac.acnumerocontrato 
			) AS arr_min ";


			if($vista=="actual"){
				//Agrego los años anteriores
				$query.=" ,SUM(if(vc.Aniomes>='".$fecha_desde_anterior."' AND vc.Aniomes <= '".$fecha_hasta_anterior."',vc.VentasSIva,0)) AS ventas_ant,
					(select SUM(if(ar.Aniomes>='".$fecha_desde_anterior."' AND ar.Aniomes <= '".$fecha_hasta_anterior."',ar.ImpSIva,0)) FROM lp_arrendamientos ar WHERE ar.numcont_lin = ac.acnumerocontrato AND ar.Aniomes >= '".$fecha_desde_anterior."' AND ar.Aniomes <= '".$fecha_hasta."' AND (cod_grupocon='ARRPORCENTUAL' OR cod_grupocon='ARRMINIMO'  )) AS arr_ant,
					(select SUM(if(cod_grupocon='ARRPORCENTUAL' AND ar.Aniomes>='".$fecha_desde_anterior."' AND ar.Aniomes <= '".$fecha_hasta_anterior."',ar.ImpSIva,0))FROM lp_arrendamientos ar WHERE ar.numcont_lin = ac.acnumerocontrato AND ar.Aniomes >= '".$fecha_desde_anterior."' AND ar.Aniomes <= '".$fecha_hasta."' ) AS arr_porc_ant";	
			}	

			$query.=", ".$numberOfMonths." as numberOfMonths";
			$query.=" FROM vtiger_account ac
			LEFT JOIN lp_ventas_contratos vc ON vc.Contrato = ac.acnumerocontrato
			
			WHERE 1=1  ".$filtros_locales;
			
			if($vista=='resumen'){
				if ($fecha_desde!=""){
				$query.=" AND vc.Aniomes >= '".$fecha_desde."'";
				}
						
			}else{
				if ($fecha_desde!=""){
					$query.=" AND vc.Aniomes >= '".$fecha_desde_anterior."'";
				}
			}
			if ($fecha_hasta!=""){
				$query.=" AND vc.Aniomes <= '".$fecha_hasta."' ";
			}
			$query.=" GROUP BY acnumerocontrato ORDER BY accountname";
		}else{

			$query="SELECT ac.accountname, ar.ImpSIva AS arr_porc ,
					AnioMes ,acnumerocontrato
					FROM lp_arrendamientos ar 
					INNER JOIN vtiger_account ac ON ar.numcont_lin = ac.acnumerocontrato
					WHERE cod_grupocon='ARRPORCENTUAL'";
			
			if ($fecha_desde!=""){
				$query.=" AND ar.Aniomes >= '".$fecha_desde."'";
			}
			if ($fecha_hasta!=""){
				$query.=" AND ar.Aniomes <= '".$fecha_hasta."' ";
			}

			$query.=" ".$filtros_locales;

			$query.=" GROUP BY ac.acnumerocontrato, ar.AnioMes
					ORDER BY accountname ASC, AnioMes ASC";
		}

		
		return $query;		

	}
	public function procesarRespuesta($query,$vista,$request){
		$adb = PearDatabase::getInstance();

		$result=$adb->query($query);

		if($vista!="detalle"){




			$json_string="[[\"Local\",\"Min $\",\"m2\",\"Min $ / m2\",\"Venta Contable\",
							\"Arr %\",\"Meses\",\"Arr Prom Mensual\",\"Arr Total\",\"Arr Total / VC (%)\",\"Venta Contable \",
							\"Arr % \",\"Meses \",\"Arr Prom Mensual \",\"Arr Total \",\"Arr Total / VC (%) \",
							\"VC\",
							\" Arr Total\",
							\"Arr Prom / m2\",
							\"Arr Prom Mensual \"],";
		
			$total=array();
			$total['arr']=0;$total['arr_porcent']=0;$total['ventas']=0;
			$total['arr_ant']=0;$total['arr_porcent_ant']=0;$total['ventas_ant']=0;	
			$total['arr_min']=0;$total['arr_min_m2']=0;	$total['acmetros']=0;	
			$total['arr_tot_mes_ant']=0;$total['arr_tot_mes']=0;
			$total['arr_prom_m2']=0;
			while ($row = $adb->fetch_array($result)){
				$arr=round($row["arr"],2);
				$arr_porcent=round($row["arr_porc"],2);
				$ventas=round($row["ventas"],2);
				$arr_porc=0;
				$division=0;
				if(isset($ventas) && $ventas!=0){
					$arr_porc=(double)round((($arr*100)/$ventas),2);
					$division=(double)round(($arr/$ventas)*100,2);
				}

				$total['arr']+=$arr;$total['arr_porcent']+=$arr_porcent;$total['ventas']+=$ventas;

				$arr_ant=round($row["arr_ant"],2);
				$arr_porcent_ant=round($row["arr_porc_ant"],2);
				$ventas_ant=round($row["ventas_ant"],2);
				$arr_porc_ant=0;
				$division_ant=0;
				if(isset($ventas_ant) && $ventas_ant!=0){
					$arr_porc_ant=(double)round((($arr_ant*100)/$ventas_ant),2);
					$division_ant=(double)round(($arr_ant/$ventas_ant)*100,2);
				}
				$total['arr_ant']+=$arr_ant;$total['arr_porcent_ant']+=$arr_porcent_ant;
				$total['ventas_ant']+=$ventas_ant;	

				$dif=$ventas-$ventas_ant;
				$ventas_ev=round(($dif/$ventas_ant)*100,2);

				$dif=$arr-$arr_ant;
				$arr_ev=round(($dif/$arr_ant)*100,2);
			
			
				$arr_min=round($row["arr_min"],2);
				
				$arr_min_m2=0;
				if(isset($row['acmetros']) && $row['acmetros']!="")	
					$arr_min_m2=round(($row["arr_min"]/$row['acmetros']),2);

				$total['arr_min']+=$arr_min;$total['arr_min_m2']+=$arr_min_m2;
				$total['acmetros']+=$row['acmetros'];	

				$arr_tot_mes_ant=round($arr_porcent_ant/$row['numberofmonths'],2);
				$arr_tot_mes=round($arr_porcent/$row['numberofmonths'],2);

				$total['arr_tot_mes_ant']+=$arr_tot_mes_ant;	
				$total['arr_tot_mes']+=$arr_tot_mes;	

				$arr_prom_m2=0;
				if(isset($row['acmetros']) && $row['acmetros']!=""){	
					$arr_prom_m2=round($arr_porcent/$row['acmetros'],2);
					//echo $arr_porcent;
				}


				$total['arr_prom_m2']+=$arr_prom_m2;	

				$json_string.="[\"".$row['accountname']."\",
								\"".$arr_min."\",
								\"".$row['acmetros']."\",
								\"".$arr_min_m2."\",
								\"".$ventas_ant."\",
								\"".$arr_porcent_ant."\",
								\"".$row['numberofmonths']."\",
								\"".$arr_tot_mes_ant."\",
								\"".$arr_ant."\",
								\"".$division_ant."\",
								\"".$ventas."\",
								\"".$arr_porcent."\",
								\"".$row['numberofmonths']."\",
								\"".$arr_tot_mes."\",
								\"".$arr."\",
								\"".$division."\",
								\"".$ventas_ev."\",
								\"".$arr_ev."\",
								\"".$arr_tot_mes."\",
								\"".$arr_prom_m2."\"
								],";
				
			
			}

			//Agrego el total
			$arr=round($total["arr"],2);
			$arr_porcent=round($total["arr_porcent"],2);
			$ventas=round($total["ventas"],2);
			$arr_porc=0;
			$division=0;
			if(isset($ventas) && $ventas!=0){
				$arr_porc=(double)round((($arr*100)/$ventas),2);
				$division=(double)round(($arr/$ventas)*100,2);
			}

			$arr_ant=round($total["arr_ant"],2);
			$arr_porcent_ant=round($total["arr_porcent_ant"],2);
			$ventas_ant=round($total["ventas_ant"],2);
			$arr_porc_ant=0;
			$division_ant=0;
			if(isset($ventas_ant) && $ventas_ant!=0){
				$arr_porc_ant=(double)round((($arr_ant*100)/$ventas_ant),2);
				$division_ant=(double)round(($arr_ant/$ventas_ant)*100,2);
			}
			
			$dif=$ventas-$ventas_ant;
			$ventas_ev=round(($dif/$ventas_ant)*100,2);

			$dif=$arr-$arr_ant;
			$arr_ev=round(($dif/$arr_ant)*100,2);


			$arr_min=round($total["arr_min"],2);
			$arr_min_m2=round($total["arr_min_m2"],2);
			
			

			$json_string.="[\"Total\",
							\"".$arr_min."\",
							\"".$total['acmetros']."\",
							\"".$arr_min_m2."\",
							\"".$ventas_ant."\",
							\"".$arr_porcent_ant."\",
							\"".$row['numberofmonths']."\",
							\"".$total['arr_tot_mes_ant']."\",
							\"".$arr_ant."\",
							\"".$division_ant."\",
							\"".$ventas."\",
							\"".$arr_porcent."\",
							\"".$row['numberofmonths']."\",
							\"".$total['arr_tot_mes']."\",
							\"".$arr."\",
							\"".$division."\",
							\"".$ventas_ev."\",
							\"".$arr_ev."\",
							\"".$total['arr_tot_mes']."\",
							\"".$total['arr_prom_m2']."\"
							],";
			



			$json_string=rtrim($json_string, ",");
			$json_string.="]";
		}else{
			$json_string=$this->procesarVistaDetalle($query,$request);
		}	
		return $json_string;
	}
	public function procesarVistaDetalle($query,$request){
		$adb = PearDatabase::getInstance();

		$result=$adb->query($query);



		$fecha2=date('Y-m-01');
		$date = strtotime( date('Y-m-01')." -11 months");
		$fecha=date("Y-m-d", $date);
		
		$createdTime = $request->get('createdtime');
		if(!empty($createdTime)) {
			$fecha_desde = DateTimeField::__convertToDBFormat($createdTime['start'],'dd-mm-yyyy');
			$fecha_hasta = DateTimeField::__convertToDBFormat($createdTime['end'],'dd-mm-yyyy');
			list($d, $m, $y) = explode('-', $createdTime['start']);
			$fecha= $y."-".$m."-".$d;
			list($d, $m, $y) = explode('-', $createdTime['end']);
			$fecha2=$y."-".$m."-".$d;
		}

		$total = array();
		$meses = array();
		$json_string="[[\"Local\"";

		
		while (strtotime($fecha) <= strtotime($fecha2)) {
			$mes = $this->convertirmes(date('m-Y',strtotime($fecha)),"-");
			$mes2 = $this->convertirmes(date('m-Y',strtotime($fecha)),"");
			$json_string.=",\"".$mes."\"";
			$total[$mes2]=0;
			$meses[]=$mes2;
			$fecha = date ("Y-m-d", strtotime("+1 MONTH", strtotime($fecha)));
		}
		$json_string.="]";

		
		$datos=array();	
		while ($row = $adb->fetch_array($result)){
			$cuenta=$row["accountname"];
			$aniomes=$this->convertirmesInvertido($row["aniomes"],'');

			$arr_porcent=round($row["arr_porc"],2);

			$datos[$cuenta][$aniomes]=$arr_porcent;

			$total[$aniomes]+=$arr_porcent;
		}
		
		foreach ($datos as $local=>$mes) {
			$json_string.=",[\"".$local."\"";
			foreach ($meses as $mes_a_comparar) {
				//echo $mes_a_comparar."<br>";
				if(isset($datos[$local][$mes_a_comparar])){
					//Si existe valor para el mes lo agrego
					$json_string.=",\"".$datos[$local][$mes_a_comparar]."\"";
				}else{
					//Sino Pongo 0
					$json_string.=",\"0\"";
				}
			}
			$json_string.="]";
		    /*foreach ($mes as $mes=>$arr_porc) {
		        
		    }*/
		}

		$json_string.=",[\"Total\"";
		foreach ($meses as $mes_a_comparar) {
			if(isset($total[$mes_a_comparar])){
				//Si existe valor para el mes lo agrego
				$json_string.=",\"".$total[$mes_a_comparar]."\"";
			}else{
				//Sino Pongo 0
				$json_string.=",\"0\"";
			}
		}
		$json_string.="]";

		$json_string=rtrim($json_string, ",");
		$json_string.="]";



		return $json_string;

	}
	function convertirmes($dato,$sep){
		  require_once 'include/utils/utils.php';
		$arr = split('-', $dato);
		if (intval($arr[0])<10){
			$arr[0]="0".$arr[0];
		}
		return nombremes($arr[0]).$sep.$arr[1];
		//return $arr[0]."-".$arr[1];
 	}
	function convertirmesInvertido($dato,$sep){
		require_once 'include/utils/utils.php';
		$anio=substr($dato, 0,4);
		$mes=substr($dato, 4,2);
		if (intval($mes)<10){
			$mes="0".$mes;
		}
		//echo $mes;
		return nombremes($mes).$sep.$anio;
		//return $arr[0]."-".$arr[1];
 	}
}