<?php
class Accounts_Graphic_View extends Vtiger_View_Controller {
	public function checkPermission(Vtiger_Request $request) {
		return true;
	}

	public function process(Vtiger_Request $request) {
		$viewer = $this->getViewer($request);
		$recordId = $request->get('id');
		$moduleName = $request->getModule();
		global $adb;
		$tuplas = null;

		$result = $adb->pquery("SELECT DATE_FORMAT(invoicedate, '%Y-%c') as 'date', sum(total) as 'total' FROM vtiger_invoice WHERE accountid = ? group by DATE_FORMAT(invoicedate, '%Y-%c');", array($recordId));
		if ($result && $adb->num_rows($result) > 0) {
			foreach ($result as $value) {
				$tuplas[] = array($value['date'].'-01', doubleval($value['total']));
			}
		}
		$viewer->assign('MODULE_NAME', $moduleName);
		$viewer->assign('valores', $tuplas);
		$viewer->assign('record', $recordId);
		$viewer->view('Grafica.tpl', $moduleName);
	}

}