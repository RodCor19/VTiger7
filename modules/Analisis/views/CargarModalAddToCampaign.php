<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

class Analisis_CargarModalAddToCampaign_View extends Vtiger_IndexAjax_View {

	public function checkPermission(Vtiger_Request $request) {
	}

	public function process(Vtiger_Request $request) {
		global $log, $adb;
		//$log->debug("mauro");
		$moduleName = $request->getModule();
		//global $log;
		//$log->debug("record: ".$request->get("record"));
		//$rec = $request->get("record");

		/*$result = $adb->pquery("select valmonto from vtiger_valessac where valessacid = $rec", array());
		$monto = $adb->query_result($result, 0, 'valmonto');
		$log->debug("Monto mauro: ".$monto);*/
		$sql = "SELECT DISTINCT crm.crmid, cam.campaignname FROM vtiger_crmentity crm INNER JOIN vtiger_campaign cam ON crm.label = cam.campaignname WHERE cam.campaignstatus != 'Completed' AND crm.deleted = 0 AND crm.setype = 'Campaigns' ORDER BY cam.campaignname";
		$result = $adb->query($sql);
		//$no_of_rows=$adb->num_rows($result);

		while($row = $adb->fetch_array($result)){
			$campaigns[] = array("campaignname" => $row['campaignname'],
								"crmid" => $row['crmid']);
		}
		//$log->debug("Log mauro: ".var_dump($campaigns))
		$viewer = $this->getViewer($request);
		$viewer->assign('MODULE', $moduleName);
		$viewer->assign('CAMPAIGNS', $campaigns);
		$viewer->assign('SCRIPTS', $this->getHeaderScripts($request));
		echo $viewer->view('CargarModalAddToCampaign.tpl',$moduleName,true);
	}
	
	
	public function getHeaderScripts(Vtiger_Request $request) {
		
		$moduleName = $request->getModule();
		
		$jsFileNames = array(
			"modules.Vtiger.resources.Edit"
		);

		$jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
		return $jsScriptInstances;
	}
        
    public function validateRequest(Vtiger_Request $request) { 
        $request->validateWriteAccess(); 
    } 
}