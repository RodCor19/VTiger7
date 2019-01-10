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
ini_set('memory_limit', '2048M');

set_time_limit(0);

class Analisis_RankingClientes_View extends Vtiger_Index_View {

	public function preProcess(Vtiger_Request $request, $display = true) {
		$viewer = $this->getViewer($request);
		$viewer->assign('MODULE_NAME', $request->getModule());

		parent::preProcess($request, false);
		if($display) {
			$this->preProcessDisplay($request);
		}
	}

	protected function preProcessTplName(Vtiger_Request $request) {
		return 'RankingClientesViewPreProcess.tpl';
	}

	
	public function getHeaderScripts(Vtiger_Request $request) {
		$headerScriptInstances = parent::getHeaderScripts($request);
		$moduleName = $request->getModule();

		$jsFileNames = array(
			'~/libraries/jquery/gridster/jquery.gridster.min.js',
			"modules.Analisis.resources.AnalisisView",
			'modules.Vtiger.resources.DashBoard',
			'modules.Vtiger.resources.dashboards.Widget',
			'~/libraries\jquery/jquery-ui/third-party/jQuery-UI-Date-Range-Picker/js/daterangepicker.jQuery.js',
			//"~/libraries/pivot-js/lib/jquery.min.js",
			"~/libraries/pivot-js/lib/javascripts/accounting.min.js",
			"~/libraries/pivot-js/lib/javascripts/jquery.dataTables.js",
			"~/libraries/pivot-js/lib/javascripts/dataTables.rangeFilter.js",
			"~/libraries/pivot-js/lib/javascripts/dataTables.bootstrap.js",
			"~/libraries/pivot-js/pivot.js",
			"~/libraries/pivot-js/jquery_pivot.js",
			'~/libraries/jquery/funciones.js',
			'~/libraries/jquery/excellentexport.min.js',
			"~/libraries/pivottable/ext/jquery-ui-1.9.2.custom.min.js",
			"~/libraries/pivottable/dist/d3.min.js",
			"~/libraries/pivottable/dist/c3.min.js",
			"~/libraries/pivottable/dist/pivot.min.js",
			"~/libraries/pivottable/dist/c3_renderers.min.js",
			"~/libraries/pivottable/dist/d3_renderers.min.js",
			"~/libraries/pivottable/dist/pivot.es.min.js",
			"~/libraries/luderepro/luderepro.js",
			
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
			'~/libraries/pivot-js/lib/css/jquery.dataTables.css',
			'~/libraries/pivot-js/lib/css/dataTables.tableTools.css',
			'~/libraries/pivot-js/lib/css/demo_table.css',
			'~/libraries/pivottable/dist/pivot.min.css',
			'~/libraries/pivottable/dist/c3.min.css',
			"~/libraries/luderepro/luderepro.css",
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
		if($request->getMode() == 'pivot'){
			return $this->getPivot($request);
		}
		$viewer->assign('CURRENT_USER', $currentUserModel);

		//$respuesta=$this->getData($request);
		
		$moduleModel = Vtiger_Module_Model::getInstance("Analisis");
		//$respuesta = $moduleModel->getRankingClientes($request);
		$respuesta=array();
		//var_dump($respuesta);
		$viewer->assign('DATA', $respuesta);
		
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

		//Campos Pivot
		$campospivot = array();
		$sql = "SELECT fieldname, fieldlabel from vtiger_field where tabid = 4 order by fieldlabel";
		$result = $adb->query($sql);
		$no_of_rows = $adb->num_rows($result);
		if($no_of_rows != 0){
			while($row = $adb->fetch_array($result)){
		    	$campospivot[] = array($row['fieldname'], vtranslate($row["fieldlabel"], "Contacts"));
			}
		}
		$viewer->assign('campospivot', $campospivot);
		//Campos Pivot

		//Promociones
		$sql = "SELECT DISTINCT promocion FROM vtiger_promocion ORDER BY 1 ASC";
		$result = $adb->query($sql);
		$no_of_rows = $adb->num_rows($result);
		if($no_of_rows != 0 ){
			while($row = $adb->fetch_array($result)){
			    $promociones[] = array($row['promocion'], html_entity_decode($row["promocion"]));
			}
		}
		$viewer->assign('promociones', $promociones);
		//Promociones

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


		$fecha_hasta=date('d-m-Y');
		$date = strtotime( date('Y-m-01')." -11 months");
		$fecha_desde=date("d-m-Y", $date);
		$date_range=$fecha_desde.",".$fecha_hasta;
		$viewer->assign('date_range', $date_range);
		$viewer->view('RankingClientesView.tpl', $request->getModule());
	}
	
	/*
	 * Function to get the calendar settings view
	 */
	public function getAjax(Vtiger_Request $request){
		
		
		//$respuesta=$this->getData($request);
		$moduleModel = Vtiger_Module_Model::getInstance("Analisis");
		$respuesta = $moduleModel->getRankingClientes($request);

		echo  json_encode($respuesta);
		
	}

	public function getPivot(Vtiger_Request $request){
		global $log;
		$desde = $request->get('desde');
		$hasta = $request->get('hasta');
	
		
		$hasta = $hasta==""? date('Y-m-d') : $hasta;
		$desde = $desde==""? date("Y-m-d",strtotime($hasta.' -1 year')) : $desde;

		$desdeActual = DateTimeField::__convertToDBFormat($desde,'yyyy-mm-dd');
		$hastaActual = DateTimeField::__convertToDBFormat($hasta,'yyyy-mm-dd');

		$porcionesDesde = explode("-", $desdeActual);
		$desdeActual =$porcionesDesde[2]."-".$porcionesDesde[1]."-".$porcionesDesde[0];

		$porcionesHasta=explode("-", $hastaActual);
		$hastaActual =$porcionesHasta[2]."-".$porcionesHasta[1]."-".$porcionesHasta[0];

		//el mismo periodo 1 año antes
		$desdeAnterior = DateTimeField::__convertToDBFormat(date("Y-m-d",strtotime($desde.'-1 year')),'yyyy-mm-dd');
		$hastaAnterior = DateTimeField::__convertToDBFormat(date("Y-m-d",strtotime($hasta.'-1 year')),'yyyy-mm-dd');

		/* --- Obtener Fechas --- */
		
        $campos_pivot = $request->get('campospivot');
		$campos_pivot_originales = array();

		$joinCuentas = false;

		if(!empty($campos_pivot) && $campos_pivot != 'null'){
			//que no tenga necesariamente el saldo actual y anterior, eso es solo para la consulta (que los necesita)
			if(stripos($campos_pivot, "account_id") !== false){
				$joinCuentas = true;
			}
			$campos_pivot_originales = explode(",", $campos_pivot);

			if(stripos($campos_pivot, "cnsaldoactual") === false)
				$campos_pivot .= ",cnsaldoactual";
			if(stripos($campos_pivot, "cnsaldoanterior") === false)
				$campos_pivot .= ",cnsaldoanterior";
			
			//ponemos los campos que se hayan elegido en el tpl
			$campos_pivot = explode(",", $campos_pivot);
			//$campos_pivot[] = "Rango";

			$aColumns = $campos_pivot;
		} else {
			$campos_pivot = array( 'rango_edades',  'cnsaldoactual', 'cnsaldoanterior'/*, "Rango"*/);
			$campos_pivot_originales = $campos_pivot;
			$aColumns = $campos_pivot;
		}
        

        /* Indexed column (used for fast and accurate table cardinality) */
        $sIndexColumn = "contactid";
         
        /* DB table to use */
        $vista  = $request->get('vistaSel');

        $sTable = "vtiger_contactdetails 
        INNER JOIN vtiger_crmentity on crmid = contactid and deleted = 0
        INNER JOIN vtiger_contactsubdetails ON contactid=contactsubscriptionid LEFT JOIN vtiger_contactaddress ON vtiger_contactaddress.contactaddressid=contactid";

        if($joinCuentas)
         	$sTable .= " LEFT JOIN vtiger_account ON vtiger_account.accountid = vtiger_contactdetails.accountid";
		
		$sGroup = " GROUP BY contactid ";
       
        $sLimit = "";//" LIMIT 0, 100";
        $sOrder = "";


        $sWhere = "WHERE 1=1 ";

        $sWhere .= " AND (cnsaldoactual IS NOT NULL AND cnsaldoanterior IS NOT NULL)";

        $sexo = $request->get('sexo');
		$vista = $request->get('vista');
		$canal = $request->get('canal');
		$estatuto = $request->get('estatuto');
		$rango =htmlspecialchars_decode($request->get('rango'));
		$programa = $request->get('programa');		
		$promociones = $request->get('promociones');

		if(!empty($rango) && $rango!="") {
			$rangos=explode(",", $rango);
			$sWhere.=" AND (";
			foreach($rangos as $id){
				$sWhere.=" rango_edades LIKE '%".$id."%' OR";
			}	
			$sWhere=rtrim($sWhere,'OR');
			$sWhere.=" )";
		}
		if(!empty($sexo) && $sexo!="") {
			$sexos=explode(",", $sexo);
			$sWhere.=" AND (";
			foreach($sexos as $id){
				$sWhere.=" cnsexo='".$id."' OR";	
			}	
			$sWhere=rtrim($sWhere,'OR');
			$sWhere.=" )";
		}
		if(!empty($canal) && $canal!="") {
			$canales=explode(",", $canal);
			$sWhere.=" AND (";
			foreach($canales as $id){
				$sWhere.=" canal_activo LIKE '%".$id."%' OR";	
			}	
			$sWhere=rtrim($sWhere,'OR');
			$sWhere.=" )";
		}
		if(!empty($estatuto) && $estatuto!="") {
			$estatutos=explode(",", $estatuto);
			$sWhere.=" AND (";
			foreach($estatutos as $id){
				$sWhere.=" estatuto LIKE '%".$id."%' OR";	
			}	
			$sWhere=rtrim($sWhere,'OR');
			$sWhere.=" )";
		}	

		if(!empty($programa) && $programa!="") {
			$programas=explode(",", $programa);
			$sWhere.=" AND (";
			foreach($programas as $id){
				$sWhere .= " ( programa = '$id' OR programa LIKE '$id |%' OR programa LIKE '%| $id |%' OR programa LIKE '%| $id' ) OR";
			}	
			$sWhere=rtrim($sWhere,'OR');
			$sWhere.=" )";
		}
		if(!empty($promociones) && $promociones!="") {
			$promociones = explode(" |##| ", $promociones);
			$sWhere.=" AND (";
			foreach($promociones as $id){
				$sWhere .= " ( lppromociones = '$id' OR lppromociones LIKE '$id |%' OR lppromociones LIKE '%| $id |%' OR lppromociones LIKE '%| $id' ) OR";
			}	
			$sWhere=rtrim($sWhere,'OR');
			$sWhere.=" )";
		}

		//campos nuevos
		$conCanjes = $request->get('conCanjes');
		$conVales = $request->get('conVales');
		$tieneGaviotas = $request->get('tieneGaviotas');
		$tieneVisa = $request->get('tieneVisa');
		$tieneAmex = $request->get('tieneAmex');

		if($conCanjes == "true")
			$sWhere .= " AND cnconcanjes = 'Si' ";
		if($conVales == "true")
			$sWhere .= " AND cnconvales = 'Si' ";
		if($tieneGaviotas == "true")
			$sWhere .= " AND cncongaviotas = 'Si' ";
		if($tieneVisa == "true")
			$sWhere .= " AND cntienevisa = 1 ";
		if($tieneAmex == "true")
			$sWhere .= " AND cntienevisa = 1 ";
		//campos nuevos


        $calcTotal=false;
        $sql_calc_total="";


		$adb = PearDatabase::getInstance();
		//nuevo
		$desde = $request->get('desde');
		$hasta = $request->get('hasta');		
		$cambiar = 0;

		$vistaSel = $request->get('vistaSel');
		$confecha = false;


		if(!empty($desde) && !empty($hasta)) {
			$confecha = true;
			$desdeActual = DateTimeField::__convertToDBFormat($desde,'dd-mm-yyyy');
			$hastaActual = DateTimeField::__convertToDBFormat($hasta,'dd-mm-yyyy');
			//el mismo periodo 1 año antes
			$desdeAnterior = DateTimeField::__convertToDBFormat(date("d-m-Y",strtotime($desde.'-1 year')),'dd-mm-yyyy');
			$hastaAnterior = DateTimeField::__convertToDBFormat(date("d-m-Y",strtotime($hasta.'-1 year')),'dd-mm-yyyy');


			// aca saca las cosas de la gaviota
			// if gaviota o boleta canjeada

			$vistaSel = $request->get('vistaSel');


	        if($vistaSel == 'null' || $vistaSel == 'G'){
	        // si no se selecciono vista o la vista es gaviotas
				$auxActual = "ifnull((select Sum(gvcantidad) FROM vtiger_gaviotas WHERE `gvcontacto` = `contactid` AND gvfecha BETWEEN '".$desdeActual." 00:00:00' AND '".$hastaActual." 23:59:59' AND ( gvtipomov = 'Gaviotas generadas en el Canje' OR gvtipomov = 'Gaviotas generadas por Devolucion' OR gvtipomov = 'Gaviotas generadas por Boletas')),0) as 'cnsaldoactual'";
				$auxAnterior = "ifnull((select Sum(gvcantidad) FROM vtiger_gaviotas WHERE `gvcontacto` = `contactid` AND gvfecha BETWEEN '".$desdeAnterior." 00:00:00' AND '".$hastaAnterior." 23:59:59' AND ( gvtipomov = 'Gaviotas generadas en el Canje' OR gvtipomov = 'Gaviotas generadas por Devolucion' OR gvtipomov = 'Gaviotas generadas por Boletas')),0) as 'cnsaldoanterior'";
				$auxSaldoActual="(select Sum(gvcantidad) FROM vtiger_gaviotas WHERE `gvcontacto` = `contactid` AND gvfecha BETWEEN '".$desdeActual." 00:00:00' AND '".$hastaActual." 23:59:59' AND ( gvtipomov = 'Gaviotas generadas en el Canje' OR gvtipomov = 'Gaviotas generadas por Devolucion' OR gvtipomov = 'Gaviotas generadas por Boletas'))";
				$auxSaldoAnterior="(select Sum(gvcantidad) FROM vtiger_gaviotas WHERE `gvcontacto` = `contactid` AND gvfecha BETWEEN '".$desdeAnterior." 00:00:00' AND '".$hastaAnterior." 23:59:59' AND ( gvtipomov = 'Gaviotas generadas en el Canje' OR gvtipomov = 'Gaviotas generadas por Devolucion' OR gvtipomov = 'Gaviotas generadas por Boletas'))";
				$auxDiferencia="ifnull(ifnull($auxSaldoActual,0)-ifnull($auxSaldoAnterior,0),0) as 'cndiferencia'";	

				$auxRango = "case when cnsaldoactual < 10 then 'Menor que 10'
				WHEN cnsaldoactual >= 10 and cnsaldoactual < 20 THEN 'Entre 10 y 20'
				WHEN cnsaldoactual >= 20 and cnsaldoactual < 30 THEN 'Entre 20 y 30'
				WHEN cnsaldoactual >= 30 and cnsaldoactual < 40 THEN 'Entre 30 y 40'
				WHEN cnsaldoactual >= 40 and cnsaldoactual < 50 THEN 'Entre 40 y 50'
				WHEN cnsaldoactual >= 50 and cnsaldoactual < 60 THEN 'Entre 50 y 60'
				WHEN cnsaldoactual >= 60 and cnsaldoactual < 70 THEN 'Entre 60 y 70'
				WHEN cnsaldoactual >= 70 and cnsaldoactual < 80 THEN 'Entre 70 y 80'
				WHEN cnsaldoactual >= 80 and cnsaldoactual < 90 THEN 'Entre 80 y 90'
				WHEN cnsaldoactual >= 90 and cnsaldoactual < 100 THEN 'Entre 90 y 100'
			    ELSE 'Mayor que 100' end  as rango";			
				$cambiar = 1;

			}else if($vistaSel == 'B'){
				// si la vista es boletas
				$auxActual = "ifnull((select Sum(bcprecio) FROM vtiger_boletascanjeadas WHERE `bccontacto` = `contactid` AND bcfecha BETWEEN '".$desdeActual." 00:00:00' AND '".$hastaActual." 23:59:59'),0) as 'cnsaldoactual'";
				$auxAnterior = "ifnull((select Sum(bcprecio) FROM vtiger_boletascanjeadas WHERE `bccontacto` = `contactid` AND bcfecha BETWEEN '".$desdeAnterior." 00:00:00' AND '".$hastaAnterior." 23:59:59'),0) as 'cnsaldoanterior'";
				$auxSaldoActual="(select Sum(bcprecio) FROM vtiger_boletascanjeadas WHERE `bccontacto` = `contactid` AND bcfecha BETWEEN '".$desdeActual." 00:00:00' AND '".$hastaActual." 23:59:59')";
				$auxSaldoAnterior="(select Sum(bcprecio) FROM vtiger_boletascanjeadas WHERE `bccontacto` = `contactid` AND bcfecha BETWEEN '".$desdeAnterior." 00:00:00' AND '".$hastaAnterior." 23:59:59')";
				$auxDiferencia="ifnull(ifnull($auxSaldoActual,0)-ifnull($auxSaldoAnterior,0),0) as 'cndiferencia'";	

				$auxRango = "case when cnsaldoactual < 10000 then 'Menor que 10000'
					WHEN cnsaldoactual >= 10000 and cnsaldoactual < 20000 THEN 'Entre 10000 y 20000'
					WHEN cnsaldoactual >= 20000 and cnsaldoactual < 30000 THEN 'Entre 20000 y 30000'
					WHEN cnsaldoactual >= 30000 and cnsaldoactual < 40000 THEN 'Entre 30000 y 40000'
					WHEN cnsaldoactual >= 40000 and cnsaldoactual < 50000 THEN 'Entre 40000 y 50000'
					WHEN cnsaldoactual >= 50000 and cnsaldoactual < 60000 THEN 'Entre 50000 y 60000'
					WHEN cnsaldoactual >= 60000 and cnsaldoactual < 70000 THEN 'Entre 60000 y 70000'
					WHEN cnsaldoactual >= 70000 and cnsaldoactual < 80000 THEN 'Entre 70000 y 80000'
					WHEN cnsaldoactual >= 80000 and cnsaldoactual < 90000 THEN 'Entre 80000 y 90000'
					WHEN cnsaldoactual >= 90000 and cnsaldoactual < 100000 THEN 'Entre 90000 y 100000'
				    ELSE 'Mayor que 10000' end  as rango";	

				$cambiar = 1;
			} else if($vistaSel == 'S') {
				// si la vista es scotia
				$auxActual = "ifnull((select Sum(importe) FROM vtiger_comprasscotia WHERE `contacto` = `contactid` AND fecha BETWEEN '".$desdeActual." 00:00:00' AND '".$hastaActual." 23:59:59'),0) as 'cnsaldoactual'";
				$auxAnterior = "ifnull((select Sum(importe) FROM vtiger_comprasscotia WHERE `contacto` = `contactid` AND fecha BETWEEN '".$desdeAnterior." 00:00:00' AND '".$hastaAnterior." 23:59:59'),0) as 'cnsaldoanterior'";
				$auxSaldoActual="(select Sum(importe) FROM vtiger_comprasscotia WHERE `contacto` = `contactid` AND fecha BETWEEN '".$desdeActual." 00:00:00' AND '".$hastaActual." 23:59:59')";
				$auxSaldoAnterior="(select Sum(importe) FROM vtiger_comprasscotia WHERE `contacto` = `contactid` AND fecha BETWEEN '".$desdeAnterior." 00:00:00' AND '".$hastaAnterior." 23:59:59')";
				$auxDiferencia="ifnull(ifnull($auxSaldoActual,0)-ifnull($auxSaldoAnterior,0),0) as 'cndiferencia'";
				$auxRango = "case when cnsaldoactual < 10000 then 'Menor que 10000'
					WHEN cnsaldoactual >= 10000 and cnsaldoactual < 20000 THEN 'Entre 10000 y 20000'
					WHEN cnsaldoactual >= 20000 and cnsaldoactual < 30000 THEN 'Entre 20000 y 30000'
					WHEN cnsaldoactual >= 30000 and cnsaldoactual < 40000 THEN 'Entre 30000 y 40000'
					WHEN cnsaldoactual >= 40000 and cnsaldoactual < 50000 THEN 'Entre 40000 y 50000'
					WHEN cnsaldoactual >= 50000 and cnsaldoactual < 60000 THEN 'Entre 50000 y 60000'
					WHEN cnsaldoactual >= 60000 and cnsaldoactual < 70000 THEN 'Entre 60000 y 70000'
					WHEN cnsaldoactual >= 70000 and cnsaldoactual < 80000 THEN 'Entre 70000 y 80000'
					WHEN cnsaldoactual >= 80000 and cnsaldoactual < 90000 THEN 'Entre 80000 y 90000'
					WHEN cnsaldoactual >= 90000 and cnsaldoactual < 100000 THEN 'Entre 90000 y 100000'
				    ELSE 'Mayor que 10000' end  as rango";
				$cambiar = 1;
			}

		}        
		if($vistaSel == 'B' && $confecha == false){
		// si la vista es boletas canjeadas y no tiene filtro de fechas
				$auxActual = "ifnull((SELECT SUM(bcprecio) FROM vtiger_boletascanjeadas WHERE `bccontacto` = `contactid` AND bcfecha >= DATE_SUB(NOW(), INTERVAL 1 YEAR)),0) AS 'cnsaldoactual'";
		


				$auxAnterior = "ifnull((SELECT SUM(bcprecio) FROM vtiger_boletascanjeadas WHERE `bccontacto` = `contactid` AND bcfecha >= Date_sub(Now(), INTERVAL 24 month)
                            AND bcfecha < Date_sub(Now(), INTERVAL 12 month)),0) AS 'cnsaldoanterior'";
		


				$auxSaldoActual = "(SELECT SUM(bcprecio) AS 'cnsaldoactual' FROM vtiger_boletascanjeadas WHERE `bccontacto` = `contactid` AND bcfecha >= DATE_SUB(NOW(), INTERVAL 1 YEAR))";
				


				$auxSaldoAnterior = "(SELECT SUM(bcprecio) AS 'cnsaldoanterior' FROM vtiger_boletascanjeadas WHERE `bccontacto` = `contactid` AND bcfecha >= Date_sub(Now(), INTERVAL 24 month)
                            AND bcfecha < Date_sub(Now(), INTERVAL 12 month))";
				

				$auxDiferencia="ifnull(ifnull($auxSaldoActual,0)-ifnull($auxSaldoAnterior,0),0) as 'cndiferencia'";		
						

				$cambiar = 1;

			}


		$sSelect = str_replace(" , ", " ", implode(", ", $aColumns));		
			if($cambiar == 1){
				$aux = str_replace(" , ", " ", implode(", ", $aColumns));
				$b = str_replace('cnsaldoactual',$auxActual,$aux);
				$c = str_replace('cnsaldoanterior',$auxAnterior,$b);
				$d = str_replace('assigned_user_id', "(select concat(first_name, ' ', last_name) from vtiger_users u where u.id = vtiger_crmentity.smownerid) as assigned_user_id", $c);
				$e = str_replace('account_id', "accountname  as account_id", $d);
				//$d = str_replace('`cndiferencia`',$auxDiferencia,$c);
				$sSelect = $d;
			}
						
$log->debug("Vista SEL: $vistaSel");

$log->debug("Aux Actual: $auxActual");
$log->debug("Aux anterior: $auxAnterior");
$log->debug("Aux diferencia: $auxDiferencia");

           	$sQuery = "
    	SELECT  $auxRango, Q.* FROM ( SELECT ".$sSelect."
            FROM   $sTable
            $sWhere
            $sGroup
            $sOrder
            $sLimit
        	) as Q
            ";
            
            $queryAddToCampaign = "".
		        "SELECT ".$sSelect."
		        FROM  
		            $sTable
		        $sWhere
		        GROUP BY 1
		        $sOrder";

	        if(file_exists("archQueryAddToCampaign.txt"))
	            unlink("archQueryAddToCampaign.txt");
	        $fileAddToCampaign = fopen("archQueryAddToCampaign.txt", "a");
	        fwrite($fileAddToCampaign, $queryAddToCampaign);
	        fclose($fileAddToCampaign);

	    $log->debug("PIMBA QUERY 1".$sQuery);
        $rResult=$adb->query($sQuery);

 	    $data = array();
       	
       	//saco todos los label de los campos primero
 	    $nombresCampos = array();
		$sql = "SELECT fieldname, fieldlabel from vtiger_field where tabid = 4 order by fieldlabel";
		$result2 = $adb->query($sql);
		while($row = $adb->fetch_array($result2)){
	    	$nombresCampos[$row['fieldname']] = vtranslate($row["fieldlabel"], "Contacts");
		}

		//meto el rango a prepo
    	$nombresCampos["rango"] = "Rango";
		$campos_pivot_originales[] = "rango";

        while ( $aRow =$adb->fetch_array($rResult) ){
        	$arrayCamposData = array();
        	foreach ($campos_pivot_originales as $cp) {
        		$arrayCamposData[$nombresCampos[$cp]] = $aRow[$cp];
        	}
        	$data[] = $arrayCamposData;

        }
        $headers = array();

        foreach ($campos_pivot_originales as $cp) {
    		$headers[$cp] = $nombresCampos[$cp];
    	}

        $retornar = array("cols" => $headers, "data" => $data, "fech" => array());
         
		echo  json_encode($retornar);
		
	}
	
	
}