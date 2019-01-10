<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

class Analisis_Quejas_View extends Vtiger_Index_View {


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
		$moduleName = $request->getModule();

		$jsFileNames = array(
			"modules.Project.resources.AnalisisView",
			//"~/libraries/pivottable/ext/jquery-1.8.3.min.js",
			"~/libraries/pivottable/ext/jquery-ui-1.9.2.custom.min.js",
			"~/libraries/pivottable/dist/pivot.js",
			'~/libraries/jquery/funciones.js',
			'modules.Analisis.resources.filtros' //Parte agregada para filtros

			
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
			'~/libraries/pivot-js/lib/css/pivot.css'
		);
		$cssScripts = $this->checkAndConvertCssStyles($headerCss);
		$headerCssScriptInstances = array_merge($parentHeaderCssScriptInstances , $cssScripts);
		return $headerCssScriptInstances;
	}

	public function process(Vtiger_Request $request) {

		$mode = $request->getMode();
		$currentUser = Users_Record_Model::getCurrentUserModel();
		$viewer = $this->getViewer($request);
		$currentUserModel = Users_Record_Model::getCurrentUserModel();
		if($request->getMode() == 'Ajax'){
			return $this->getAjax($request);
		}
		
		$viewer->assign('CURRENT_USER', $currentUserModel);

		$fecha_hasta=date('d-m-Y');
		$date = strtotime( date('Y-m-01')." -11 months");
		$fecha_desde=date("d-m-Y", $date);
		$date_range=$fecha_desde.",".$fecha_hasta;
		$viewer->assign('date_range', $date_range);
		$viewer->assign('_TABLA',__CLASS__);
		$viewer->view('QuejasView.tpl', $request->getModule());
	}
	
	/**
	*	@param <Array> $row - Fila retornada por la base de datos
	*	@param <Array> $camposPermitidos - Campos que estan permitidos para el usuario
	*	@return <Array> Fila de datos con los campos permitidos para el usuario
	*/
	public function restructureData($row,$camposPermitidos){
		$ret = array();
		global $log;
		foreach ($camposPermitidos as $campo)		//Recorre los campos permitidos				
				$ret [] = $row[strtolower($campo)];	//Asigna el valor a el array de retorno
		
		return $ret;
	}
	/**
	*	CamposPermitidos
	*	@param <Array> $data - Campos de entrada
	*	@return <Array> Campos permitidos para el usuario	
	*/	
	public function camposPermitidos($data){
		$ret = array();
		$module = Vtiger_Module::getInstance('HorasDeTareas');
		$campo = Vtiger_Field::getInstance('horcosto',$module);
		$field = Vtiger_Field_Model::getInstanceFromFieldObject($campo,$module);		//Obtiene una instancia de model con el field y su modulo
		$enabled = $field->isViewEnabled();	//Checkea si dicho campo esta habilitado
		foreach ($data as $dato) {		//Recorre los campos de entrada
			if($dato != 'Costo' || $enabled)	//En caso de que no sean costo o costo este habilitado
				$ret [] = $dato;		//Lo agregaga a el arrar de retorno
 		}
		return $ret;

	}

	public function getFlitroPorPrivilegios(){
		$adb = PearDatabase::getInstance();
		if(Settings_SharingAccess_Module_Model::getInstance("HelpDesk")->isPrivate()){  
	        $modelo = Vtiger_ListView_Model::getInstance("HelpDesk");
	        $result = $adb->pquery($modelo->getQuery(),array());
		   	foreach ($result as $dato) {
		    	$acceso[] = $dato["ticketid"];
		   	}
	  	}else{
	        $modelo = Vtiger_ListView_Model::getInstance("HelpDesk");
	        $result = $adb->pquery($modelo->getQuery(),array());
		   	foreach ($result as $dato) {
		    	$acceso[] = $dato["ticketid"];
		   	}	  		
	  	}
	  	return $acceso;
	}

	/*
	 * Funcion para obtener los datos para tabla pivot
	 */
	public function getAjax(Vtiger_Request $request){	
		//titulo, contacto,cuenta(local),tipo, cat,estado,prioridad,asignado a, fecha incidencia
		$json_data 			= array();
		$campos 			= array('Titulo','Contacto','Local','Tipo','Fecha','Año','AñoMes','Mes','Prioridad','Asignado a','Categoria','Estado');
		$camposPermitidos 	= $campos;
		$json_data	[] 		=	$camposPermitidos;
		//$acceso = $this->getFlitroPorPrivilegios();
		//if($acceso){
			$adb = PearDatabase::getInstance();

			$desde="";
			$hasta="";
		 	
			$createdTime = $request->get('createdtime');

			
			//Date conversion from user to database format
			if(!empty($createdTime)) {
				$desde = DateTimeField::__convertToDBFormat($createdTime['start'],'dd-mm-yyyy');
				$hasta = DateTimeField::__convertToDBFormat($createdTime['end'],'dd-mm-yyyy');
			}

			
			$query="SELECT t.ticketid as id, t.status AS estado, t.priority AS prioridad, t.title, CONCAT(cd.firstname,' ',cd.lastname) AS 			contacto, cf.cf_1130 AS fecha, u.user_name AS 'Asignado a', a.accountname as local, lpcategoria 	as tipo, category as categoria
				FROM vtiger_troubletickets t JOIN vtiger_crmentity c ON t.ticketid = c.crmid JOIN vtiger_contactdetails cd ON t.contact_id = cd.contactid JOIN vtiger_users u ON u.id = smcreatorid JOIN vtiger_account a ON t.parent_id = a.accountid JOIN vtiger_ticketcf cf ON t.ticketid = cf.ticketid";
 										//where (".implode(',', $acceso).") ";

			if ($desde!=""){
				$query.=" AND cf.cf_1130 >= '".$desde."'  ";
			
			}
			if ($hasta!=""){
				$query.=" AND cf.cf_1130 <= '".$hasta."' ";
			
			}				
				
				
				$result=$adb->query($query);			
				
				$no_of_rows=$adb->num_rows($result);
				
				if($no_of_rows!=0){
					if($no_of_rows<10000){
					  	$total=0;
					  	while($row = $adb->fetch_array($result)){
					  		$fechaExplode = explode('-',$row['fecha']);
					  		$row['titulo'] =  "<a href='index.php?module=HelpDesk&view=Detail&record=".$row['id']."'>".$row['title']."</a>";
					  		$row['año'] = $fechaExplode[0];
					  		$row['añomes'] = $fechaExplode[0].'-'.$fechaExplode[1];
					  		$row['mes'] = $fechaExplode[1];
					  		$row['estado'] = vtranslate($row['estado'],'HelpDesk');
					  		$row['prioridad'] = vtranslate($row['prioridad'],'HelpDesk');
							$json_data[] = $this->restructureData($row,$camposPermitidos);
					  	}
				  	}	
				}
		//}
		echo json_encode($json_data);
	}
	
		
}