<?php
class Analisis_vmensuales_View extends Vtiger_View_Controller {
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
				//agrega valores mensuales
				$diasConValores[] = $value['date'].'-01';
				$tuplas[] = array($value['date'].'-01', doubleval($value['total']));
			}
			//ingresa valores 0 a los meses faltantes
			foreach ($dias as $value) {
				if (!in_array($value, $diasConValores)) {
					$tuplas[] = array($value, 0);
				}
			}
			
			$viewer->assign('itemsLabels', "%m\\'%Y");
			$viewer->assign('label', vtranslate("LBL_MONTHS_TYPE", 'Accounts'));
		}
		$viewer->assign('valores', $tuplas);
		$viewer->assign('intervalo', $intervalo);
		$viewer->assign('modo', 'Mensual');
		$viewer->assign('SCRIPTS', $this->getHeaderScripts($request));
	  	$viewer->assign('STYLES', $this->getHeaderCss($request));
		$viewer->view('Grafica.tpl', $moduleName);
	}

	//retorna valores mensuales
	function consulta(){
		$resultado = null;
		global $adb;
		$resultado = $adb->pquery("SELECT DATE_FORMAT(invoicedate, '%Y-%m') as 'date', sum(total) as 'total' FROM vtiger_invoice group by DATE_FORMAT(invoicedate, '%Y-%c');");
		return $resultado;
	}

	function arrayDias($inicio, $fin){
		$datetimeFin = new DateTime($fin);
		$datetimeInicio = new DateTime($inicio);
		$dias = null;
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
		return $dias;
	}

	function intervalo($inicio, $fin){
		$datetimeFin = new DateTime($fin);
		$datetimeInicio = new DateTime($inicio);
		$intervalo = 0;
		// 2592000000 son los milisegundos en 30 dias
		if ($datetimeFin->diff($datetimeInicio)->m < 7 && $datetimeFin->diff($datetimeInicio)->y < 1)
			$intervalo = 2592000000;
			//si la diferencia es mayor a 6 meses los intervalos se calculan como
			// milisegundos de 30 dias * 2(60 dias) * la diferencia + 1 de años 
		else
			$intervalo = 2592000000*2*($datetimeFin->diff($datetimeInicio)->y + 1);
		return $intervalo;
	}

	//retorna la primera fecha de factura de la cuenta
	function fechaMinima(){
		$resultado = null;
		global $adb;
		$resultado = $adb->pquery("SELECT DATE_FORMAT(MIN(invoicedate), '%Y-%m-%d') as 'date' FROM vtiger_invoice");
		if($adb->num_rows($resultado) > 0){
			return $resultado->fields['date'];
		}else{
			return null;
		}
	}
	//retorna la ultima fecha de factura de la cuenta
	function fechaMaxima(){
		$resultado = null;
		global $adb;
		$resultado = $adb->pquery("SELECT DATE_FORMAT(MAX(invoicedate), '%Y-%m-%d') as 'date' FROM vtiger_invoice");
		if($adb->num_rows($resultado) > 0){
			return $resultado->fields['date'];
		}else{
			return null;
		}
	}
}
