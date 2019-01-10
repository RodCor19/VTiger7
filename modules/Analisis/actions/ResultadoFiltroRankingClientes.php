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
class Analisis_ResultadoFiltroRankingClientes_Action extends Vtiger_Action_Controller {

	function checkPermission(Vtiger_Request $request) {
		return;
	}

	public function process(Vtiger_Request $request) {
		global $adb;
		
		$fileAddToCampaign = file("archQueryAddToCampaign.txt");

		foreach ($fileAddToCampaign as $nun_linea => $linea) {
			$sql .= $linea;
		}

		$result = $adb->pquery($sql, array());

		while($row = $adb->fetch_array($result)){
			$contactsid .= $row['contactid'].";";
		}
		//$log->debug("Hola: ".$contactsid);
		$result = array('success'=>true, 'message'=>$contactsid);
		
		$response = new Vtiger_Response();
		$response->setResult($result);
		$response->emit();
	}
}