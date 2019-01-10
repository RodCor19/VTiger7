<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

class Analisis_MailsFecha_Dashboard extends Vtiger_IndexAjax_View {

	/**
	 * Function to get the list of Script models to be included
	 * @param Vtiger_Request $request
	 * @return <Array> - List of Vtiger_JsScript_Model instances
	 */

	
	public function getHeaderScripts(Vtiger_Request $request) {
		$headerScriptInstances = parent::getHeaderScripts($request);
		$moduleName = $request->getModule();

		$jsFileNames = array(
			'~/libraries/jquery/gridster/jquery.gridster.min.js',
			'~/libraries/jquery/jqplot/jquery.jqplot.min.js',
			'~/libraries/jquery/jqplot/plugins/jqplot.dateAxisRenderer.min.js',
			'~/libraries/jquery/jqplot/plugins/jqplot.canvasTextRenderer.min.js',
			'~/libraries/jquery/jqplot/plugins/jqplot.canvasAxisTickRenderer.min.js',
			//'modules.Vtiger.resources.DashBoard',
			'modules.Vtiger.resources.dashboards.Widget',
			'~/libraries/jquery/jqplot/plugins/jqplot.categoryAxisRenderer.min.js',
			'~/libraries/jquery/jqplot/plugins/jqplot.barRenderer.min.js',
			'~/libraries/jquery/jqplot/plugins/jqplot.pointLabels.min.js',
			'~/libraries/jquery/jqplot/plugins/jqplot.canvasAxisLabelRenderer.min.js',
			'~/libraries/jquery/jqplot/plugins/jqplot.canvasTextRenderer.min.js',
			'~/libraries/jquery/jqplot/plugins/jqplot.highlighter.min.js',
			'~/libraries/jquery/jqplot/plugins/jqplot.cursor.min.js',
			'~/libraries/jquery/jqplot/plugins/jqplot.enhancedLegendRenderer.min.js',
			//'~/libraries\jquery/jquery-ui/third-party/jQuery-UI-Date-Range-Picker/js/daterangepicker.jQuery.js',
			'~/libraries/jquery/funciones.js',

			
		);

		$jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
		$headerScriptInstances = array_merge($headerScriptInstances, $jsScriptInstances);
		return $headerScriptInstances;
	}

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
		$currentUser = Users_Record_Model::getCurrentUserModel();
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
				WHERE c.lpchimpemailssent>0 and email!=''
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

		$linkId = $request->get('linkid');
		$widget = Vtiger_Widget_Model::getInstance($linkId, $currentUser->getId());
		$viewer->assign('WIDGET', $widget);
		
		$moduleName = $request->getModule();
		$viewer->assign('MODULE_NAME', $moduleName);
		
		$viewer->view('MailsFecha_Dash.tpl', $request->getModule());
	}
}
