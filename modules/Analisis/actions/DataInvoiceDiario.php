<?php

class Analisis_DataInvoiceDiario_Action extends Vtiger_Action_Controller {

	function checkPermission(Vtiger_Request $request) {
		return;
	}

	public function process(Vtiger_Request $request) {
		$moduleName = $request->getModule();
		$initialDate = $request->get('inicio');
		$finalDate = $request->get('fin');
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
				$initial = $this->fechaMinima();
				//consulta si retorno algo
				if($initial != null){
					//pide el intervalo y el array de dias o meses entre fechas
					$intervalo = $this->intervalo($initial,$finalDate);
					$dias = $this->arrayDias($initial,$finalDate);
				}
			}elseif ($initialDate != null) {
				//pide la fecha final de facturas
				$final = $this->fechaMaxima();
				//consulta si retorno algo
				if($final != null){
					//pide el intervalo y el array de dias o meses entre fechas
					$intervalo = $this->intervalo($initialDate,$final);
					$dias = $this->arrayDias($initialDate,$final);
				}
			}else{
				//pide fecha inicial y final
				$initial = $this->fechaMinima();
				$final = $this->fechaMaxima();
				//consulta si retorno algo
				if($final != null && $initial != null){
					//pide el intervalo y el array de dias o meses entre fechas
					$intervalo = $this->intervalo($initial,$final);
					$dias = $this->arrayDias($initial,$final);
				}
			}
		}
		//pide los valores en bd dependiendo de las fechas e intervalo
		$resultado = $this->consulta($initialDate, $finalDate);
		//consulta si no hubo error y si hay resultados
		if ($resultado && $adb->num_rows($resultado) > 0) {
			//agrega los valores al array para el grafico y el array de control
			foreach ($resultado as $value) {
				//agrega valores diarios
				$diasConValores[] = $value['date'];
				$tuplas[] = array($value['date'], doubleval($value['total']));
			}
			//ingresa valores 0 a los dias o meses faltantes
			foreach ($dias as $value) {
				if (!in_array($value, $diasConValores)) {
					$tuplas[] = array($value, 0);
				}
			}
			//asigna el success
			$result['success'] = true;
			//asigna label, ticklabel e intervalo para valores diarios
			//el atributo rotar es porque cambia el orden de las fechas en las vistas
			//porque la fecha inicial enviada es mayor a la fecha final
			$result['data'] = array('valores' => $tuplas, 'label' => vtranslate("LBL_DAYS_TYPE", 'Accounts'), 'itemsLabels' => '%d-%m', 'intervalo' => $intervalo, 'rotacion' => $rotacionFechas);
			
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
		//suma un dia a la fecha final para incluir el ultimo dia
		$datetimeFin->add(new DateInterval('P1D'));
		//agrega al array todos los dias entre las dos fechas
		while ($datetimeFin->diff($datetimeInicio)->d !=0 || $datetimeFin->diff($datetimeInicio)->m != 0){
			$dias[] =  $datetimeInicio->format('Y-m-d');
			$datetimeInicio->add(new DateInterval('P1D'));
		}
		return $dias;
	}

	function intervalo($inicio, $fin){
		$datetimeFin = new DateTime($fin);
		$datetimeInicio = new DateTime($inicio);
		$intervalo = 0;
		//86400000 son los milisegundos en un dia, segun la cantidad de dias
		// modifica el intervalo
		$diferencia = ($datetimeFin->diff($datetimeInicio)->days)/15;
		$intervalo = 86400000*$diferencia;
		return $intervalo;
	}
	//retorna la primera fecha de factura de la cuenta
	function fechaMinima(){
		global $adb;
		$resultado = $adb->pquery("SELECT DATE_FORMAT(MIN(invoicedate), '%Y-%m-%d') as 'date' FROM vtiger_invoice");
		if($adb->num_rows($resultado) > 0)
			return $resultado->fields['date'];
		else
			return null;
	}
	//retorna la ultima fecha de factura de la cuenta
	function fechaMaxima(){
		global $adb;
		$resultado = $adb->pquery("SELECT DATE_FORMAT(MAX(invoicedate), '%Y-%m-%d') as 'date' FROM vtiger_invoice");
		if($adb->num_rows($resultado) > 0)
			return $resultado->fields['date'];
		else
			return null;
	}
	//retorna valores mensuales o diarios dependiedo de la diferencia entre las fechas
	function consulta($fechaInicio, $fechaFin){
		$resultado = null;
		global $adb;
		if ($fechaFin != null && $fechaInicio != null) {
			$resultado = $adb->pquery("SELECT DATE_FORMAT(invoicedate, '%Y-%m-%d') as 'date', sum(total) as 'total' FROM vtiger_invoice WHERE invoicedate between ? and ? group by invoicedate;", array($fechaInicio, $fechaFin));
		}else{
			if ($fechaFin != null ) {
				$resultado = $adb->pquery("SELECT DATE_FORMAT(invoicedate, '%Y-%m-%d') as 'date', sum(total) as 'total' FROM vtiger_invoice WHERE invoicedate <= ? group by invoicedate", array($fechaFin));
			}elseif ($fechaInicio != null) {
				$resultado = $adb->pquery("SELECT DATE_FORMAT(invoicedate, '%Y-%m-%d') as 'date', sum(total) as 'total' FROM vtiger_invoice WHERE invoicedate >= ? group by invoicedate", array($fechaInicio));
			}else{
				$resultado = $adb->pquery("SELECT DATE_FORMAT(invoicedate, '%Y-%m-%d') as 'date', sum(total) as 'total' FROM vtiger_invoice group by invoicedate;");
			}
		}
		return $resultado;
	}
}
