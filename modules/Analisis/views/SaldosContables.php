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

class Analisis_SaldosContables_View extends Vtiger_Index_View {

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
		global $log;
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
		
		$viewer->assign('anios', $this->getAnios());
		$viewer->assign('anioActual', date("Y"));
		$viewer->assign('meses', $this->getMeses());
		$viewer->assign('mesActual', date("n"));
		
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

		
		$date = strtotime( date('Y-m-d')." +1 year");
		$fecha_hasta=date('t-m-Y',$date);
		$date = strtotime( date('Y-01-01')." -1 year");
		$fecha_desde=date("d-m-Y", $date);
		$date_range=$fecha_desde.",".$fecha_hasta;
		$viewer->assign('date_range', $date_range);
		$viewer->view('SaldosContablesView.tpl', $request->getModule());
	}

	public function getAnios(){
		$arrayAnios = array();
		$anioActual = intval(date("Y"));
		while ($anioActual != 1999){
			$arrayAnios[] = $anioActual;
			$anioActual -= 1;
		}
		return $arrayAnios;
	}

	public function getMeses(){
		$arrayMeses = array();
		$arrayMeses[] = array('num' => '1', 'nombre' => 'Enero');
		$arrayMeses[] = array('num' => '2', 'nombre' => 'Febrero');
		$arrayMeses[] = array('num' => '3', 'nombre' => 'Marzo');
		$arrayMeses[] = array('num' => '4', 'nombre' => 'Abril');
		$arrayMeses[] = array('num' => '5', 'nombre' => 'Mayo');
		$arrayMeses[] = array('num' => '6', 'nombre' => 'Junio');
		$arrayMeses[] = array('num' => '7', 'nombre' => 'Julio');
		$arrayMeses[] = array('num' => '8', 'nombre' => 'Agosto');
		$arrayMeses[] = array('num' => '9', 'nombre' => 'Septiembre');
		$arrayMeses[] = array('num' => '10', 'nombre' => 'Octubre');
		$arrayMeses[] = array('num' => '11', 'nombre' => 'Noviembre');
		$arrayMeses[] = array('num' => '12', 'nombre' => 'Diciembre');
		return $arrayMeses;
	}
	
	/*
	 * Function to get the calendar settings view
	 */
	public function getAjax(Vtiger_Request $request){
		
		
		//$respuesta=$this->getData($request);
		//$moduleModel = Vtiger_Module_Model::getInstance("Analisis");
		/*$respuesta = $this->getMorosidad($request);

		echo  json_encode($respuesta);*/
		
	}

	
}