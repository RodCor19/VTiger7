<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

class Analisis_List_View extends Vtiger_Index_View {

	public function preProcess(Vtiger_Request $request, $display = true) {
		global $adb;
		$viewer = $this->getViewer($request);
		$viewer->assign('MODULE_NAME', $request->getModule());

		//ver si se tienen permisos para Ver los Mails Rebotados
		$currentUserId = Users_Record_Model::getCurrentUserModel()->get('id');
		$permiso = $adb->query_result($adb->query("SELECT pu.permission 
											FROM vtiger_profile2utility pu
											INNER JOIN vtiger_role2profile rp ON pu.profileid = rp.profileid
											INNER JOIN vtiger_user2role ur ON ur.roleid = rp.roleid
											INNER JOIN vtiger_actionmapping am ON am.actionid = pu.activityid
											WHERE ur.userid = $currentUserId AND pu.tabid = 54 AND am.actionname = 'MailsPorPersona'"), 0, 'permission');

		$viewer->assign('PERMISO', $permiso);

		parent::preProcess($request, false);
		if($display) {
			$this->preProcessDisplay($request);
		}
	}

	protected function preProcessTplName(Vtiger_Request $request) {
		return 'MailsFechaViewPreProcess.tpl';
	}

	
	public function getHeaderScripts(Vtiger_Request $request) {
		$headerScriptInstances = parent::getHeaderScripts($request);
		$moduleName = $request->getModule();

		$jsFileNames = array(
			'~/libraries/jquery/gridster/jquery.gridster.min.js',
			'~/libraries/jquery/jqplot/jquery.jqplot.min.js',
			'~/libraries/jquery/jqplot/plugins/jqplot.dateAxisRenderer.min.js',
			'~/libraries/jquery/jqplot/plugins/jqplot.canvasTextRenderer.min.js',
			'~/libraries/jquery/jqplot/plugins/jqplot.canvasAxisTickRenderer.min.js',
			'modules.Vtiger.resources.DashBoard',
			'modules.Vtiger.resources.dashboards.Widget',
			'~/libraries/jquery/jqplot/plugins/jqplot.categoryAxisRenderer.min.js',
			'~/libraries/jquery/jqplot/plugins/jqplot.barRenderer.min.js',
			'~/libraries/jquery/jqplot/plugins/jqplot.pointLabels.min.js',
			'~/libraries/jquery/jqplot/plugins/jqplot.canvasAxisLabelRenderer.min.js',
			'~/libraries/jquery/jqplot/plugins/jqplot.canvasTextRenderer.min.js',
			'~/libraries/jquery/jqplot/plugins/jqplot.highlighter.min.js',
			'~/libraries/jquery/jqplot/plugins/jqplot.cursor.min.js',
			'~/libraries/jquery/jqplot/plugins/jqplot.enhancedLegendRenderer.min.js',
			'~/libraries\jquery/jquery-ui/third-party/jQuery-UI-Date-Range-Picker/js/daterangepicker.jQuery.js',
			'~/libraries/jquery/funciones.js',

			
		);

		$jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
		$headerScriptInstances = array_merge($headerScriptInstances, $jsScriptInstances);
		return $headerScriptInstances;
	}

	/**
	 * Function to get the list of Css models to be included
	 * @param Vtiger_Request $request
	 * @return <Array> - List of Vtiger_CssScript_Model instances
	 */
	public function getHeaderCss(Vtiger_Request $request) {
		$parentHeaderCssScriptInstances = parent::getHeaderCss($request);

		$headerCss = array(
			'~/libraries/jquery/gridster/jquery.gridster.min.css',
			'~/libraries/jquery/jqplot/jquery.jqplot.min.css',
		);
		$cssScripts = $this->checkAndConvertCssStyles($headerCss);
		$headerCssScriptInstances = array_merge($parentHeaderCssScriptInstances , $cssScripts);
		return $headerCssScriptInstances;
	}

		public function process(Vtiger_Request $request) {
		$mode = $request->getMode();
		
		$viewer = $this->getViewer($request);
		$currentUserModel = Users_Record_Model::getCurrentUserModel();
		if($request->getMode() == 'Ajax'){
			return $this->getAjax($request);
		}
		$viewer->assign('CURRENT_USER', $currentUserModel);

		$adb = PearDatabase::getInstance();

		$desde="";
		$hasta="";
	 	
		$desde = date('01-m-Y'); // hard-coded '01' for first day
		$desde = date('01-m-Y', strtotime("-3 months"));
		$hasta  = date('t-m-Y');

		/*$desde = Vtiger_Date_UIType::getDBInsertedValue($desde);
		$hasta = Vtiger_Date_UIType::getDBInsertedValue($hasta);*/
		$desde = DateTimeField::__convertToDBFormat($desde,'dd-mm-yyyy');
		$hasta = DateTimeField::__convertToDBFormat($hasta,'dd-mm-yyyy');
				
		$query="select cantidad AS cantidadmails, COUNT(*) AS cantidadpersonas from (select crmid as contactid,count(*) as cantidad 
				from vtiger_activity
				inner join vtiger_seactivityrel on vtiger_seactivityrel.activityid = vtiger_activity.activityid 
				INNER JOIN vtiger_contactdetails ON vtiger_contactdetails.contactid= vtiger_seactivityrel.crmid
				where vtiger_activity.activitytype='Emails' ";

		if ($desde!=""){
			$query.=" and date_start >= '".$desde."'  ";
		}
		if ($hasta!=""){
			$query.=" AND date_start <= '".$hasta."' ";
		}		
		
		$query.=" GROUP BY crmid
				UNION

				SELECT cr.contactid , COUNT(cr.contactid) AS cantidad
				FROM vtiger_campaign c
				INNER JOIN `vtiger_campaigncontrel` cr ON cr.`campaignid`=c.`campaignid`
				INNER JOIN vtiger_contactdetails ON vtiger_contactdetails.contactid=cr.contactid
				WHERE c.lpchimpemailssent>0 and email!='' AND status is not null
				";
		if ($desde!=""){
			$query.=" and lpcamdesde >= '".$desde."'  ";
		}
		if ($hasta!=""){
			$query.=" AND lpcamdesde <= '".$hasta."' ";
		}			

		$query.=" GROUP BY cr.contactid ) AS dat
				GROUP BY cantidad ";
		$result=$adb->query($query);
		//fwrite($fp,$query.PHP_EOL);
		$no_of_rows=$adb->num_rows($result);
		$ar_mails = array();
		$ar_aux = array();

		if($no_of_rows!=0){
			$total=0;

			while($row = $adb->fetch_array($result)){
			    $total+=$row['cantidadpersonas'];
				//$ar_aux[] = array("name"=>$row['cantidadmails']." Mensajes recibido en el mes","count"=>(int)$row['cantidadpersonas'],"id"=>(int)$row['cantidadmails']);
				$ar_aux[] = array((int)$row['cantidadmails'],(int)$row['cantidadpersonas']);
			}
				//array_unshift($ar_aux, array("0 Mensajes recibido en la temporada",(int)($cant_clientes-$total)));
				$ar_mails=$ar_aux;
					
		}else{
			$ar_mails = array(0,0);
		}

		//var_dump($ar_mails);

		$viewer->assign('DATA', $ar_mails);
		
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
		$programas = array();
		$sql="SELECT DISTINCT programa FROM vtiger_programa order by programa asc";
		$result=$adb->query($sql);
		$no_of_rows=$adb->num_rows($result);
		if($no_of_rows!=0){
			while($row = $adb->fetch_array($result)){
			    $programas[] = $row['programa'];
			}
		}
		$viewer->assign('programas', $programas);

		$first_day_this_month = date('01-m-Y'); // hard-coded '01' for first day
		$first_day_this_month = date('01-m-Y', strtotime("-3 months"));
		$last_day_this_month  = date('t-m-Y');
		$date_range=$first_day_this_month.",".$last_day_this_month;
		$viewer->assign('date_range', $date_range);
		$viewer->view('MailsFechaView.tpl', $request->getModule());
	}
	
	/*
	 * Function to get the calendar settings view
	 */
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
		$filtrar=false;

		if( (!empty($rango) && $rango!="") || (!empty($sexo) && $sexo!="") || (!empty($canal) && $canal!="") || (!empty($estatuto) && $estatuto!="") || (!empty($programa) && $programa!="")  ) {
			$filtrar=true;
		}	
		
								
		$query="select cantidad AS cantidadmails, COUNT(*) AS cantidadpersonas from (select crmid as contactid,count(*) as cantidad 
				from vtiger_activity
				inner join vtiger_seactivityrel on vtiger_seactivityrel.activityid = vtiger_activity.activityid ";
		
		//if($filtrar){
			$query.=" INNER JOIN vtiger_contactdetails ON vtiger_contactdetails.contactid= vtiger_seactivityrel.crmid";
		//}		
		$query.=" where vtiger_activity.activitytype='Emails' ";
					
		if ($desde!=""){
			$query.=" and date_start >= '".$desde."'  ";
		
		}
		if ($hasta!=""){
			$query.=" AND date_start <= '".$hasta."' ";
		
		}		

		if(!empty($rango) && $rango!="") {
			//$query.=" and vtiger_contactdetails.rango_edades='".$rango."'";
			$rangos=explode(",", $rango);
			$query.=" AND (";
			foreach($rangos as $id){
				$query.=" rango_edades LIKE '%".$id."%' OR";
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
			foreach($canales as $id){/*
				$query.=" FIND_IN_SET('".$id."',canal_activo)<>0 OR";	*/
				$query.=" canal_activo LIKE '%".$id."%' OR";	
			}	
			$query=rtrim($query,'OR');
			$query.=" )";
		}
		if(!empty($estatuto) && $estatuto!="") {
			//$query.=" and vtiger_contactdetails.estatuto='".$estatuto."'";
			$estatutos=explode(",", $estatuto);
			$query.=" AND (";
			foreach($estatutos as $id){
				$query.=" estatuto LIKE '%".$id."%' OR";	
			}	
			$query=rtrim($query,'OR');
			$query.=" )";
		}
		
		if(!empty($programa) && $programa!="") {
			$programas=explode(",", $programa);
			$query.=" AND (";
			foreach($programas as $id){
				$query.=" programa LIKE '%".$id."%' OR";	
			}	
			$query=rtrim($query,'OR');
			$query.=" )";
		}
		
		$query.=" GROUP BY crmid
				UNION

				SELECT cr.contactid , COUNT(cr.contactid) AS cantidad
				FROM vtiger_campaign c
				INNER JOIN `vtiger_campaigncontrel` cr ON cr.`campaignid`=c.`campaignid`
				INNER JOIN vtiger_contactdetails ON vtiger_contactdetails.contactid=cr.contactid
				WHERE c.lpchimpemailssent>0 and email!=''  AND status is not null
				";
		if ($desde!=""){
			$query.=" and lpcamdesde >= '".$desde."'  ";
		}
		if ($hasta!=""){
			$query.=" AND lpcamdesde <= '".$hasta."' ";
		}			
		if(!empty($rango) && $rango!="") {
			//$query.=" and vtiger_contactdetails.rango_edades='".$rango."'";
			$rangos=explode(",", $rango);
			$query.=" AND (";
			foreach($rangos as $id){
				$query.=" rango_edades LIKE '%".$id."%' OR";
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
			foreach($canales as $id){/*
				$query.=" FIND_IN_SET('".$id."',canal_activo)<>0 OR";	*/
				$query.=" canal_activo LIKE '%".$id."%' OR";	
			}	
			$query=rtrim($query,'OR');
			$query.=" )";
		}
		if(!empty($estatuto) && $estatuto!="") {
			//$query.=" and vtiger_contactdetails.estatuto='".$estatuto."'";
			$estatutos=explode(",", $estatuto);
			$query.=" AND (";
			foreach($estatutos as $id){
				$query.=" estatuto LIKE '%".$id."%' OR";	
			}	
			$query=rtrim($query,'OR');
			$query.=" )";
		}
		
		if(!empty($programa) && $programa!="") {
			$programas=explode(",", $programa);
			$query.=" AND (";
			foreach($programas as $id){
				$query.=" programa LIKE '%".$id."%' OR";	
			}	
			$query=rtrim($query,'OR');
			$query.=" )";
		}

		echo $query.=" GROUP BY cr.contactid) AS dat
				GROUP BY cantidad ";

		$result=$adb->query($query);
		//fwrite($fp,$query.PHP_EOL);
		$no_of_rows=$adb->num_rows($result);
		$ar_mails = array();
		$ar_aux = array();

		if($no_of_rows!=0){
			$total=0;

			while($row = $adb->fetch_array($result)){
			    $total+=$row['cantidadpersonas'];
				//$ar_aux[] = array("name"=>$row['cantidadmails']." Mensajes recibido en el mes","count"=>(int)$row['cantidadpersonas'],"id"=>(int)$row['cantidadmails']);
				$ar_aux[] = array((int)$row['cantidadmails'],(int)$row['cantidadpersonas']);
			}
				//array_unshift($ar_aux, array("0 Mensajes recibido en la temporada",(int)($cant_clientes-$total)));
				$ar_mails=$ar_aux;
					
		}else{
			$ar_mails = array(0,0);
		}
		echo  json_encode($ar_mails);
		
	}
	
}