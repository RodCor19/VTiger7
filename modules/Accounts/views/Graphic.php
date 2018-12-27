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
		$diasConValores = null;
		$dias = null;

		$resultado = $adb->pquery("SELECT DATE_FORMAT(MIN(invoicedate), '%Y-%c') as 'date' FROM vtiger_invoice WHERE accountid = ?;", array($recordId));
		$stringFecha = $resultado->fields['date'].'-01';
		$datetime2 = new DateTime($stringFecha);
		$resultado = $adb->pquery("SELECT DATE_FORMAT(MAX(invoicedate), '%Y-%c') as 'date' FROM vtiger_invoice WHERE accountid = ?;", array($recordId));
		$stringFecha = $resultado->fields['date'].'-01';
		$datetime1 = new DateTime($stringFecha);
		$datetime1->add(new DateInterval('P1M'));
		while ( $datetime1->diff($datetime2)->m != 0  || $datetime1->diff($datetime2)->y != 0){
			$dias[] =  $datetime2->format('Y-m-d');
			$datetime2->add(new DateInterval('P1M'));
		}
		$resultado = $adb->pquery("SELECT DATE_FORMAT(invoicedate, '%Y-%m') as 'date', sum(total) as 'total' FROM vtiger_invoice WHERE accountid = ? group by DATE_FORMAT(invoicedate, '%Y-%c');", array($recordId));
		if ($resultado && $adb->num_rows($resultado) > 0) {
			foreach ($resultado as $value) {
				$diasConValores[] = $value['date'].'-01';
				$tuplas[] = array($value['date'].'-01', doubleval($value['total']));
			}
			foreach ($dias as $value) {
				if (!in_array($value, $diasConValores)) {
					$tuplas[] = array($value, 0);
				}
			}
		}
		$viewer->assign('MODULE_NAME', $moduleName);
		$viewer->assign('valores', $tuplas);
		$viewer->assign('record', $recordId);
		$viewer->view('Grafica.tpl', $moduleName);
	}

}