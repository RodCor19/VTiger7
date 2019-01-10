<?php 
ini_set("display_errors", 1);
error_reporting(E_ALL & ~E_NOTICE);

class Analisis_ChequesDiferidos_Action extends Vtiger_BasicAjax_Action{

	public function process(Vtiger_Request $request) {

		ini_set("display_errors", 1);
				error_reporting(E_ALL & ~E_NOTICE);		
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
	public function obtenerFiltrosLocales(Vtiger_Request $request){
		$filtros_locales="";
		$nombreLocal = htmlspecialchars_decode($request->get('nombreLocal'));
		$cuenta = htmlspecialchars_decode($request->get('cuenta'));
		
		
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

		if($cuenta!=""){
			//$filtros_locales .= "AND accountid=$nombreLocal";
			$rangos=explode(",", $cuenta);
			$filtros_locales.=" AND (";
			foreach($rangos as $id){
				$filtros_locales.=" cod_cta = ".$id." OR";
			}	
			$filtros_locales=rtrim($filtros_locales,'OR');
			$filtros_locales.=" )";
		}else{
			$filtros_locales.=" AND 1=1";
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

		$query="";

		if ($fecha_desde!=""){
			$filtro_fechas.=" AND fec_venc >= '".$fecha_desde."'";
		}
		if ($fecha_hasta!=""){
			$filtro_fechas.=" AND fec_venc <= '".$fecha_hasta."' ";
		}


		$query.=" SELECT s.*,ac.accountid,ac.accountname,ac.accodigo, DATE_FORMAT(fec_venc, '%d/%m/%Y') as fecha, ac.luc FROM lp_saldoscontables s inner join vtiger_account ac on s.num_Cont=ac.acnumerocontrato WHERE 1=1 ";
		$query.=$filtros_locales.$filtro_fechas;
		$query.=" ORDER by num_cont asc,fec_venc ";		
		
		// echo $query. " hsta aca";
		return $query;		

	}
	public function procesarRespuesta($query,$vista,$request,$query_aux){
		$adb = PearDatabase::getInstance();
		ini_set("display_errors", 1);
		error_reporting(E_ALL & ~E_NOTICE);
		$result=$adb->query($query);
		$result2=NULL;
		$localesvalores=array();
		$indice="";
		

			$json_string="[[";


			$json_string.="\"LUC\",\"Nombre Fantasía\",\"Razón Social\",\"Documento\",\"Vencimiento\",
							\"Importe\",\"Total\",\"Codigo\"],";
		
			$total=0;
			//$total['importe']=0;
			$agrupar2=$agrupar;
			$total_loc=array();
			$filas=array();
			while ($row = $adb->fetch_array($result)){
				$filas[]=$row;
				if(!isset($total_loc[$row["accountid"]]))
					$total_loc[$row["accountid"]]=array(0,0);
				$total_loc[$row["accountid"]][0]++;
				$total_loc[$row["accountid"]][1]+=$row["saldobco"];
			}
			$local_ant="";
			$total_loc_aux="";
			$cant_local=0;
			for ($i=0; $i <count($filas)-1 ; $i++) { 
				$row=$filas[$i];
					
				if($local_ant==""){
					$local_ant=$row['accountname'];
				}
				if($local_ant!=$row['accountname']){
					$total_loc_aux="";
					$cant_local=0;
					$local_ant=$row['accountname'];
					$cant_local++;
				}else{
					$cant_local++;
				}
				if($cant_local==$total_loc[$row["accountid"]][0]){
					$total_loc_aux=$total_loc[$row["accountid"]][1];
				}

				$url="<a href='index.php?module=Accounts&view=Detail&record=".$row['accountid']."'>".$row['accountname']."</a>";
				
				$json_string.="[";
						
				$json_string.="\"".$row["luc"]."\",
				\"".$url."\",
				\"".$row["nom_tit"]."\",
				\"".$row['nom_docum']."\",
				\"".$row['fecha']."\",
				\"".$row["saldobco"]."\",
				\"".$total_loc_aux."\",
				\"".$row["accodigo"]."\"
				],";	
				$total+=$row["saldobco"];
			}		
			$json_string.="[\"TOTAL\",
				\"-\",
				\"-\",
				\"-\",
				\"-\",
				\"".$total."\",
				\"".$total."\",
				\"-\"
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