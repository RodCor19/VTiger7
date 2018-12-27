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
		//inicializar variables
		$tuplas = null;
		$result = array();
		$resultado = null;
		$intervalo = 0;
		$dias = null;
		$diasConValores = null;
		$rotacionFechas = false;
		global $adb;
		// si la fecha final es menor a la inicial las intercambia
		if ($finalDate != null && $initialDate != null && new DateTime($initialDate) > new DateTime($finalDate)) {
			$aux = $finalDate;
			$finalDate = $initialDate;
			$initialDate = $aux;
			$rotacionFechas = true;
		}

		//realiza diferentes procedimientos dependiendo de si las fechas estan ingresadas
		if ($finalDate != null && $initialDate != null) {
			//pide el intervalo y el array de dias o meses entre fechas
			$intervalo = $this->intervalo($initialDate,$finalDate);
			$dias = $this->arrayDias($initialDate,$finalDate);
		}else{
			if ($finalDate != null ) {
				//pide la fecha inicial de facturas
				$initial = $this->fechaMinima($recordId);
				//consulta si retorno algo
				if($initial != null){
					//pide el intervalo y el array de dias o meses entre fechas
					$intervalo = $this->intervalo($initial,$finalDate);
					$dias = $this->arrayDias($initial,$finalDate);
				}
			}elseif ($initialDate != null) {
				//pide la fecha final de facturas
				$final = $this->fechaMaxima($recordId);
				//consulta si retorno algo
				if($final != null){
					//pide el intervalo y el array de dias o meses entre fechas
					$intervalo = $this->intervalo($initialDate,$final);
					$dias = $this->arrayDias($initialDate,$final);
				}
			}else{
				//pide fecha inicial y final
				$initial = $this->fechaMinima($recordId);
				$final = $this->fechaMaxima($recordId);
				//consulta si retorno algo
				if($final != null && $initial != null){
					//pide el intervalo y el array de dias o meses entre fechas
					$intervalo = $this->intervalo($initial,$final);
					$dias = $this->arrayDias($initial,$final);
				}
			}
		}
		//pide los valores en bd dependiendo de las fechas e intervalo
		$resultado = $this->consulta($recordId, $initialDate, $finalDate, $intervalo);
		//consulta si no hubo error y si hay resultados
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
			//consulta si el intervalo es menor a 30 dias
			if ($intervalo < 2592000000) {
				//asigna el success
				$result['success'] = true;
				//asigna label, ticklabel e intervalo para valores diarios
				//el atributo rotar es porque cambia el orden de las fechas en las vistas
				//porque la fecha inicial enviada es mayor a la fecha final
				$result['data'] = array('valores' => $tuplas, 'label' => vtranslate("LBL_DAYS_TYPE", $moduleName), 'itemsLabels' => '%d-%m', 'intervalo' => $intervalo, 'rotacion' => $rotacionFechas);
			} else {
				//asigna el success
				$result['success'] = true;
				//asigna label, ticklabel e intervalo para valores mensuales
				//el atributo rotar es porque cambia el orden de las fechas en las vistas
				//porque la fecha inicial enviada es mayor a la fecha final
				$result['data'] = array('valores' => $tuplas, 'label' => vtranslate("LBL_MONTHS_TYPE", $moduleName), 'itemsLabels' => '%m\'%Y', 'intervalo' => $intervalo, 'rotacion' => $rotacionFechas);
			}
			
		}else{
			//si hay error en la consulta o el resultado no tiene valores da error
			$result['success'] = false;
			if (!$resultado) {
				$result['error'] = 'Error en la consulta';
			} else {
				$result['error'] = 'No hay facturas entre las fechas elegidas';
			}
		}

		$response = new Vtiger_Response();
		$response->setResult($result);
		$response->emit();
	}
	//retorna todos los dias o meses que exiten entre las dos fechas
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
			// si la diferencia es mayor a 6 meses los intervalos se calculan como
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
