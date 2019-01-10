<?php
class Analisis_vdiarias_View extends Vtiger_View_Controller {
	public function checkPermission(Vtiger_Request $request) {
		return true;
	}

	public function getHeaderScripts(Vtiger_Request $request) {
		$headerScriptInstances = parent::getHeaderScripts($request);
		$moduleName = $request->getModule();

		$jsFileNames = array(
			"~/libraries/jquery/jqplot/jquery.jqplot.min.js",
			"~/libraries/jquery/jqplot/plugins/jqplot.dateAxisRenderer.js",
			"~/libraries/jquery/jqplot/plugins/jqplot.canvasAxisRenderer.js",
			"~/libraries/jquery/jqplot/plugins/jqplot.highlighter.min.js",
			"~/libraries/jquery/jqplot/plugins/jqplot.cursor.min.js",
			"~/libraries/jquery/jqplot/plugins/jqplot.pointLabels.min.js",
		);

		$jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
		$headerScriptInstances = array_merge($headerScriptInstances, $jsScriptInstances);
		return $headerScriptInstances;
	}

	public function getHeaderCss(Vtiger_Request $request) {
		$headerCssInstances = parent::getHeaderCss($request);
		$cssFileNames = array(
			"~/libraries/jquery/jqplot/jquery.jqplot.min.css",
		);
		$cssInstances = $this->checkAndConvertCssStyles($cssFileNames);
		$headerCssInstances = array_merge($headerCssInstances, $cssInstances);
		return $headerCssInstances;
	}

	public function process(Vtiger_Request $request) {
		global $adb;
		$viewer = $this->getViewer($request);
		$moduleName = $request->getModule();
		//inicializar variables
		$tuplas = null;
		$diasConValores = null;
		$dias = null;
		$resultado = null;
		$intervalo = 0;
		//obtenerfechas
		$fechaFin = $this->fechaMaxima();
		$fechaInicio = $this->fechaMinima();
		//obtener array de dias, el intervalo parra que se vea mejor y los valores
		if ($fechaFin != null && $fechaInicio != null) {
			$intervalo = $this->intervalo($fechaInicio,$fechaFin);
			$dias = $this->arrayDias($fechaInicio,$fechaFin);
			$resultado = $this->consulta();
		}
		
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
			//cambia lables de los tick y del axis dependiendo si es dia o mes
			$viewer->assign('itemsLabels', '%d-%m');
			$viewer->assign('label', vtranslate("LBL_DAYS_TYPE", 'Accounts'));
		}
		$datetimeFin = new DateTime($fechaFin);
		$datetimeInicio = new DateTime($fechaInicio);
		$viewer->assign('valores', $tuplas);
		$viewer->assign('intervalo', $intervalo);
		$viewer->assign('modo', 'Diario');
		$viewer->assign('SCRIPTS',$this->getHeaderScripts($request));
	  	$viewer->assign('STYLES',$this->getHeaderCss($request));
		$viewer->view('Grafica.tpl', $moduleName);
	}

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
	function consulta(){
		$resultado = null;
		global $adb;
		$resultado = $adb->pquery("SELECT DATE_FORMAT(invoicedate, '%Y-%m-%d') as 'date', sum(total) as 'total' FROM vtiger_invoice group by invoicedate");
		return $resultado;
	}

}