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
		$result = array();
		$resultado = null;
		$intervalo = 0;
		$dias = null;
		$diasConValores = null;
		$rotacionFechas = false;
		global $adb;

		if ($finalDate != null && $initialDate != null && new DateTime($initialDate) > new DateTime($finalDate)) {
			$aux = $finalDate;
			$finalDate = $initialDate;
			$initialDate = $aux;
			$rotacionFechas = true;
		}

		if ($finalDate != null && $initialDate != null) {
			$intervalo = $this->intervalo($initialDate,$finalDate);
			$dias = $this->arrayDias($initialDate,$finalDate);
		}else{
			if ($finalDate != null ) {
				$initial = $this->fechaMinima($recordId);
				if($initial != null){
					$intervalo = $this->intervalo($initial,$finalDate);
					$dias = $this->arrayDias($initial,$finalDate);
				}
			}elseif ($initialDate != null) {
				$final = $this->fechaMaxima($recordId);
				if($final != null){
					$intervalo = $this->intervalo($initialDate,$final);
					$dias = $this->arrayDias($initialDate,$final);
				}
			}else{
				$initial = $this->fechaMinima($recordId);
				$final = $this->fechaMaxima($recordId);
				if($final != null && $initial != null){
					$intervalo = $this->intervalo($initial,$final);
					$dias = $this->arrayDias($initial,$final);
				}
			}
		}
		$resultado = $this->consulta($recordId, $initialDate, $finalDate, $intervalo);

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
				$result['success'] = true;
				$result['data'] = array('valores' => $tuplas, 'label' => vtranslate("LBL_DAYS_TYPE", $moduleName), 'itemsLabels' => '%d-%m', 'intervalo' => $intervalo, 'rotacion' => $rotacionFechas);
			} else {
				$result['success'] = true;
				// 2592000000 son los milisegundos en 30 dias
				$result['data'] = array('valores' => $tuplas, 'label' => vtranslate("LBL_MONTHS_TYPE", $moduleName), 'itemsLabels' => '%m\'%Y', 'intervalo' => $intervalo, 'rotacion' => $rotacionFechas);
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
		if ($datetimeFin->diff($datetimeInicio)->d < 30 && $datetimeFin->diff($datetimeInicio)->m < 1
			&& $datetimeFin->diff($datetimeInicio)->y < 1) {
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

	function consulta($recordId, $fechaInicio, $fechaFin, $intervalo){
		$resultado = null;
		global $adb;
		if($intervalo >= 2592000000){
			if ($fechaFin != null && $fechaInicio != null) {
				$resultado = $adb->pquery("SELECT DATE_FORMAT(invoicedate, '%Y-%m') as 'date', sum(total) as 'total' FROM vtiger_invoice WHERE accountid = ? and invoicedate between ? and ? group by DATE_FORMAT(invoicedate, '%Y-%c');", array($recordId, $fechaInicio, $fechaFin));
			}else{
				if ($fechaFin != null ) {
					$resultado = $adb->pquery("SELECT DATE_FORMAT(invoicedate, '%Y-%m') as 'date', sum(total) as 'total' FROM vtiger_invoice WHERE accountid = ? and invoicedate <= ? group by DATE_FORMAT(invoicedate, '%Y-%c')", array($recordId, $fechaFin));
				}elseif ($fechaInicio != null) {
					$resultado = $adb->pquery("SELECT DATE_FORMAT(invoicedate, '%Y-%m') as 'date', sum(total) as 'total' FROM vtiger_invoice WHERE accountid = ? and invoicedate >= ? group by DATE_FORMAT(invoicedate, '%Y-%c')", array($recordId, $fechaInicio));
				}else{
					$resultado = $adb->pquery("SELECT DATE_FORMAT(invoicedate, '%Y-%m') as 'date', sum(total) as 'total' FROM vtiger_invoice WHERE accountid = ? group by DATE_FORMAT(invoicedate, '%Y-%c');", array($recordId));
				}
			}
		}else{
			if ($fechaFin != null && $fechaInicio != null) {
				$resultado = $adb->pquery("SELECT DATE_FORMAT(invoicedate, '%Y-%m-%d') as 'date', sum(total) as 'total' FROM vtiger_invoice WHERE accountid = ? and invoicedate between ? and ? group by invoicedate;", array($recordId, $fechaInicio, $fechaFin));
			}else{
				if ($fechaFin != null ) {
					$resultado = $adb->pquery("SELECT DATE_FORMAT(invoicedate, '%Y-%m-%d') as 'date', sum(total) as 'total' FROM vtiger_invoice WHERE accountid = ? and invoicedate <= ? group by invoicedate", array($recordId, $fechaFin));
				}elseif ($fechaInicio != null) {
					$resultado = $adb->pquery("SELECT DATE_FORMAT(invoicedate, '%Y-%m-%d') as 'date', sum(total) as 'total' FROM vtiger_invoice WHERE accountid = ? and invoicedate >= ? group by invoicedate", array($recordId, $fechaInicio));
				}else{
					$resultado = $adb->pquery("SELECT DATE_FORMAT(invoicedate, '%Y-%m-%d') as 'date', sum(total) as 'total' FROM vtiger_invoice WHERE accountid = ? group by invoicedate;", array($recordId));
				}
			}
		}
		return $resultado;
	}
}
