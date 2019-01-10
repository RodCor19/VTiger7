
<?php 

class Analisis_FiltrarGraficaDiaoMes_Action extends Vtiger_BasicAjax_Action{

//funcion que se llama cada vez que se cambia alguno de los filtros para armar la grafica
	public function process(Vtiger_Request $request) {

		$diaoMes = htmlspecialchars_decode($request->get('diaoMes'));		
		$montoCantidad = htmlspecialchars_decode($request->get('montoCantidad'));	
		$promocion = htmlspecialchars_decode($request->get('promocion'));		
		$anualComparativa = htmlspecialchars_decode($request->get('anualComparativa'));		
		
		
		$db =  PearDatabase::getInstance();
		
		$fechadia = "CONCAT(YEAR(bcfecha),'-',LPAD(MONTH(bcfecha),2,'0'),'-',LPAD(DAY(bcfecha),2,'0'))";
		$fechames = "CONCAT(YEAR(bcfecha),'-',LPAD(MONTH(bcfecha),2,'0'),'-','01')";

		$filtro1 = "";
		$filtro2 = "";
		$fechaFiltro = "";

		$date = strtotime( date('Y-m-d'));
		$fechaHoy = date("Y-m-d", $date);

		$date2 = strtotime($fechaHoy." - 2 months");
		$fechaFilDia = date("Y-m-d", $date2);

		$date3 = strtotime($fechaHoy." - 2 years");
		$fechaFilMes = date("Y-m-d", $date3);

		$createdTime = $request->get('createdtime');
		if(!empty($createdTime)) {
			$fechaFilDia = DateTimeField::__convertToDBFormat($createdTime['start'],'dd-mm-yyyy');
			$fechaFilMes=$fechaFilDia;
			$fechaHoy = DateTimeField::__convertToDBFormat($createdTime['end'],'dd-mm-yyyy');
		}
		

		// filtro segun se haya elegido por mes o por año para realizar la consulta a la BD
		if ($diaoMes == "dia") {
			
			$filtro1 = $fechadia;
			$fechaFiltro = $fechaFilDia; 
		
		} else {
			
			$filtro1 = $fechames;
			$fechaFiltro = $fechaFilMes; 
		
		}

		if($montoCantidad == "monto"){

			$filtro2 = "SUM(bcprecio)";

		}else{

			$filtro2 = "COUNT(boletascanjeadasid)";		

		}

		$filtro_promo="";
		if($promocion!=""){
			$rangos=explode(",", $promocion);
			$filtro_promo.=" AND (";
			foreach($rangos as $id){
				$filtro_promo.=" bcpromocion ='".$id."' OR";
			}	
			$filtro_promo=rtrim($filtro_promo,'OR');
			$filtro_promo.=" )";
		}


		if ($anualComparativa == "comparativa") {
		
			$query1 = "SELECT DISTINCT $filtro1 AS fecha, $filtro2 AS filtro2, 
			bcpromocion AS promocion FROM vtiger_boletascanjeadas 
			WHERE bcfecha >= '$fechaFiltro' ".$filtro_promo." 
			GROUP BY fecha, bcpromocion ORDER BY bcpromocion, bcfecha";
		
		} 

		else { 
		
			$query1 = "SELECT DISTINCT $filtro1 AS fecha, $filtro2 AS filtro2, 
			bcpromocion AS promocion FROM vtiger_boletascanjeadas 
			WHERE bcfecha >= '$fechaFiltro' ".$filtro_promo." 
			GROUP BY fecha, bcpromocion ORDER BY bcpromocion, bcfecha";
		
		}

		//$fechaFiltro
		// var_dump($query1);
		//echo $query1;
		$filconsulta = $db->query($query1);

		$pro = array();
		$i = 0;
		$cantPro = 0;
		$anios = 0;
		$fechas = array();
		foreach ($filconsulta as $key) {
			$fechas[$o] = $key["fecha"];

			$pro[$i]= $key["promocion"];

			if ($pro[$i] != $pro[$i-1]){
				$promo[$i]= $key["promocion"];
				
				$i++;
			}
			

		}

		$cantPro = $i;
		
		$filpromo = implode(',  ',$promo);
	

		foreach ($filconsulta as $key) {

			$a= $key["fecha"];
			$aux[$a] = $key["filtro2"];
			$auxIpc[$a] = $key["promocion"];
			$eachpromo[$key["promocion"]][$key["fecha"]]= $aux[$a];

		}

		$j = 0;

		$datos = array();
		
		if($diaoMes == "mes"){
			$fechaAnterior = $fechaFilMes;
			$j = 0;
			foreach ($promo as $p){

				while (strtotime($fechaAnterior) <= strtotime($fechaHoy)) {

					$mes = date('Y-m-d',strtotime($fechaAnterior));



			// si para esa promocion en ese mes no existe un monto o cantidad se le asigna monto/cantidad 0
					if(!isset($eachpromo[$p][$mes])){

						$total = 0;

					}else 
					$total = $eachpromo[$p][$mes];

					$datos[$j][] = array($mes,floatval($total));

					
		
					
					
					$fechaAnterior = date ("Y-m-d", strtotime("+1 MONTH", strtotime($fechaAnterior)));
				}
			
				$j++;
				$fechaAnterior = $fechaFilMes;
				
			}
			
		}else {
			$fechaAnterior = $fechaFilDia;
			$j = 0;
			foreach($promo as $p){

				while(strtotime($fechaAnterior) <= strtotime($fechaHoy)){

					$dia = date('Y-m-d',strtotime($fechaAnterior));


					// si para esa promocion en ese dia no existe monto o cantidad se le asigna monto/cantidad 0
					if(!isset($eachpromo[$p][$dia])){

						$total = 0;
					}
					else 
						$total = $eachpromo[$p][$dia];

					
					$datos[$j][] = array($dia,floatval($total));

					$fechaAnterior = date ("Y-m-d", strtotime("+1 DAY", strtotime($fechaAnterior)));			
				}

				$j++;
				$fechaAnterior = $fechaFilDia;	

			}
			
		}

		if($filconsulta == NULL){
			$datos = "";
			$promo = "";
		}
// se envian los datos y un array con cada promocion
		echo json_encode(array($datos, $promo));

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