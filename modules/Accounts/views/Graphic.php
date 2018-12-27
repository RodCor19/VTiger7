<?php
class Accounts_Graphic_View extends Vtiger_View_Controller {
	public function checkPermission(Vtiger_Request $request) {
		return true;
	}

	public function process(Vtiger_Request $request) {
		global $adb;
		$viewer = $this->getViewer($request);
		$recordId = $request->get('id');
		$moduleName = $request->getModule();
		//inicializar variables
		$tuplas = null;
		$diasConValores = null;
		$dias = null;
		$resultado = null;
		$intervalo = 0;
		//obtenerfechas
		$fechaFin = $this->fechaMaxima($recordId);
		$fechaInicio = $this->fechaMinima($recordId);
		//obtener array de dias, el intervalo parra que se vea mejor y los valores
		if ($fechaFin != null && $fechaInicio != null) {
			$intervalo = $this->intervalo($fechaInicio,$fechaFin);
			$dias = $this->arrayDias($fechaInicio,$fechaFin);
			$resultado = $this->consulta($recordId, $intervalo);
		}
		
		if ($resultado && $adb->num_rows($resultado) > 0) {
			//agrega los valores al array para el grafico y el array de control
			foreach ($resultado as $value) {
				//consulta si es menor a 30 dias
				if ($intervalo < 2592000000) {
					//agrega valores diarios
					$diasConValores[] = $value['date'];
					$tuplas[] = array($value['date'], doubleval($value['total']));
				} else {
					//agrega valores mensuales
					$diasConValores[] = $value['date'].'-01';
					$tuplas[] = array($value['date'].'-01', doubleval($value['total']));
				}
			}
			//ingresa valores 0 a los dias o meses faltantes
			foreach ($dias as $value) {
				if (!in_array($value, $diasConValores)) {
					$tuplas[] = array($value, 0);
				}
			}
			//cambia lables de los tick y del axis dependiendo si es dia o mes
			if ($intervalo < 2592000000) {
				$viewer->assign('itemsLabels', '%d-%m');
				$viewer->assign('label', vtranslate("LBL_DAYS_TYPE", $moduleName));
			} else {
				$viewer->assign('itemsLabels', "%m\\'%Y");
				$viewer->assign('label', vtranslate("LBL_MONTHS_TYPE", $moduleName));
			}
			
		}
		$viewer->assign('valores', $tuplas);
		$viewer->assign('intervalo', $intervalo);
		$viewer->assign('record', $recordId);
		$viewer->view('Grafica.tpl', $moduleName);
	}

	function arrayDias($inicio, $fin){
		$datetimeFin = new DateTime($fin);
		$datetimeInicio = new DateTime($inicio);
		$dias = null;
		//verifica si la diferencia es menor a 30 dias
		if ($datetimeFin->diff($datetimeInicio)->d < 30 && $datetimeFin->diff($datetimeInicio)->m < 1 && $datetimeFin->diff($datetimeInicio)->y < 1) {
			//suma un dia a la fecha final para incluir el ultimo dia
			$datetimeFin->add(new DateInterval('P1D'));
			//agrega al array todos los dias entre las dos fechas
			while ($datetimeFin->diff($datetimeInicio)->d !=0 || $datetimeFin->diff($datetimeInicio)->m != 0){
				$dias[] =  $datetimeInicio->format('Y-m-d');
				$datetimeInicio->add(new DateInterval('P1D'));
			}
		}else{
			//coloca las dos fechas al primer dia del mes
			$stringFecha = $datetimeFin->format('Y-m').'-01';
			$datetimeFin = new DateTime($stringFecha);
			$stringFecha = $datetimeInicio->format('Y-m').'-01';
			$datetimeInicio = new DateTime($stringFecha);
			//suma un mes a la fecha final para incluir el ultimo mes
			$datetimeFin->add(new DateInterval('P1M'));
			//agrega al array todos los meses entre las dos fechas
			while ( $datetimeFin->diff($datetimeInicio)->m != 0  || $datetimeFin->diff($datetimeInicio)->y != 0){
				$dias[] =  $datetimeInicio->format('Y-m').'-01';
				$datetimeInicio->add(new DateInterval('P1M'));
			}
		}
		return $dias;
	}

	function intervalo($inicio, $fin){
		$datetimeFin = new DateTime($fin);
		$datetimeInicio = new DateTime($inicio);
		$intervalo = 0;
		if ($datetimeFin->diff($datetimeInicio)->d < 30 && $datetimeFin->diff($datetimeInicio)->m < 1 && $datetimeFin->diff($datetimeInicio)->y < 1) {
			//86400000 son los milisegundos en un dia, segun la cantidad de dias
			// modifica el intervalo
			if($datetimeFin->diff($datetimeInicio)->d < 11)
				$intervalo = 86400000;
			if($datetimeFin->diff($datetimeInicio)->d > 10)
				$intervalo = 86400000*2;
			if($datetimeFin->diff($datetimeInicio)->d > 20)
				$intervalo = 86400000*3;
		}else{
			// 2592000000 son los milisegundos en 30 dias
			if ($datetimeFin->diff($datetimeInicio)->m < 7 && $datetimeFin->diff($datetimeInicio)->y < 1)
				$intervalo = 2592000000;
			//si la diferencia es mayor a 6 meses los intervalos se calculan como
			// milisegundos de 30 dias * 2(60 dias) * la diferencia + 1 de años 
			else
				$intervalo = 2592000000*2*($datetimeFin->diff($datetimeInicio)->y + 1);
		}
		return $intervalo;
	}
	//retorna la primera fecha de factura de la cuenta
	function fechaMinima($recordId){
		global $adb;
		$resultado = $adb->pquery("SELECT DATE_FORMAT(MIN(invoicedate), '%Y-%m-%d') as 'date' FROM vtiger_invoice WHERE accountid = ?;", array($recordId));
		if($adb->num_rows($resultado) > 0)
			return $resultado->fields['date'];
		else
			return null;
	}
	//retorna la ultima fecha de factura de la cuenta
	function fechaMaxima($recordId){
		global $adb;
		$resultado = $adb->pquery("SELECT DATE_FORMAT(MAX(invoicedate), '%Y-%m-%d') as 'date' FROM vtiger_invoice WHERE accountid = ?;", array($recordId));
		if($adb->num_rows($resultado) > 0)
			return $resultado->fields['date'];
		else
			return null;
	}
	//retorna valores mensuales o diarios dependiedo de la diferencia entre las fechas
	function consulta($recordId, $intervalo){
		$resultado = null;
		global $adb;
		if($intervalo >= 2592000000){
			$resultado = $adb->pquery("SELECT DATE_FORMAT(invoicedate, '%Y-%m') as 'date', sum(total) as 'total' FROM vtiger_invoice WHERE accountid = ? group by DATE_FORMAT(invoicedate, '%Y-%c');", array($recordId));
		}else{
			$resultado = $adb->pquery("SELECT DATE_FORMAT(invoicedate, '%Y-%m-%d') as 'date', sum(total) as 'total' FROM vtiger_invoice WHERE accountid = ? group by invoicedate;", array($recordId));
		}
		return $resultado;
	}

}