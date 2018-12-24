<?php

class Accounts_DataInvoice_Action extends Vtiger_Action_Controller {

	function checkPermission(Vtiger_Request $request) {
		return;
	}

	public function process(Vtiger_Request $request) {
		$moduleName = $request->getModule();
		$initialDate = $request->get('inicio');
		$finalDate = $request->get('fin');
		$recordId = $request->get('record');
		$tuplas = null;
		global $adb;
		$result = array();
		$resultado = null;
		if ($finalDate != null && $initialDate != null) {
			$resultado = $adb->pquery("SELECT DATE_FORMAT(invoicedate, '%Y-%c-%d') as 'date', sum(total) as 'total' FROM vtiger_invoice WHERE accountid = ? and invoicedate between ? and ? group by invoicedate;", array($recordId, $initialDate, $finalDate));
		}else{
			if ($finalDate != null ) {
			$resultado = $adb->pquery("SELECT DATE_FORMAT(invoicedate, '%Y-%c-%d') as 'date', sum(total) as 'total' FROM vtiger_invoice WHERE accountid = ? and invoicedate <= ? group by invoicedate;", array($recordId, $finalDate));
			}elseif ($initialDate != null) {
				$resultado = $adb->pquery("SELECT DATE_FORMAT(invoicedate, '%Y-%c-%d') as 'date', sum(total) as 'total' FROM vtiger_invoice WHERE accountid = ? and invoicedate >= ? group by invoicedate;", array($recordId, $initialDate));
			}
		}
		if ($resultado && $adb->num_rows($resultado) > 0) {
			foreach ($resultado as $value) {
				$tuplas[] = array($value['date'], doubleval($value['total']));
			}
			$result['success'] = true;
			$result['data'] = array('valores' => $tuplas, 'label' => vtranslate("LBL_MONTHS_TYPE", $moduleName), 'itemsLabels' => '%m\'%Y');
		}else{
			$result['success'] = false;
			$result['error'] = 'Error en la consulta';
		}

		$response = new Vtiger_Response();
		$response->setResult($result);
		$response->emit();
	}
}
