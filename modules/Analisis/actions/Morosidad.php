<?php 
/*ini_set("display_errors", 1);
error_reporting(E_ALL & ~E_NOTICE);
*/
class Analisis_Morosidad_Action extends Vtiger_BasicAjax_Action{

	public function process(Vtiger_Request $request) {

		
		$vista = $request->get('vista');

		$query=$this->getQuery($request);
		$fp = fopen("consultas.txt","a");
		fwrite($fp,$query.PHP_EOL);
		fclose($fp);
		
		$query_aux="";

			$json_string=$this->procesarRespuesta($query,"",$request,$query_aux);
		;
		echo $json_string;
		return ;



	}
	public function obtenerFiltroCuentas(Vtiger_Request $request){
			$filtro_cuentas="";
			$cuenta = htmlspecialchars_decode($request->get('cuenta'));
			if($cuenta!=""){
				//$filtros_locales .= "AND accountid=$nombreLocal";
				$rangos=explode(",", $cuenta);
				//$filtro_cuentas.=" AND (";
				foreach($rangos as $id){
					$filtro_cuentas.=" cod_cta = ".$id." OR";
				}	
				$filtro_cuentas=rtrim($filtro_cuentas,'OR');
				//$filtro_cuentas.=" )";
			}	
			return $filtro_cuentas;
	}	
	public function obtenerFiltrosLocales(Vtiger_Request $request){
		$filtros_locales="";
		$nombreLocal = htmlspecialchars_decode($request->get('nombreLocal'));
		
		
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
		$filtro_cuentas=$this->obtenerFiltroCuentas($request);
		if($filtro_cuentas!=""){
			$filtros_locales.=" AND (";
			$filtros_locales.=$filtro_cuentas;
			$filtros_locales.=" )";
		}
		

		
		return $filtros_locales;
	}
	public function getQuery(Vtiger_Request $request){
		
		$fecha_desde="";
		$fecha_hasta="";

		$fecha_hasta=date('Y-m-d');
		$fecha2=date('Y-m-01');
		$date = strtotime( date('Y-m-01')." -11 months");
		$date = strtotime( date('Y-m-01')." -11 months");
		$fecha_desde=date("Y-m-d", $date);
		$fecha1=date("Y-m-d", $date);
		
		$date = strtotime( date('Y-m-01')." -6 months");
		$fecha_ventas=date("Ym", $date);
		$date = strtotime( date('Y-m-01')." -1 months");
		$fecha_ventas2=date("Ym", $date);

		$createdTime = $request->get('createdtime');
		if(!empty($createdTime)) {
			$fecha_desde = DateTimeField::__convertToDBFormat($createdTime['start'],'dd-mm-yyyy');
			$fecha_hasta = DateTimeField::__convertToDBFormat($createdTime['end'],'dd-mm-yyyy');
			
			/*list($d, $m, $y) = explode('-', $createdTime['start']);
			$fecha_desde =$y.$m;
			$fecha_desde= strtotime($y."/".$m."/".$d);
			
			list($d, $m, $y) = explode('-', $createdTime['end']);
			$fecha_hasta =$y.$m;
			$fecha_hasta=strtotime($y."/".$m."/".$d);*/
		
		}
		
		
		$filtros_locales=$this->obtenerFiltrosLocales($request);
		$filtros_cuentas=$this->obtenerFiltroCuentas($request);
		
		$query="";

		if ($fecha_desde!=""){
			//$filtro_fechas.=" AND fec_venc >= '".$fecha_desde."'";
		}
		if ($fecha_hasta!=""){
			$filtro_fechas.=" AND fec_venc <= '".$fecha_hasta."' ";
		}

		$query="SELECT DISTINCT num_Cont,luc,
				SUM(saldobco) AS deuda_total, 
				SUM(IF(fec_venc<NOW(),saldobco,0)) AS deuda_vencida, 
				SUM(IF(fec_venc>NOW(),saldobco,0)) AS deuda_vencer, nom_tit,
				ROUND(SUM(IF(fec_venc<NOW(),saldobco,0)) * 100 / t.s,2) AS deuda_vencida_porc,
				ac.accountid,ac.accountname,
				(select SUM(ImpSIva)/count(distinct AnioMes) from lp_arrendamientos where numcont_lin=s.num_Cont  AND AnioMes >='".$fecha_ventas."') as ventas
				FROM lp_saldoscontables s inner join vtiger_account ac on s.num_Cont=ac.acnumerocontrato
				CROSS JOIN (SELECT SUM(IF(fec_venc<NOW(),saldobco,0)) AS s FROM lp_saldoscontables  inner join vtiger_account on num_Cont=acnumerocontrato WHERE cod_cta=".$filtros_cuentas." ) t
				WHERE 1=1 
				";

		$query="SELECT DISTINCT num_Cont,luc,
				SUM(saldopesos) AS deuda_total, 
				SUM(IF(fec_venc<NOW(),saldopesos,0)) AS deuda_vencida, 
				SUM(IF(fec_venc>NOW(),saldopesos,0)) AS deuda_vencer, nom_tit,
				ROUND(SUM(IF(fec_venc<NOW(),saldopesos,0)) * 100 / t.s,2) AS deuda_vencida_porc,
				ac.accountid,ac.accountname,ac.acrazsocial,
				(select SUM(ImpSIva)/count(distinct AnioMes) from lp_arrendamientos where numcont_lin=s.num_Cont  AND AnioMes >='".$fecha_ventas."') as ventas
				FROM v_lp_saldoscontables s inner join vtiger_account ac on s.num_Cont=ac.acnumerocontrato
				CROSS JOIN (SELECT SUM(IF(fec_venc<NOW(),saldopesos,0)) AS s FROM v_lp_saldoscontables  inner join vtiger_account on num_Cont=acnumerocontrato ";
				
                if(!empty($filtro_cuentas))
					$query .= "WHERE cod_cta=".$filtros_cuentas." ) t WHERE 1=1	";
				else
					$query .= " ) t WHERE 1=1	";

		
		$query.=$filtros_locales.$filtro_fechas;
		$query.=" GROUP BY num_cont ORDER BY deuda_vencida_porc DESC";		
		
		// echo $query. " hsta aca";
		return $query;		

	}
	public function procesarRespuesta($query,$vista,$request,$query_aux){
		$adb = PearDatabase::getInstance();
/*		ini_set("display_errors", 1);
		error_reporting(E_ALL & ~E_NOTICE);*/
		$result=$adb->query($query);
		$result2=NULL;
		$localesvalores=array();
		$indice="";
		

			$json_string="[[";
			$json_string.="\"Nro\",\"Nombre Fantasía\",\"Razón Social\",\"LUC\",\"Deuda a Vencer\",
				\"Deuda Vencida\",\"Total Deuda\",\"% Morosidad\",\"Rotación(Meses)\",\"Deuda Vencida(Cliente/Total)\",\"Acumulado\"],";
		
			$total=0;
			//$total['importe']=0;

			$totales["vencer"]=0;
			$totales["vencida"]=0;
			$totales["total"]=0;
			$totales["vencidatot"]=0;
			$totales["vencidaac"]=0;
			$totales["arrtotal"]=0;
			$agrupar2=$agrupar;
			$nro=1;$acumulado=0;$morosidad=0;$rotacion=0;
			while ($row = $adb->fetch_array($result)){
				
				$url="<a href='index.php?module=Accounts&view=Detail&record=".$row['accountid']."'>".$row['accountname']."</a>";
				$acumulado+=$row["deuda_vencida_porc"];
				$morosidad=0;
				$rotacion=0;
				if($row["deuda_total"]>0){
					$morosidad=round(($row["deuda_vencida"]/$row["deuda_total"])*100,2);
					if($row["ventas"]>0){
							$rotacion=round($row["deuda_total"]/$row["ventas"],2);
						}
	
				}
				
				$json_string.="[";
				$json_string.="\"".$nro."\",
				\"".$url."\",
				\"".$row["acrazsocial"]."\",
				\"".$row['luc']."\",
				\"".$row['deuda_vencer']."\",
				\"".$row["deuda_vencida"]."\",
				\"".$row["deuda_total"]."\",
				\"".$morosidad."\",
				\"".$rotacion."\",
				\"".$row["deuda_vencida_porc"]."\",
				\"".$acumulado."\"
				],";	
				
				$totales["vencer"]+=$row['deuda_vencer'];
				$totales["vencida"]+=$row['deuda_vencida'];
				$totales["total"]+=$row['deuda_total'];
				$totales["vencidatot"]+=$row["deuda_vencida_porc"];
				$totales["arrtotal"]+=$row["ventas"];
				$totales["vencidaac"]+=$acumulado;
			
				$nro++;
			}		
			
			if($totales["total"]>0){
				$morosidad=round(($totales["vencida"]/$totales["total"])*100,2);
				if($totales["arrtotal"]>0){
						$rotacion=round($totales["total"]/$totales["arrtotal"],2);
					}
			}

			$json_string.="[\"".$nro."\",
				\"TOTAL\",
				\"-\",
				\"-\",
				\"".$totales["vencer"]."\",
				\"".$totales["vencida"]."\",
				\"".$totales["total"]."\",
				\"".$morosidad."\",
				\"".$rotacion."\",
				\"".$totales["vencidatot"]."\",
				\"".$acumulado."\"
				],";	

			
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