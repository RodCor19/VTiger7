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
		$intervaloDia = false;

		if ($finalDate != null && $initialDate != null) {
			$datetime1 = new DateTime($finalDate);
			$datetime2 = new DateTime($initialDate);
			if ($datetime1->diff($datetime2)->d < 30) {
				$intervaloDia = true;
				$resultado = $adb->pquery("SELECT DATE_FORMAT(invoicedate, '%Y-%c-%d') as 'date', sum(total) as 'total' FROM vtiger_invoice WHERE accountid = ? and invoicedate between ? and ? group by invoicedate;", array($recordId, $initialDate, $finalDate));
			}else{
				$resultado = $adb->pquery("SELECT DATE_FORMAT(invoicedate, '%Y-%c') as 'date', sum(total) as 'total' FROM vtiger_invoice WHERE accountid = ? and invoicedate between ? and ? group by DATE_FORMAT(invoicedate, '%Y-%c');", array($recordId, $initialDate, $finalDate));
			}
		}else{
			if ($finalDate != null ) {
				$resultado = $adb->pquery("SELECT DATE_FORMAT(invoicedate, '%Y-%c') as 'date', sum(total) as 'total' FROM vtiger_invoice WHERE accountid = ? and invoicedate <= ? group by DATE_FORMAT(invoicedate, '%Y-%c')", array($recordId, $finalDate));
			}elseif ($initialDate != null) {
				$resultado = $adb->pquery("SELECT DATE_FORMAT(invoicedate, '%Y-%c') as 'date', sum(total) as 'total' FROM vtiger_invoice WHERE accountid = ? and invoicedate >= ? group by DATE_FORMAT(invoicedate, '%Y-%c')", array($recordId, $initialDate));
			}else{
				$resultado = $adb->pquery("SELECT DATE_FORMAT(invoicedate, '%Y-%c') as 'date', sum(total) as 'total' FROM vtiger_invoice WHERE accountid = ? group by DATE_FORMAT(invoicedate, '%Y-%c');", array($recordId));
			}
		}
		if ($resultado && $adb->num_rows($resultado) > 0) {
			foreach ($resultado as $value) {
				if ($intervaloDia) {
					$tuplas[] = array($value['date'], doubleval($value['total']));
				} else {
					$tuplas[] = array($value['date'].'-01', doubleval($value['total']));
				}
			}
			if ($intervaloDia) {
				$result['success'] = true;
				// 172800000 son los milisegundos en 2 dias
				$result['data'] = array('valores' => $tuplas, 'label' => vtranslate("LBL_DAYS_TYPE", $moduleName), 'itemsLabels' => '%d-%m', 'intervalo' => 172800000);
			} else {
				$result['success'] = true;
				// 2592000000 son los milisegundos en 30 dias
				$result['data'] = array('valores' => $tuplas, 'label' => vtranslate("LBL_MONTHS_TYPE", $moduleName), 'itemsLabels' => '%m\'%Y', 'intervalo' => 2592000000);
			}
			
			
		}else{
			$result['success'] = false;
			$result['error'] = 'Error en la consulta';
		}

		$response = new Vtiger_Response();
		$response->setResult($result);
		$response->emit();
	}
}
