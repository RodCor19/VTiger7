<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * ************************************************************************************/

class Analisis_Module_Model extends Vtiger_Module_Model {

	/**
	 * Function to get the Quick Links for the module
	 * @param <Array> $linkParams
	 * @return <Array> List of Vtiger_Link_Model instances
	 */
	public function getSideBarLinks($linkParams) {
		$parentQuickLinks = parent::getSideBarLinks($linkParams);

		$quickLink = array(
				'linktype' => 'SIDEBARLINK',
				'linklabel' => 'LBL_DASHBOARD',
				'linkurl' => $this->getDashBoardUrl(),
				'linkicon' => '',
		);

		//Check profile permissions for Dashboards
		$moduleModel = Vtiger_Module_Model::getInstance('Dashboard');
		$userPrivilegesModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
		$permission = $userPrivilegesModel->hasModulePermission($moduleModel->getId());
		if($permission) {
			$parentQuickLinks['SIDEBARLINK'][] = Vtiger_Link_Model::getInstanceFromValues($quickLink);
		}
		
		return $parentQuickLinks;
	}

	/**
	 * Function to get Settings links for admin user
	 * @return Array
	 */
	public function getSettingLinks() {
		$settingsLinks = parent::getSettingLinks();
		$currentUserModel = Users_Record_Model::getCurrentUserModel();

		if ($currentUserModel->isAdminUser()) {
			$settingsLinks[] = array(
				'linktype' => 'LISTVIEWSETTING',
				'linklabel' => 'LBL_EDIT_MAILSCANNER',
				'linkurl' =>'index.php?parent=Settings&module=MailConverter&view=List',
				'linkicon' => ''
			);
		}
		return $settingsLinks;
	}


	/**
	 * Function returns Tickets grouped by Status
	 * @param type $data
	 * @return <Array>
	 */
	
	
	
	
	
	public function getVentilacionClientes(Vtiger_Request $request) {
		
		$adb = PearDatabase::getInstance();


		/*GENERICO
		SOCIO AFIN
		SOCIO J&J
		TARJETA GAVIOTAS
		ADMINISTRATIVO
		FUNCIONARIO
		PROVEEDOR
		CLUB de GOLF
		LOS 1, WTC Member,COMPRADOR FRECUENTE*/

		 $query="
			SELECT COUNT(*) AS total_personas ,
			SUM(IF(programa LIKE '%WTC Member%',1,0 )) AS wtc,
			SUM(IF(programa LIKE '%Comprador Frecuente%',1,0 )) AS frecuentes,
			SUM(IF(programa LIKE '%Los 1%',1,0 )) AS unos,
			SUM(IF(programa LIKE '%Tarjeta Gaviotas%',1,0 )) AS gaviotas,
			SUM(IF(programa LIKE '%Generico%',1,0 )) AS genericos,
			SUM(IF(programa LIKE '%Socio Afin%',1,0 )) AS afines,
			SUM(IF(programa LIKE '%Socio J&J%',1,0 )) AS jyjs,
			SUM(IF(programa LIKE '%Administrativo%',1,0 )) AS administrativos,
			SUM(IF(programa LIKE '%Funcionario%',1,0 )) AS funcionarios,
			SUM(IF(programa LIKE '%Proveedor%',1,0 )) AS proveedores,
			SUM(IF(programa LIKE '%Club de Golf%',1,0 )) AS golfs,
			
			SUM(IF(programa LIKE '%WTC Member%' AND programa LIKE '%Comprador Frecuente%' ,1,0 )) AS wtc_frecuentes,
			SUM(IF(programa LIKE '%WTC Member%' AND programa LIKE '%Los 1%' ,1,0 )) AS wtc_unos,
			SUM(IF(programa LIKE '%WTC Member%' AND programa LIKE '%Tarjeta Gaviotas%' ,1,0 )) AS wtc_gaviotas,
			SUM(IF(programa LIKE '%WTC Member%' AND programa LIKE '%Generico%' ,1,0 )) AS wtc_genericos,
			SUM(IF(programa LIKE '%WTC Member%' AND programa LIKE '%Socio Afin%' ,1,0 )) AS wtc_afines,
			SUM(IF(programa LIKE '%WTC Member%' AND programa LIKE '%Socio J&J%' ,1,0 )) AS wtc_jyjs,
			SUM(IF(programa LIKE '%WTC Member%' AND programa LIKE '%Administrativo%' ,1,0 )) AS wtc_administrativos,
			SUM(IF(programa LIKE '%WTC Member%' AND programa LIKE '%Funcionario%' ,1,0 )) AS wtc_funcionarios,
			SUM(IF(programa LIKE '%WTC Member%' AND programa LIKE '%Proveedor%' ,1,0 )) AS wtc_proveedores,
			SUM(IF(programa LIKE '%WTC Member%' AND programa LIKE '%Club de Golf%' ,1,0 )) AS wtc_golfs,
			
			SUM(IF(programa LIKE '%Comprador Frecuente%' AND programa LIKE '%Los 1%' ,1,0 )) AS frecuentes_unos,
			SUM(IF(programa LIKE '%Comprador Frecuente%' AND programa LIKE '%Tarjeta Gaviotas%' ,1,0 )) AS frecuentes_gaviotas,
			SUM(IF(programa LIKE '%Comprador Frecuente%' AND programa LIKE '%Generico%' ,1,0 )) AS frecuentes_genericos,
			SUM(IF(programa LIKE '%Comprador Frecuente%' AND programa LIKE '%Socio Afin%' ,1,0 )) AS frecuentes_afines,
			SUM(IF(programa LIKE '%Comprador Frecuente%' AND programa LIKE '%Socio J&J%' ,1,0 )) AS frecuentes_jyjs,
			SUM(IF(programa LIKE '%Comprador Frecuente%' AND programa LIKE '%Administrativo%' ,1,0 )) AS frecuentes_administrativos,
			SUM(IF(programa LIKE '%Comprador Frecuente%' AND programa LIKE '%Funcionario%' ,1,0 )) AS frecuentes_funcionarios,
			SUM(IF(programa LIKE '%Comprador Frecuente%' AND programa LIKE '%Proveedor%' ,1,0 )) AS frecuentes_proveedores,
			SUM(IF(programa LIKE '%Comprador Frecuente%' AND programa LIKE '%Club de Golf%' ,1,0 )) AS frecuentes_golfs,
			

			SUM(IF(programa LIKE '%Los 1%' AND programa LIKE '%Tarjeta Gaviotas%' ,1,0 )) AS unos_gaviotas,
			SUM(IF(programa LIKE '%Los 1%' AND programa LIKE '%Genericos%' ,1,0 )) AS unos_genericos,
			SUM(IF(programa LIKE '%Los 1%' AND programa LIKE '%Socio Afin%' ,1,0 )) AS unos_afines,
			SUM(IF(programa LIKE '%Los 1%' AND programa LIKE '%Socio J&J%' ,1,0 )) AS unos_jyjs,
			SUM(IF(programa LIKE '%Los 1%' AND programa LIKE '%Administrativo%' ,1,0 )) AS unos_administrativos,
			SUM(IF(programa LIKE '%Los 1%' AND programa LIKE '%Funcionario%' ,1,0 )) AS unos_funcionarios,
			SUM(IF(programa LIKE '%Los 1%' AND programa LIKE '%Proveedor%' ,1,0 )) AS unos_proveedores,
			SUM(IF(programa LIKE '%Los 1%' AND programa LIKE '%Club de Golf%' ,1,0 )) AS unos_golfs,

			SUM(IF(programa LIKE '%Tarjeta Gaviotas%' AND programa LIKE '%Genericos%' ,1,0 )) AS gaviotas_genericos,
			SUM(IF(programa LIKE '%Tarjeta Gaviotas%' AND programa LIKE '%Socio Afin%' ,1,0 )) AS gaviotas_afines,
			SUM(IF(programa LIKE '%Tarjeta Gaviotas%' AND programa LIKE '%Socio J&J%' ,1,0 )) AS gaviotas_jyjs,
			SUM(IF(programa LIKE '%Tarjeta Gaviotas%' AND programa LIKE '%Administrativo%' ,1,0 )) AS gaviotas_administrativos,
			SUM(IF(programa LIKE '%Tarjeta Gaviotas%' AND programa LIKE '%Funcionario%' ,1,0 )) AS gaviotas_funcionarios,
			SUM(IF(programa LIKE '%Tarjeta Gaviotas%' AND programa LIKE '%Proveedor%' ,1,0 )) AS gaviotas_proveedores,
			SUM(IF(programa LIKE '%Tarjeta Gaviotas%' AND programa LIKE '%Club de Golf%' ,1,0 )) AS gaviotas_golfs,

			SUM(IF(programa LIKE '%Socio Afin%' AND programa LIKE '%Socio J&J%' ,1,0 )) AS afines_jyjs,
			SUM(IF(programa LIKE '%Socio Afin%' AND programa LIKE '%Administrativo%' ,1,0 )) AS afines_administrativos,
			SUM(IF(programa LIKE '%Socio Afin%' AND programa LIKE '%Funcionario%' ,1,0 )) AS afines_funcionarios,
			SUM(IF(programa LIKE '%Socio Afin%' AND programa LIKE '%Proveedor%' ,1,0 )) AS afines_proveedores,
			SUM(IF(programa LIKE '%Socio Afin%' AND programa LIKE '%Club de Golf%' ,1,0 )) AS afines_golfs,

			SUM(IF(programa LIKE '%Socio J&J%' AND programa LIKE '%Administrativo%' ,1,0 )) AS jyjs_administrativos,
			SUM(IF(programa LIKE '%Socio J&J%' AND programa LIKE '%Funcionario%' ,1,0 )) AS jyjs_funcionarios,
			SUM(IF(programa LIKE '%Socio J&J%' AND programa LIKE '%Proveedor%' ,1,0 )) AS jyjs_proveedores,
			SUM(IF(programa LIKE '%Socio J&J%' AND programa LIKE '%Club de Golf%' ,1,0 )) AS jyjs_golfs,

			SUM(IF(programa LIKE '%Administrativo%' AND programa LIKE '%Funcionario%' ,1,0 )) AS administrativos_funcionarios,
			SUM(IF(programa LIKE '%Administrativo%' AND programa LIKE '%Proveedor%' ,1,0 )) AS administrativos_proveedores,
			SUM(IF(programa LIKE '%Administrativo%' AND programa LIKE '%Club de Golf%' ,1,0 )) AS administrativos_golfs,

			SUM(IF(programa LIKE '%Funcionario%' AND programa LIKE '%Proveedor%' ,1,0 )) AS funcionarios_proveedores,
			SUM(IF(programa LIKE '%Funcionario%' AND programa LIKE '%Club de Golf%' ,1,0 )) AS funcionarios_golfs,

			SUM(IF(programa LIKE '%Proveedor%' AND programa LIKE '%Club de Golf%' ,1,0 )) AS proveedores_golfs

			FROM vtiger_contactdetails WHERE 1=1 
        ";
			
		$sexo = $request->get('sexo');
		$canal = $request->get('canal');
		$estatuto = $request->get('estatuto');
		$rango =htmlspecialchars_decode($request->get('rango'));
		$programa = $request->get('programa');

		if(!empty($rango) && $rango!="") {
							//$query.=" and vtiger_contactdetails.rango_edades='".$rango."'";
			$rangos=explode(",", $rango);
			$query.=" AND (";
			foreach($rangos as $id){
				//$query.=" FIND_IN_SET('".$id."',rango_edades)<>0 OR";	
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
			foreach($canales as $id){
				//$query.=" FIND_IN_SET('".$id."',canal_activo)<>0 OR";	
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
				//$query.=" FIND_IN_SET('".$id."',estatuto)<>0 OR";	
				$query.=" estatuto LIKE '%".$id."%' OR";	
			}	
			$query=rtrim($query,'OR');
			$query.=" )";
		}
		
		if(!empty($programa) && $programa!="") {
			$programas=explode(",", $programa);
			$query.=" AND (";
			foreach($programas as $id){
				//$query.=" programa LIKE '%".$id."%' OR";	
				$query.=" programa LIKE '%".$id."%' OR";	
			}	
			$query=rtrim($query,'OR');
			$query.=" )";
		}
		
		$result=$adb->query($query);
		$no_of_rows=$adb->num_rows($result);

		//Inicializo matriz
		$matriz['wtc']['frecuentes']=$matriz['wtc']['unos']=$matriz['wtc']['gaviotas']=$matriz['wtc']['genericos']=0;
		$matriz['wtc']['afines']=$matriz['wtc']['jyjs']=$matriz['wtc']['administrativos']=$matriz['wtc']['funcionarios']=0;
		$matriz['wtc']['proveedores']=$matriz['wtc']['golfs']=0;

		$matriz['frecuentes']['wtc']=$matriz['frecuentes']['unos']=$matriz['frecuentes']['gaviotas']=$matriz['frecuentes']['genericos']=0;
		$matriz['frecuentes']['afines']=$matriz['frecuentes']['jyjs']=$matriz['frecuentes']['administrativos']=$matriz['frecuentes']['funcionarios']=0;
		$matriz['frecuentes']['proveedores']=$matriz['frecuentes']['golfs']=0;
		
		$matriz['unos']['wtc']=$matriz['unos']['frecuentes']=$matriz['unos']['gaviotas']=$matriz['unos']['genericos']=0;
		$matriz['unos']['afines']=$matriz['unos']['jyjs']=$matriz['unos']['administrativos']=$matriz['unos']['funcionarios']=0;
		$matriz['unos']['proveedores']=$matriz['unos']['golfs']=0;
		
		$matriz['gaviotas']['wtc']=$matriz['gaviotas']['unos']=$matriz['gaviotas']['genericos']=$matriz['gaviotas']['frecuentes']=0;
		$matriz['gaviotas']['afines']=$matriz['gaviotas']['jyjs']=$matriz['gaviotas']['administrativos']=$matriz['gaviotas']['funcionarios']=0;
		$matriz['gaviotas']['proveedores']=$matriz['gaviotas']['golfs']=0;
		
		$matriz['genericos']['wtc']=$matriz['genericos']['unos']=$matriz['genericos']['frecuentes']=$matriz['genericos']['gaviotas']=0;
		$matriz['genericos']['afines']=$matriz['genericos']['jyjs']=$matriz['genericos']['administrativos']=$matriz['genericos']['funcionarios']=0;
		$matriz['genericos']['proveedores']=$matriz['genericos']['golfs']=0;
		
		$matriz['afines']['wtc']=$matriz['afines']['unos']=$matriz['afines']['frecuentes']=$matriz['afines']['gaviotas']=0;
		$matriz['afines']['genericos']=$matriz['afines']['jyjs']=$matriz['afines']['administrativos']=$matriz['afines']['funcionarios']=0;
		$matriz['afines']['proveedores']=$matriz['afines']['golfs']=0;
		
		$matriz['jyjs']['wtc']=$matriz['jyjs']['unos']=$matriz['jyjs']['frecuentes']=$matriz['jyjs']['gaviotas']=0;
		$matriz['jyjs']['genericos']=$matriz['jyjs']['afines']=$matriz['jyjs']['administrativos']=$matriz['jyjs']['funcionarios']=0;
		$matriz['jyjs']['proveedores']=$matriz['jyjs']['golfs']=0;
		
		$matriz['administrativos']['wtc']=$matriz['administrativos']['unos']=$matriz['administrativos']['frecuentes']=$matriz['administrativos']['gaviotas']=0;
		$matriz['administrativos']['genericos']=$matriz['administrativos']['afines']=$matriz['administrativos']['jyjs']=$matriz['administrativos']['funcionarios']=0;
		$matriz['administrativos']['proveedores']=$matriz['administrativos']['golfs']=0;
		
		$matriz['funcionarios']['wtc']=$matriz['funcionarios']['unos']=$matriz['funcionarios']['frecuentes']=$matriz['funcionarios']['gaviotas']=0;
		$matriz['funcionarios']['genericos']=$matriz['funcionarios']['afines']=$matriz['funcionarios']['jyjs']=$matriz['funcionarios']['administrativos']=0;
		$matriz['funcionarios']['proveedores']=$matriz['funcionarios']['golfs']=0;
		
		$matriz['proveedores']['wtc']=$matriz['proveedores']['unos']=$matriz['proveedores']['frecuentes']=$matriz['proveedores']['gaviotas']=0;
		$matriz['proveedores']['genericos']=$matriz['proveedores']['afines']=$matriz['proveedores']['jyjs']=$matriz['proveedores']['administrativos']=0;
		$matriz['proveedores']['funcionarios']=$matriz['proveedores']['golfs']=0;

		$matriz['golfs']['wtc']=$matriz['golfs']['unos']=$matriz['golfs']['frecuentes']=$matriz['golfs']['gaviotas']=0;
		$matriz['golfs']['genericos']=$matriz['golfs']['afines']=$matriz['golfs']['jyjs']=$matriz['golfs']['administrativos']=0;
		$matriz['golfs']['funcionarios']=$matriz['golfs']['proveedores']=0;
		
		
		if($no_of_rows>0){

			$total_personas = number_format($adb->query_result($result,0,'total_personas'),0,",",".");
			$wtc = number_format($adb->query_result($result,0,'wtc'),0,",",".");
			$unos = number_format($adb->query_result($result,0,'unos'),0,",",".");
			$frecuentes = number_format($adb->query_result($result,0,'frecuentes'),0,",",".");
			$gaviotas = number_format($adb->query_result($result,0,'gaviotas'),0,",",".");
			$genericos = number_format($adb->query_result($result,0,'genericos'),0,",",".");
			$afines = number_format($adb->query_result($result,0,'afines'),0,",",".");
			$jyjs = number_format($adb->query_result($result,0,'jyjs'),0,",",".");
			$administrativos = number_format($adb->query_result($result,0,'administrativos'),0,",",".");
			$funcionarios = number_format($adb->query_result($result,0,'funcionarios'),0,",",".");
			$proveedores = number_format($adb->query_result($result,0,'proveedores'),0,",",".");
			$golfs = number_format($adb->query_result($result,0,'golfs'),0,",",".");
			
			
			
			$matriz['wtc']['frecuentes'] =$matriz['frecuentes']['wtc'] = $adb->query_result($result,0,'wtc_frecuentes');
			$matriz['wtc']['unos'] =$matriz['unos']['wtc'] =$adb->query_result($result,0,'wtc_unos');
			$matriz['wtc']['gaviotas'] =$matriz['gaviotas']['wtc'] =$adb->query_result($result,0,'wtc_gaviotas');
			$matriz['wtc']['genericos'] =$matriz['genericos']['wtc'] = $adb->query_result($result,0,'wtc_genericos');
			$matriz['wtc']['afines'] =$matriz['afines']['wtc'] = $adb->query_result($result,0,'wtc_afines');
			$matriz['wtc']['jyjs'] =$matriz['jyjs']['wtc'] = $adb->query_result($result,0,'wtc_jyjs');
			$matriz['wtc']['administrativos'] =$matriz['administrativos']['wtc'] = $adb->query_result($result,0,'wtc_administrativos');
			$matriz['wtc']['funcionarios'] =$matriz['funcionarios']['wtc'] = $adb->query_result($result,0,'wtc_funcionarios');
			$matriz['wtc']['proveedores'] =$matriz['proveedores']['wtc'] = $adb->query_result($result,0,'wtc_proveedores');
			$matriz['wtc']['golfs'] =$matriz['golfs']['wtc'] = $adb->query_result($result,0,'wtc_golfs');
			
			$matriz['frecuentes']['unos'] =$matriz['unos']['frecuentes'] =$adb->query_result($result,0,'frecuentes_unos');
			$matriz['frecuentes']['gaviotas'] =$matriz['gaviotas']['frecuentes'] =$adb->query_result($result,0,'frecuentes_gaviotas');
			$matriz['frecuentes']['genericos'] =$matriz['genericos']['frecuentes'] = $adb->query_result($result,0,'frecuentes_genericos');
			$matriz['frecuentes']['afines'] =$matriz['afines']['frecuentes'] = $adb->query_result($result,0,'frecuentes_afines');
			$matriz['frecuentes']['jyjs'] =$matriz['jyjs']['frecuentes'] = $adb->query_result($result,0,'frecuentes_jyjs');
			$matriz['frecuentes']['administrativos'] =$matriz['administrativos']['frecuentes'] = $adb->query_result($result,0,'frecuentes_administrativos');
			$matriz['frecuentes']['funcionarios'] =$matriz['funcionarios']['frecuentes'] = $adb->query_result($result,0,'frecuentes_funcionarios');
			$matriz['frecuentes']['proveedores'] =$matriz['proveedores']['frecuentes'] = $adb->query_result($result,0,'frecuentes_proveedores');
			$matriz['frecuentes']['golfs'] =$matriz['golfs']['frecuentes'] = $adb->query_result($result,0,'frecuentes_golfs');
			

			$matriz['unos']['gaviotas'] =$matriz['gaviotas']['unos'] =$adb->query_result($result,0,'unos_gaviotas');
			$matriz['unos']['genericos'] =$matriz['genericos']['unos'] = $adb->query_result($result,0,'unos_genericos');
			$matriz['unos']['afines'] =$matriz['afines']['unos'] = $adb->query_result($result,0,'unos_afines');
			$matriz['unos']['jyjs'] =$matriz['jyjs']['unos'] = $adb->query_result($result,0,'unos_jyjs');
			$matriz['unos']['administrativos'] =$matriz['administrativos']['unos'] = $adb->query_result($result,0,'unos_administrativos');
			$matriz['unos']['funcionarios'] =$matriz['funcionarios']['unos'] = $adb->query_result($result,0,'unos_funcionarios');
			$matriz['unos']['proveedores'] =$matriz['proveedores']['unos'] = $adb->query_result($result,0,'unos_proveedores');
			$matriz['unos']['golfs'] =$matriz['golfs']['unos'] = $adb->query_result($result,0,'unos_golfs');
			
			$matriz['gaviotas']['genericos'] =$matriz['genericos']['gaviotas'] = $adb->query_result($result,0,'gaviotas_genericos');
			$matriz['gaviotas']['afines'] =$matriz['afines']['gaviotas'] = $adb->query_result($result,0,'gaviotas_afines');
			$matriz['gaviotas']['jyjs'] =$matriz['jyjs']['gaviotas'] = $adb->query_result($result,0,'gaviotas_jyjs');
			$matriz['gaviotas']['administrativos'] =$matriz['administrativos']['gaviotas'] = $adb->query_result($result,0,'gaviotas_administrativos');
			$matriz['gaviotas']['funcionarios'] =$matriz['funcionarios']['gaviotas'] = $adb->query_result($result,0,'gaviotas_funcionarios');
			$matriz['gaviotas']['proveedores'] =$matriz['proveedores']['gaviotas'] = $adb->query_result($result,0,'gaviotas_proveedores');
			$matriz['gaviotas']['golfs'] =$matriz['golfs']['gaviotas'] = $adb->query_result($result,0,'gaviotas_golfs');

			$matriz['genericos']['afines'] =$matriz['afines']['genericos'] = $adb->query_result($result,0,'genericos_afines');
			$matriz['genericos']['jyjs'] =$matriz['jyjs']['genericos'] = $adb->query_result($result,0,'genericos_jyjs');
			$matriz['genericos']['administrativos'] =$matriz['administrativos']['genericos'] = $adb->query_result($result,0,'genericos_administrativos');
			$matriz['genericos']['funcionarios'] =$matriz['funcionarios']['genericos'] = $adb->query_result($result,0,'genericos_funcionarios');
			$matriz['genericos']['proveedores'] =$matriz['proveedores']['genericos'] = $adb->query_result($result,0,'genericos_proveedores');
			$matriz['genericos']['golfs'] =$matriz['golfs']['genericos'] = $adb->query_result($result,0,'genericos_golfs');
			
			$matriz['afines']['jyjs'] =$matriz['jyjs']['afines'] = $adb->query_result($result,0,'afines_jyjs');
			$matriz['afines']['administrativos'] =$matriz['administrativos']['afines'] = $adb->query_result($result,0,'afines_administrativos');
			$matriz['afines']['funcionarios'] =$matriz['funcionarios']['afines'] = $adb->query_result($result,0,'afines_funcionarios');
			$matriz['afines']['proveedores'] =$matriz['proveedores']['afines'] = $adb->query_result($result,0,'afines_proveedores');
			$matriz['afines']['golfs'] =$matriz['golfs']['afines'] = $adb->query_result($result,0,'afines_golfs');

			$matriz['jyjs']['administrativos'] =$matriz['administrativos']['jyjs'] = $adb->query_result($result,0,'jyjs_administrativos');
			$matriz['jyjs']['funcionarios'] =$matriz['funcionarios']['jyjs'] = $adb->query_result($result,0,'jyjs_funcionarios');
			$matriz['jyjs']['proveedores'] =$matriz['proveedores']['jyjs'] = $adb->query_result($result,0,'jyjs_proveedores');
			$matriz['jyjs']['golfs'] =$matriz['golfs']['jyjs'] = $adb->query_result($result,0,'jyjs_golfs');
			

			$matriz['administrativos']['funcionarios'] =$matriz['funcionarios']['administrativos'] = $adb->query_result($result,0,'administrativos_funcionarios');
			$matriz['administrativos']['proveedores'] =$matriz['proveedores']['administrativos'] = $adb->query_result($result,0,'administrativos_proveedores');
			$matriz['administrativos']['golfs'] =$matriz['golfs']['administrativos'] = $adb->query_result($result,0,'administrativos_golfs');
			
			$matriz['funcionarios']['proveedores'] =$matriz['proveedores']['funcionarios'] = $adb->query_result($result,0,'funcionarios_proveedores');
			$matriz['funcionarios']['golfs'] =$matriz['golfs']['funcionarios'] = $adb->query_result($result,0,'funcionarios_golfs');

			$matriz['proveedores']['golfs'] =$matriz['golfs']['proveedores'] = $adb->query_result($result,0,'funcionarios_golfs');
			

			if(isset($wtc) && $wtc!=0) $porc_wtc_frecuentes = (($wtc_frecuentes/$wtc)*100)."%";
			if(isset($frecuentes) && $frecuentes!=0) $porc_frecuentes_wtc = (($wtc_frecuentes/$frecuentes)*100)."%";
			if(isset($wtc) && $wtc!=0) $porc_wtc_beneficios = (($wtc_beneficios/$wtc)*100)."%";
			if(isset($beneficios) && $beneficios!=0) $porc_beneficios_wtc = (($wtc_beneficios/$beneficios)*100)."%";
			if(isset($wtc) && $wtc!=0) $porc_wtc_gaviotas = (($wtc_gaviotas/$wtc)*100)."%";
			if(isset($gaviotas) && $gaviotas!=0) $porc_gaviotas_wtc = (($wtc_gaviotas/$gaviotas)*100)."%";
			if(isset($wtc) && $wtc!=0) $porc_wtc_unos = (($wtc_unos/$wtc)*100)."%";
			if(isset($unos) && $unos!=0) $porc_unos_wtc = (($wtc_unos/$unos)*100)."%";


			



			$json_string="[[\"Programa\",\"Todas Personas\",\"Generico\",\"Comprador Frecuente\",\"Socio Afin\",\"Los 1\",
							\"Socio J&J\",\"Tarjeta Gaviotas\",\"Administrativo\",\"Funcionario\",\"Proveedor\",\"WTC Member\",
							\"Club de Golf\",\"Orden\"],";
			
			$json_string.="[\"Todas Personas\",\"".$total_personas."\",\"".$this->getPctje($genericos,$total_personas)."\",
							\"".$this->getPctje($frecuentes,$total_personas)."\",\"".$this->getPctje($afines,$total_personas)."\",\"".$this->getPctje($unos,$total_personas)."\",
							\"".$this->getPctje($jyjs,$total_personas)."\",\"".$this->getPctje($gaviotas,$total_personas)."\",\"".$this->getPctje($administrativos,$total_personas)."\",
							\"".$this->getPctje($funcionarios,$total_personas)."\",\"".$this->getPctje($proveedores,$total_personas)."\",\"".$this->getPctje($wtc,$total_personas)."\",
							\"".$this->getPctje($golfs,$total_personas)."\",\"999\"],";

			/*$json_string.="[\"Generico\",\"100%\",\"".$genericos."\",\"".$this->getPctje($matriz["genericos"]["frecuentes"],$genericos)."\",
			\"".$this->getPctje($matriz["genericos"]["afines"],$genericos)."\",\"".$this->getPctje($matriz["genericos"]["unos"],$genericos)."\",
			\"".$this->getPctje($matriz["genericos"]["jyjs"],$genericos)."\",\"".$this->getPctje($matriz["genericos"]["gaviotas"],$genericos)."\",
			\"".$this->getPctje($matriz["genericos"]["administrativos"],$genericos)."\",\"".$this->getPctje($matriz["genericos"]["funcionarios"],$genericos)."\",
			\"".$this->getPctje($matriz["genericos"]["proveedores"],$genericos)."\",\"".$this->getPctje($matriz["genericos"]["wtc"],$genericos)."\",
			\"".$this->getPctje($matriz["genericos"]["golfs"],$genericos)."\",\"998\"],";
			*/
			$json_string.="[\"Comprador Frecuente\",\"100%\",\"".$this->getPctje($matriz["genericos"]["frecuentes"],$frecuentes)."\",\"".$frecuentes."\",
			\"".$this->getPctje($matriz["frecuentes"]["afines"],$frecuentes)."\",\"".$this->getPctje($matriz["frecuentes"]["unos"],$frecuentes)."\",
			\"".$this->getPctje($matriz["frecuentes"]["jyjs"],$frecuentes)."\",\"".$this->getPctje($matriz["frecuentes"]["gaviotas"],$frecuentes)."\",
			\"".$this->getPctje($matriz["frecuentes"]["administrativos"],$frecuentes)."\",\"".$this->getPctje($matriz["frecuentes"]["funcionarios"],$frecuentes)."\",
			\"".$this->getPctje($matriz["frecuentes"]["proveedores"],$frecuentes)."\",\"".$this->getPctje($matriz["frecuentes"]["wtc"],$frecuentes)."\",
			\"".$this->getPctje($matriz["frecuentes"]["golfs"],$frecuentes)."\",\"997\"],";
			/*
			$json_string.="[\"Socio Afin\",\"100%\",\"".$this->getPctje($matriz["genericos"]["afines"],$afines)."\",\"".$this->getPctje($matriz["frecuentes"]["afines"],$afines)."\",
			\"".$afines."\",\"".$this->getPctje($matriz["afines"]["unos"],$afines)."\",
			\"".$this->getPctje($matriz["afines"]["jyjs"],$afines)."\",\"".$this->getPctje($matriz["afines"]["gaviotas"],$afines)."\",
			\"".$this->getPctje($matriz["afines"]["administrativos"],$afines)."\",\"".$this->getPctje($matriz["afines"]["funcionarios"],$afines)."\",
			\"".$this->getPctje($matriz["afines"]["proveedores"],$afines)."\",\"".$this->getPctje($matriz["afines"]["wtc"],$afines)."\",
			\"".$this->getPctje($matriz["afines"]["golfs"],$afines)."\",\"997\"],";
			*/
		
			$json_string.="[\"Los 1\",\"100%\",\"".$this->getPctje($matriz["genericos"]["unos"],$unos)."\",\"".$this->getPctje($matriz["frecuentes"]["unos"],$unos)."\",
			\"".$this->getPctje($matriz["unos"]["afines"],$unos)."\",\"".$unos."\",
			\"".$this->getPctje($matriz["unos"]["jyjs"],$unos)."\",\"".$this->getPctje($matriz["unos"]["gaviotas"],$unos)."\",
			\"".$this->getPctje($matriz["unos"]["administrativos"],$unos)."\",\"".$this->getPctje($matriz["unos"]["funcionarios"],$unos)."\",
			\"".$this->getPctje($matriz["unos"]["proveedores"],$unos)."\",\"".$this->getPctje($matriz["unos"]["wtc"],$unos)."\",
			\"".$this->getPctje($matriz["unos"]["golfs"],$unos)."\",\"996\"],";
			/*
			$json_string.="[\"Socio J&J\",\"100%\",\"".$this->getPctje($matriz["genericos"]["jyjs"],$jyjs)."\",
			\"".$this->getPctje($matriz["frecuentes"]["jyjs"],$jyjs)."\",
			\"".$this->getPctje($matriz["jyjs"]["afines"],$jyjs)."\",
			\"".$this->getPctje($matriz["jyjs"]["unos"],$jyjs)."\",\"".$jyjs."\",
			\"".$this->getPctje($matriz["jyjs"]["gaviotas"],$jyjs)."\",
			\"".$this->getPctje($matriz["jyjs"]["administrativos"],$jyjs)."\",\"".$this->getPctje($matriz["jyjs"]["funcionarios"],$jyjs)."\",
			\"".$this->getPctje($matriz["jyjs"]["proveedores"],$jyjs)."\",\"".$this->getPctje($matriz["jyjs"]["wtc"],$jyjs)."\",
			\"".$this->getPctje($matriz["jyjs"]["golfs"],$jyjs)."\",\"995\"],";
			*/
			$json_string.="[\"Tarjeta Gaviotas\",\"100%\",\"".$this->getPctje($matriz["genericos"]["gaviotas"],$gaviotas)."\",
			\"".$this->getPctje($matriz["frecuentes"]["gaviotas"],$gaviotas)."\",
			\"".$this->getPctje($matriz["gaviotas"]["afines"],$gaviotas)."\",
			\"".$this->getPctje($matriz["gaviotas"]["unos"],$gaviotas)."\",
			\"".$this->getPctje($matriz["gaviotas"]["jyjs"],$gaviotas)."\",\"".$gaviotas."\",
			\"".$this->getPctje($matriz["gaviotas"]["administrativos"],$gaviotas)."\",\"".$this->getPctje($matriz["gaviotas"]["funcionarios"],$gaviotas)."\",
			\"".$this->getPctje($matriz["gaviotas"]["proveedores"],$gaviotas)."\",\"".$this->getPctje($matriz["gaviotas"]["wtc"],$gaviotas)."\",
			\"".$this->getPctje($matriz["gaviotas"]["golfs"],$gaviotas)."\",\"994\"],";
			/*
			$json_string.="[\"Administrativo\",\"100%\",\"".$this->getPctje($matriz["genericos"]["administrativos"],$administrativos)."\",\"".$this->getPctje($matriz["frecuentes"]["administrativos"],$administrativos)."\",
			\"".$this->getPctje($matriz["administrativos"]["afines"],$administrativos)."\",
			\"".$this->getPctje($matriz["administrativos"]["unos"],$administrativos)."\",
			\"".$this->getPctje($matriz["administrativos"]["jyjs"],$administrativos)."\",
			\"".$this->getPctje($matriz["administrativos"]["gaviotas"],$administrativos)."\",\"".$administrativos."\",
			\"".$this->getPctje($matriz["administrativos"]["funcionarios"],$administrativos)."\",
			\"".$this->getPctje($matriz["administrativos"]["proveedores"],$administrativos)."\",\"".$this->getPctje($matriz["administrativos"]["wtc"],$administrativos)."\",
			\"".$this->getPctje($matriz["administrativos"]["golfs"],$administrativos)."\",\"993\"],";
			*/
		
			$json_string.="[\"Funcionario\",\"100%\",\"".$this->getPctje($matriz["genericos"]["funcionarios"],$funcionarios)."\",\"".$this->getPctje($matriz["frecuentes"]["funcionarios"],$funcionarios)."\",
			\"".$this->getPctje($matriz["funcionarios"]["afines"],$funcionarios)."\",
			\"".$this->getPctje($matriz["funcionarios"]["unos"],$funcionarios)."\",
			\"".$this->getPctje($matriz["funcionarios"]["jyjs"],$funcionarios)."\",
			\"".$this->getPctje($matriz["funcionarios"]["gaviotas"],$funcionarios)."\",
			\"".$this->getPctje($matriz["funcionarios"]["administrativos"],$funcionarios)."\",\"".$funcionarios."\",
			\"".$this->getPctje($matriz["funcionarios"]["proveedores"],$funcionarios)."\",\"".$this->getPctje($matriz["funcionarios"]["wtc"],$funcionarios)."\",
			\"".$this->getPctje($matriz["funcionarios"]["golfs"],$funcionarios)."\",\"992\"],";

			/*	
			$json_string.="[\"Proveedor\",\"100%\",\"".$this->getPctje($matriz["genericos"]["proveedores"],$proveedores)."\",\"".$this->getPctje($matriz["frecuentes"]["proveedores"],$proveedores)."\",
			\"".$this->getPctje($matriz["proveedores"]["afines"],$proveedores)."\",
			\"".$this->getPctje($matriz["proveedores"]["unos"],$proveedores)."\",
			\"".$this->getPctje($matriz["proveedores"]["jyjs"],$proveedores)."\",
			\"".$this->getPctje($matriz["proveedores"]["gaviotas"],$proveedores)."\",
			\"".$this->getPctje($matriz["proveedores"]["administrativos"],$proveedores)."\",
			\"".$this->getPctje($matriz["proveedores"]["funcionarios"],$proveedores)."\",\"".$proveedores."\",\"".$this->getPctje($matriz["proveedores"]["wtc"],$proveedores)."\",
			\"".$this->getPctje($matriz["proveedores"]["golfs"],$proveedores)."\",\"991\"],";
			*/
		
			$json_string.="[\"WTC Member\",\"100%\",\"".$this->getPctje($matriz["genericos"]["wtc"],$wtc)."\",\"".$this->getPctje($matriz["frecuentes"]["wtc"],$wtc)."\",
			\"".$this->getPctje($matriz["wtc"]["afines"],$wtc)."\",
			\"".$this->getPctje($matriz["wtc"]["unos"],$wtc)."\",
			\"".$this->getPctje($matriz["wtc"]["jyjs"],$wtc)."\",
			\"".$this->getPctje($matriz["wtc"]["gaviotas"],$wtc)."\",
			\"".$this->getPctje($matriz["wtc"]["administrativos"],$wtc)."\",
			\"".$this->getPctje($matriz["wtc"]["funcionarios"],$wtc)."\",
			\"".$this->getPctje($matriz["wtc"]["proveedores"],$wtc)."\",
			\"".$wtc."\",\"".$this->getPctje($matriz["wtc"]["golfs"],$wtc)."\",\"990\"]";
			/*
			$json_string.="[\"Club de Golf\",\"100%\",\"".$this->getPctje($matriz["genericos"]["golfs"],$golfs)."\",\"".$this->getPctje($matriz["frecuentes"]["golfs"],$golfs)."\",
			\"".$this->getPctje($matriz["golfs"]["afines"],$golfs)."\",
			\"".$this->getPctje($matriz["golfs"]["unos"],$golfs)."\",
			\"".$this->getPctje($matriz["golfs"]["jyjs"],$golfs)."\",
			\"".$this->getPctje($matriz["golfs"]["gaviotas"],$golfs)."\",
			\"".$this->getPctje($matriz["golfs"]["administrativos"],$golfs)."\",
			\"".$this->getPctje($matriz["golfs"]["funcionarios"],$golfs)."\",
			\"".$this->getPctje($matriz["golfs"]["proveedores"],$golfs)."\",
			\"".$this->getPctje($matriz["golfs"]["wtc"],$golfs)."\",\"".$golfs."\",\"989\"]";
			*/
			$json_string.="]";				
		}else{

			$json_string="[[\"Programa\",\"Todas Personas\",\"Compradores Frecuentes\",\"Beneficios\",\"Gaviotas\",\"Los 1\",\"WTC\",\"Orden\"]]";

		}

	
	
	return $json_string;

	}	
	public function getPctje($valor1,$valor2){
		$valorFinal="0%";

		$valor2=$num = (int)str_replace('.', '', $valor2);
		$valor1 = (int)str_replace('.', '', $valor1);
		//if(isset($valor2) && $valor2!=0) $valorFinal = round((($valor1/$valor2)*100))."%";
		if(isset($valor2) && $valor2!=0) $valorFinal = round((($valor1*100)/$valor2))."%";
		//echo "(".$valor1."*100)/".$valor2."=".$valorFinal."<br>";
		return $valorFinal;
	}
	public function getParetoClientes(Vtiger_Request $request) {
		
		$adb = PearDatabase::getInstance();
		$sexo = $request->get('sexo');
		$canal = $request->get('canal');
		$estatuto = $request->get('estatuto');
		$rango =htmlspecialchars_decode($request->get('rango'));
		$programa = $request->get('programa');
		$filtrar=false;

		if( (!empty($rango) && $rango!="") || (!empty($sexo) && $sexo!="") || (!empty($canal) && $canal!="") || (!empty($estatuto) && $estatuto!="") || (!empty($programa) && $programa!="")  ) {
			$filtrar=true;
		}

		




		$desde=" CURDATE() - INTERVAL 24 MONTH";
		$hasta=" CURDATE()";

		
		$query="SELECT gvcontacto, 
				SUM( gvcantidad ) AS cant
				FROM vtiger_gaviotas";
		if($filtrar){
			$query.=" INNER JOIN vtiger_contactdetails on contactid=gvcontacto";			
		}
		
		$query.=" WHERE 1=1 ";
				
		if ($desde!=""){
			$query.=" AND gvfecha >= ".$desde." ";
		
		}
		if ($hasta!=""){
			$query.=" AND gvfecha <= ".$hasta." ";
		
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
			foreach($canales as $id){
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


		$query.="	GROUP BY gvcontacto
					ORDER BY SUM( gvcantidad ) DESC
				";
		$result=$adb->query($query);
		//fwrite($fp,$query.PHP_EOL);
		$no_of_rows=$adb->num_rows($result);
		$ar_pareto = array();
		$ar_pareto2 = array();

		if($no_of_rows!=0){
			$total=0;
			$clientes=0;

			while($row = $adb->fetch_array($result)){
			     //Se marea sin esto, por las personas que tienen saldo -
				if($row['cant']>0){
					$total+=$row['cant'];
					 $clientes++;
					$ar_pareto[] = array($clientes,(double)$row['cant']);
				}
			}

			
			$i=0;
			$porcentaje_ac=0;
			$porcentaje=0;
			$j=50;
			while ($i<=$clientes){
				$porcentaje=(($ar_pareto[$i][1]*100)/$total);
				$porcentaje_ac+=$porcentaje;
				$ar_pareto[$i][1]=(double)$porcentaje_ac;
				if ($j==50){
					$ar_pareto2[]=array($ar_pareto[$i][0],(double)$porcentaje_ac);
					$j=0;
				}
				
				$j++;
				$i++;
			}		
		}
		
		return  array($ar_pareto2); 
		


		
		
		


	}

	public function getBeneficios(Vtiger_Request $request) {
		
		$adb = PearDatabase::getInstance();

		
		$familia = htmlspecialchars_decode($request->get('familia'));
		$rubro = htmlspecialchars_decode($request->get('rubro'));
		$localizacion = htmlspecialchars_decode($request->get('localizacion'));
		$adherido = htmlspecialchars_decode($request->get('adherido'));
		//$adherido =htmlspecialchars_decode($request->get('rango'));
		$filtrar=false;

		

		$desde=" CURDATE() - INTERVAL 6 MONTH";
		$hasta=" CURDATE()";

		
		$query="SELECT SUM(
				IF(lpadherido=1,1,0)
				) AS _in,
				SUM(
				IF(lpadherido=0,1,0)
				) AS _out
				FROM vtiger_account";
		
		
		$query.=" WHERE 1=1 ";
				
		/*if ($desde!=""){
			$query.=" AND gvfecha >= ".$desde." ";
		
		}
		if ($hasta!=""){
			$query.=" AND gvfecha <= ".$hasta." ";
		
		}*/

		if(!empty($familia) && $familia!="") {
			$query.=" and lpfamilia LIKE'%".$familia."'";
		}
		if(!empty($rubro) && $rubro!="") {
			$query.=" and lprubro LIKE '%".$rubro."'";
		}
		if(!empty($localizacion) && $localizacion!="") {
			$query.=" and lplocalizacion LIKE '%".$localizacion."'";
		}		

		if(!empty($adherido) && $adherido!="") {
			if($adherido=="Beneficios"){
				$query.=" and lpadherido=1";	
			}else{
				$query.=" and lpadherido=0";
			}
			
		}
		//echo $query;
		
		$result=$adb->query($query);


		$in=$adb->query_result($result,0,'_in');
		$out=$adb->query_result($result,0,'_out');

		$total=$in+$out;

		//$in=round(($in*100)/$total);
		//$out=round(($out*100)/$total);

		return array(array(0=>intval($in)),array(0=>intval($out)));


		
		
		


	}

	public function getGaviotas(Vtiger_Request $request) {
		
		$adb = PearDatabase::getInstance();
		$desde=" CURDATE() - INTERVAL 2 YEAR";
		$hasta=" CURDATE()";
		$desde = date('Y-m-01'); // hard-coded '01' for first day
		$desde = date('Y-m-01', strtotime("-2 years"));
		$hasta  = date('Y-m-t');

		$createdTime = $request->get('createdtime');
		if(!empty($createdTime)) {
			$desde = Vtiger_Date_UIType::getDBInsertedValue($createdTime['start']);
			$hasta = Vtiger_Date_UIType::getDBInsertedValue($createdTime['end']);
		}

		$vista2 = $request->get('vista2');
		if(empty($vista2)) $vista2="gaviotas"; 
		if($vista2=='gaviotas'){

				$query="SELECT SUM(
						IF(gvtipomov='Gaviotas dada de baja por Canje' OR gvtipomov='Gaviotas dada de baja por Cheque Obsequio' ,gvcantidad,0)
						) AS consumidas,
						SUM(
						IF(gvvencimiento >= CURDATE() AND (gvtipomov='Gaviotas Electronicas' OR gvtipomov='Gaviotas generadas en el Canje' 
						OR gvtipomov='Gaviotas generadas por Devolucion' OR gvtipomov='Gaviotas Reintegradas'  
						OR gvtipomov='Gaviotas generadas por Boletas')  ,gvcantidad,0)
						) AS vivas,
						SUM(
						IF((gvtipomov='Gaviotas Electronicas' OR gvtipomov='Gaviotas generadas en el Canje' 
						OR gvtipomov='Gaviotas generadas por Devolucion' OR gvtipomov='Gaviotas Reintegradas'  
						OR gvtipomov='Gaviotas generadas por Boletas') AND gvvencimiento < CURDATE() ,gvcantidad,0)
						) AS vencidas,
						SUM(
						IF(gvtipomov='Gaviotas anuladas' ,gvcantidad,0)
						) AS anuladas, 
						SUM(IF(gvvencimiento >= CURDATE(),gvcantidad,0)) as cantidad_vivas
						FROM vtiger_gaviotas
						INNER JOIN vtiger_contactdetails on contactid=gvcontacto";
				$query.=" WHERE 1=1 ";
				
				if ($desde!=""){
					$query.=" AND gvfecha >= '".$desde."'";
				
				}
				if ($hasta!=""){
					$query.=" AND gvfecha <= '".$hasta."' ";
				
				}

				$sexo = $request->get('sexo');
				$canal = $request->get('canal');
				$estatuto = $request->get('estatuto');
				$rango =htmlspecialchars_decode($request->get('rango'));
				$programa = $request->get('programa');

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
					foreach($canales as $id){
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
		}else{
				$sexo = $request->get('sexo');
				$canal = $request->get('canal');
				$estatuto = $request->get('estatuto');
				$rango =htmlspecialchars_decode($request->get('rango'));

				$filtros="";
				if(!empty($rango) && $rango!="") {
					$rangos=explode(",", $rango);
					$filtros.=" AND (";
					foreach($rangos as $id){
						//$filtros.=" FIND_IN_SET('".$id."',rango_edades)<>0 OR";	
						$query.=" rango_edades LIKE '%".$id."%' OR";
					}	
					$filtros=rtrim($filtros,'OR');
					$filtros.=" )";
				}
				if(!empty($sexo) && $sexo!="") {
					//$query.=" and vtiger_contactdetails.cnsexo='".$sexo."'";
					$sexos=explode(",", $sexo);
					$filtros.=" AND (";
					foreach($sexos as $id){
						$filtros.=" cnsexo='".$id."' OR";	
					}	
					$filtros=rtrim($filtros,'OR');
					$filtros.=" )";
				}
				if(!empty($canal) && $canal!="") {
					$canales=explode(",", $canal);
					$filtros.=" AND (";
					foreach($canales as $id){
						//$filtros.=" FIND_IN_SET('".$id."',canal_activo)<>0 OR";	
						$query.=" canal_activo LIKE '%".$id."%' OR";
					}	
					$filtros=rtrim($filtros,'OR');
					$filtros.=" )";
				}
				if(!empty($estatuto) && $estatuto!="") {
					//$query.=" and vtiger_contactdetails.estatuto='".$estatuto."'";
					$estatutos=explode(",", $estatuto);
					$filtros.=" AND (";
					foreach($estatutos as $id){
						//$filtros.=" FIND_IN_SET('".$id."',estatuto)<>0 OR";	
						$query.=" estatuto LIKE '%".$id."%' OR";
					}	
					$filtros=rtrim($filtros,'OR');
					$filtros.=" )";
				}
				if(!empty($programa) && $programa!="") {
					$programas=explode(",", $programa);
					$filtros.=" AND (";
					foreach($programas as $id){
						//$filtros.=" FIND_IN_SET('".$id."',programa)<>0 OR";	
						$query.=" programa LIKE '%".$id."%' OR";
					}	
					$filtros=rtrim($filtros,'OR');
					$filtros.=" )";
				}

				$query="SELECT (SELECT count(distinct vtiger_gaviotas.gvcontacto) from vtiger_gaviotas";
		        if ($filtros!=""){$query.=" INNER JOIN vtiger_contactdetails ON contactid=gvcontacto ";}     		
		        $query.=" 
        		where 1=1
             	and gvtipomov in ('Gaviotas dada de baja por Canje','Gaviotas dada de baja por Cheque Obsequio')";
		     	if ($desde!=""){$query.=" AND gvfecha >= '".$desde."'";}
				if ($hasta!=""){$query.=" AND gvfecha <= '".$hasta."' ";}
				if ($filtros!=""){$query.=$filtros;}
		        $query.=") 
		             	as consumidas,
		             	(SELECT count(distinct vtiger_gaviotas.gvcontacto) from vtiger_gaviotas";
		        if ($filtros!=""){$query.=" INNER JOIN vtiger_contactdetails ON contactid=gvcontacto ";}     		
		        $query.=" 
		        		where 1=1 AND gvvencimiento >= CURDATE() 
		             	)";
					//and gvtipomov in ('Gaviotas Electronicas','Gaviotas generadas en el Canje','Gaviotas generadas por Devolucion','Gaviotas Reintegradas','Gaviotas generadas por Boletas'
		     	if ($desde!=""){$query.=" AND gvfecha >= '".$desde."'";}
				if ($hasta!=""){$query.=" AND gvfecha <= '".$hasta."' ";}
				if ($filtros!=""){$query.=$filtros;}
		        $query.=") 
		             	as cantidad_vivas,
		             	(SELECT count(distinct vtiger_gaviotas.gvcontacto) from vtiger_gaviotas";
		        if ($filtros!=""){$query.=" INNER JOIN vtiger_contactdetails ON contactid=gvcontacto ";}     		
		        $query.=" 
		        		where 1=1 AND gvvencimiento < CURDATE()
		             	and gvtipomov in ('Gaviotas Electronicas','Gaviotas generadas en el Canje','Gaviotas generadas por Devolucion',
		             		'Gaviotas Reintegradas','Gaviotas generadas por Boletas')";
		     	if ($desde!=""){$query.=" AND gvfecha >= '".$desde."'";}
				if ($hasta!=""){$query.=" AND gvfecha <= '".$hasta."' ";}
				if ($filtros!=""){$query.=$filtros;}
		        $query.=")
		             	as vencidas,
		             	(SELECT count(distinct vtiger_gaviotas.gvcontacto) from vtiger_gaviotas";
		        if ($filtros!=""){$query.=" INNER JOIN vtiger_contactdetails ON contactid=gvcontacto ";}     		
		        $query.=" WHERE gvfecha >= '2013-01-01' AND gvfecha <= '2015-01-31'
		             	and gvtipomov in ('Gaviotas anuladas')";
		     	if ($desde!=""){$query.=" AND gvfecha >= '".$desde."'";}
				if ($hasta!=""){$query.=" AND gvfecha <= '".$hasta."' ";}
				if ($filtros!=""){$query.=$filtros;}
				
		        $query.=")
		             	as anuladas
		             	 ";	

				/*$query="SELECT 
				SUM(IF(_consumidas >0,1,0)) consumidas,SUM(IF(_vivas>0,1,0)) vivas,SUM(IF(_vencidas>0,1,0)) vencidas,SUM(IF(_anuladas>0,1,0)) anuladas		
				FROM
				(
					SELECT 

					SUM(
					IF(gvtipomov='Gaviotas dada de baja por Canje' OR gvtipomov='Gaviotas dada de baja por Cheque Obsequio' ,1,0)
					)AS _consumidas,
					SUM(
					IF(gvvencimiento >= CURDATE() AND (gvtipomov='Gaviotas Electronicas' OR gvtipomov='Gaviotas generadas en el Canje' 
					OR gvtipomov='Gaviotas generadas por Devolucion' OR gvtipomov='Gaviotas Reintegradas'  
					OR gvtipomov='Gaviotas generadas por Boletas')  ,1,0)
					) AS _vivas,
					SUM(
					IF((gvtipomov='Gaviotas Electronicas' OR gvtipomov='Gaviotas generadas en el Canje' 
					OR gvtipomov='Gaviotas generadas por Devolucion' OR gvtipomov='Gaviotas Reintegradas'  
					OR gvtipomov='Gaviotas generadas por Boletas') AND gvvencimiento < CURDATE() ,1,0)
					) AS _vencidas,
					SUM(
					IF(gvtipomov='Gaviotas anuladas' ,1,0)
					) AS _anuladas

					FROM vtiger_gaviotas
					INNER JOIN vtiger_contactdetails ON contactid=gvcontacto WHERE 1=1";
				
				if ($desde!=""){
					$query.=" AND gvfecha >= '".$desde."'";
				
				}
				if ($hasta!=""){
					$query.=" AND gvfecha <= '".$hasta."'";
				
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
					foreach($canales as $id){
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
				$query.=" GROUP BY gvcontacto
			) d";*/
		}
		
		//echo $query;

		$result=$adb->query($query);


		$consumidas=abs($adb->query_result($result,0,'consumidas'));
		$vencidas=abs($adb->query_result($result,0,'vencidas'));
		//$vivas=abs($adb->query_result($result,0,'vivas'));
		$vivas=abs($adb->query_result($result,0,'cantidad_vivas'));
		$anuladas=abs($adb->query_result($result,0,'anuladas'));

		$total=$consumidas+$vencidas+$vivas+$anuladas;

		//$in=round(($in*100)/$total);
		//$out=round(($out*100)/$total);
		//return array(array(0=>intval($vencidas)),array(0=>intval($consumidas)),array(0=>intval($vivas)),array(0=>intval($anuladas)));
		return  array(
					array(array('Vencidas' ,intval($vencidas)) ,array('Consumidas' ,intval($consumidas)) ,array('Vivas' ,intval($vivas)) ,array('Anuladas' ,intval($anuladas) ) ),
					array(array('Vencidas' ,round($vencidas * 6.5)) ,array('Consumidas' ,round($consumidas * 6.5)) ,array('Vivas' ,round($vivas * 6.5)) ,array('Anuladas' ,round($anuladas * 6.5) ))
					);
	}

	public function getAnalisisCampania(Vtiger_Request $request) {
		
		$adb = PearDatabase::getInstance();

		$fecha_desde="";
		$fecha_hasta="";

		$fecha_hasta=date('Y-m-d');
		$date = strtotime( date('Y-m-01')." -11 months");
		$fecha_desde=date("Y-m-d", $date);
		
		
		$createdTime = $request->get('createdtime');
		if(!empty($createdTime)) {
			$fecha_desde = Vtiger_Date_UIType::getDBInsertedValue($createdTime['start']);
			$fecha_hasta = Vtiger_Date_UIType::getDBInsertedValue($createdTime['end']);
		}
		
		$sexo = $request->get('sexo');
		$canal = $request->get('canal');
		$estatuto = $request->get('estatuto');
		$rango =htmlspecialchars_decode($request->get('rango'));
		$programa = $request->get('programa');
		$filtros_where=" WHERE 1=1 ";
		$filtros="";	
		if(!empty($rango) && $rango!="") {
			$rangos=explode(",", $rango);
			$filtros.=" AND (";
			foreach($rangos as $id){
				//$filtros.=" FIND_IN_SET('".$id."',rango_edades)<>0 OR";	
				$filtros.=" rango_edades LIKE '%".$id."%' OR";
			}	
			$filtros=rtrim($filtros,'OR');
			$filtros.=" )";
		}
		if(!empty($sexo) && $sexo!="") {
			$sexos=explode(",", $sexo);
			$filtros.=" AND (";
			foreach($sexos as $id){
				$filtros.=" cnsexo='".$id."' OR";	
			}	
			$filtros=rtrim($filtros,'OR');
			$filtros.=" )";
		}
		if(!empty($canal) && $canal!="") {
			$canales=explode(",", $canal);
			$filtros.=" AND (";
			foreach($canales as $id){
				//$filtros.=" FIND_IN_SET('".$id."',canal_activo)<>0 OR";	
				$filtros.=" canal_activo LIKE '%".$id."%' OR";
			}	
			$filtros=rtrim($filtros,'OR');
			$filtros.=" )";
		}
		if(!empty($estatuto) && $estatuto!="") {
			$estatutos=explode(",", $estatuto);
			$filtros.=" AND (";
			foreach($estatutos as $id){
				//$filtros.=" FIND_IN_SET('".$id."',estatuto)<>0 OR";	
				$filtros.=" estatuto LIKE '%".$id."%' OR";
			}	
			$filtros=rtrim($filtros,'OR');
			$filtros.=" )";
		}
		if(!empty($programa) && $programa!="") {
			$programas=explode(",", $programa);
			$filtros.=" AND (";
			foreach($programas as $id){
				//$filtros.=" FIND_IN_SET('".$id."',programa)<>0 OR";	
				$filtros.=" programa LIKE '%".$id."%' OR";
			}	
			$filtros=rtrim($filtros,'OR');
			$filtros.=" )";
		}

		$query="select *,
				(SELECT COUNT(crmid) as cantidad FROM vtiger_crmentityrel
					LEFT JOIN vtiger_contactdetails ON contactid=crmid 
					WHERE module='SMSNotifier' AND relmodule='Contacts' 
					     AND crmid IN(SELECT  DISTINCT crmid 
					FROM vtiger_crmentityrel
					INNER JOIN vtiger_envioemails ON vtiger_envioemails.envioemailsid= vtiger_crmentityrel.relcrmid 
					WHERE vtiger_envioemails.lpcampania=c.campaignid AND  vtiger_crmentityrel.relmodule='EnvioEmails')
					".$filtros."
				) as sms,
				(select count(*) as cantidad from lp_mailsenviados
					LEFT JOIN vtiger_contactdetails ON vtiger_contactdetails.contactid=lp_mailsenviados.contactid 
					inner join vtiger_envioemails on vtiger_envioemails.envioemailsid=lp_mailsenviados.envioemailsid
					where vtiger_envioemails.lpcampania=c.campaignid ".$filtros.") as mailsenv,
				(select count(*) as cantidad from vtiger_email_track et
					inner join lp_mailsenviados me on et.mailid=me.emailid
					inner join vtiger_envioemails em on em.envioemailsid = me.envioemailsid
					LEFT JOIN vtiger_contactdetails ON vtiger_contactdetails.contactid=me.contactid 
					where lpcampania=c.campaignid ".$filtros.") as abiertos,
				(SELECT  COUNT(*)
				 FROM  vtiger_campaigncontrel 
				 LEFT JOIN vtiger_contactdetails ON vtiger_contactdetails.contactid=vtiger_campaigncontrel.contactid 	
					 WHERE vtiger_campaigncontrel.campaignid=c.campaignid ".$filtros.") 
				AS cant_cont,
				(SELECT  COUNT(DISTINCT gvcontacto) AS cantidad FROM vtiger_gaviotas
				LEFT JOIN vtiger_contactdetails ON vtiger_contactdetails.contactid=gvcontacto		
				WHERE gvfecha>= c.lpcamdesde AND gvfecha<=c.closingdate ".$filtros."
				) AS activas,
				(SELECT COUNT( crmid ) AS cant FROM vtiger_crmentity 
					LEFT JOIN vtiger_contactdetails ON vtiger_contactdetails.contactid=crmid		
					WHERE setype =  'Contacts' AND createdtime >= c.lpcamdesde AND createdtime <= c.closingdate 
					".$filtros." AND crmid IN (SELECT DISTINCT gvcontacto FROM vtiger_gaviotas i
				WHERE gvfecha>=  c.lpcamdesde AND gvfecha<=c.closingdate)) AS reclutadas,
				(SELECT  COUNT(DISTINCT gvcontacto) AS cantidad FROM vtiger_gaviotas
				 LEFT JOIN vtiger_contactdetails ON vtiger_contactdetails.contactid=gvcontacto			
				WHERE gvfecha>= c.lpcamdesde AND gvfecha<=c.closingdate
				".$filtros." and gvcontacto in (select contactid from vtiger_campaigncontrel where campaignid=c.campaignid)
				) AS activas_obj,
				(SELECT COUNT( crmid ) AS cant 
					FROM vtiger_crmentity 
					LEFT JOIN vtiger_contactdetails ON vtiger_contactdetails.contactid=crmid			
					WHERE setype =  'Contacts' AND createdtime >= c.lpcamdesde AND createdtime <= c.closingdate 
					AND crmid IN (SELECT DISTINCT gvcontacto FROM vtiger_gaviotas i WHERE gvfecha>=  c.lpcamdesde 
									AND gvfecha<=c.closingdate)
					".$filtros." and crmid in (select contactid from vtiger_campaigncontrel where campaignid=c.campaignid)
					) AS reclutadas_obj
				from vtiger_campaign c inner join vtiger_crmentity e on c.campaignid = e.crmid and e.deleted=0 WHERE 1=1";
		if ($fecha_desde!=""){
			$query.=" AND lpcamdesde >= '".$fecha_desde."'";
		
		}
		if ($fecha_hasta!=""){
			$query.=" AND closingdate <= '".$fecha_hasta."' ";
		
		}		

		$query.=" 	ORDER BY createdtime";
			
		$result=$adb->query($query);
		//fwrite($fp,$query.PHP_EOL);
		$json_string="[[\"Campaña\",\"Desde\",\"Hasta\",\"SMS Enviados\",\"Mensajes Enviados\",
						\"% Mails Abiertos\",\"Cantidad Rebotados\",\"Clientes activos por día\",
						\"Clientes reclutados por día\",\"Clientes activos por día \",
						\"Clientes reclutados por día \",\"Cantidad de Clientes\"],";
	

	while ($row = $adb->fetch_array($result)){

		$C_desde=$row['lpcamdesde'];
		$C_hasta=$row['closingdate'];
		$aux_C_desde=strtotime($row['lpcamdesde']);
		$aux_C_hasta=min(strtotime(date("Y-m-d")),strtotime($row['closingdate']));
		$dias	= ($aux_C_desde-$aux_C_hasta)/86400;
		$dias 	= abs($dias); 
		$dias = floor($dias)+1;
		if ($dias==0) $dias=1;
		
		$activasdia=round($row["activas"]/$dias);
		$reclutadasdia=round($row["reclutadas"]/$dias);
		$activasdiaobj=round($row["activas_obj"]/$dias);
		$reclutadasdiaobj=round($row["reclutadas_obj"]/$dias);
		$clientes=round($row["cant_cont"]);

		

		$json_string.="[\"<a href='index.php?module=Campaigns&parenttab=Marketing&action=DetailView&record=".$row['campaignid']."'>".$row['campaignname']."</a>\",
						\"".$row['createdtime']."\",
						\"".$row['closingdate']."\",
						\"".$row['sms']."\",
						\"".$row['mailsenv']."\",
						\"".$row['abiertos']."\",
						\"".($row['lpchimpsoftbounces']+$row['lpchimphardbounces'])."\",
						\"".$activasdia."\",
						\"".$reclutadasdia."\",
						\"".$activasdiaobj."\",
						\"".$reclutadasdiaobj."\",
						\"".$clientes."\"],";
		
	
	}

	$json_string=rtrim($json_string, ",");
	$json_string.="]";
	return $json_string;

	}
	
	public function _getRankingClientes(Vtiger_Request $request) {
		
		$adb = PearDatabase::getInstance();

		

                $query="

		 SELECT firstname,lastname,email,phone ,birthday, cnsaldoactual as actuales, cnsaldoanterior as anteriores, cndiferencia as evolucion
                FROM  vtiger_contactdetails 
                INNER JOIN vtiger_contactsubdetails ON contactid=contactsubscriptionid
                WHERE 1=1 ";


		$sexo = $request->get('sexo');
		$vista = $request->get('vista');
		$canal = $request->get('canal');
		$estatuto = $request->get('estatuto');
		$rango =htmlspecialchars_decode($request->get('rango'));
		$programa = $request->get('programa');

		if(!empty($vista) && $vista=="") {
			$vista="mayor";
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
			foreach($canales as $id){
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
		$query.=" ";
		switch ($vista) {
			case 'menor':
				$query.="  ORDER BY cndiferencia ASC LIMIT 100 ";				
				break;
			case 'todos':
				$query.=" ORDER BY cndiferencia ASC";				
				break;
			
			default:
				$query.=" ORDER BY cndiferencia DESC LIMIT 100";				
				break;
		}

		//echo $query;
			
		$result=$adb->query($query);
		//fwrite($fp,$query.PHP_EOL);
		$json_string="[[\"Nombre\",\"Apellido\",\"Mail\",\"Teléfono\",\"Fecha de Nac.\",\"Consumo Actual\",\"Consumo Anterior\",\"Evolución\"],";
	

		while ($row = $adb->fetch_array($result)){

		$consumo_actual=$row['actuales'];
		$consumo_anterior=$row['anteriores'];
		$evolucion=$consumo_actual-$consumo_anterior;

		$json_string.="[\"".$row['firstname']."\",
						\"".$row['lastname']."\",
						\"".$row['email']."\",
						\"".$row['phone']."\",
						\"".$row['birthday']."\",
						\"".$consumo_actual."\",
						\"".$consumo_anterior."\",
						\"".$evolucion."\"],";
		
	
		}

		$json_string=rtrim($json_string, ",");
		$json_string.="]";
		return $json_string;

	}
	public function getRankingClientes(Vtiger_Request $request) {

		 /* Array of database columns which should be read and sent back to DataTables. Use a space where
         * you want to insert a non-database field (for example a counter or static image)
        */
        $aColumns = array( 'firstname','lastname','email','phone' ,'birthday', 'cnsaldoactual', 'cnsaldoanterior', 'cndiferencia' );
         
        /* Indexed column (used for fast and accurate table cardinality) */
        $sIndexColumn = "contactid";
         
        /* DB table to use */
        $sTable = "vtiger_contactdetails INNER JOIN vtiger_contactsubdetails ON contactid=contactsubscriptionid";
		

       /*
         * Paging
        */
       
       $iDisplayStart=$request->get('iDisplayStart');

       
        $sLimit = "";
        if ( isset($iDisplayStart)	&& $request->get('iDisplayLength') != '-1' )
        {
            $sLimit = "LIMIT ".intval( $request->get('iDisplayStart') ).", ".
                    intval( $request->get('iDisplayLength') );
        }

        /*if(!empty($vista) && $vista=="") {
			$vista="mayor";
		}*/

        /*
         * Ordering
        */
        $sOrder = "";
        $iSortCol_0=$request->get('iSortCol_0');
        if ( isset( $iSortCol_0 ) )
        {
            $sOrder = "ORDER BY  ";
            for ( $i=0 ; $i<intval( $request->get('iSortingCols') ) ; $i++ )
            {
                if ( $request->get( 'bSortable_'.intval($request->get('iSortCol_'.$i)) ) == "true" )
                {
                    $sOrder .= "`".$aColumns[ intval( $request->get('iSortCol_'.$i) ) ]."` ".
                        ($request->get('sSortDir_'.$i)==='asc' ? 'asc' : 'desc') .", ";
                }
            }
         
            $sOrder = substr_replace( $sOrder, "", -2 );
            if ( $sOrder == "ORDER BY" )
            {
                $sOrder = "";
            }
        }

       
        



        $sWhere = "WHERE 1=1 ";


        $sexo = $request->get('sexo');
		$vista = $request->get('vista');
		$canal = $request->get('canal');
		$estatuto = $request->get('estatuto');
		$rango =htmlspecialchars_decode($request->get('rango'));
		$programa = $request->get('programa');

			

		if(!empty($rango) && $rango!="") {
							//$query.=" and vtiger_contactdetails.rango_edades='".$rango."'";
			$rangos=explode(",", $rango);
			$sWhere.=" AND (";
			foreach($rangos as $id){
				$sWhere.=" rango_edades LIKE '%".$id."%' OR";
			}	
			$sWhere=rtrim($sWhere,'OR');
			$sWhere.=" )";
		}
		if(!empty($sexo) && $sexo!="") {
			//$query.=" and vtiger_contactdetails.cnsexo='".$sexo."'";
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
			//$sWhere.=" and vtiger_contactdetails.estatuto='".$estatuto."'";
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
				$sWhere.=" programa LIKE '%".$id."%' OR";	
			}	
			$sWhere=rtrim($sWhere,'OR');
			$sWhere.=" )";
		}

         /*
         * Filtering
        * NOTE this does not match the built-in DataTables filtering which does it
        * word by word on any field. It's possible to do here, but concerned about efficiency
        * on very large tables, and MySQL's regex functionality is very limited
        */
        
        $sSearch=$request->get('sSearch');
        if ( isset($sSearch) && $request->get('sSearch') != "" )
        {
            if ( $sWhere == "" )
                {
                    $sWhere .= "WHERE ";
                }
                else
                {
                    $sWhere .= " AND ";
                }
            $sWhere .= " (";
            for ( $i=0 ; $i<count($aColumns) ; $i++ )
            {
                $sWhere .= "`".$aColumns[$i]."` LIKE '%".mysql_real_escape_string( $request->get('sSearch') )."%' OR ";
            }
            $sWhere = substr_replace( $sWhere, "", -3 );
            $sWhere .= ')';
        }

         /* Individual column filtering */
        for ( $i=0 ; $i<count($aColumns) ; $i++ )
        {
            if ( $request->get('bSearchable_'.$i) == "true" && $request->get('sSearch_'.$i) != '' )
            {
                if ( $sWhere == "" )
                {
                    $sWhere = "WHERE ";
                }
                else
                {
                    $sWhere .= " AND ";
                }
                $sWhere .= "`".$aColumns[$i]."` LIKE '%".mysql_real_escape_string($request->get('sSearch_'.$i))."%' ";
            }
        }

        

        $calcTotal=false;
        $sql_calc_total="";
        if($request->get('calcularTotal')==1 || $request->get('sSearch') != ""){
        	$calcTotal=true;
        	$sql_calc_total="SQL_CALC_FOUND_ROWS";
        	$iTotal =$request->get("total_records");
        }else{
        	$iFilteredTotal=$request->get("display_records");
         	$iTotal =$request->get("total_records");
        }

		$adb = PearDatabase::getInstance();

		
		/*
         * SQL queries
        * Get data to display
        */
       //SQL_CALC_FOUND_ROWS
         $sQuery = "
    	SELECT  `".str_replace(" , ", " ", implode("`, `", $aColumns))."`
            FROM   $sTable
            $sWhere
            $sOrder
            $sLimit
            ";

        $rResult=$adb->query($sQuery);
        
        if($calcTotal){
        	/* Data set length after filtering */
        	$sQuery = "
    		SELECT COUNT(`".$sIndexColumn."`)
            FROM   $sTable
            $sWhere
            $sOrder
            $sLimit
            ";
	        $rResultFilterTotal = $adb->query($sQuery);
	        $aResultFilterTotal = $adb->fetch_array($rResultFilterTotal);
	        $iFilteredTotal = $aResultFilterTotal[0];	
	        $tot=$request->get("total_records");
	        if(isset($tot) && $tot==0){
		        /* Total data set length */
		         $sQuery = "
		    		SELECT COUNT(`".$sIndexColumn."`)
		            FROM   $sTable
		            ";
		        $rResultTotal = $adb->query($sQuery);
		        $aResultTotal = $adb->fetch_array($rResultTotal);
		        $iTotal = $aResultTotal[0];
	    	}
        }
        /**/
       
         /*
         * Output
        */
        $output = array(
                "sEcho" => intval($request->get('sEcho')),
                "iTotalRecords" => $iTotal,
                "iTotalDisplayRecords" => $iFilteredTotal,
                "aaData" => array()
        );
         
        while ( $aRow =$adb->fetch_array($rResult) )
        {
            $row = array();
            for ( $i=0 ; $i<count($aColumns) ; $i++ )
            {
                if ( $aColumns[$i] == "version" )
                {
                    /* Special output formatting for 'version' column */
                    $row[] = ($aRow[ $aColumns[$i] ]=="0") ? '-' : $aRow[ $aColumns[$i] ];
                }
                else if ( $aColumns[$i] != ' ' )
                {
                    /* General output */
                    $row[] = $aRow[ $aColumns[$i] ];
                }
            }
            $output['aaData'][] = $row;
        }
         
        return $output;
        exit;




             


		
		

		
	}


	public function getClientes(Vtiger_Request $request) {
		$fecha_desde="";
		$fecha_hasta="";

		$fecha_hasta=date('Y-m-d');
		$date = strtotime( date('Y-m-01')." -11 months");
		$fecha_desde=date("Y-m-d", $date);
		
		
		$adb = PearDatabase::getInstance();
		$createdTime = $request->get('createdtime');
		if(!empty($createdTime)) {
			$fecha_desde = Vtiger_Date_UIType::getDBInsertedValue($createdTime['start']);
			$fecha_hasta = Vtiger_Date_UIType::getDBInsertedValue($createdTime['end']);
		}
		$startDate = strtotime(date("$fecha_desde"));
		$endDate   = strtotime(date("$fecha_hasta"));
		$currentDate = $startDate;
		$i=0;
		$meses_nom=array();
		$ar_nuevas=array();
		$ar_compraron=array();
		$ar_porcentaje=array();
		while ($currentDate <= $endDate) {
		    $cur_month= date('m',$currentDate);
		    $ar_nuevas[]=array(nombremes($cur_month), (double)0);
		    $ar_compraron[]=array(nombremes($cur_month), (double)0);
		    $ar_porcentaje[]=array(nombremes($cur_month), (double)0);
		    $currentDate = strtotime( date('Y/m/01/',$currentDate).' +1 month');
		    $i++;
		    $meses_nom[]=nombremes($cur_month);
		}
		
		$rango =htmlspecialchars_decode($request->get('rango'));
		$sexo = $request->get('sexo');
		$canal = $request->get('canal');
		$estatuto = $request->get('estatuto');
		$programa = $request->get('programa');
		$fuente = $request->get('fuente');
		$mostrar_canjes=true;
		$mostrar_vales=true;
		$mostrar_gaviotas=true;
		if(isset($fuente) && $fuente!=""){
			$mostrar_canjes=$mostrar_vales=$mostrar_gaviotas=false;
			$fuentes=explode(",", $fuente);
			foreach($fuentes as $id){
				switch ($id) {
					case 'gaviotas':
						$mostrar_gaviotas=true;
						break;
					case 'vales':
						$mostrar_vales=true;
						break;
					case 'canjes':
						$mostrar_canjes=true;
						break;
				}
			}	
		}
		
		$filtrar=false;

		if( (!empty($rango) && $rango!="") || (!empty($sexo) && $sexo!="") || (!empty($canal) && $canal!="") || (!empty($estatuto) && $estatuto!="") || (!empty($programa) && $programa!="")   ) {
			$filtrar=true;
		}	
		

			$query="SELECT anio,mes, COUNT(contacto) AS total, 
					SUM(IF(MONTH(createdtime)=mes AND YEAR(e.createdtime)=anio ,1,0)) AS nuevos, 
					SUM(IF(MONTH(createdtime)=mes AND YEAR(e.createdtime)=anio ,0,1)) AS viejos 
					FROM (";
		if($mostrar_canjes){				
			$query.="SELECT YEAR(cafecha) AS anio, MONTH(cafecha) AS mes ,cacontacto AS contacto
						FROM vtiger_canjes";
			

			$query.=" WHERE cafecha  >=  '".$fecha_desde."' AND cafecha <= '".$fecha_hasta."'";
			
			$query.=" UNION ";
		}	
		if($mostrar_gaviotas){
				$query.="
				SELECT YEAR(gvfecha) AS anio,MONTH(gvfecha) AS mes , gvcontacto AS contacto

        		FROM vtiger_gaviotas";
				
				$query.=" WHERE gvfecha  >=  '".$fecha_desde."' AND gvfecha <= '".$fecha_hasta."'";
				
				$query.=" UNION ";
		}		
		if($mostrar_vales){
				$query.=" 
				SELECT YEAR(vafecha) AS anio,MONTH(vafecha) AS mes ,vacontacto AS contacto

				FROM vtiger_vales";
				
				$query.=" WHERE vafecha  >=  '".$fecha_desde."' AND vafecha <= '".$fecha_hasta."'";
				

		}		
		$query=rtrim($query,' UNION ');

			$query.=") as dat 	";
				
		$query.="	INNER JOIN vtiger_contactdetails c ON dat.contacto=c.contactid

					INNER JOIN vtiger_crmentity e ON e.crmid = c.contactid";

		if($filtrar){
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
							foreach($canales as $id){
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
					}				
		$query.=" GROUP BY mes";

		$result=$adb->query($query);
		//fwrite($fp,$query.PHP_EOL);
		$no_of_rows=$adb->num_rows($result);
		$mes=date("m");
		$i=0;
		while ($i<$nro_dias){

		}
		if($no_of_rows!=0){
		  $total=0;
		  while($row = $adb->fetch_array($result)){
		        $nombremes=nombremes($row['mes']);
		        $indice = array_search($nombremes, $meses_nom);
				$ar_nuevas[$indice] = array($nombremes, (double)$row['nuevos']);
				$ar_compraron[$indice] = array($nombremes, (double)$row['viejos']);
		    
		  }
		}


		

		return  array($ar_nuevas,$ar_compraron); 
	}
	

	/**
	 * Function returns Tickets grouped by Status
	 * @param type $data
	 * @return <Array>
	 */
	public function getTicketsByStatus($owner, $dateFilter) {
		$db = PearDatabase::getInstance();

		$ownerSql = $this->getOwnerWhereConditionForDashBoards($owner);
		if(!empty($ownerSql)) {
			$ownerSql = ' AND '.$ownerSql;
		}
		
		$params = array();
		if(!empty($dateFilter)) {
			$dateFilterSql = ' AND createdtime BETWEEN ? AND ? ';
			//client is not giving time frame so we are appending it
			$params[] = $dateFilter['start']. ' 00:00:00';
			$params[] = $dateFilter['end']. ' 23:59:59';
		}
		
		$result = $db->pquery('SELECT COUNT(*) as count, CASE WHEN vtiger_troubletickets.status IS NULL OR vtiger_troubletickets.status = "" THEN "" ELSE vtiger_troubletickets.status END AS statusvalue 
							FROM vtiger_troubletickets INNER JOIN vtiger_crmentity ON vtiger_troubletickets.ticketid = vtiger_crmentity.crmid AND vtiger_crmentity.deleted=0
							'.Users_Privileges_Model::getNonAdminAccessControlQuery($this->getName()). $ownerSql .' '.$dateFilterSql.
							' INNER JOIN vtiger_ticketstatus ON vtiger_troubletickets.status = vtiger_ticketstatus.ticketstatus GROUP BY statusvalue ORDER BY vtiger_ticketstatus.sortorderid', $params);

		$response = array();

		for($i=0; $i<$db->num_rows($result); $i++) {
			$row = $db->query_result_rowdata($result, $i);
			$response[$i][0] = $row['count'];
			$ticketStatusVal = $row['statusvalue'];
			if($ticketStatusVal == '') {
				$ticketStatusVal = 'LBL_BLANK';
			}
			$response[$i][1] = vtranslate($ticketStatusVal, $this->getName());
			$response[$i][2] = $ticketStatusVal;
		}
		return $response;
	}

	/**
	 * Function to get relation query for particular module with function name
	 * @param <record> $recordId
	 * @param <String> $functionName
	 * @param Vtiger_Module_Model $relatedModule
	 * @return <String>
	 */
	public function getRelationQuery($recordId, $functionName, $relatedModule) {
		if ($functionName === 'get_activities') {
			$userNameSql = getSqlForNameInDisplayFormat(array('first_name' => 'vtiger_users.first_name', 'last_name' => 'vtiger_users.last_name'), 'Users');

			$query = "SELECT CASE WHEN (vtiger_users.user_name not like '') THEN $userNameSql ELSE vtiger_groups.groupname END AS user_name,
						vtiger_crmentity.*, vtiger_activity.activitytype, vtiger_activity.subject, vtiger_activity.date_start, vtiger_activity.time_start,
						vtiger_activity.recurringtype, vtiger_activity.due_date, vtiger_activity.time_end, vtiger_seactivityrel.crmid AS parent_id,
						CASE WHEN (vtiger_activity.activitytype = 'Task') THEN (vtiger_activity.status) ELSE (vtiger_activity.eventstatus) END AS status
						FROM vtiger_activity
						INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_activity.activityid
						LEFT JOIN vtiger_seactivityrel ON vtiger_seactivityrel.activityid = vtiger_activity.activityid
						LEFT JOIN vtiger_cntactivityrel ON vtiger_cntactivityrel.activityid = vtiger_activity.activityid
						LEFT JOIN vtiger_users ON vtiger_users.id = vtiger_crmentity.smownerid
						LEFT JOIN vtiger_groups ON vtiger_groups.groupid = vtiger_crmentity.smownerid
							WHERE vtiger_crmentity.deleted = 0 AND vtiger_activity.activitytype <> 'Emails'
								AND vtiger_seactivityrel.crmid = ".$recordId;

			$relatedModuleName = $relatedModule->getName();
			$query .= $this->getSpecificRelationQuery($relatedModuleName);
			$nonAdminQuery = $this->getNonAdminAccessControlQueryForRelation($relatedModuleName);
			if ($nonAdminQuery) {
				$query = appendFromClauseToQuery($query, $nonAdminQuery);
			}
		} else {
			$query = parent::getRelationQuery($recordId, $functionName, $relatedModule);
		}

		return $query;
	}

	/**
	 * Function to get list view query for popup window
	 * @param <String> $sourceModule Parent module
	 * @param <String> $field parent fieldname
	 * @param <Integer> $record parent id
	 * @param <String> $listQuery
	 * @return <String> Listview Query
	 */
	public function getQueryByModuleField($sourceModule, $field, $record, $listQuery) {
		if (in_array($sourceModule, array('Assets', 'Project', 'ServiceContracts', 'Services'))) {
			$condition = " vtiger_troubletickets.ticketid NOT IN (SELECT relcrmid FROM vtiger_crmentityrel WHERE crmid = '$record' UNION SELECT crmid FROM vtiger_crmentityrel WHERE relcrmid = '$record') ";
			$pos = stripos($listQuery, 'where');

			if ($pos) {
				$split = spliti('where', $listQuery);
				$overRideQuery = $split[0] . ' WHERE ' . $split[1] . ' AND ' . $condition;
			} else {
				$overRideQuery = $listQuery . ' WHERE ' . $condition;
			}
			return $overRideQuery;
		}
	}



}
