<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/
error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set("display_errors", 1);
class Analisis_MailsRebotados_View extends Vtiger_Index_View {

	public function preProcess(Vtiger_Request $request, $display = true) {
		$viewer = $this->getViewer($request);
		$viewer->assign('MODULE_NAME', $request->getModule());

		parent::preProcess($request, false);
		if($display) {
			$this->preProcessDisplay($request);
		}
	}

	protected function preProcessTplName(Vtiger_Request $request) {
		return 'MailsRebotadosViewPreProcess.tpl';
	}





	public function getHeaderScripts(Vtiger_Request $request) {
		$headerScriptInstances = parent::getHeaderScripts($request);
		$jsFileNames = array(
			"modules.Analisis.resources.AnalisisView",
			//"~/libraries/pivottable/ext/jquery-1.8.3.min.js",
			//"~/libraries/pivottable/ext/jquery-ui-1.9.2.custom.min.js",
			//"~/libraries/pivot-js/lib/jquery.min.js",
			"~/libraries/pivot-js/lib/javascripts/subnav.js",
			"~/libraries/pivot-js/lib/javascripts/accounting.min.js",
			"~/libraries/pivot-js/lib/javascripts/jquery.dataTables.js",
			"~/libraries/pivot-js/lib/javascripts/dataTables.rangeFilter.js",
			"~/libraries/pivot-js/lib/javascripts/dataTables.bootstrap.js"
		);

		$jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
		$headerScriptInstances = array_merge($headerScriptInstances, $jsScriptInstances);
		return $headerScriptInstances;
	}

	public function getHeaderCss(Vtiger_Request $request) {
		$headerCssInstances = parent::getHeaderCss($request);


		$cssFileNames = array(
			'~/libraries/pivot-js/lib/css/subnav.css',
			'~/libraries/pivot-js/lib/css/pivot.css'
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
		if($mode == 'data'){
			$this->getData($request);exit;
		}
		if($request->getMode() == 'Ajax'){
			return $this->getAjax($request);
		}
		$viewer = $this->getViewer($request);
		$currentUserModel = Users_Record_Model::getCurrentUserModel();
		if($request->getMode() == 'Settings'){
			return $this->getAnalisisSettings($request);
		}
		$viewer->assign('CURRENT_USER', $currentUserModel);

		$viewer->view('AnalisisView.tpl', $request->getModule());
	}
	
	/*
	 * Function to get the calendar settings view
	 */
	public function getData(Vtiger_Request $request){
		$adb = PearDatabase::getInstance();

	 	$query="SELECT contactid,destinatario,firstname,lastname,mobile,cndocumento AS  documento,asunto,canal_activo, DATE_FORMAT(fecha_error, '%d/%m/%Y') AS fecha_error,mod_nodum_por
					FROM vtiger_lp_errores_correos INNER JOIN vtiger_contactdetails ON destinatario=email 
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
			$json_string="[[\"Correo\",\"Nombre\",\"Celular\",\"Asunto\",\"Fecha\",";
			$ar[]=array('Correo','Nombre','Documento','Celular','Asunto','Fecha','Canal Activo','Empleado Modificó','Link');
			if($no_of_rows!=0){
			  $total=0;
			  while($row = $adb->fetch_array($result)){
			  		$link="<a href='index.php?module=Contacts&view=Detail&record=".$row["contactid"]."'>Ver</a>";
			    	$json_string.="[\"".$row['destinatario']."\",\"".$row['firstname']." ".$row['lastname']."\",\"".$row['mobile']."\",\"".$row['asunto']."\",\"".$row['fecha_error']."\",\"".$row['local']."\"],";
			    	$ar[]=array($row['destinatario'],$row['firstname']." ".$row['lastname'],$row['documento']."",$row['mobile']."",$row['asunto']."",$row['fecha_error']."",$row['canal_activo'],$row['mod_nodum_por']."",$link."");
			//    	$ar[]=array("\"".$row['destinatario']."\",\"".$row['firstname']." ".$row['lastname']."\",\"".$row['lpdocumento']."\",\"".$row['mobile']."\",\"".$row['asunto']."\",\"".$row['fecha_error']."\",\"".$row['local']."\"");
			  }
			}
			$json_string=rtrim($json_string, ",");
			$json_string.="]";

			
			//echo $json_string;
			echo json_encode($ar);
	}
	public function getAjax(Vtiger_Request $request){
		$adb = PearDatabase::getInstance();

		$desde="";
		$hasta="";
	 	
		$createdTime = $request->get('createdtime');
		
		//Date conversion from user to database format
		if(!empty($createdTime)) {
			/*$desde = Vtiger_Date_UIType::getDBInsertedValue($createdTime['start']);
			$hasta = Vtiger_Date_UIType::getDBInsertedValue($createdTime['end']);*/
			$desde = DateTimeField::__convertToDBFormat($createdTime['start'],'dd-mm-yyyy');
			$hasta = DateTimeField::__convertToDBFormat($createdTime['end'],'dd-mm-yyyy');
		}


		/*if($_REQUEST['desde']!="" ){
			$desde=$_REQUEST['desde'];
			$hasta =date("Y-m-t", strtotime($desde));
		}*/

		$rango =htmlspecialchars_decode($request->get('edad'));
		$sexo = $request->get('sexo');
		$canal = $request->get('canal');
		$estatuto = $request->get('estatuto');
		$programa = $request->get('programa');

		$query="SELECT contactid,destinatario,firstname,lastname,mobile,cndocumento AS  documento,asunto,canal_activo, DATE_FORMAT(fecha_error, '%d/%m/%Y') AS fecha_error,mod_nodum_por
					FROM vtiger_lp_errores_correos INNER JOIN vtiger_contactdetails ON destinatario=email 
					WHERE 1=1";

		if ($desde!=""){
			$query.=" and fecha_error >= '".$desde."'  ";
		
		}
		if ($hasta!=""){
			$query.=" AND fecha_error <= '".$hasta."' ";
		
		}		

		if(!empty($rango) && $rango!="") {
			//$query.=" and vtiger_contactdetails.rango_edades='".$rango."'";
			$rangos=explode(",", $rango);
			$query.=" AND (";
			foreach($rangos as $id){
				$query.=" FIND_IN_SET('".$id."',rango_edades)<>0 OR";	
			}	
			$query=rtrim($query,'OR');
			$query.=" )";
		}
		if(!empty($sexo) && $sexo!="") {
			//$query.=" and vtiger_contactdetails.cnsexo='".$sexo."'";
			$sexos=explode(",", $sexo);
			$query.=" AND (";
			foreach($sexos as $id){
				$query.=" cnsexo='".$id."' OR";	
			}	
			$query=rtrim($query,'OR');
			$query.=" )";
		}
		if(!empty($canal) && $canal!="") {
			$canales=explode(",", $canal);
			$query.=" AND (";
			foreach($canales as $id){
				$query.=" FIND_IN_SET('".$id."',canal_activo)<>0 OR";	
			}	
			$query=rtrim($query,'OR');
			$query.=" )";
		}
		if(!empty($estatuto) && $estatuto!="") {
			//$query.=" and vtiger_contactdetails.estatuto='".$estatuto."'";
			$estatutos=explode(",", $estatuto);
			$query.=" AND (";
			foreach($estatutos as $id){
				$query.=" FIND_IN_SET('".$id."',estatuto)<>0 OR";	
			}	
			$query=rtrim($query,'OR');
			$query.=" )";
		}
		
		if(!empty($programa) && $programa!="") {
			$programas=explode(",", $programa);
			$query.=" AND (";
			foreach($programas as $id){
				$query.=" FIND_IN_SET('".$id."',programa)<>0 OR";	
			}	
			$query=rtrim($query,'OR');
			$query.=" )";
		}
			
			$result=$adb->query($query);
			//fwrite($fp,$query.PHP_EOL);
			$no_of_rows=$adb->num_rows($result);
			$json_string="[[\"Correo\",\"Nombre\",\"Celular\",\"Asunto\",\"Fecha\",";
			$ar[]=array('Correo','Nombre','Documento','Celular','Asunto','Fecha','Canal Activo','Empleado Modificó','Link');
			if($no_of_rows!=0){
			  $total=0;
			  while($row = $adb->fetch_array($result)){
			    	$link="<a href='index.php?module=Contacts&view=Detail&record=".$row["contactid"]."'>Ver</a>";
			    	$json_string.="[\"".$row['destinatario']."\",\"".$row['firstname']." ".$row['lastname']."\",\"".$row['mobile']."\",\"".$row['asunto']."\",\"".$row['fecha_error']."\",\"".$row['local']."\"],";
			    	//$ar[]=array($row['destinatario'],$row['firstname']." ".$row['lastname'],$row['lpdocumento']."",$row['mobile']."",$row['asunto']."",$row['fecha_error']."",$row['canal_activo']);
			    	$ar[]=array($row['destinatario'],$row['firstname']." ".$row['lastname'],$row['documento']."",$row['mobile']."",$row['asunto']."",$row['fecha_error']."",$row['canal_activo'],$row['mod_nodum_por']."",$link."");
			//    	$ar[]=array("\"".$row['destinatario']."\",\"".$row['firstname']." ".$row['lastname']."\",\"".$row['lpdocumento']."\",\"".$row['mobile']."\",\"".$row['asunto']."\",\"".$row['fecha_error']."\",\"".$row['local']."\"");
			  }
			}
			$json_string=rtrim($json_string, ",");
			$json_string.="]";

			
			//echo $json_string;
			echo json_encode($ar);
	}	
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