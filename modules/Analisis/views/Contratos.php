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

class Analisis_Contratos_View extends Vtiger_Index_View {

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
		/*$respuesta = $this->getContratos($request);

		//var_dump($respuesta);
		$viewer->assign('DATA', $respuesta);*/
		
		//FILTROS
		$adb = PearDatabase::getInstance();
		
		
		//Familias
		$familias = array();
		$sql="SELECT DISTINCT lpfamilia FROM vtiger_lpfamilia order by lpfamilia asc";
		$result=$adb->query($sql);
		$no_of_rows=$adb->num_rows($result);
		if($no_of_rows!=0){
			while($row = $adb->fetch_array($result)){
			    $familias[] = $row['lpfamilia'];
			}
		}
		$viewer->assign('familias', $familias);

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

		//formas de pago
		$forma_pago = array();
		$sql="SELECT DISTINCT FPDescripcion as nombre, FPId as id FROM lp_ventas_rubro_fp  order by FPDescripcion asc";
		$result=$adb->query($sql);
		$no_of_rows=$adb->num_rows($result);
		if($no_of_rows!=0){
			while($row = $adb->fetch_array($result)){
			    $forma_pago[] = array($row['nombre'], $row["id"]);
			}
		}
		$viewer->assign('forma_pago', $forma_pago);

		//rubros
		$rubros = array();
		$sql="SELECT DISTINCT lprubro FROM vtiger_lprubro order by lprubro asc";
		$result=$adb->query($sql);
		$no_of_rows=$adb->num_rows($result);
		if($no_of_rows!=0){
			while($row = $adb->fetch_array($result)){
			    $rubros[] = $row['lprubro'];
			}
		}
		$viewer->assign('rubros', $rubros);
		
		//localizacion
		$localizacion = array();
		$sql="SELECT DISTINCT lplocalizacion FROM vtiger_lplocalizacion order by lplocalizacion asc";
		$result=$adb->query($sql);
		$no_of_rows=$adb->num_rows($result);
		if($no_of_rows!=0){
			while($row = $adb->fetch_array($result)){
			    $localizacion[] = $row['lplocalizacion'];
			}
		}
		$viewer->assign('localizacion', $localizacion);
		//adheridos
		$adheridos = array('Beneficios','No Beneficios');
		$viewer->assign('adheridos', $adheridos);


		
		$date = strtotime( date('Y-m-d')." -2 months");
		$fecha_hasta=date('t-m-Y',$date);
		$date = strtotime( date('Y-m-01')." -13 months");
		$fecha_desde=date("d-m-Y", $date);
		$date_range=$fecha_desde.",".$fecha_hasta;
		$viewer->assign('date_range', $date_range);
		$viewer->view('ContratosView.tpl', $request->getModule());
	}
	
	/*
	 * Function to get the calendar settings view
	 */
	public function getAjax(Vtiger_Request $request){
		
		
		//$respuesta=$this->getData($request);
		//$moduleModel = Vtiger_Module_Model::getInstance("Analisis");
		/*$respuesta = $this->getContratos($request);

		echo  json_encode($respuesta);*/
		
	}

	
}