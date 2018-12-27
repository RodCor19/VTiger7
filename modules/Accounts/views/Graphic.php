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
		$tuplas = null;
		$diasConValores = null;
		$dias = null;
		$resultado = null;
		$fechaFin = $this->fechaMaxima($recordId);
		$fechaInicio = $this->fechaMinima($recordId);
		$intervalo = 0;
		if ($fechaFin != null && $fechaInicio != null) {
			$intervalo = $this->intervalo($fechaInicio,$fechaFin);
			$dias = $this->arrayDias($fechaInicio,$fechaFin);
			$resultado = $this->consulta($recordId, $intervalo);
		}
		
		if ($resultado && $adb->num_rows($resultado) > 0) {
			foreach ($resultado as $value) {
				if ($intervalo < 2592000000) {
					$diasConValores[] = $value['date'];
					$tuplas[] = array($value['date'], doubleval($value['total']));
				} else {
					$diasConValores[] = $value['date'].'-01';
					$tuplas[] = array($value['date'].'-01', doubleval($value['total']));
				}
			}
			foreach ($dias as $value) {
				if (!in_array($value, $diasConValores)) {
					$tuplas[] = array($value, 0);
				}
			}

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
		if ($datetimeFin->diff($datetimeInicio)->d < 30 && $datetimeFin->diff($datetimeInicio)->m < 1 && $datetimeFin->diff($datetimeInicio)->y < 1) {
			$datetimeFin->add(new DateInterval('P1D'));
			while ($datetimeFin->diff($datetimeInicio)->d !=0 || $datetimeFin->diff($datetimeInicio)->m != 0){
				$dias[] =  $datetimeInicio->format('Y-m-d');
				$datetimeInicio->add(new DateInterval('P1D'));
			}
		}else{
			$stringFecha = $datetimeFin->format('Y-m').'-01';
			$datetimeFin = new DateTime($stringFecha);
			$stringFecha = $datetimeInicio->format('Y-m').'-01';
			$datetimeInicio = new DateTime($stringFecha);
			$datetimeFin->add(new DateInterval('P1M'));
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
			else
				$intervalo = 2592000000*2*($datetimeFin->diff($datetimeInicio)->y + 1);
		}
		return $intervalo;
	}

	function fechaMinima($recordId){
		global $adb;
		$resultado = $adb->pquery("SELECT DATE_FORMAT(MIN(invoicedate), '%Y-%m-%d') as 'date' FROM vtiger_invoice WHERE accountid = ?;", array($recordId));
		if($adb->num_rows($resultado) > 0)
			return $resultado->fields['date'];
		else
			return null;
	}

	function fechaMaxima($recordId){
		global $adb;
		$resultado = $adb->pquery("SELECT DATE_FORMAT(MAX(invoicedate), '%Y-%m-%d') as 'date' FROM vtiger_invoice WHERE accountid = ?;", array($recordId));
		if($adb->num_rows($resultado) > 0)
			return $resultado->fields['date'];
		else
			return null;
	}

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