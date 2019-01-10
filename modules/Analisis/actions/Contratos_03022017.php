<?php 
/*ini_set("display_errors", 1);
error_reporting(E_ALL & ~E_NOTICE);
*/
class Analisis_Contratos_Action extends Vtiger_BasicAjax_Action{

	public function process(Vtiger_Request $request) {

		
		$vista = $request->get('vista');

		$query=$this->getQuery($request);
		$fp = fopen("consultas.txt","a");
		fwrite($fp,$query.PHP_EOL);
		fclose($fp);
		
		$query_aux="";

		$agrupar = $request->get('agrupar');
		if($agrupar=='local'){
			$json_string=$this->procesarRespuesta($query,$vista,$request,$query_aux);
		}else{
			// Si es familia o rubro, tengo que intercalar por cada familia, los locales que tiene
			$request2=$request;
			//Le cambio el query para que me devuelva los locales
			$request2->set('agrupar','local');
			$query_aux=$this->getQuery($request2);
			$request->set('agrupar',$agrupar);
			$json_string=$this->procesarRespuesta($query,$vista,$request,$query_aux);
		}
		
		;
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
		$agrupar = htmlspecialchars_decode($request->get('agrupar'));
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
		$agrupar = $request->get('agrupar');
		$conIpc = $request->get('ipc');
		
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
				$filtro_fechas.=" AND Aniomes >= '".$fecha_desde."'";
				}
						
			}else{
				if ($fecha_desde!=""){
					$filtro_fechas.=" AND Aniomes >= '".$fecha_desde_anterior."'";
				}
			}
			if ($fecha_hasta!=""){
				$filtro_fechas.=" AND Aniomes <= '".$fecha_hasta."' ";
			}
			$query="";
			switch ($agrupar) {
				case 'familia':
					$query.="SELECT f.lpfamilia,sum(acmetros) as acmetros,sum(arr_min) as arr_min, sum(arr) as arr,
						sum(arr_porc) as arr_porc,avg(arr_porc_cant) as arr_porc_cant,sum(arr_min_cant) as arr_min_cant, sum(ventas) as ventas,
						sum(arr_ant) as arr_ant,sum(cantidad_ar_anterior) as cantidad_ar_anterior,sum(cantidad_ar_actual) as cantidad_ar_actual,sum(cantidad_ventas_actual) as cantidad_ventas_actual,sum(arr_porc_ant) as arr_porc_ant,sum(ventas_ant) as ventas_ant,sum(cantidad_ventas_anterior) as cantidad_ventas_anterior,numberOfMonths,
						sum(arr_min_ant_cant) as arr_min_ant_cant,
						sum(arr_ant/arr_min_ant_cant) as ar_prom_ant,
						sum(arr/arr_min_cant) as ar_prom
						from vtiger_lpfamilia f
						inner join ( ";
					break;
				case 'rubro':
					
					$query.="SELECT r.lprubro,sum(acmetros) as acmetros,sum(arr_min) as arr_min, sum(arr) as arr,sum(cantidad_ar_actual) as cantidad_ar_actual,
						sum(arr_porc) as arr_porc,avg(arr_porc_cant) as arr_porc_cant,sum(arr_min_cant) as arr_min_cant, sum(ventas) as ventas,
						sum(cantidad_ventas_actual) as cantidad_ventas_actual,
						sum(arr_ant) as arr_ant,sum(cantidad_ar_anterior) as cantidad_ar_anterior,sum(arr_porc_ant) as arr_porc_ant,sum(ventas_ant) as ventas_ant,sum(cantidad_ventas_anterior) as cantidad_ventas_anterior,numberOfMonths,
						sum(arr_min_ant_cant) as arr_min_ant_cant,
						sum(arr_ant/arr_min_ant_cant) as ar_prom_ant,
						sum(arr/arr_min_cant) as ar_prom
						from vtiger_lprubro r
						inner join ( ";
					break;
				
				default:
					# code...
					break;
			}

			//query común a actual y resumen
			

			$query.="SELECT lpfamilia,lprubro,accountid, accountname ,fecha,fecha_prox,metros as acmetros,arr_min,";
			$query.=" IF(CONCAT(YEAR(fecha),LPAD(MONTH(fecha),2,'0'))< '".$fecha_desde_anterior."' AND CONCAT(YEAR(fecha_prox),LPAD(MONTH(fecha_prox),2,'0'))>='".$fecha_hasta."' ,1,0 ) AS mismo_periodo,";
			$query.=" SUM(IF(ar.Aniomes>='".$fecha_desde."' AND ar.Aniomes <= '".$fecha_hasta."' AND 
				(cod_grupocon='ARRPORCENTUAL' OR cod_grupocon='ARRMINIMO' ) AND ar.AnioMes >=CONCAT(YEAR(fecha),LPAD(MONTH(fecha),2,'0')) 
				AND ar.AnioMes < CONCAT(YEAR(fecha_prox),LPAD(MONTH(fecha_prox),2,'0')),ar.ImpSIva,0 )) AS arr,
				";
			$query.=" COUNT(DISTINCT CASE WHEN ar.Aniomes>='".$fecha_desde."' AND ar.Aniomes <= '".$fecha_hasta."' AND ar.AnioMes >=CONCAT(YEAR(fecha),LPAD(MONTH(fecha),2,'0')) 
				AND ar.AnioMes < CONCAT(YEAR(fecha_prox),LPAD(MONTH(fecha_prox),2,'0')) THEN ar.AnioMes END) AS cantidad_ar_actual,
				";
			$query.="SUM(IF(ar.Aniomes>='".$fecha_desde."' AND ar.Aniomes <= '".$fecha_hasta."' AND cod_grupocon='ARRPORCENTUAL' AND ar.AnioMes >=CONCAT(YEAR(fecha),LPAD(MONTH(fecha),2,'0')) 
				AND ar.AnioMes < CONCAT(YEAR(fecha_prox),LPAD(MONTH(fecha_prox),2,'0')),ar.ImpSIva,0 )) AS arr_porc,
				";			
			//$query.="(SELECT ImpSIva FROM lp_arrendamientos ar WHERE ar.numcont_lin = cuentas.acnumerocontrato AND ar.cod_grupocon='ARRMINIMO' AND ar.Aniomes>='".$fecha_desde."' AND ar.Aniomes <= '".$fecha_hasta."' AND ar.AnioMes >=CONCAT(YEAR(fecha),LPAD(MONTH(fecha),2,'0')) AND ar.AnioMes < CONCAT(YEAR(fecha_prox),LPAD(MONTH(fecha_prox),2,'0')) ORDER BY ar.AnioMes DESC LIMIT 1)	AS arr_min ,";

				// editar aca
			$query.="SUM(IF(ar.Aniomes>='".$fecha_desde."' AND ar.Aniomes <= '".$fecha_hasta."' AND cod_grupocon='ARRPORCENTUAL' AND ar.AnioMes >=CONCAT(YEAR(fecha),LPAD(MONTH(fecha),2,'0')) 
				AND ar.AnioMes < CONCAT(YEAR(fecha_prox),LPAD(MONTH(fecha_prox),2,'0')),1,0 )) AS arr_porc_cant,
				SUM(IF(ar.Aniomes>='".$fecha_desde."' AND ar.Aniomes <= '".$fecha_hasta."' AND cod_grupocon='ARRMINIMO' AND ar.AnioMes >=CONCAT(YEAR(fecha),LPAD(MONTH(fecha),2,'0')) 
				AND ar.AnioMes < CONCAT(YEAR(fecha_prox),LPAD(MONTH(fecha_prox),2,'0')),1,0 )) AS arr_min_cant,";
// si se quiere calcular la venta sin ipc
			if ($conIpc == 'ipcNo'){
				$query.="(select SUM(if(vc.Aniomes>='".$fecha_desde."' AND vc.Aniomes <= '".$fecha_hasta."',vc.VentasSIva,0))
					FROM lp_ventas_contratos vc WHERE vc.Contrato = cuentas.acnumerocontrato $filtro_fechas AND vc.AnioMes >=CONCAT(YEAR(fecha),LPAD(MONTH(fecha),2,'0')) AND vc.AnioMes < CONCAT(YEAR(fecha_prox),LPAD(MONTH(fecha_prox),2,'0'))
				)AS ventas,";
			}
			else{
// venta con ipc
				$query.="(SELECT  SUM(venta *(ipcactual/(IFNULL(ipcmes,ipcactual))) ) AS ventas 
				FROM(SELECT Contrato,Aniomes, vc.VentasSIva AS venta, ipc.ipcmensual AS ipcmes,
				(SELECT ipcmensual FROM vtiger_ipc WHERE  CONCAT(ipcanio,LPAD(ipcmes,2,'0')) <= '".$fecha_hasta."' ORDER BY ipcanio DESC, ipcmes DESC LIMIT 1) AS ipcactual
				FROM lp_ventas_contratos vc
				LEFT JOIN vtiger_ipc ipc ON CONCAT(ipcanio,LPAD(ipcmes,2,'0')) = vc.Aniomes 
				WHERE vc.Aniomes >= '".$fecha_desde."' AND vc.Aniomes <= '".$fecha_hasta."'
				GROUP BY vc.Contrato,  Aniomes
				) datos WHERE datos.Contrato = cuentas.acnumerocontrato $filtro_fechas AND datos.AnioMes >=CONCAT(YEAR(fecha),LPAD(MONTH(fecha),2,'0')) AND datos.AnioMes < CONCAT(YEAR(fecha_prox),LPAD(MONTH(fecha_prox),2,'0')) 
				 GROUP BY Contrato) AS ventas,";


			}
			
				$query.="(select COUNT(DISTINCT CASE WHEN vc.Aniomes>='".$fecha_desde."' AND vc.Aniomes <= '".$fecha_hasta."' THEN vc.AnioMes END)
					FROM lp_ventas_contratos vc WHERE vc.Contrato = cuentas.acnumerocontrato $filtro_fechas AND vc.AnioMes >=CONCAT(YEAR(fecha),LPAD(MONTH(fecha),2,'0')) AND vc.AnioMes < CONCAT(YEAR(fecha_prox),LPAD(MONTH(fecha_prox),2,'0'))
				)AS cantidad_ventas_actual
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



// agregar ipc a venta contable en vista actual
			if($vista=="actual"){
				//Agrego los años anteriores
				$query.=", SUM(IF(ar.Aniomes>='".$fecha_desde_anterior."' AND ar.Aniomes <= '".$fecha_hasta_anterior."' AND 
				(cod_grupocon='ARRPORCENTUAL' OR cod_grupocon='ARRMINIMO' ) AND ar.AnioMes >=CONCAT(YEAR(fecha),LPAD(MONTH(fecha),2,'0')) 
				AND ar.AnioMes < CONCAT(YEAR(fecha_prox),LPAD(MONTH(fecha_prox),2,'0')),ar.ImpSIva,0 )) AS arr_ant,
				";
				$query.=" COUNT(DISTINCT CASE WHEN ar.Aniomes>='".$fecha_desde_anterior."' AND ar.Aniomes <= '".$fecha_hasta_anterior."' AND ar.AnioMes >=CONCAT(YEAR(fecha),LPAD(MONTH(fecha),2,'0')) 
				AND ar.AnioMes < CONCAT(YEAR(fecha_prox),LPAD(MONTH(fecha_prox),2,'0'))THEN ar.AnioMes END) AS cantidad_ar_anterior,
				";
				$query.="SUM(IF(ar.Aniomes>='".$fecha_desde_anterior."' AND ar.Aniomes <= '".$fecha_hasta_anterior."' AND cod_grupocon='ARRPORCENTUAL' AND ar.AnioMes >=CONCAT(YEAR(fecha),LPAD(MONTH(fecha),2,'0')) 
					AND ar.AnioMes < CONCAT(YEAR(fecha_prox),LPAD(MONTH(fecha_prox),2,'0')),ar.ImpSIva,0 )) AS arr_porc_ant,
					";			
				$query.="SUM(IF(ar.Aniomes>='".$fecha_desde_anterior."' AND ar.Aniomes <= '".$fecha_hasta_anterior."' AND cod_grupocon='ARRPORCENTUAL' AND ar.AnioMes >=CONCAT(YEAR(fecha),LPAD(MONTH(fecha),2,'0')) 
					AND ar.AnioMes < CONCAT(YEAR(fecha_prox),LPAD(MONTH(fecha_prox),2,'0')),1,0 )) AS arr_porc_ant_cant,
					SUM(IF(ar.Aniomes>='".$fecha_desde_anterior."' AND ar.Aniomes <= '".$fecha_hasta_anterior."' AND cod_grupocon='ARRMINIMO' AND ar.AnioMes >=CONCAT(YEAR(fecha),LPAD(MONTH(fecha),2,'0')) 
					AND ar.AnioMes < CONCAT(YEAR(fecha_prox),LPAD(MONTH(fecha_prox),2,'0')),1,0 )) AS arr_min_ant_cant,"; 
			// si se quiere calcular la venta sin ipc
			if ($conIpc == 'ipcNo'){
				$query.="(select SUM(if(vc.Aniomes>='".$fecha_desde_anterior."' AND vc.Aniomes <= '".$fecha_hasta_anterior."',vc.VentasSIva,0)) 
						FROM lp_ventas_contratos vc WHERE vc.Contrato = cuentas.acnumerocontrato $filtro_fechas AND vc.AnioMes >=CONCAT(YEAR(fecha),LPAD(MONTH(fecha),2,'0')) AND vc.AnioMes < CONCAT(YEAR(fecha_prox),LPAD(MONTH(fecha_prox),2,'0'))
					)AS ventas_ant,";
			}else{
				// venta con ipc
				$query.="(SELECT  SUM(venta *(ipcactual/(IFNULL(ipcmes,ipcactual))) ) AS ventas 
				FROM(SELECT Contrato,Aniomes, vc.VentasSIva AS venta, ipc.ipcmensual AS ipcmes,
				(SELECT ipcmensual FROM vtiger_ipc WHERE  CONCAT(ipcanio,LPAD(ipcmes,2,'0')) <= '".$fecha_hasta."' ORDER BY ipcanio DESC, ipcmes DESC LIMIT 1) AS ipcactual
				FROM lp_ventas_contratos vc
				LEFT JOIN vtiger_ipc ipc ON CONCAT(ipcanio,LPAD(ipcmes,2,'0')) = vc.Aniomes 
				WHERE vc.Aniomes >= '".$fecha_desde_anterior."' AND vc.Aniomes <= '".$fecha_hasta_anterior."'
				GROUP BY vc.Contrato,  Aniomes
				) datos WHERE datos.Contrato = cuentas.acnumerocontrato $filtro_fechas AND datos.AnioMes >=CONCAT(YEAR(fecha),LPAD(MONTH(fecha),2,'0')) AND datos.AnioMes < CONCAT(YEAR(fecha_prox),LPAD(MONTH(fecha_prox),2,'0')) 
				 GROUP BY Contrato) AS ventas_ant,";
			}
					$query.="(select COUNT(DISTINCT CASE WHEN vc.Aniomes>='".$fecha_desde_anterior."' AND vc.Aniomes <= '".$fecha_hasta_anterior."'THEN vc.AnioMes END) 
						FROM lp_ventas_contratos vc WHERE vc.Contrato = cuentas.acnumerocontrato $filtro_fechas AND vc.AnioMes >=CONCAT(YEAR(fecha),LPAD(MONTH(fecha),2,'0')) AND vc.AnioMes < CONCAT(YEAR(fecha_prox),LPAD(MONTH(fecha_prox),2,'0'))
					)AS cantidad_ventas_anterior
					";	

					
					
			}else{
				$query.=",0 as arr_ant,0 as cantidad_ventas_anterior,0 as cantidad_ar_anterior,0 as arr_porc_ant,0 as ventas_ant, 0 as arr_min_ant_cant";
			}	

			$query.=", ".$numberOfMonths." as numberOfMonths";
			/*$query.=" FROM vtiger_account ac
			
			inner join vtiger_crmentity e on e.crmid=ac.accountid 

			inner join vtiger_superficie s on s.cuenta=ac.accountid
			
			WHERE 1=1 AND deleted=0  ".$filtros_locales;*/

			$query.=" FROM(
			SELECT lpfamilia,lprubro,s.metros,ac.accountid,superficieid,ac.acnumerocontrato,ac.accountname,s.fecha, IFNULL((SELECT s1.fecha FROM vtiger_superficie s1 WHERE s.cuenta=s1.cuenta
			AND s.fecha < s1.fecha LIMIT 1),NOW()) AS fecha_prox, ac.acarminimo as arr_min,createdtime
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

			switch ($agrupar) {
				case 'familia':
					$query.=") datos on INSTR(datos.lpfamilia ,f.lpfamilia)>0
						group by f.lpfamilia order by f.lpfamilia";
					break;
				case 'rubro':
					$query.=") datos on INSTR(datos.lprubro ,r.lprubro)>0
						group by r.lprubro order by r.lprubro";
					break;
				
				default:
					# code...
					break;
			}


		}else{

			if($vista=="detalle"){
				/*INNER JOIN vtiger_account*/
				$query="";
				switch ($agrupar) {
					case 'familia':
						$query.="SELECT f.lpfamilia ,SUM(arr_porc) AS arr_porc,AnioMes,cod_grupocon
							FROM vtiger_lpfamilia f
							INNER JOIN ( ";
						break;
					case 'rubro':
						
						$query.="SELECT r.lprubro ,SUM(arr_porc) AS arr_porc,AnioMes,cod_grupocon
							FROM vtiger_lprubro r
							INNER JOIN (";
						break;
					
					default:
						# code...
						break;
				}

					$ipc=false;
				if($vista=="ventas_ipc")
					$ipc=true;

				$valor_mensual=' ,0 as ipcmensual';
				$join_ipc='';
				if($ipc){
					$valor_mensual=' ,ipcmensual';
					$join_ipc=" LEFT JOIN vtiger_ipc ipc ON AnioMes=CONCAT(ipc.ipcanio,LPAD(ipc.ipcmes,2,'0')) ";
				}
				$query.="SELECT lpfamilia,lprubro,ac.accountid ,ac.accountname, ar.ImpSIva AS arr_porc ,
						AnioMes ,acnumerocontrato,cod_grupocon, ac.superficieid $valor_mensual
						FROM lp_arrendamientos ar 
						INNER JOIN (SELECT lpfamilia,lprubro,s.metros,ac.accountid,superficieid,ac.acnumerocontrato,ac.accountname,s.fecha, 
							IFNULL((SELECT s1.fecha FROM vtiger_superficie s1 WHERE s.cuenta=s1.cuenta
										AND s.fecha < s1.fecha LIMIT 1),NOW()) AS fecha_prox, ac.acarminimo as arr_min
										FROM vtiger_account ac 
										INNER JOIN vtiger_crmentity e ON e.crmid=ac.accountid 
										INNER JOIN vtiger_superficie s ON s.cuenta=ac.accountid 
										$filtros_locales
										WHERE 1=1 AND deleted=0 
										ORDER BY accountname) ac ON ar.numcont_lin = ac.acnumerocontrato
						inner join vtiger_crmentity e on e.crmid=ac.accountid
						$join_ipc
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
				switch ($agrupar) {
					case 'familia':
						$query.=") datos ON INSTR(datos.lpfamilia ,f.lpfamilia)>0
						GROUP BY f.lpfamilia,AnioMes, cod_grupocon ORDER BY f.lpfamilia,AnioMes, cod_grupocon";
						break;
					case 'rubro':
						
						$query.=") datos ON INSTR(datos.lprubro ,r.lprubro)>0
						GROUP BY r.lprubro,AnioMes, cod_grupocon ORDER BY r.lprubro,AnioMes, cod_grupocon";
						break;
					
					default:
						# code...
						break;
				}
		
						
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
				$query="";
				switch ($agrupar) {
					case 'familia':
						$query.="SELECT f.lpfamilia ,SUM(ventas) AS ventas,AnioMes,ipcmensual
							FROM vtiger_lpfamilia f
							INNER JOIN (";
						break;
					case 'rubro':
						$query.="SELECT r.lprubro ,SUM(ventas) AS ventas,AnioMes,ipcmensual
							FROM vtiger_lprubro r
							INNER JOIN (";
						break;
					
					default:
						# code...
						break;
				}

				$query.="SELECT lpfamilia,lprubro,ac.accountid ,ac.accountname, vc.VentasSIva AS ventas ,
						AnioMes ,acnumerocontrato,ac.superficieid $valor_mensual
						FROM lp_ventas_contratos vc  
						INNER JOIN (SELECT lpfamilia,lprubro,s.metros,ac.accountid,superficieid,ac.acnumerocontrato,ac.accountname,s.fecha, 
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

				switch ($agrupar) {
					case 'familia':
						$query.=") datos ON INSTR(datos.lpfamilia ,f.lpfamilia)>0
							GROUP BY f.lpfamilia,AnioMes ORDER BY f.lpfamilia,AnioMes";
						break;
					case 'rubro':
						$query.=") datos ON INSTR(datos.lprubro ,r.lprubro)>0
							GROUP BY r.lprubro,AnioMes ORDER BY r.lprubro,AnioMes";
						break;
					
					default:
						# code...
						break;
				}		

			}			
		}

		// echo $query. " hsta aca";
		return $query;		

	}
	public function procesarRespuesta($query,$vista,$request,$query_aux){
		$adb = PearDatabase::getInstance();

		$result=$adb->query($query);
		$agrupar = htmlspecialchars_decode($request->get('agrupar'));
		$result2=NULL;
		$localesvalores=array();
		$indice="";
		if($agrupar!='local'){
			$indice='lprubro';
			if($agrupar=='familia'){
				$indice='lpfamilia';
			}
			$result2=$adb->query($query_aux);
			while ($row2 = $adb->fetch_array($result2)){
				//echo $row2[$indice];
				$sep=' |##| ';
				$indices=explode($sep, $row2[$indice]);
				foreach ($indices as  $value) {
					$localesvalores[$value][]=$row2;	
				}
				
			}
		}

		if($vista=="actual" || $vista=="resumen"){

			$json_string="[[";

			if($agrupar!='local')
				$json_string.="\"Agrupar\",";				

			$json_string.="\"Local\",\"Min $\",\"m2\",\"Min $ / m2\",\"Venta Contable\",
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
			$agrupar2=$agrupar;
			while ($row1 = $adb->fetch_array($result)){
				$valores=array();
				$valores[]=$row1;
				$index=$row1[$indice];
				//echo $row1[$indice].count($localesvalores[$row1[$indice]])."<br>";
				
				for ($e=0; $e < count($localesvalores[$row1[$indice]]) ; $e++) { 
					$valores[]=$localesvalores[$row1[$indice]][$e];
				}	
				for ($i=0; $i <=count($valores) ; $i++) { 
					
					$row=$valores[$i];	
					$agrupar2=$agrupar;
					if($i!=0){
						$agrupar2=$agrupar.'local';
					}	
					//echo $agrupar2."<br>";
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
					$cantidad_ventas_anterior=$row["cantidad_ventas_anterior"];	
					$cantidad_ventas_actual=$row["cantidad_ventas_actual"];	
					$cantidad_ar_anterior=$row["cantidad_ar_anterior"];	
					$cantidad_ar_actual=$row["cantidad_ar_actual"];	
					//Filtro los que no tengan datos en Ventas y Arr
					if(($arr>0 || $arr_ant>0) || ($ventas > 0 || $ventas_ant > 0)){

						if($i==0){
							$total['arr']+=$arr;
							$total['arr_porcent']+=$arr_porcent;
							$total['ventas']+=$ventas;
						}
						
						$arr_porc_ant=0;
						$division_ant=0;
						if(isset($ventas_ant) && $ventas_ant!=0){
							$arr_porc_ant=(double)round((($arr_ant*100)/$ventas_ant),2);
							$division_ant=(double)round(($arr_ant/$ventas_ant)*100,1);
							$division_ant=number_format($division_ant, 1, ',', '');
						}
						if($i==0){
							$total['arr_ant']+=$arr_ant;$total['arr_porcent_ant']+=$arr_porcent_ant;
							$total['ventas_ant']+=$ventas_ant;	
						}
						$dif=$ventas-$ventas_ant;
						$ventas_ev=round(($dif/$ventas_ant)*100,1)."%";

						$dif=$arr-$arr_ant;
						$arr_ev=round(($dif/$arr_ant)*100,1)."%";
						
						//echo $cantidad_ar_actual."-".$cantidad_ar_anterior."<br>"; 
						//if($mismo_periodo==0){
						if($cantidad_ar_actual!=$cantidad_ar_anterior){
							$arr_ev="<font color='red'>".$arr_ev."</font>";						
							//$arr_ev.=" *";						
						}
						//if($mismo_periodo==0){
						if($cantidad_ventas_actual!=$cantidad_ventas_anterior){
							$ventas_ev="<font color='red'>".$ventas_ev."</font>";						
						}
					
						$arr_min=round($row["arr_min"],1);

						$vc_m2=0;
						if(isset($row['acmetros']) && $row['acmetros']!="")	
							$vc_m2=round($ventas/$row['acmetros'],2);
						
						$arr_min_m2=0;
						if(isset($row['acmetros']) && $row['acmetros']!="")	
							$arr_min_m2=round(($row["arr_min"]/$row['acmetros']),1);
						if($i==0){
							$total['arr_min']+=$arr_min;$total['arr_min_m2']+=$arr_min_m2;
							$total['acmetros']+=$row['acmetros'];	
							$total['vc_m2']+=$row['vc_m2'];	
						}

						$arr_tot_mes_ant=0;
						if(isset($row['arr_min_ant_cant']) 
							&& $row['arr_min_ant_cant']!="")	
							$arr_tot_mes_ant=round($arr_ant/$row['arr_min_ant_cant'],2);

						
						$arr_tot_mes=0;
						if(isset($row['arr_min_cant']) 
							&& $row['arr_min_cant']!="")	
							$arr_tot_mes=round($arr/$row['arr_min_cant'],2);
						
						if($agrupar2=='familia' || $agrupar2=='rubro'){
							$arr_tot_mes_ant=round($row['ar_prom_ant'],2);
							$arr_tot_mes=round($row['ar_prom'],2);
						}

						if($i==0){
							$total['arr_tot_mes_ant']+=$arr_tot_mes_ant;	
							$total['arr_tot_mes']+=$arr_tot_mes;	
						}	
						$arr_prom_m2=0;
						if(isset($row['acmetros']) && $row['acmetros']!=""){	
							$arr_prom_m2=round($arr_tot_mes/$row['acmetros'],2);
							//echo $arr_porcent;
						}

						if($i==0){
							$total['arr_prom_m2']+=$arr_prom_m2;	
						}	
						$url="";					
						switch ($agrupar2) {
							case 'familia':
								$url="*";//.$row['lpfamilia'];
								break;
							case 'familialocal':
								//$url=$index;
								$url="<a href='index.php?module=Accounts&view=Detail&record=".$row['accountid']."'>".$row['accountname']."</a>";
								break;
							case 'rubro':
								$url="*";//.$row['lprubro'];
								break;
							case 'rubrolocal':
								//$url=$index;
								$url="<a href='index.php?module=Accounts&view=Detail&record=".$row['accountid']."'>".$row['accountname']."</a>";
								break;
							
							default:
								$url="<a href='index.php?module=Accounts&view=Detail&record=".$row['accountid']."'>".$row['accountname']."</a>";
								break;
						}

						$json_string.="[";
						if($agrupar!='local')
							$json_string.="\"".$index."\",";
				
						$json_string.="\"".$url."\",
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
			
			$arr_prom_m2=round(($total["arr_tot_mes"]/$total['acmetros']),1);
			

			$vc_m2=round($ventas/$total['acmetros'],2);
			
			$json_string.="[";
			if($agrupar!='local')
				$json_string.="\"Total\",";

			$json_string.="\"Total\",
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
							\"".$arr_prom_m2."\",
							\"".$total['arr_tot_mes']."\",
							\"".$vc_m2."\"
							],";
			



			$json_string=rtrim($json_string, ",");
			$json_string.="]";
		}elseif($vista=="detalle"){
			$json_string=$this->procesarVistaDetalle($query,$request,$query_aux);
		}else{
			$json_string=$this->procesarVistaVentas($query,$request,$query_aux);
		}	
		return $json_string;
	}
	public function procesarVistaDetalle($query,$request,$query_aux){
		$adb = PearDatabase::getInstance();

		$result=$adb->query($query);

		$agrupar = htmlspecialchars_decode($request->get('agrupar'));
		$result2=NULL;
		$localesvalores=array();
		$indice="";
		if($agrupar!='local'){
			$indice='lprubro';
			if($agrupar=='familia'){
				$indice='lpfamilia';
			}
			$result2=$adb->query($query_aux);
			while ($row2 = $adb->fetch_array($result2)){
				//echo $row2[$indice];
				$sep=' |##| ';
				$indices=explode($sep, $row2[$indice]);
				foreach ($indices as  $value) {
					$localesvalores[$value][]=$row2;	
				}
				
			}
		}



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

		$json_string="[[";
		if($agrupar!='local')
				$json_string.="\"Agrupar\",";				

		$json_string.="\"Local\"";

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
		//echo $fecha."-".$fecha2;exit;
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
		$agrupar = $request->get('agrupar');



		while ($row1 = $adb->fetch_array($result)){
			$valores=array();
			$valores[]=$row1;
			$index=$row1[$indice];
			for ($e=0; $e < count($localesvalores[$row1[$indice]]) ; $e++) { 
				$valores[]=$localesvalores[$row1[$indice]][$e];
			}	
			for ($i=0; $i <=count($valores) ; $i++) { 
				
				$row=$valores[$i];	
				$agrupar2=$agrupar;
				if($i!=0){
					$agrupar2=$agrupar.'local';
				}	
				
				switch ($agrupar2) {
					case 'familia':
						$cuenta=$row["lpfamilia"];
						$locales[$cuenta]=$row["lpfamilia"];
						$localesnombre[$cuenta]=array($row["lpfamilia"],"");
						break;
					case 'rubro':
						$cuenta=$row["lprubro"];
						$locales[$cuenta]=$row["lprubro"];
						$localesnombre[$cuenta]=array($row["lprubro"],"");
						break;
					case 'rubrolocal':
						$cuenta=$index.$row["accountname"].$row["superficieid"];
						$locales[$cuenta]=$row["accountid"];
						$localesnombre[$cuenta]=array($index,$row["accountname"]);
						break;
					case 'familialocal':
						$cuenta=$index.$row["accountname"].$row["superficieid"];
						$locales[$cuenta]=$row["accountid"];
						$localesnombre[$cuenta]=array($index,$row["accountname"]);
						break;
						
					default:
						$cuenta=$row["accountname"].$row["superficieid"];
						$locales[$cuenta]=$row["accountid"];
						$localesnombre[$cuenta]=array($row["accountname"],$row["accountname"]);
						break;
				}
				
				$tipo=$row["cod_grupocon"];
				
				$aniomes=$this->convertirmesInvertido($row["aniomes"],'');

				if($tipo=="ARRPORCENTUAL")
					$aniomes.=" ";	

				$arr_porcent=round($row["arr_porc"],2);

				$datos[$cuenta][$aniomes]=$arr_porcent;
				

				$total[$aniomes]+=$arr_porcent;
			}	
		}
		//var_dump($datos);
		foreach ($datos as $local=>$mes) {
			switch ($agrupar) {
				case 'familia':
					$local_url=$localesnombre[$local][1];
					$agrupar_url=$localesnombre[$local][0];
					if($local_url!=''){
						$local_url="<a href='index.php?module=Accounts&view=Detail&record=".$locales[$local]."'>".$localesnombre[$local][1]."</a>";	
					}else{
						$local_url="*";
					}
					break;
				case 'rubro':
					$local_url=$localesnombre[$local][1];
					$agrupar_url=$localesnombre[$local][0];
					if($local_url!=''){
						$local_url="<a href='index.php?module=Accounts&view=Detail&record=".$locales[$local]."'>".$localesnombre[$local][1]."</a>";	
					}else{
						$local_url="*";
					}
					break;
				
				default:
					$agrupar_url=$localesnombre[$local][0];
					$local_url="<a href='index.php?module=Accounts&view=Detail&record=".$locales[$local]."'>".$localesnombre[$local][1]."</a>";

					break;
			}
			$json_string.=",[";
			if($agrupar!='local')
				$json_string.="\"".$agrupar_url."\",";
				
			$json_string.="\"".$local_url."\"";
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

		if($agrupar!='local')
			$json_string.=",\"Total\"";
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
	public function procesarVistaVentas($query,$request,$query_aux){
		$adb = PearDatabase::getInstance();

		$result=$adb->query($query);

		$agrupar = htmlspecialchars_decode($request->get('agrupar'));
		$result2=NULL;
		$localesvalores=array();
		$indice="";
		if($agrupar!='local'){
			$indice='lprubro';
			if($agrupar=='familia'){
				$indice='lpfamilia';
			}
			$result2=$adb->query($query_aux);
			while ($row2 = $adb->fetch_array($result2)){
				//echo $row2[$indice];
				$sep=' |##| ';
				$indices=explode($sep, $row2[$indice]);
				foreach ($indices as  $value) {
					$localesvalores[$value][]=$row2;	
				}
				
			}
		}

		//var_dump($localesvalores);
		$vista = $request->get('vista');

		$ver_ipc=false;
		if($vista=="ventas_ipc")
			$ver_ipc=true;

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
			$fecha22= strtotime($y."/".$m."/".$d);
			$date = strtotime( date('Y-m-d',$fecha22)." -12 months");
			$fecha_hasta_anterior=date('Y-m-d', $date);

		}

		$total = array();
		$meses = array();
		//$json_string="[[\"Local\"";
		$json_string="[[";

		if($agrupar!='local')
			$json_string.="\"Agrupar\",";				
		
		$json_string.="\"Local\"";				



		$fechaCopia=$fecha;
		$fecha2Copia=$fecha2;

		$fecha=$fecha_desde_anterior;

		$mes_actual=$this->convertirmes(date('m-Y'),"-");

		$fechaAux = date ("m-Y", strtotime("- 12 MONTH", strtotime(date('Y-m-d'))));
		$mes_actual_ant=$this->convertirmes($fechaAux,"-");

		//echo $fecha."-".$fecha_hasta_anterior;exit;
		while (strtotime($fecha) <= strtotime($fecha_hasta_anterior)) {
			$mes = $this->convertirmes(date('m-Y',strtotime($fecha)),"-");
			$mes2 = $this->convertirmes(date('m-Y',strtotime($fecha)),"");
			//if($mes!=$mes_actual && $mes!=$mes_actual_ant ){
				$json_string.=",\"".$mes."\"";	
				$total[$mes2]=0;
				$meses[]=$mes2;
			//}
			$fecha = date ("Y-m-d", strtotime("+1 MONTH", strtotime($fecha)));
		}
		$fecha=$fecha_desde;
		//echo $fecha."-".$fecha_hasta;exit;
		while (strtotime($fecha) <= strtotime($fecha_hasta)) {
			$mes = $this->convertirmes(date('m-Y',strtotime($fecha)),"-");
			$mes2 = $this->convertirmes(date('m-Y',strtotime($fecha)),"");
			//if($mes!=$mes_actual && $mes!=$mes_actual_ant ){
				$json_string.=",\"".$mes."\"";	
				$total[$mes2]=0;
				$meses[]=$mes2;
			//}
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

		$agrupar = $request->get('agrupar');
		

		while ($row1 = $adb->fetch_array($result)){
			//$cuenta=$row["accountname"].$row["superficieid"];
			$valores=array();
			$valores[]=$row1;
			$index=$row1[$indice];
			//echo $row1[$indice].count($localesvalores[$row1[$indice]])."<br>";
			for ($e=0; $e < count($localesvalores[$row1[$indice]]) ; $e++) { 
				$valores[]=$localesvalores[$row1[$indice]][$e];
			}	
			for ($i=0; $i <=count($valores) ; $i++) { 
				
				$row=$valores[$i];	
				$agrupar2=$agrupar;
				if($i!=0){
					$agrupar2=$agrupar.'local';
				}	
			
				switch ($agrupar2) {
					case 'familia':
						$cuenta=$row["lpfamilia"];
						$locales[$cuenta]=$row["lpfamilia"];
						$localesnombre[$cuenta]=array($row["lpfamilia"],"");
						break;
					case 'rubro':
						$cuenta=$row["lprubro"];
						$locales[$cuenta]=$row["lprubro"];
						$localesnombre[$cuenta]=array($row["lprubro"],"");
						break;
					case 'rubrolocal':
						$cuenta=$index.$row["accountname"].$row["superficieid"];
						$locales[$cuenta]=$row["accountid"];
						$localesnombre[$cuenta]=array($index,$row["accountname"]);
						break;
					case 'familialocal':
						$cuenta=$index.$row["accountname"].$row["superficieid"];
						$locales[$cuenta]=$row["accountid"];
						$localesnombre[$cuenta]=array($index,$row["accountname"]);
						break;
						
					default:
						$cuenta=$row["accountname"].$row["superficieid"];
						$locales[$cuenta]=$row["accountid"];
						$localesnombre[$cuenta]=array($row["accountname"],$row["accountname"]);
						break;
				}
				
				//echo $row['accountname'].$index;	
				
				$aniomes=$this->convertirmesInvertido($row["aniomes"],'');
				
				
				if(isset($row["ipcmensual"]) && $row["ipcmensual"]!=0){
					$ipc_actual=$row["ipcmensual"];
				}	

				$ventas=round($row["ventas"],2);

				$datos[$cuenta][$aniomes]=$ventas;
				$ipc[$cuenta][$aniomes]=$row["ipcmensual"];
				//$locales[$cuenta]=$row["accountid"];
				//$localesnombre[$cuenta]=$row["accountname"];
				if(strpos($agrupar2, 'local') !== false)
					$total[$aniomes]+=$ventas;
			}	
		}
		
		$total_ipc = array();
		
		foreach ($datos as $local=>$mes) {
			
			switch ($agrupar) {
				case 'familia':
					$local_url=$localesnombre[$local][1];
					$agrupar_url=$localesnombre[$local][0];
					if($local_url!=''){
						$local_url="<a href='index.php?module=Accounts&view=Detail&record=".$locales[$local]."'>".$localesnombre[$local][1]."</a>";	
					}else{
						$local_url="*";
					}
					break;
				case 'rubro':
					$local_url=$localesnombre[$local][1];
					$agrupar_url=$localesnombre[$local][0];
					if($local_url!=''){
						$local_url="<a href='index.php?module=Accounts&view=Detail&record=".$locales[$local]."'>".$localesnombre[$local][1]."</a>";	
					}else{
						$local_url="*";
					}
					break;
				
				default:
					$agrupar_url=$localesnombre[$local][0];
					$local_url="<a href='index.php?module=Accounts&view=Detail&record=".$locales[$local]."'>".$localesnombre[$local][1]."</a>";

					break;
			}
			$json_string.=",[";
			if($agrupar!='local')
				$json_string.="\"".$agrupar_url."\",";
				
			$json_string.="\"".$local_url."\"";
			
			foreach ($meses as $mes_a_comparar) {
				//echo $mes_a_comparar."<br>";
				
				$valor=$datos[$local][$mes_a_comparar];
				
				if(!isset($valor))
					$valor=0;
				
				//echo "valor:".$valor;

				if($ver_ipc){
					$ipcmes=$ipc[$local][$mes_a_comparar];
					if(!isset($ipcmes))
						$ipcmes=$ipc_actual;

					$dif1=$ipc_actual/$ipcmes;
					$valor=round($valor*($dif1),2);
					if($local_url=="*")
						$total_ipc[$mes_a_comparar]+=$valor;			
				}
				//echo "valor:".$valor;
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

		if($ver_ipc){
			$total=$total_ipc;
		}	

		$json_string.=",[\"Total\"";

		if($agrupar!='local')
			$json_string.=",\"Total\"";

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