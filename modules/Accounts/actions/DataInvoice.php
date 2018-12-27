<?php

class Accounts_DataInvoice_Action extends Vtiger_Action_Controller {

	function checkPermission(Vtiger_Request $request) {
		return;
	}

	public function process(Vtiger_Request $request) {
		$moduleName = $request->getModule();
		$initialDate = $request->get('inicio');
		$finalDate = $request->get('fin');
		$recordId = $request->get('record');
		$tuplas = null;
		global $adb;
		$result = array();
		$resultado = null;
		$intervaloDia = false;
		$intervalo = 0;
		$dias = null;
		$diasConValores = null;


		if ($finalDate != null && $initialDate != null) {
			$datetime1 = new DateTime($finalDate);
			$datetime2 = new DateTime($initialDate);
			if ($datetime1->diff($datetime2)->d < 30 && $datetime1->diff($datetime2)->m < 1) {
				$intervaloDia = true;
				//86400000 son los milisegundos en un dia, segun la cantidad de dias
				// modifica el intervalo
				if($datetime1->diff($datetime2)->d < 11)
					$intervalo = 86400000;
				if($datetime1->diff($datetime2)->d > 10)
					$intervalo = 86400000*2;
				if($datetime1->diff($datetime2)->d > 20)
					$intervalo = 86400000*3;
				$datetime1->add(new DateInterval('P1D'));
				while ($datetime1->diff($datetime2)->d !=0 || $datetime1->diff($datetime2)->m != 0){
					$dias[] =  $datetime2->format('Y-m-d');
					$datetime2->add(new DateInterval('P1D'));
				}
				
				
				$resultado = $adb->pquery("SELECT DATE_FORMAT(invoicedate, '%Y-%m-%d') as 'date', sum(total) as 'total' FROM vtiger_invoice WHERE accountid = ? and invoicedate between ? and ? group by invoicedate;", array($recordId, $initialDate, $finalDate));
			}else{
				$stringFecha = $datetime1->format('Y-m').'-01';
				$datetime1 = new DateTime($stringFecha);
				$datetime1->add(new DateInterval('P1M'));
				$stringFecha = $datetime2->format('Y-m').'-01';
				$datetime2 = new DateTime($stringFecha);
				while ( $datetime1->diff($datetime2)->m != 0  || $datetime1->diff($datetime2)->y != 0){
					$dias[] =  $datetime2->format('Y-m').'-01';
					$datetime2->add(new DateInterval('P1M'));
				}
				$resultado = $adb->pquery("SELECT DATE_FORMAT(invoicedate, '%Y-%m') as 'date', sum(total) as 'total' FROM vtiger_invoice WHERE accountid = ? and invoicedate between ? and ? group by DATE_FORMAT(invoicedate, '%Y-%c');", array($recordId, $initialDate, $finalDate));
			}
		}else{
			if ($finalDate != null ) {
				$datetime1 = new DateTime($finalDate);
				$stringFecha = $datetime1->format('Y-m').'-01';
				$datetime1 = new DateTime($stringFecha);
				$datetime1->add(new DateInterval('P1M'));
				$resultado = $adb->pquery("SELECT DATE_FORMAT(MIN(invoicedate), '%Y-%m') as 'date' FROM vtiger_invoice WHERE accountid = ?;", array($recordId));
				$stringFecha = $resultado->fields['date'].'-01';
				$datetime2 = new DateTime($stringFecha);
				while ( $datetime1->diff($datetime2)->m != 0  || $datetime1->diff($datetime2)->y != 0){
					$dias[] =  $datetime2->format('Y-m').'-01';
					$datetime2->add(new DateInterval('P1M'));
				}
				$resultado = $adb->pquery("SELECT DATE_FORMAT(invoicedate, '%Y-%m') as 'date', sum(total) as 'total' FROM vtiger_invoice WHERE accountid = ? and invoicedate <= ? group by DATE_FORMAT(invoicedate, '%Y-%c')", array($recordId, $finalDate));
			}elseif ($initialDate != null) {
				$datetime2 = new DateTime($initialDate);
				$stringFecha = $datetime2->format('Y-m').'-01';
				$datetime2 = new DateTime($stringFecha);
				$resultado = $adb->pquery("SELECT DATE_FORMAT(MIN(invoicedate), '%Y-%m') as 'date' FROM vtiger_invoice WHERE accountid = ?;", array($recordId));
				$stringFecha = $resultado->fields['date'].'-01';
				$datetime1 = new DateTime($stringFecha);
				$datetime1->add(new DateInterval('P1M'));
				while ( $datetime1->diff($datetime2)->m != 0  || $datetime1->diff($datetime2)->y != 0){
					$dias[] =  $datetime2->format('Y-m').'-01';
					$datetime2->add(new DateInterval('P1M'));
				}
				$resultado = $adb->pquery("SELECT DATE_FORMAT(invoicedate, '%Y-%m') as 'date', sum(total) as 'total' FROM vtiger_invoice WHERE accountid = ? and invoicedate >= ? group by DATE_FORMAT(invoicedate, '%Y-%c')", array($recordId, $initialDate));
			}else{
				$resultado = $adb->pquery("SELECT DATE_FORMAT(MIN(invoicedate), '%Y-%m') as 'date' FROM vtiger_invoice WHERE accountid = ?;", array($recordId));
				$stringFecha = $resultado->fields['date'].'-01';
				$datetime2 = new DateTime($stringFecha);
				$resultado = $adb->pquery("SELECT DATE_FORMAT(MAX(invoicedate), '%Y-%m') as 'date' FROM vtiger_invoice WHERE accountid = ?;", array($recordId));
				$stringFecha = $resultado->fields['date'].'-01';
				$datetime1 = new DateTime($stringFecha);
				$datetime1->add(new DateInterval('P1M'));
				while ( $datetime1->diff($datetime2)->m != 0  || $datetime1->diff($datetime2)->y != 0){
					$dias[] =  $datetime2->format('Y-m').'-01';
					$datetime2->add(new DateInterval('P1M'));
				}
				$resultado = $adb->pquery("SELECT DATE_FORMAT(invoicedate, '%Y-%m') as 'date', sum(total) as 'total' FROM vtiger_invoice WHERE accountid = ? group by DATE_FORMAT(invoicedate, '%Y-%c');", array($recordId));
			}
		}
		if ($resultado && $adb->num_rows($resultado) > 0) {
			foreach ($resultado as $value) {
				if ($intervaloDia) {
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
			if ($intervaloDia) {
				$result['success'] = true;
				$result['data'] = array('valores' => $tuplas, 'label' => vtranslate("LBL_DAYS_TYPE", $moduleName), 'itemsLabels' => '%d-%m', 'intervalo' => $intervalo);
			} else {
				$result['success'] = true;
				// 2592000000 son los milisegundos en 30 dias
				$result['data'] = array('valores' => $tuplas, 'label' => vtranslate("LBL_MONTHS_TYPE", $moduleName), 'itemsLabels' => '%m\'%Y', 'intervalo' => 2592000000, 'meses' => $dias);
			}
			
			
		}else{
			$result['success'] = false;
			if ($resultado) {
				$result['error'] = 'Error en la consulta';
			} else {
				$result['error'] = 'No hay facturas entre las fechas elegidas';
			}
		}

		$response = new Vtiger_Response();
		$response->setResult($result);
		$response->emit();
	}

	function arrayDias($inicio, $fin){
		$datetimeFin = new DateTime($fin);
		$datetimeInicio = new DateTime($inicio);
		$dias = null;
		if ($datetimeFin->diff($datetimeInicio)->d < 30 && $datetimeFin->diff($datetimeInicio)->m < 1) {
			$datetimeFin->add(new DateInterval('P1D'));
			while ($datetime1->diff($datetimeInicio)->d !=0 || $datetimeFin->diff($datetimeInicio)->m != 0){
				$dias[] =  $datetimeInicio->format('Y-m-d');
				$datetimeInicio->add(new DateInterval('P1D'));
			}
		}else{
			$stringFecha = $datetimeFin->format('Y-m').'-01';
			$datetimeFin = new DateTime($stringFecha);
			$stringFecha = $datetimeInicio->format('Y-m').'-01';
			$datetimeInicio = new DateTime($stringFecha);
			$datetimeFin->add(new DateInterval('P1M'));
			while ( $datetime1->diff($datetimeInicio)->m != 0  || $datetime1->diff($datetimeInicio)->y != 0){
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
		if ($datetimeFin->diff($datetimeInicio)->d < 30 && $datetimeFin->diff($datetimeInicio)->m < 1) {
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
			if($datetimeFin->diff($datetimeInicio)->y < 1)
				$intervalo = 2592000000*2;
			else
				$intervalo = 2592000000*2*($datetimeFin->diff($datetimeInicio)->y + 1);
		}
		return $intervalo;
	}

	function fechaMinima($recordId){
		global $adb;
		$resultado = $adb->pquery("SELECT DATE_FORMAT(MIN(invoicedate), '%Y-%m') as 'date' FROM vtiger_invoice WHERE accountid = ?;", array($recordId));
		if($adb->num_rows($resultado) > 0)
			return $resultado->fields['date'].'-01';
		else
			return null;
	}

	function fechaMaxima($recordId){
		global $adb;
		$resultado = $adb->pquery("SELECT DATE_FORMAT(MAX(invoicedate), '%Y-%m') as 'date' FROM vtiger_invoice WHERE accountid = ?;", array($recordId));
		if($adb->num_rows($resultado) > 0)
			return $resultado->fields['date'].'-01';
		else
			return null;
	}

	function consulta($recordId, $fechaInicio, $fechaFin, $intervalo){
		$resultado = null;
		global $adb;
		if($intervalo > 2592000000){
			if ($finalDate != null && $initialDate != null) {
				$resultado = $adb->pquery("SELECT DATE_FORMAT(invoicedate, '%Y-%m') as 'date', sum(total) as 'total' FROM vtiger_invoice WHERE accountid = ? and invoicedate between ? and ? group by DATE_FORMAT(invoicedate, '%Y-%c');", array($recordId, $initialDate, $finalDate));
			}else{
				if ($finalDate != null ) {
					$resultado = $adb->pquery("SELECT DATE_FORMAT(invoicedate, '%Y-%m') as 'date', sum(total) as 'total' FROM vtiger_invoice WHERE accountid = ? and invoicedate <= ? group by DATE_FORMAT(invoicedate, '%Y-%c')", array($recordId, $finalDate));
				}elseif ($initialDate != null) {
					$resultado = $adb->pquery("SELECT DATE_FORMAT(invoicedate, '%Y-%m') as 'date', sum(total) as 'total' FROM vtiger_invoice WHERE accountid = ? and invoicedate >= ? group by DATE_FORMAT(invoicedate, '%Y-%c')", array($recordId, $initialDate));
				}else{
					$resultado = $adb->pquery("SELECT DATE_FORMAT(invoicedate, '%Y-%m') as 'date', sum(total) as 'total' FROM vtiger_invoice WHERE accountid = ? group by DATE_FORMAT(invoicedate, '%Y-%c');", array($recordId));
				}
			}
		}else{
			if ($finalDate != null && $initialDate != null) {
				$resultado = $adb->pquery("SELECT DATE_FORMAT(invoicedate, '%Y-%m-%d') as 'date', sum(total) as 'total' FROM vtiger_invoice WHERE accountid = ? and invoicedate between ? and ? group by invoicedate;", array($recordId, $initialDate, $finalDate));
			}else{
				if ($finalDate != null ) {
					$resultado = $adb->pquery("SELECT DATE_FORMAT(invoicedate, '%Y-%m-%d') as 'date', sum(total) as 'total' FROM vtiger_invoice WHERE accountid = ? and invoicedate <= ? group by invoicedate", array($recordId, $finalDate));
				}elseif ($initialDate != null) {
					$resultado = $adb->pquery("SELECT DATE_FORMAT(invoicedate, '%Y-%m-%d') as 'date', sum(total) as 'total' FROM vtiger_invoice WHERE accountid = ? and invoicedate >= ? group by invoicedate", array($recordId, $initialDate));
				}else{
					$resultado = $adb->pquery("SELECT DATE_FORMAT(invoicedate, '%Y-%m-%d') as 'date', sum(total) as 'total' FROM vtiger_invoice WHERE accountid = ? group by invoicedate;", array($recordId));
				}
			}
		}
		return $resultado;
	}
}
