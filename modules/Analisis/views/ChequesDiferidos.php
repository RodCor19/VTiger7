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

class Analisis_ChequesDiferidos_View extends Vtiger_Index_View {

	public function preProcess(Vtiger_Request $request, $display = true) {
		$viewer = $this->getViewer($request);
		$viewer->assign('MODULE_NAME', $request->getModule());

		parent::preProcess($request, false);
		if($display) {
			$this->preProcessDisplay($request);
		}
	}

	protected function preProcessTplName(Vtiger_Request $request) {
		return 'ContratosViewPreProcess.tpl';
	}

	
	public function getHeaderScripts(Vtiger_Request $request) {
		$headerScriptInstances = parent::getHeaderScripts($request);
		$moduleName = $request->getModule();

		$jsFileNames = array(
			'~/libraries/jquery/gridster/jquery.gridster.min.js',
			"modules.Analisis.resources.AnalisisView",
			'modules.Vtiger.resources.DashBoard',
			'modules.Vtiger.resources.dashboards.Widget',
			
			//"~/libraries/pivot-js/lib/jquery.min.js",
			"~/libraries/pivot-js/lib/javascripts/accounting.min.js",
			"~/libraries/pivot-js/lib/javascripts/jquery.dataTables.js",
			"~/libraries/pivot-js/lib/javascripts/dataTables.rangeFilter.js",
			"~/libraries/pivot-js/lib/javascripts/dataTables.bootstrap.js",
			"~/libraries/pivot-js/pivot.js",
			"~/libraries/pivot-js/jquery_pivot.js",
			'~/libraries\jquery/jquery-ui/third-party/jQuery-UI-Date-Range-Picker/js/daterangepicker.jQuery.js',
			'~/libraries/jquery/funciones.js',


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
			'~/libraries/pivot-js/lib/css/pivot.css',
			'~/libraries/jquery/jqplot/jquery.jqplot.min.css',
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

		$local= $request->get('local');
		$viewer->assign('idLocal', $local);
		//$respuesta=$this->getData($request);
		
		//$moduleModel = Vtiger_Module_Model::getInstance("Analisis");
		/*$respuesta = $this->getChequesDiferidos($request);

		//var_dump($respuesta);
		$viewer->assign('DATA', $respuesta);*/
		
		//FILTROS
		$adb = PearDatabase::getInstance();
		
		
		
/*M E*/		//Locales
/*M E*/		$locales = array();
/*M E*/		$sql="SELECT DISTINCT accountname as nombre, accountid as id FROM vtiger_account 
					inner join vtiger_crmentity on crmid=accountid 
					where  deleted=0 order by accountname asc";
/*M E*/		$result=$adb->query($sql);
/*M E*/		$no_of_rows=$adb->num_rows($result);
/*M E*/		if($no_of_rows!=0){
/*M E*/			while($row = $adb->fetch_array($result)){
/*M E*/			    $locales[] = array($row['nombre'], $row["id"]);
/*M E*/			}
/*M E*/		}
/*M E*/		$viewer->assign('locales', $locales);
		//Cuentas
			$cuentas = array();
			$sql="SELECT DISTINCT cod_cta, nom_cta FROM lp_saldoscontables where cod_cta in(11307,11308,11312,11319,12105) order by nom_cta";
			$result=$adb->query($sql);
			$no_of_rows=$adb->num_rows($result);
			if($no_of_rows!=0){
				while($row = $adb->fetch_array($result)){
				    $cuentas[] = array($row['nom_cta'], $row["cod_cta"]);
				}
			}
			$viewer->assign('cuentas', $cuentas);

		
		$date = strtotime( date('Y-m-d')." +1 year");
		$fecha_hasta=date('t-m-Y',$date);
		$date = strtotime( date('Y-m-01')." -12 month");
		$fecha_desde=date("d-m-Y", $date);
		$date_range=$fecha_desde.",".$fecha_hasta;
		$viewer->assign('date_range', $date_range);
		$viewer->view('ChequesDiferidosView.tpl', $request->getModule());
	}
	
	/*
	 * Function to get the calendar settings view
	 */
	public function getAjax(Vtiger_Request $request){
		
		
		//$respuesta=$this->getData($request);
		//$moduleModel = Vtiger_Module_Model::getInstance("Analisis");
		/*$respuesta = $this->getChequesDiferidos($request);

		echo  json_encode($respuesta);*/
		
	}

	
}