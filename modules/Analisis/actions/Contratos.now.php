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
		$m2 = htmlspecialchars_decode($request->get('m2'));
		if($fam!=""){
			//$filtros_locales .= "AND lpfamilia LIKE '%$fam%'";
			$rangos=explode(",", $fam);
			$filtros_locales.=" AND (";
			foreach($rangos as $id){
				$filtros_locales.=" lpfamilia LIKE '%".$id."%' OR";
			}	
			$filtros_locales=rtrim($filtros_locales,'OR');
			$filtros_locales.=" )";
		}

		
		if($rub!=""){
			//$filtros_locales .= "AND lprubro LIKE '%$rub%'";
			$rangos=explode(",", $rub);
			$filtros_locales.=" AND (";
			foreach($rangos as $id){
				$filtros_locales.=" lprubro LIKE '%".$id."%' OR";
			}	
			$filtros_locales=rtrim($filtros_locales,'OR');
			$filtros_locales.=" )";
		}		
		if($loc!=""){
			$filtros_locales .= "AND lplocalizacion='$loc'";
		}
		
		if($loc!=""){
			$filtros_locales .= "AND lplocalizacion='$loc'";
		}
		
		
		if($nombreLocal!=""){
			//$filtros_locales .= "AND accountid=$nombreLocal";
			$rangos=explode(",", $nombreLocal);
			$filtros_locales.=" AND (";
			foreach($rangos as $id){
				$filtros_locales.=" accountid = ".$id." OR";
			}	
			$filtros_locales=rtrim($filtros_locales,'OR');
			$filtros_locales.=" )";
		}

		if($m2!=""){
			switch ($m2) {
				case '<9':
					$cons=$m2;
					break;
				case '9<39':
					$cons=">=9 AND acmetros<40";
					break;
				case '40<70':
					$cons=">=40 AND acmetros<71";
					break;
				case '71<120':
					$cons=">=71 AND acmetros<121";
					break;
				case '121<200':
					$cons=">=121 AND acmetros<201";
					break;
				case '201<399':
					$cons=">=201 AND acmetros<400";
					break;
				case '400<800':
					$cons=">=400 AND acmetros<801";
					break;
				case '801<':
				case '801':
					$cons=">=801";
					break;
				
				default:
					$cons=">0";
					break;
			}
			$filtros_locales .= "AND acmetros".$cons;
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
			$date = strtotime( date('Y/m/d',$fecha1)." -23 months");
			//$fecha_desde_anterior=date('Ym', $date);
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

		if($vista=="actual" || $vista=="resumen"){

			$filtro_fechas="";
			if($vista=='resumen'){
				if ($fecha_desde!=""){
				$filtro_fechas.=" AND vc.Aniomes >= '".$fecha_desde."'";
				}
						
			}else{
				if ($fecha_desde!=""){
					$filtro_fechas.=" AND vc.Aniomes >= '".$fecha_desde_anterior."'";
				}
			}
			if ($fecha_hasta!=""){
				$filtro_fechas.=" AND vc.Aniomes <= '".$fecha_hasta."' ";
			}


			//query común a actual y resumen

			$query="SELECT accountid, accountname ,fecha,fecha_prox,metros as acmetros,arr_min,";
			$query.=" IF(CONCAT(YEAR(fecha),LPAD(MONTH(fecha),2,'0'))< '".$fecha_desde."' AND CONCAT(YEAR(fecha_prox),LPAD(MONTH(fecha_prox),2,'0'))>='".$fecha_hasta."',1,0 ) AS mismo_periodo,";
			$query.=" SUM(IF(ar.Aniomes>='".$fecha_desde."' AND ar.Aniomes <= '".$fecha_hasta."' AND 
				(cod_grupocon='ARRPORCENTUAL' OR cod_grupocon='ARRMINIMO' ) AND ar.AnioMes >=CONCAT(YEAR(fecha),LPAD(MONTH(fecha),2,'0')) 
				AND ar.AnioMes < CONCAT(YEAR(fecha_prox),LPAD(MONTH(fecha_prox),2,'0')),ar.ImpSIva,0 )) AS arr,
				";
			$query.="SUM(IF(ar.Aniomes>='".$fecha_desde."' AND ar.Aniomes <= '".$fecha_hasta."' AND cod_grupocon='ARRPORCENTUAL' AND ar.AnioMes >=CONCAT(YEAR(fecha),LPAD(MONTH(fecha),2,'0')) 
				AND ar.AnioMes < CONCAT(YEAR(fecha_prox),LPAD(MONTH(fecha_prox),2,'0')),ar.ImpSIva,0 )) AS arr_porc,
				";			
			//$query.="(SELECT ImpSIva FROM lp_arrendamientos ar WHERE ar.numcont_lin = cuentas.acnumerocontrato AND ar.cod_grupocon='ARRMINIMO' AND ar.Aniomes>='".$fecha_desde."' AND ar.Aniomes <= '".$fecha_hasta."' AND ar.AnioMes >=CONCAT(YEAR(fecha),LPAD(MONTH(fecha),2,'0')) AND ar.AnioMes < CONCAT(YEAR(fecha_prox),LPAD(MONTH(fecha_prox),2,'0')) ORDER BY ar.AnioMes DESC LIMIT 1)	AS arr_min ,";
			$query.="SUM(IF(ar.Aniomes>='".$fecha_desde."' AND ar.Aniomes <= '".$fecha_hasta."' AND cod_grupocon='ARRPORCENTUAL' AND ar.AnioMes >=CONCAT(YEAR(fecha),LPAD(MONTH(fecha),2,'0')) 
				AND ar.AnioMes < CONCAT(YEAR(fecha_prox),LPAD(MONTH(fecha_prox),2,'0')),1,0 )) AS arr_porc_cant,
				SUM(IF(ar.Aniomes>='".$fecha_desde."' AND ar.Aniomes <= '".$fecha_hasta."' AND cod_grupocon='ARRMINIMO' AND ar.AnioMes >=CONCAT(YEAR(fecha),LPAD(MONTH(fecha),2,'0')) 
				AND ar.AnioMes < CONCAT(YEAR(fecha_prox),LPAD(MONTH(fecha_prox),2,'0')),1,0 )) AS arr_min_cant,

				(select SUM(if(vc.Aniomes>='".$fecha_desde."' AND vc.Aniomes <= '".$fecha_hasta."',vc.VentasSIva,0)) 
					FROM lp_ventas_contratos vc WHERE vc.Contrato = cuentas.acnumerocontrato $filtro_fechas AND vc.AnioMes >=CONCAT(YEAR(fecha),LPAD(MONTH(fecha),2,'0')) AND vc.AnioMes < CONCAT(YEAR(fecha_prox),LPAD(MONTH(fecha_prox),2,'0'))
				)AS ventas
				";	

			/*$query=" SELECT ac.accountid ,ac.accountname, metros as acmetros,
				(select SUM(if(vc.Aniomes>='".$fecha_desde."' AND vc.Aniomes <= '".$fecha_hasta."',vc.VentasSIva,0)) 
					FROM lp_ventas_contratos vc WHERE vc.Contrato = ac.acnumerocontrato $filtro_fechas
				)AS ventas,
				(select SUM(if(ar.Aniomes>='".$fecha_desde."' AND ar.Aniomes <= '".$fecha_hasta."',ar.ImpSIva,0))FROM lp_arrendamientos ar WHERE ar.numcont_lin = ac.acnumerocontrato AND ar.Aniomes >= '".$fecha_desde_anterior."' AND ar.Aniomes <= '".$fecha_hasta."' AND (cod_grupocon='ARRPORCENTUAL' OR cod_grupocon='ARRMINIMO'  )) AS arr,
				(select SUM(if(cod_grupocon='ARRPORCENTUAL' AND ar.Aniomes>='".$fecha_desde."' AND ar.Aniomes <= '".$fecha_hasta."',ar.ImpSIva,0))FROM lp_arrendamientos ar WHERE ar.numcont_lin = ac.acnumerocontrato AND ar.Aniomes >= '".$fecha_desde_anterior."' AND ar.Aniomes <= '".$fecha_hasta."' ) AS arr_porc,
				(select COUNT(DISTINCT AnioMes)  FROM lp_arrendamientos ar WHERE ar.numcont_lin = ac.acnumerocontrato AND ar.Aniomes >= '".$fecha_desde."' AND ar.Aniomes <= '".$fecha_hasta."' AND (cod_grupocon='ARRPORCENTUAL') ) AS arr_porc_cant,
			(SELECT ImpSIva
			FROM lp_arrendamientos ar 
			WHERE  ar.numcont_lin = ac.acnumerocontrato and ar.cod_grupocon='ARRMINIMO' 
			order by ar.AnioMes desc limit 1)	AS arr_min 
			";*/


			if($vista=="actual"){
				//Agrego los años anteriores
				$query.=", SUM(IF(ar.Aniomes>='".$fecha_desde_anterior."' AND ar.Aniomes <= '".$fecha_hasta_anterior."' AND 
				(cod_grupocon='ARRPORCENTUAL' OR cod_grupocon='ARRMINIMO' ) AND ar.AnioMes >=CONCAT(YEAR(fecha),LPAD(MONTH(fecha),2,'0')) 
				AND ar.AnioMes < CONCAT(YEAR(fecha_prox),LPAD(MONTH(fecha_prox),2,'0')),ar.ImpSIva,0 )) AS arr_ant,
				";
				$query.="SUM(IF(ar.Aniomes>='".$fecha_desde_anterior."' AND ar.Aniomes <= '".$fecha_hasta_anterior."' AND cod_grupocon='ARRPORCENTUAL' AND ar.AnioMes >=CONCAT(YEAR(fecha),LPAD(MONTH(fecha),2,'0')) 
					AND ar.AnioMes < CONCAT(YEAR(fecha_prox),LPAD(MONTH(fecha_prox),2,'0')),ar.ImpSIva,0 )) AS arr_porc_ant,
					";			
				$query.="SUM(IF(ar.Aniomes>='".$fecha_desde_anterior."' AND ar.Aniomes <= '".$fecha_hasta_anterior."' AND cod_grupocon='ARRPORCENTUAL' AND ar.AnioMes >=CONCAT(YEAR(fecha),LPAD(MONTH(fecha),2,'0')) 
					AND ar.AnioMes < CONCAT(YEAR(fecha_prox),LPAD(MONTH(fecha_prox),2,'0')),1,0 )) AS arr_porc_ant_cant,
					SUM(IF(ar.Aniomes>='".$fecha_desde_anterior."' AND ar.Aniomes <= '".$fecha_hasta_anterior."' AND cod_grupocon='ARRMINIMO' AND ar.AnioMes >=CONCAT(YEAR(fecha),LPAD(MONTH(fecha),2,'0')) 
					AND ar.AnioMes < CONCAT(YEAR(fecha_prox),LPAD(MONTH(fecha_prox),2,'0')),1,0 )) AS arr_min_ant_cant, 
					(select SUM(if(vc.Aniomes>='".$fecha_desde_anterior."' AND vc.Aniomes <= '".$fecha_hasta_anterior."',vc.VentasSIva,0)) 
						FROM lp_ventas_contratos vc WHERE vc.Contrato = cuentas.acnumerocontrato $filtro_fechas AND vc.AnioMes >=CONCAT(YEAR(fecha),LPAD(MONTH(fecha),2,'0')) AND vc.AnioMes < CONCAT(YEAR(fecha_prox),LPAD(MONTH(fecha_prox),2,'0'))
					)AS ventas_ant
					";	
					

					
					
			}	

			$query.=", ".$numberOfMonths." as numberOfMonths";
			/*$query.=" FROM vtiger_account ac
			
			inner join vtiger_crmentity e on e.crmid=ac.accountid 

			inner join vtiger_superficie s on s.cuenta=ac.accountid
			
			WHERE 1=1 AND deleted=0  ".$filtros_locales;*/

			$query.=" FROM(
			SELECT s.metros,ac.accountid,superficieid,ac.acnumerocontrato,ac.accountname,s.fecha, IFNULL((SELECT s1.fecha FROM vtiger_superficie s1 WHERE s.cuenta=s1.cuenta
			AND s.fecha < s1.fecha LIMIT 1),NOW()) AS fecha_prox, ac.acarminimo as arr_min
			FROM vtiger_account ac 
			INNER JOIN vtiger_crmentity e ON e.crmid=ac.accountid 
			INNER JOIN vtiger_superficie s ON s.cuenta=ac.accountid 
			WHERE 1=1 AND deleted=0 ".$filtros_locales."
			ORDER BY accountname) cuentas 
			LEFT JOIN lp_arrendamientos ar ON ar.numcont_lin = cuentas.acnumerocontrato 
			WHERE ar.Aniomes >= '".$fecha_desde_anterior."' AND ar.Aniomes <= '".$fecha_hasta."'
			AND CONCAT(YEAR(fecha_prox),MONTH(fecha_prox)) >='".$fecha_desde_anterior."'
			GROUP BY acnumerocontrato,superficieid";
			
			
			$query.=" ORDER BY accountname";
		}else{

			if($vista=="detalle"){
				/*INNER JOIN vtiger_account*/
				$query="SELECT ac.accountid ,ac.accountname, ar.ImpSIva AS arr_porc ,
						AnioMes ,acnumerocontrato,cod_grupocon, ac.superficieid
						FROM lp_arrendamientos ar 
						INNER JOIN (SELECT s.metros,ac.accountid,superficieid,ac.acnumerocontrato,ac.accountname,s.fecha, 
							IFNULL((SELECT s1.fecha FROM vtiger_superficie s1 WHERE s.cuenta=s1.cuenta
										AND s.fecha < s1.fecha LIMIT 1),NOW()) AS fecha_prox, ac.acarminimo as arr_min
										FROM vtiger_account ac 
										INNER JOIN vtiger_crmentity e ON e.crmid=ac.accountid 
										INNER JOIN vtiger_superficie s ON s.cuenta=ac.accountid 
										$filtros_locales
										WHERE 1=1 AND deleted=0 
										ORDER BY accountname) ac ON ar.numcont_lin = ac.acnumerocontrato
						inner join vtiger_crmentity e on e.crmid=ac.accountid
						WHERE deleted=0 AND (cod_grupocon='ARRPORCENTUAL' OR cod_grupocon='ARRMINIMO')";
				$query.="and ar.AnioMes < CONCAT(YEAR(ac.fecha_prox),LPAD(MONTH(ac.fecha_prox),2,'0'))
					and ar.AnioMes >= CONCAT(YEAR(ac.fecha),LPAD(MONTH(ac.fecha),2,'0'))";
				if ($fecha_desde!=""){
					$query.=" AND ar.Aniomes >= '".$fecha_desde."'";
				}
				if ($fecha_hasta!=""){
					$query.=" AND ar.Aniomes <= '".$fecha_hasta."' ";
				}

				//$query.=" ".$filtros_locales;

				$query.=" GROUP BY ac.acnumerocontrato, ac.superficieid, ar.AnioMes, cod_grupocon
						ORDER BY accountname ASC, AnioMes ASC, cod_grupocon";
			}else{

				$ipc=false;
				if($vista=="ventas_ipc")
					$ipc=true;

				$valor_mensual=' ,0 as ipcmensual';
				$join_ipc='';
				if($ipc){
					$valor_mensual=' ,ipcmensual';
					$join_ipc=" LEFT JOIN vtiger_ipc ipc ON AnioMes=CONCAT(ipc.ipcanio,LPAD(ipc.ipcmes,2,'0')) ";
				}

				$query="SELECT ac.accountid ,ac.accountname, vc.VentasSIva AS ventas ,
						AnioMes ,acnumerocontrato,ac.superficieid $valor_mensual
						FROM lp_ventas_contratos vc  
						INNER JOIN (SELECT s.metros,ac.accountid,superficieid,ac.acnumerocontrato,ac.accountname,s.fecha, 
							IFNULL((SELECT s1.fecha FROM vtiger_superficie s1 WHERE s.cuenta=s1.cuenta
										AND s.fecha < s1.fecha LIMIT 1),NOW()) AS fecha_prox, ac.acarminimo as arr_min
										FROM vtiger_account ac 
										INNER JOIN vtiger_crmentity e ON e.crmid=ac.accountid 
										INNER JOIN vtiger_superficie s ON s.cuenta=ac.accountid 
										$filtros_locales
										WHERE 1=1 AND deleted=0 
										ORDER BY accountname) ac ON vc.Contrato = ac.acnumerocontrato
						inner join vtiger_crmentity e on e.crmid=ac.accountid 
						$join_ipc
						WHERE deleted=0 ";
				
				if ($fecha_desde!=""){
					$query.=" AND vc.Aniomes >= '".$fecha_desde_anterior."'";
				}
				if ($fecha_hasta!=""){
					$query.=" AND vc.Aniomes <= '".$fecha_hasta."' ";
				}

				$query.="and vc.AnioMes < CONCAT(YEAR(ac.fecha_prox),LPAD(MONTH(ac.fecha_prox),2,'0'))
					and vc.AnioMes >= CONCAT(YEAR(ac.fecha),LPAD(MONTH(ac.fecha),2,'0'))";

				//$query.=" ".$filtros_locales;

				$query.=" GROUP BY ac.acnumerocontrato,ac.superficieid,  vc.AnioMes
						ORDER BY accountname ASC,ac.superficieid asc, AnioMes ASC,fecha asc";

			}			
		}

		
		return $query;		

	}
	public function procesarRespuesta($query,$vista,$request){
		$adb = PearDatabase::getInstance();

		$result=$adb->query($query);

		if($vista=="actual" || $vista=="resumen"){




			$json_string="[[\"Local\",\"Min $\",\"m2\",\"Min $ / m2\",\"Venta Contable\",
							\"Arr %\",\"Meses\",\"Arr Prom Mensual\",\"Arr Total\",\"Arr Total / VC (%)\",\"Venta Contable \",
							\"Arr % \",\"Meses \",\"Arr Prom Mensual \",\"Arr Total \",\"Arr Total / VC (%) \",
							\"VC\",
							\" Arr Total\",
							\"Arr Prom / m2\",
							\"Arr Prom Mensual \",
							\"VC/m2\"],";
		
			$total=array();
			$total['arr']=0;$total['arr_porcent']=0;$total['ventas']=0;
			$total['arr_ant']=0;$total['arr_porcent_ant']=0;$total['ventas_ant']=0;	
			$total['arr_min']=0;$total['arr_min_m2']=0;	$total['acmetros']=0;	
			$total['arr_tot_mes_ant']=0;$total['arr_tot_mes']=0;
			$total['arr_prom_m2']=0;
			$total['vc_m2']=0;
			while ($row = $adb->fetch_array($result)){
				$arr=round($row["arr"],2);
				$arr_porcent=round($row["arr_porc"],2);
				$ventas=round($row["ventas"],2);
				$arr_porc=0;
				$division=0;
				if(isset($ventas) && $ventas!=0){
					$arr_porc=(double)round((($arr*100)/$ventas),2);
					$division=(double)round(($arr/$ventas)*100,1);
					$division=number_format($division, 1, ',', '');
				}

				$arr_ant=round($row["arr_ant"],2);
				$arr_porcent_ant=round($row["arr_porc_ant"],2);
				$ventas_ant=round($row["ventas_ant"],2);
				$mismo_periodo=$row["mismo_periodo"];	
				//Filtro los que no tengan datos en Ventas y Arr
				if(($arr>0 || $arr_ant>0) || ($ventas > 0 || $ventas_ant > 0)){


					$total['arr']+=$arr;$total['arr_porcent']+=$arr_porcent;$total['ventas']+=$ventas;

					
					$arr_porc_ant=0;
					$division_ant=0;
					if(isset($ventas_ant) && $ventas_ant!=0){
						$arr_porc_ant=(double)round((($arr_ant*100)/$ventas_ant),2);
						$division_ant=(double)round(($arr_ant/$ventas_ant)*100,1);
						$division_ant=number_format($division_ant, 1, ',', '');
					}
					$total['arr_ant']+=$arr_ant;$total['arr_porcent_ant']+=$arr_porcent_ant;
					$total['ventas_ant']+=$ventas_ant;	

					$dif=$ventas-$ventas_ant;
					$ventas_ev=round(($dif/$ventas_ant)*100,1)."%";

					$dif=$arr-$arr_ant;
					$arr_ev=round(($dif/$arr_ant)*100,1)."%";
					

					if($mismo_periodo==0){
						$ventas_ev="<font color='red'>".$ventas_ev."</font>";						
						$arr_ev="<font color='red'>".$arr_ev."</font>";						
						//$arr_ev.=" *";						
					}
				
					$arr_min=round($row["arr_min"],1);

					$vc_m2=0;
					if(isset($row['acmetros']) && $row['acmetros']!="")	
						$vc_m2=round($ventas/$row['acmetros'],2);
					
					$arr_min_m2=0;
					if(isset($row['acmetros']) && $row['acmetros']!="")	
						$arr_min_m2=round(($row["arr_min"]/$row['acmetros']),1);

					$total['arr_min']+=$arr_min;$total['arr_min_m2']+=$arr_min_m2;
					$total['acmetros']+=$row['acmetros'];	
					$total['vc_m2']+=$row['vc_m2'];	


					$arr_tot_mes_ant=0;
					if(isset($row['arr_min_ant_cant']) 
						&& $row['arr_min_ant_cant']!="")	
						$arr_tot_mes_ant=round($arr_ant/$row['arr_min_ant_cant'],2);

					$arr_tot_mes=0;
					if(isset($row['arr_min_cant']) 
						&& $row['arr_min_cant']!="")	
						$arr_tot_mes=round($arr/$row['arr_min_cant'],2);

					$total['arr_tot_mes_ant']+=$arr_tot_mes_ant;	
					$total['arr_tot_mes']+=$arr_tot_mes;	

					$arr_prom_m2=0;
					if(isset($row['acmetros']) && $row['acmetros']!=""){	
						$arr_prom_m2=round($arr_tot_mes/$row['acmetros'],2);
						//echo $arr_porcent;
					}


					$total['arr_prom_m2']+=$arr_prom_m2;	
					
					$json_string.="[\"<a href='index.php?module=Accounts&view=Detail&record=".$row['accountid']."'>".$row['accountname']."</a>\",
									\"".$arr_min."\",
									\"".$row['acmetros']."\",
									\"".$arr_min_m2."\",
									\"".$ventas_ant."\",
									\"".$arr_porcent_ant."\",
									\"".$row['arr_porc_ant_cant']."\",
									\"".$arr_tot_mes_ant."\",
									\"".$arr_ant."\",
									\"".$division_ant."%\",
									\"".$ventas."\",
									\"".$arr_porcent."\",
									\"".$row['arr_porc_cant']."\",
									\"".$arr_tot_mes."\",
									\"".$arr."\",
									\"".$division."%\",
									\"".$ventas_ev."\",
									\"".$arr_ev."\",
									\"".$arr_prom_m2."\",
									\"".$arr_tot_mes."\",
									\"".$vc_m2."\"
									],";
					
				}
			}

			//Agrego el total
			$arr=round($total["arr"],1);
			$arr_porcent=round($total["arr_porcent"],1);
			$ventas=round($total["ventas"],1);
			$arr_porc=0;
			$division=0;
			if(isset($ventas) && $ventas!=0){
				$arr_porc=(double)round((($arr*100)/$ventas),1);
				$division=(double)round(($arr/$ventas)*100,1);
			}

			$arr_ant=round($total["arr_ant"],1);
			$arr_porcent_ant=round($total["arr_porcent_ant"],1);
			$ventas_ant=round($total["ventas_ant"],1);
			$arr_porc_ant=0;
			$division_ant=0;
			if(isset($ventas_ant) && $ventas_ant!=0){
				$arr_porc_ant=(double)round((($arr_ant*100)/$ventas_ant),1);
				$division_ant=(double)round(($arr_ant/$ventas_ant)*100,1);
			}
			
			$dif=$ventas-$ventas_ant;
			$ventas_ev=round(($dif/$ventas_ant)*100,1);

			$dif=$arr-$arr_ant;
			$arr_ev=round(($dif/$arr_ant)*100,1);


			$arr_min=round($total["arr_min"],1);
			//$arr_min_m2=round($total["arr_min_m2"],2);
			$arr_min_m2=round(($total["arr_min"]/$total['acmetros']),1);
			
			$vc_m2=round($ventas/$total['acmetros'],2);
			

			$json_string.="[\"Total\",
							\"".$arr_min."\",
							\"".$total['acmetros']."\",
							\"".$arr_min_m2."\",
							\"".$ventas_ant."\",
							\"".$arr_porcent_ant."\",
							\"".$row['numberofmonths']."\",
							\"".$total['arr_tot_mes_ant']."\",
							\"".$arr_ant."\",
							\"".$division_ant."%\",
							\"".$ventas."\",
							\"".$arr_porcent."\",
							\"".$row['numberofmonths']."\",
							\"".$total['arr_tot_mes']."\",
							\"".$arr."\",
							\"".$division."%\",
							\"".$ventas_ev."\",
							\"".$arr_ev."\",
							\"".$total['arr_prom_m2']."\",
							\"".$total['arr_tot_mes']."\",
							\"".$vc_m2."\"
							],";
			



			$json_string=rtrim($json_string, ",");
			$json_string.="]";
		}elseif($vista=="detalle"){
			$json_string=$this->procesarVistaDetalle($query,$request);
		}else{
			$json_string=$this->procesarVistaVentas($query,$request);
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

		$fechaCopia=$fecha;
		$fecha2Copia=$fecha2;
		
		$mes_actual=$this->convertirmes(date('m-Y'),"-");

		$fechaAux = date ("m-Y", strtotime("- 12 MONTH", strtotime(date('Y-m-d'))));
		$mes_actual_ant=$this->convertirmes($fechaAux,"-");

		while (strtotime($fecha) <= strtotime($fecha2)) {
			$mes = $this->convertirmes(date('m-Y',strtotime($fecha)),"-");
			$mes2 = $this->convertirmes(date('m-Y',strtotime($fecha)),"");
			
			$json_string.=",\"".$mes."\"";
			$total[$mes2]=0;
			$meses[]=$mes2;
			$fecha = date ("Y-m-d", strtotime("+1 MONTH", strtotime($fecha)));
		}
		$fecha=$fechaCopia;
		$fecha2=$fecha2Copia;
		while (strtotime($fecha) <= strtotime($fecha2)) {
			$mes = $this->convertirmes(date('m-Y',strtotime($fecha)),"-");
			$mes2 = $this->convertirmes(date('m-Y',strtotime($fecha)),"");
			//Agrego un espacio al final para diferenciarlo en el js
			$mes.=" ";
			$mes2.=" ";
			$json_string.=",\"".$mes."\"";
			$total[$mes2]=0;
			$meses[]=$mes2;
			$fecha = date ("Y-m-d", strtotime("+1 MONTH", strtotime($fecha)));
		}
		$json_string.="]";

		
		$datos=array();	
		$locales=array();	
		$localesnombre=array();	
		while ($row = $adb->fetch_array($result)){
			$cuenta=$row["accountname"].$row["superficieid"];
			$tipo=$row["cod_grupocon"];
			
			$aniomes=$this->convertirmesInvertido($row["aniomes"],'');

			if($tipo=="ARRPORCENTUAL")
				$aniomes.=" ";	

			$arr_porcent=round($row["arr_porc"],2);

			$datos[$cuenta][$aniomes]=$arr_porcent;
			$locales[$cuenta]=$row["accountid"];
			$localesnombre[$cuenta]=$row["accountname"];

			$total[$aniomes]+=$arr_porcent;
		}
		
		foreach ($datos as $local=>$mes) {
			
			$local_url="<a href='index.php?module=Accounts&view=Detail&record=".$locales[$local]."'>".$localesnombre[$local]."</a>";

			$json_string.=",[\"".$local_url."\"";
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
	public function procesarVistaVentas($query,$request){
		$adb = PearDatabase::getInstance();

		$result=$adb->query($query);

		$vista = $request->get('vista');

		$ipc=false;
		if($vista=="ventas_ipc")
			$ipc=true;

		$fecha2=date('Y-m-01');
		$date = strtotime( date('Y-m-01')." -11 months");
		$fecha=date("Y-m-d", $date);
		
		$createdTime = $request->get('createdtime');
		if(!empty($createdTime)) {
			$fecha_desde = DateTimeField::__convertToDBFormat($createdTime['start'],'dd-mm-yyyy');
			$fecha_hasta = DateTimeField::__convertToDBFormat($createdTime['end'],'dd-mm-yyyy');
			list($d, $m, $y) = explode('-', $createdTime['start']);
			$fecha= $y."-".$m."-".$d;

			$fecha1= strtotime($y."/".$m."/".$d);
			$date = strtotime( date('Y-m-d',$fecha1)." -12 months");
			$fecha_desde_anterior=date('Y-m-d', $date);

			list($d, $m, $y) = explode('-', $createdTime['end']);
			$fecha2=$y."-".$m."-".$d;
		}

		$total = array();
		$meses = array();
		$json_string="[[\"Local\"";

		$fechaCopia=$fecha;
		$fecha2Copia=$fecha2;

		$fecha=$fecha_desde_anterior;

		$mes_actual=$this->convertirmes(date('m-Y'),"-");

		$fechaAux = date ("m-Y", strtotime("- 12 MONTH", strtotime(date('Y-m-d'))));
		$mes_actual_ant=$this->convertirmes($fechaAux,"-");


		while (strtotime($fecha) <= strtotime($fecha2)) {
			$mes = $this->convertirmes(date('m-Y',strtotime($fecha)),"-");
			$mes2 = $this->convertirmes(date('m-Y',strtotime($fecha)),"");
			if($mes!=$mes_actual && $mes!=$mes_actual_ant ){
				$json_string.=",\"".$mes."\"";	
				$total[$mes2]=0;
				$meses[]=$mes2;
			}
			$fecha = date ("Y-m-d", strtotime("+1 MONTH", strtotime($fecha)));
		}
		/*$fecha=$fechaCopia;
		$fecha2=$fecha2Copia;
		while (strtotime($fecha) <= strtotime($fecha2)) {
			$mes = $this->convertirmes(date('m-Y',strtotime($fecha)),"-");
			$mes2 = $this->convertirmes(date('m-Y',strtotime($fecha)),"");
			//Agrego un espacio al final para diferenciarlo en el js
			$mes.=" ";
			$mes2.=" ";
			$json_string.=",\"".$mes."\"";
			$total[$mes2]=0;
			$meses[]=$mes2;
			$fecha = date ("Y-m-d", strtotime("+1 MONTH", strtotime($fecha)));
		}*/
		$json_string.="]";

		
		$datos=array();	
		$ipc=array();	
		$locales=array();	
		$localesnombre=array();

		$ipc_actual=0;

		while ($row = $adb->fetch_array($result)){
			$cuenta=$row["accountname"].$row["superficieid"];
			
			
			$aniomes=$this->convertirmesInvertido($row["aniomes"],'');
			
			
			if(isset($row["ipcmensual"]) && $row["ipcmensual"]!=0){
				$ipc_actual=$row["ipcmensual"];
			}	

			$ventas=round($row["ventas"],2);

			$datos[$cuenta][$aniomes]=$ventas;
			$ipc[$cuenta][$aniomes]=$row["ipcmensual"];
			$locales[$cuenta]=$row["accountid"];
			$localesnombre[$cuenta]=$row["accountname"];
			$total[$aniomes]+=$ventas;
		}
		
		$total_ipc = array();
		
		foreach ($datos as $local=>$mes) {
			
			$local_url="<a href='index.php?module=Accounts&view=Detail&record=".$locales[$local]."'>".$localesnombre[$local]."</a>";

			$json_string.=",[\"".$local_url."\"";
			foreach ($meses as $mes_a_comparar) {
				//echo $mes_a_comparar."<br>";
				
				$valor=$datos[$local][$mes_a_comparar];
				if(!isset($valor))
					$valor=0;
				
				if($ipc){
					$ipcmes=$ipc[$local][$mes_a_comparar];
					if(!isset($ipcmes))
						$ipcmes=$ipc_actual;

					$dif1=$ipc_actual-$ipcmes;
					$valor=round($valor*(1+$dif1/100),2);
					$total_ipc[$mes_a_comparar]+=$valor;			
				}

				$json_string.=",\"".$valor."\"";

				/*if(isset($datos[$local][$mes_a_comparar])){
					//Si existe valor para el mes lo agrego
					$json_string.=",\"".$datos[$local][$mes_a_comparar]."\"";
				}else{
					//Sino Pongo 0
					$json_string.=",\"0\"";
				}*/
			}
			$json_string.="]";
		    /*foreach ($mes as $mes=>$arr_porc) {
		        
		    }*/
		}

		if($ipc){
			$total=$total_ipc;
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