<?php 
/*ini_set("display_errors", 1);
error_reporting(E_ALL & ~E_NOTICE);*/

class Analisis_ParetoCuentas_Action extends Vtiger_BasicAjax_Action{

	public function process(Vtiger_Request $request) {

		$adb = PearDatabase::getInstance();

		$desde=" CURDATE() - INTERVAL 24 MONTH";
		$hasta=" CURDATE()";

		$date = strtotime( date('Y-m-01')." -23 months");
		$desde=date("Ym", $date);
		$date = strtotime( date('Y-m-01'));
		$hasta=date("Ym", $date);
		
		$createdTime = $request->get('createdtime');
		if(!empty($createdTime)) {
			$fecha_desde = DateTimeField::__convertToDBFormat($createdTime['start'],'dd-mm-yyyy');
			$fecha_hasta = DateTimeField::__convertToDBFormat($createdTime['end'],'dd-mm-yyyy');
			
			list($d, $m, $y) = explode('-', $createdTime['start']);
			$desde =$y.$m;
			
			list($d, $m, $y) = explode('-', $createdTime['end']);
			$hasta =$y.$m;
			
		}
		


		$filtros_locales=$this->obtenerFiltrosLocales($request);

		$vista = $request->get('vista');
		$query="";
		switch ($vista) {
			case 'ventas':
				$query="SELECT accountname, 
						SUM( vc.VentasSIva ) AS cant,acmetros
						FROM lp_ventas_contratos vc 
						INNER JOIN vtiger_account cuentas on vc.Contrato = cuentas.acnumerocontrato
						INNER JOIN vtiger_crmentity on crmid=accountid";
				$query.=" WHERE 1=1 and deleted=0 ".$filtros_locales;				
				if ($desde!=""){
					$query.=" AND vc.Aniomes >= ".$desde." ";
				}
				if ($hasta!=""){
					$query.=" AND vc.Aniomes <= ".$hasta." ";
				}
				$query.="	GROUP BY acnumerocontrato
							ORDER BY SUM( VentasSIva ) DESC";		
				break;
			case 'arr':
				$query="SELECT accountname, 
						SUM( ar.ImpSIva ) AS cant,acmetros
						FROM lp_arrendamientos ar 
						INNER JOIN vtiger_account cuentas ON ar.numcont_lin = cuentas.acnumerocontrato
						INNER JOIN vtiger_crmentity on crmid=accountid";
				$query.=" WHERE 1=1 AND deleted=0 AND (cod_grupocon='ARRPORCENTUAL' OR cod_grupocon='ARRMINIMO' )".$filtros_locales;				
				if ($desde!=""){
					$query.=" AND ar.Aniomes >= ".$desde." ";
				}
				if ($hasta!=""){
					$query.=" AND ar.Aniomes <= ".$hasta." ";
				}
				$query.="	GROUP BY accountid
							ORDER BY SUM( ImpSIva ) DESC";		
				break;
			
			default:
				$query="SELECT accountname, 
						acmetros AS cant
						FROM vtiger_account cuentas
						INNER JOIN vtiger_crmentity on crmid=accountid";
				$query.=" WHERE 1=1 and deleted=0 ".$filtros_locales;				
				
				$query.="	GROUP BY accountid
							ORDER BY acmetros DESC";	
				break;
		}


		
		$result=$adb->query($query);
		//fwrite($fp,$query.PHP_EOL);
		$no_of_rows=$adb->num_rows($result);
		$ar_pareto = array();
		$ar_pareto2 = array();
		$ar_pareto_grafica = array();
		$ar_pareto_tabla = array();

		if($no_of_rows!=0){
			$total=0;
			$clientes=0;

			while($row = $adb->fetch_array($result)){
			     //Se marea sin esto, por las personas que tienen saldo -
				if($row['cant']>0){
					$total+=$row['cant'];
					 $clientes++;
					$ar_pareto[] = array($clientes,(double)$row['cant']);
					$ar_pareto2[] = array($row['accountname'],(double)$row['cant'],(double)$row['acmetros']);
				}
			}

			
			$i=0;
			$porcentaje_ac=0;
			$porcentaje=0;
			$j=50;
			//echo $total."<br>";
			while ($i<=$clientes){
				$porcentaje=(($ar_pareto[$i][1]*100)/$total);
				//echo $porcentaje."<br>";
				$porcentaje_ac+=$porcentaje;
				$ar_pareto[$i][1]=(double)$porcentaje_ac;
				//if ($j==50){
				$ar_pareto_grafica[]=array($ar_pareto[$i][0],(double)$porcentaje_ac);
				$ar_pareto_tabla[]=array($ar_pareto2[$i][0],(double)round($porcentaje,2),(double)$ar_pareto2[$i][1],(double)$ar_pareto2[$i][2]);
				//	$j=0;
				//}
				
				$j++;
				$i++;
			}		
		}

		echo json_encode(array(array($ar_pareto_grafica),$ar_pareto_tabla)); 
		return;
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
	
}