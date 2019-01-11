<?php
/* +**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * ***********************************************************************************/

class Accounts_List_View extends Vtiger_List_View {

	function process(Vtiger_Request $request) {
		$link = 'module='.$request->getModule().'&view='.$request->get('view').'&viewname'.$request->get('viewname').'&app='.$request->get('app');
		$viewer = $this->getViewer($request);
		$moduleName = $request->getModule();
		$moduleModel = Vtiger_Module_Model::getInstance($moduleName);
		$viewName = $request->get('viewname');
		if ($viewName) {
			$this->viewName = $viewName;
		}
		if ($moduleName =='Accounts' && ($viewName == 'vmensuales' || $viewName == 'vdiarias')) {
			$viewer->assign('VIEW', $request->get('view'));
			$viewer->assign('VIEWNAME', $viewName);
			$viewer->assign('SCRIPTS',$this->getHeaderScripts($request));
	  		$viewer->assign('STYLES',$this->getHeaderCss($request));
			$viewer->assign('RECORD_ACTIONS', $this->getRecordActionsFromModule($moduleModel));
			$viewer->assign('MODULE_NAME', $moduleName);
			$viewer->view('ListViewContentsGrafica.tpl', $moduleName);
		}else{
			$this->initializeListViewContents($request, $viewer);
			$this->assignCustomViews($request, $viewer);
			$viewer->assign('VIEW', $request->get('view'));
			$viewer->assign('MODULE_MODEL', $moduleModel);
			$viewer->assign('RECORD_ACTIONS', $this->getRecordActionsFromModule($moduleModel));
			$viewer->assign('CURRENT_USER_MODEL', Users_Record_Model::getCurrentUserModel());

			$viewer->assign('IS_CREATE_PERMITTED', true);
			$viewer->assign('IS_MODULE_EDITABLE', false);
			$viewer->assign('IS_MODULE_DELETABLE', false);
			$viewer->view('ListViewContents.tpl', $moduleName);
		}
	}

	public function getRecordActionsFromModule($moduleModel) {
		$recordActions = array();
		$recordActions['edit'] = true;
		$recordActions['delete'] = true;
		return $recordActions;
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
}
