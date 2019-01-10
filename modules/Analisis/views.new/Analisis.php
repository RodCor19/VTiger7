<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

class Analisis_Analisis_View extends Vtiger_Index_View {

	public function preProcess(Vtiger_Request $request, $display = true) {
		$viewer = $this->getViewer($request);
		$viewer->assign('MODULE_NAME', $request->getModule());

		parent::preProcess($request, false);
		if($display) {
			$this->preProcessDisplay($request);
		}
	}

	protected function preProcessTplName(Vtiger_Request $request) {
		return 'AnalisisViewPreProcess.tpl';
	}

	public function getHeaderScripts(Vtiger_Request $request) {
		$headerScriptInstances = parent::getHeaderScripts($request);
		$jsFileNames = array(
			"modules.Analisis.resources.AnalisisView",
			//"~/libraries/pivottable/ext/jquery-1.8.3.min.js",
			"~/libraries/pivottable/ext/jquery-ui-1.9.2.custom.min.js",
			"~/libraries/pivottable/dist/pivot.js",
			'~/libraries/jquery/funciones.js',
		);

		$jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
		$headerScriptInstances = array_merge($headerScriptInstances, $jsScriptInstances);
		return $headerScriptInstances;
	}

	public function getHeaderCss(Vtiger_Request $request) {
		$headerCssInstances = parent::getHeaderCss($request);


		$cssFileNames = array(
			'~/libraries/fullcalendar/fullcalendar.css',
			'~/libraries/fullcalendar/fullcalendar-bootstrap.css',
			'~/libraries/pivottable/dist/pivot.css'
		);
		$cssInstances = $this->checkAndConvertCssStyles($cssFileNames);
		$headerCssInstances = array_merge($headerCssInstances, $cssInstances);

		return $headerCssInstances;
	}

	public function process(Vtiger_Request $request) {
		$mode = $request->getMode();
		if($mode == 'settings'){
			$this->getAnalisisSettings($request);
		}
		$viewer = $this->getViewer($request);
		$currentUserModel = Users_Record_Model::getCurrentUserModel();
		if($request->getMode() == 'Settings'){
			return $this->getAnalisisSettings($request);
		}
		$viewer->assign('CURRENT_USER', $currentUserModel);


		$adb = PearDatabase::getInstance();

	 	$query="SELECT destinatario,firstname,lastname,mobile,cndocumento,asunto, DATE_FORMAT(fecha_error, '%d/%m/%Y') AS fecha_error,mod_nodum_por
					FROM vtiger_lp_errores_correos 
					INNER JOIN vtiger_contactdetails ON destinatario=email 
					WHERE fecha_error>=DATE_ADD(NOW(),INTERVAL -30 DAY)";

		/*select destinatario,firstname,lastname,lpdocumento,asunto,DATE_FORMAT(fecha_error, '%d/%m/%Y') as fecha_error,
		(select campaignname from vtiger_envioemails ee
		inner join vtiger_emakertemplates em on ee.lpeeplantilla=em.templatename 
		inner join vtiger_campaign c on c.campaignid = ee.lpcampania where em.subject like MID(ec.asunto,0,50) limit 1)
		from vtiger_lp_errores_correos ec 
		inner join vtiger_contactdetails on destinatario=email
		where fecha_error>=date_add(now(),interval -30 day)*/
			
			$result=$adb->query($query);
			//fwrite($fp,$query.PHP_EOL);
			$no_of_rows=$adb->num_rows($result);
			$json_string="[[\"Correo\",\"Nombre\",\"Documento\",\"Celular\",\"Asunto\",\"Fecha\",\"Local de Preferencia\",\"Empleado Modifico\"],";
			$ar[]=array('Correo','Nombre','Documento','Celular','Asunto','Fecha','Local de Preferencia','Empleado Modifico');
			if($no_of_rows!=0){
			  $total=0;
			  while($row = $adb->fetch_array($result)){
			    	$json_string.="[\"".$row['destinatario']."\",\"".$row['firstname']." ".$row['lastname']."\",\"".$row['lpdocumento']."\",\"".$row['mobile']."\",\"".$row['asunto']."\",\"".$row['fecha_error']."\",\"".$row['local']."\"],";
			    	$ar[]=array($row['destinatario'],$row['firstname']." ".$row['lastname'],$row['lpdocumento']."",$row['mobile']."",$row['asunto']."",$row['fecha_error'],$row['local']."",$row['mod_nodum_por']."");
			//    	$ar[]=array("\"".$row['destinatario']."\",\"".$row['firstname']." ".$row['lastname']."\",\"".$row['lpdocumento']."\",\"".$row['mobile']."\",\"".$row['asunto']."\",\"".$row['fecha_error']."\",\"".$row['local']."\"");
			  }
			}
			$json_string=rtrim($json_string, ",");
			$json_string.="]";

			
			//echo $json_string;
			
			//var_dump($ar);

			$viewer->assign('ARR', json_encode($ar));
			//FILTROS
			$adb = PearDatabase::getInstance();
			//TIpo de actividad
			$actividades = array();
			$sql="SELECT DISTINCT lpactipo FROM vtiger_lpactipo";
			$result=$adb->query($sql);
			$no_of_rows=$adb->num_rows($result);
			if($no_of_rows!=0){
				while($row = $adb->fetch_array($result)){
				    $actividades[] = $row['lpactipo'];
				}
			}
			$viewer->assign('actividades', $actividades);

			//Rango de edad
			$edades = array();
			$sql="SELECT DISTINCT rango_edades FROM vtiger_contactdetails order by rango_edades asc";
			$result=$adb->query($sql);
			$no_of_rows=$adb->num_rows($result);
			if($no_of_rows!=0){
				while($row = $adb->fetch_array($result)){
				    $edades[] = $row['rango_edades'];
				}
			}
			$viewer->assign('edades', $edades);
			
			//Sexo
			$sexo = array('Femenino','Masculino');
			$viewer->assign('sexo', $sexo);
			
			//Canal Activo
			$canales = array('Mall','EShop','Redes','App Smartphone');
			$canales[]="Sin Dato";
			$viewer->assign('canales', $canales);
			//Estatuto
			$estatutos = array('En Baja','Fiel','Inactivo','Nuevo');
			$estatutos[]="Sin Dato";
			$viewer->assign('estatutos', $estatutos);
			//Programas
					$programas = array("Comprador Frecuente","Los 1",
							"Programas Gaviotas","Funcionario","WTC Member",
							"Gaviotas Electronicas","Gaviotas Efectivo","Tarjeta Beneficios");
			$programas[]="Sin Dato";
			$viewer->assign('programas', $programas);

			$first_day_this_month = date('01-m-Y'); // hard-coded '01' for first day
			$first_day_this_month = date('01-m-Y', strtotime("-1 months"));
			$last_day_this_month  = date('t-m-Y');
			$date_range=$first_day_this_month.",".$last_day_this_month;
			$viewer->assign('date_range', $date_range);

			$viewer->view('AnalisisView.tpl', $request->getModule());
	}
	
	/*
	 * Function to get the calendar settings view
	 */
	public function getAnalisisSettings(Vtiger_Request $request){
		
		$viewer = $this->getViewer($request);
		$currentUserModel = Users_Record_Model::getCurrentUserModel();
		$module = $request->getModule();
		$detailViewModel = Vtiger_DetailView_Model::getInstance('Users', $currentUserModel->id);
		$userRecordStructure = Vtiger_RecordStructure_Model::getInstanceFromRecordModel($detailViewModel->getRecord(), Vtiger_RecordStructure_Model::RECORD_STRUCTURE_MODE_EDIT);
		$recordStructure = $userRecordStructure->getStructure();
		$allUsers = Users_Record_Model::getAll(true);
		$sharedUsers = Analisis_Module_Model::getCaledarSharedUsers($currentUserModel->id);
		$sharedType = Analisis_Module_Model::getSharedType($currentUserModel->id);
		$dayStartPicklistValues = Users_Record_Model::getDayStartsPicklistValues($recordStructure);
		
		$viewer->assign('CURRENTUSER_MODEL',$currentUserModel);
		$viewer->assign('SHAREDUSERS', $sharedUsers);
		$viewer->assign("DAY_STARTS", Zend_Json::encode($dayStartPicklistValues));
		$viewer->assign('ALL_USERS',$allUsers);
		$viewer->assign('RECORD_STRUCTURE', $recordStructure);
		$viewer->assign('MODULE',$module);
		$viewer->assign('RECORD', $currentUserModel->id);
		$viewer->assign('SHAREDTYPE', $sharedType);
		
		$viewer->view('AnalisisSettings.tpl', $request->getModule());
	}
	
	
}