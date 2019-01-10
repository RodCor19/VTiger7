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

class Analisis_RankingClientes_View extends Vtiger_Index_View {

	public function preProcess(Vtiger_Request $request, $display = true) {
		$viewer = $this->getViewer($request);
		$viewer->assign('MODULE_NAME', $request->getModule());

		parent::preProcess($request, false);
		if($display) {
			$this->preProcessDisplay($request);
		}
	}

	protected function preProcessTplName(Vtiger_Request $request) {
		return 'RankingClientesViewPreProcess.tpl';
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
		$respuesta=array();
		//var_dump($respuesta);
		$viewer->assign('DATA', $respuesta);
		
		//FILTROS
		$adb = PearDatabase::getInstance();
		//TIpo de actividad
		$actividades = array();
		$sql="SELECT DISTINCT lpactipo FROM vtiger_lpactipo";
		$result=$adb->query($sql);
		$no_of_rows=$adb->num_rows($result);
		if($no_of_rows!=0){
			while($row = $adb->fetch_array($result)){
			    $actividades[] = $row['lpactipo'];
			}
		}
		$viewer->assign('actividades', $actividades);

		//Rango de edad
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
		
		//Sexo
		$sexo = array('Femenino','Masculino');
		$viewer->assign('sexo', $sexo);
		
		//Canal Activo
		$canales = array('Mall','EShop','Redes','App Smartphone');
		$canales[]="Sin Dato";
		$viewer->assign('canales', $canales);
		//Estatuto
		$estatutos = array('En Baja','Fiel','Inactivo','Nuevo');
		$estatutos[]="Sin Dato";
		$viewer->assign('estatutos', $estatutos);

		//Programas
				$programas = array("Comprador Frecuente","Los 1",
							"Programas Gaviotas","Funcionario","WTC Member",
							"Gaviotas Electronicas","Gaviotas Efectivo","Tarjeta Beneficios");
		$programas[]="Sin Dato";
		$viewer->assign('programas', $programas);


		$fecha_hasta=date('d-m-Y');
		$date = strtotime( date('Y-m-01')." -11 months");
		$fecha_desde=date("d-m-Y", $date);
		$date_range=$fecha_desde.",".$fecha_hasta;
		$viewer->assign('date_range', $date_range);
		$viewer->view('RankingClientesView.tpl', $request->getModule());
	}
	
	/*
	 * Function to get the calendar settings view
	 */
	public function getAjax(Vtiger_Request $request){
		
		
		//$respuesta=$this->getData($request);
		$moduleModel = Vtiger_Module_Model::getInstance("Analisis");
		$respuesta = $moduleModel->getRankingClientes($request);

		echo  json_encode($respuesta);
		
	}
	
	
}