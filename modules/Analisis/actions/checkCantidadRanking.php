<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

class Analisis_CheckCantidadRanking_Action extends Vtiger_Action_Controller {

	function checkPermission(Vtiger_Request $request) {
		return;
	}

	public function process(Vtiger_Request $request) {
		global $adb, $log;
		/*$moduleName = $request->getModule();
		$accountName = $request->get('accountname');
		$record = $request->get('record');

		if ($record) {
			$recordModel = Vtiger_Record_Model::getInstanceById($record, $moduleName);
		} else {
			$recordModel = Vtiger_Record_Model::getCleanInstance($moduleName);
		}*/

		//$recordModel->set('accountname', $accountName);

		$fileAddToCampaign = file("archQueryAddToCampaign.txt");

		foreach ($fileAddToCampaign as $nun_linea => $linea) {
			$sql .= $linea;
		}

		$result = $adb->pquery($sql, array());

		$num_rows = $adb->num_rows($result);

		if ($num_rows == 0) {
			$result = array('success'=>false);
		} else {
			$result = array('success'=>true, 'message'=>vtranslate('LBL_DUPLICATES_EXIST', $moduleName));
		}
		$response = new Vtiger_Response();
		$response->setResult($result);
		$response->emit();
	}
}