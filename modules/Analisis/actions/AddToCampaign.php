<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/
set_time_limit(0);
class Analisis_AddToCampaign_Action extends Vtiger_Action_Controller {

	function checkPermission(Vtiger_Request $request) {
		return;
	}

	public function process(Vtiger_Request $request) {
		global $adb, $log;
		
		$contactsId = $request->get('contactsId');
		$crmid = $request->get('crmid');
		$log->debug("Mauro llegué aca".$crmid);
		$contacts = explode(";", $contactsId);
		$cantContacts = substr_count($contactsId, ";");		
		$log->debug("hola ".$cantContacts);
		for($i = 0; $i < $cantContacts; $i++) {
			$result = $adb->pquery("select * from vtiger_campaigncontrel where campaignid = ? and contactid = ?", array($crmid, $contacts[$i]));
			if($adb->num_rows($result) == 0){
				$sql = "INSERT INTO vtiger_campaigncontrel VALUES(".$crmid.", ".$contacts[$i].", 1, null, null, null, null);";
				$adb->pquery($sql, array());
			}
		}

		$result = array('success'=>true);
		$response = new Vtiger_Response();
		$response->setResult($result);
		$response->emit();
	}
}