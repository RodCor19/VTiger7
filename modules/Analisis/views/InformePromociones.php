<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/
error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set("display_errors", 1);

class Analisis_InformePromociones_View extends Vtiger_Index_View {

	public function preProcess(Vtiger_Request $request, $display = true) {
		$viewer = $this->getViewer($request);
		$viewer->assign('MODULE_NAME', $request->getModule());

		parent::preProcess($request, false);
		if($display) {
			$this->preProcessDisplay($request);
		}
	}

	protected function preProcessTplName(Vtiger_Request $request) {
		return 'InformePromocionesViewPreProcess.tpl';
	}

	
	public function getHeaderScripts(Vtiger_Request $request) {
		$headerScriptInstances = parent::getHeaderScripts($request);
		$moduleName = $request->getModule();

		$jsFileNames = array(
			'~/libraries/jquery/gridster/jquery.gridster.min.js',
			"modules.Analisis.resources.AnalisisView",
			'modules.Vtiger.resources.DashBoard',
			'modules.Vtiger.resources.dashboards.Widget',
			'~/libraries\jquery/jquery-ui/third-party/jQuery-UI-Date-Range-Picker/js/daterangepicker.jQuery.js',
			//"~/libraries/pivot-js/lib/jquery.min.js",
			"~/libraries/pivot-js/lib/javascripts/accounting.min.js",
			"~/libraries/pivot-js/lib/javascripts/jquery.dataTables.js",
			"~/libraries/pivot-js/lib/javascripts/dataTables.rangeFilter.js",
			"~/libraries/pivot-js/lib/javascripts/dataTables.bootstrap.js",
			"~/libraries/pivot-js/pivot.js",
			"~/libraries/pivot-js/jquery_pivot.js",
			'~/libraries/jquery/funciones.js',
			'~/libraries/jquery/excellentexport.min.js',

			
		);

		$jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
		$headerScriptInstances = array_merge($headerScriptInstances, $jsScriptInstances);
		return $headerScriptInstances;
	}

	/**
	 * Function to get the list of Css models to be included
	 * @param Vtiger_Request $request
	 * @return <Array> - List of Vtiger_CssScript_Model instances
	 */
	public function getHeaderCss(Vtiger_Request $request) {
		$parentHeaderCssScriptInstances = parent::getHeaderCss($request);

		$headerCss = array(
			'~/libraries/jquery/gridster/jquery.gridster.min.css',
			'~/libraries/pivot-js/lib/css/jquery.dataTables.css',
			'~/libraries/pivot-js/lib/css/dataTables.tableTools.css',
			'~/libraries/pivot-js/lib/css/demo_table.css'
		);
		$cssScripts = $this->checkAndConvertCssStyles($headerCss);
		$headerCssScriptInstances = array_merge($parentHeaderCssScriptInstances , $cssScripts);
		return $headerCssScriptInstances;
	}

	public function process(Vtiger_Request $request) {
		$mode = $request->getMode();
		$currentUser = Users_Record_Model::getCurrentUserModel();
		$viewer = $this->getViewer($request);
		$currentUserModel = Users_Record_Model::getCurrentUserModel();
		if($request->getMode() == 'Ajax'){
			return $this->getAjax($request);
		}
		$viewer->assign('CURRENT_USER', $currentUserModel);

		//$respuesta=$this->getData($request);
		
		$moduleModel = Vtiger_Module_Model::getInstance("Analisis");
		//$respuesta = $moduleModel->getRankingClientes($request);
		//$respuesta=array();
		//var_dump($respuesta);
		//$viewer->assign('DATA', $respuesta);
		
		//FILTROS
		$adb = PearDatabase::getInstance();
		//TIpo de actividad
		
		$edades = array();
		$sql="SELECT DISTINCT rango_edades FROM vtiger_contactdetails order by rango_edades asc";
		$result=$adb->query($sql);
		$no_of_rows=$adb->num_rows($result);
		if($no_of_rows!=0){
			while($row = $adb->fetch_array($result)){
			    $edades[] = $row['rango_edades'];
			}
		}
		$viewer->assign('edades', $edades);	

		$promociones = array();
		$sql2 = "SELECT DISTINCT bcpromocion FROM vtiger_boletascanjeadas ORDER BY bcpromocion";
		$result2 = $adb->pquery($sql2, array());
		foreach ($result2 as $r) {
			$promociones[] = $r['bcpromocion'];
		}

		$viewer->assign('promociones', $promociones);	
		
		$viewer->view('InformePromocionesView.tpl', $request->getModule());
	}
	
	/*
	 * Function to get the calendar settings view
	 */
	public function getAjax(Vtiger_Request $request){
		
		
		//$respuesta=$this->getData($request);
		/*$moduleModel = Vtiger_Module_Model::getInstance("Analisis");
		$respuesta = $moduleModel->getRankingClientes($request);*/
		global $log;
		$adb = PearDatabase::getInstance();

		$promocion = $request->get('promocion');
		$log->debug("aca5");
		$log->debug('aca1: ' . $promocion);
		$log->debug("acaaaa Mauro");
		$sql = "SELECT c.`rango_edades`, COUNT(*) cantidad, FORMAT(SUM(bcprecio), 0) precio FROM vtiger_boletascanjeadas b INNER JOIN vtiger_contactdetails c ON c.contactid=b.bccontacto INNER JOIN vtiger_crmentity e ON b.boletascanjeadasid=e.crmid AND deleted=0 LEFT JOIN vtiger_account a ON b.bclocal=a.accountid WHERE bcpromocion='$promocion' GROUP BY c.`rango_edades`";
		$result = $adb->pquery($sql, array());
		$num_rows = $adb->num_rows($result);
		$totalCantidad = 0;
		$totalImporte = 0;
		if($num_rows > 0){
			foreach ($result as $r) {
				$totalCantidad = $totalCantidad + $r['cantidad'];
				$totalImporte = $totalImporte +  str_replace(",", "", $r['precio']);
				
			}
			$log->debug("Mauro total importe: " . $totalImporte);
			//$totalImporte = (int) $totalImporte;
			foreach ($result as $add) {
				$porcentajeCantidad = ($add['cantidad'] * 100) / $totalCantidad;
				$precio = str_replace(",", "", $add['precio']);
				$porcentajeImporte = ($precio * 100) / $totalImporte;
				$ret[] = array("edades" => $add['rango_edades'], 
							"cantidad" => $add['cantidad'], 
							"porcentajeCantidad" => round($porcentajeCantidad, 3), 
							"importe" => str_replace(",", ".", $add['precio']), 
							"porcentajeImporte" => round($porcentajeImporte, 3));
			}
			
		}	

		$sql2 = "SELECT b.bclocal, a.accountname, COUNT(b.bcfactura) cantidad FROM vtiger_boletascanjeadas b INNER JOIN vtiger_contactdetails c ON c.contactid=b.bccontacto INNER JOIN vtiger_crmentity e ON b.boletascanjeadasid=e.crmid AND deleted=0 INNER JOIN vtiger_account a ON b.bclocal=a.accountid WHERE bcpromocion='$promocion' GROUP BY b.bclocal ORDER BY cantidad DESC";

		$result2 = $adb->pquery($sql2, array());
		$num_rows2 = $adb->num_rows($result2);
		if($num_rows2 > 0){
			foreach ($result2 as $add2) {
				$ret2[] = array("local" => $add2['accountname'], 
							"cantidad" => $add2['cantidad']);
			}
			
		}

		$data2["data"] = $ret;
		$data2["locales"] = $ret2;
		echo json_encode($data2);	
		
	}
	
	
}