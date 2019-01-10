<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * ************************************************************************************/
ini_set("display_errors", 0);

class Analisis_Module_Model extends Vtiger_Module_Model {

	/*Para que aparezca la opcion de seguridad en gráficas en la parte de Perfiles*/
	public function isUtilityActionEnabled() {
	    return true;
	}
	
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
			SUM(IF(programa LIKE '%Programas Gaviotas%',1,0 )) AS gaviotas,
			SUM(IF(programa LIKE '%Generico%',1,0 )) AS genericos,
			SUM(IF(programa LIKE '%Socio Afin%',1,0 )) AS afines,
			SUM(IF(programa LIKE '%Socio J&J%',1,0 )) AS jyjs,
			SUM(IF(programa LIKE '%Administrativo%',1,0 )) AS administrativos,
			SUM(IF(programa LIKE '%Mi Shopping%',1,0 )) AS funcionarios,
			SUM(IF(programa LIKE '%Proveedor%',1,0 )) AS proveedores,
			SUM(IF(programa LIKE '%Club de Golf%',1,0 )) AS golfs,
			SUM(IF(programa LIKE '%Tarjeta Beneficios%',1,0 )) AS beneficios,
			
			SUM(IF(programa LIKE '%WTC Member%' AND programa LIKE '%Comprador Frecuente%' ,1,0 )) AS wtc_frecuentes,
			SUM(IF(programa LIKE '%WTC Member%' AND programa LIKE '%Los 1%' ,1,0 )) AS wtc_unos,
			SUM(IF(programa LIKE '%WTC Member%' AND programa LIKE '%Programas Gaviotas%' ,1,0 )) AS wtc_gaviotas,
			SUM(IF(programa LIKE '%WTC Member%' AND programa LIKE '%Generico%' ,1,0 )) AS wtc_genericos,
			SUM(IF(programa LIKE '%WTC Member%' AND programa LIKE '%Socio Afin%' ,1,0 )) AS wtc_afines,
			SUM(IF(programa LIKE '%WTC Member%' AND programa LIKE '%Socio J&J%' ,1,0 )) AS wtc_jyjs,
			SUM(IF(programa LIKE '%WTC Member%' AND programa LIKE '%Administrativo%' ,1,0 )) AS wtc_administrativos,
			SUM(IF(programa LIKE '%WTC Member%' AND programa LIKE '%Mi Shopping%' ,1,0 )) AS wtc_funcionarios,
			SUM(IF(programa LIKE '%WTC Member%' AND programa LIKE '%Proveedor%' ,1,0 )) AS wtc_proveedores,
			SUM(IF(programa LIKE '%WTC Member%' AND programa LIKE '%Club de Golf%' ,1,0 )) AS wtc_golfs,
			SUM(IF(programa LIKE '%WTC Member%' AND programa LIKE '%Tarjeta Beneficios%' ,1,0 )) AS wtc_beneficios,
			
			SUM(IF(programa LIKE '%Comprador Frecuente%' AND programa LIKE '%Los 1%' ,1,0 )) AS frecuentes_unos,
			SUM(IF(programa LIKE '%Comprador Frecuente%' AND programa LIKE '%Programas Gaviotas%' ,1,0 )) AS frecuentes_gaviotas,
			SUM(IF(programa LIKE '%Comprador Frecuente%' AND programa LIKE '%Generico%' ,1,0 )) AS frecuentes_genericos,
			SUM(IF(programa LIKE '%Comprador Frecuente%' AND programa LIKE '%Socio Afin%' ,1,0 )) AS frecuentes_afines,
			SUM(IF(programa LIKE '%Comprador Frecuente%' AND programa LIKE '%Socio J&J%' ,1,0 )) AS frecuentes_jyjs,
			SUM(IF(programa LIKE '%Comprador Frecuente%' AND programa LIKE '%Administrativo%' ,1,0 )) AS frecuentes_administrativos,
			SUM(IF(programa LIKE '%Comprador Frecuente%' AND programa LIKE '%Mi Shopping%' ,1,0 )) AS frecuentes_funcionarios,
			SUM(IF(programa LIKE '%Comprador Frecuente%' AND programa LIKE '%Proveedor%' ,1,0 )) AS frecuentes_proveedores,
			SUM(IF(programa LIKE '%Comprador Frecuente%' AND programa LIKE '%Club de Golf%' ,1,0 )) AS frecuentes_golfs,
			SUM(IF(programa LIKE '%Comprador Frecuente%' AND programa LIKE '%Tarjeta Beneficios%' ,1,0 )) AS frecuentes_beneficios,
			

			SUM(IF(programa LIKE '%Los 1%' AND programa LIKE '%Programas Gaviotas%' ,1,0 )) AS unos_gaviotas,
			SUM(IF(programa LIKE '%Los 1%' AND programa LIKE '%Genericos%' ,1,0 )) AS unos_genericos,
			SUM(IF(programa LIKE '%Los 1%' AND programa LIKE '%Socio Afin%' ,1,0 )) AS unos_afines,
			SUM(IF(programa LIKE '%Los 1%' AND programa LIKE '%Socio J&J%' ,1,0 )) AS unos_jyjs,
			SUM(IF(programa LIKE '%Los 1%' AND programa LIKE '%Administrativo%' ,1,0 )) AS unos_administrativos,
			SUM(IF(programa LIKE '%Los 1%' AND programa LIKE '%Mi Shopping%' ,1,0 )) AS unos_funcionarios,
			SUM(IF(programa LIKE '%Los 1%' AND programa LIKE '%Proveedor%' ,1,0 )) AS unos_proveedores,
			SUM(IF(programa LIKE '%Los 1%' AND programa LIKE '%Club de Golf%' ,1,0 )) AS unos_golfs,
			SUM(IF(programa LIKE '%Los 1%' AND programa LIKE '%Tarjeta Beneficios%' ,1,0 )) AS unos_beneficios,

			SUM(IF(programa LIKE '%Programas Gaviotas%' AND programa LIKE '%Genericos%' ,1,0 )) AS gaviotas_genericos,
			SUM(IF(programa LIKE '%Programas Gaviotas%' AND programa LIKE '%Socio Afin%' ,1,0 )) AS gaviotas_afines,
			SUM(IF(programa LIKE '%Programas Gaviotas%' AND programa LIKE '%Socio J&J%' ,1,0 )) AS gaviotas_jyjs,
			SUM(IF(programa LIKE '%Programas Gaviotas%' AND programa LIKE '%Administrativo%' ,1,0 )) AS gaviotas_administrativos,
			SUM(IF(programa LIKE '%Programas Gaviotas%' AND programa LIKE '%Mi Shopping%' ,1,0 )) AS gaviotas_funcionarios,
			SUM(IF(programa LIKE '%Programas Gaviotas%' AND programa LIKE '%Proveedor%' ,1,0 )) AS gaviotas_proveedores,
			SUM(IF(programa LIKE '%Programas Gaviotas%' AND programa LIKE '%Club de Golf%' ,1,0 )) AS gaviotas_golfs,
			SUM(IF(programa LIKE '%Programas Gaviotas%' AND programa LIKE '%Tarjeta Beneficios%' ,1,0 )) AS gaviotas_beneficios,

			SUM(IF(programa LIKE '%Socio Afin%' AND programa LIKE '%Socio J&J%' ,1,0 )) AS afines_jyjs,
			SUM(IF(programa LIKE '%Socio Afin%' AND programa LIKE '%Administrativo%' ,1,0 )) AS afines_administrativos,
			SUM(IF(programa LIKE '%Socio Afin%' AND programa LIKE '%Mi Shopping%' ,1,0 )) AS afines_funcionarios,
			SUM(IF(programa LIKE '%Socio Afin%' AND programa LIKE '%Proveedor%' ,1,0 )) AS afines_proveedores,
			SUM(IF(programa LIKE '%Socio Afin%' AND programa LIKE '%Club de Golf%' ,1,0 )) AS afines_golfs,
			SUM(IF(programa LIKE '%Socio Afin%' AND programa LIKE '%Tarjeta Beneficios%' ,1,0 )) AS afines_beneficios,

			SUM(IF(programa LIKE '%Socio J&J%' AND programa LIKE '%Administrativo%' ,1,0 )) AS jyjs_administrativos,
			SUM(IF(programa LIKE '%Socio J&J%' AND programa LIKE '%Mi Shopping%' ,1,0 )) AS jyjs_funcionarios,
			SUM(IF(programa LIKE '%Socio J&J%' AND programa LIKE '%Proveedor%' ,1,0 )) AS jyjs_proveedores,
			SUM(IF(programa LIKE '%Socio J&J%' AND programa LIKE '%Club de Golf%' ,1,0 )) AS jyjs_golfs,
			SUM(IF(programa LIKE '%Socio J&J%' AND programa LIKE '%Tarjeta Beneficios%' ,1,0 )) AS jyjs_beneficios,

			SUM(IF(programa LIKE '%Administrativo%' AND programa LIKE '%Mi Shopping%' ,1,0 )) AS administrativos_funcionarios,
			SUM(IF(programa LIKE '%Administrativo%' AND programa LIKE '%Proveedor%' ,1,0 )) AS administrativos_proveedores,
			SUM(IF(programa LIKE '%Administrativo%' AND programa LIKE '%Club de Golf%' ,1,0 )) AS administrativos_golfs,
			SUM(IF(programa LIKE '%Administrativo%' AND programa LIKE '%Tarjeta Beneficios%' ,1,0 )) AS administrativos_beneficios,

			SUM(IF(programa LIKE '%Mi Shopping%' AND programa LIKE '%Proveedor%' ,1,0 )) AS funcionarios_proveedores,
			SUM(IF(programa LIKE '%Mi Shopping%' AND programa LIKE '%Club de Golf%' ,1,0 )) AS funcionarios_golfs,
			SUM(IF(programa LIKE '%Mi Shopping%' AND programa LIKE '%Tarjeta Beneficios%' ,1,0 )) AS funcionarios_beneficios,

			SUM(IF(programa LIKE '%Proveedor%' AND programa LIKE '%Club de Golf%' ,1,0 )) AS proveedores_golfs,
			SUM(IF(programa LIKE '%Tarjeta Beneficios%' AND programa LIKE '%Club de Golf%' ,1,0 )) AS golfs_beneficios,

			SUM(IF(lppromociones is not null and lppromociones!='NULL',1,0 )) AS promociones,
			SUM(IF(lppromociones is not null and lppromociones!='NULL' AND programa LIKE '%Comprador Frecuente%' ,1,0 )) AS promociones_frecuentes,
			SUM(IF(lppromociones is not null and lppromociones!='NULL' AND programa LIKE '%Los 1%' ,1,0 )) AS promociones_unos,
			SUM(IF(lppromociones is not null and lppromociones!='NULL' AND programa LIKE '%Programas Gaviotas%' ,1,0 )) AS promociones_gaviotas,
			SUM(IF(lppromociones is not null and lppromociones!='NULL' AND programa LIKE '%Generico%' ,1,0 )) AS promociones_genericos,
			SUM(IF(lppromociones is not null and lppromociones!='NULL' AND programa LIKE '%Socio Afin%' ,1,0 )) AS promociones_afines,
			SUM(IF(lppromociones is not null and lppromociones!='NULL' AND programa LIKE '%Socio J&J%' ,1,0 )) AS promociones_jyjs,
			SUM(IF(lppromociones is not null and lppromociones!='NULL' AND programa LIKE '%Administrativo%' ,1,0 )) AS promociones_administrativos,
			SUM(IF(lppromociones is not null and lppromociones!='NULL' AND programa LIKE '%Mi Shopping%' ,1,0 )) AS promociones_funcionarios,
			SUM(IF(lppromociones is not null and lppromociones!='NULL' AND programa LIKE '%Proveedor%' ,1,0 )) AS promociones_proveedores,
			SUM(IF(lppromociones is not null and lppromociones!='NULL' AND programa LIKE '%Club de Golf%' ,1,0 )) AS promociones_golfs,
			SUM(IF(lppromociones is not null and lppromociones!='NULL' AND programa LIKE '%Tarjeta Beneficios%' ,1,0 )) AS promociones_beneficios
			
			FROM vtiger_contactdetails
			INNER JOIN vtiger_crmentity on crmid=contactid
			WHERE 1=1 AND deleted=0  
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
		//echo $query;
		$result=$adb->query($query);
		$no_of_rows=$adb->num_rows($result);

		//Inicializo matriz
		$matriz['wtc']['frecuentes']=$matriz['wtc']['unos']=$matriz['wtc']['gaviotas']=$matriz['wtc']['genericos']=0;
		$matriz['wtc']['afines']=$matriz['wtc']['jyjs']=$matriz['wtc']['administrativos']=$matriz['wtc']['funcionarios']=0;
		$matriz['wtc']['proveedores']=$matriz['wtc']['golfs']=$matriz['wtc']['beneficios']=$matriz['wtc']['promociones']=0;

		$matriz['frecuentes']['wtc']=$matriz['frecuentes']['unos']=$matriz['frecuentes']['gaviotas']=$matriz['frecuentes']['genericos']=0;
		$matriz['frecuentes']['afines']=$matriz['frecuentes']['jyjs']=$matriz['frecuentes']['administrativos']=$matriz['frecuentes']['funcionarios']=0;
		$matriz['frecuentes']['proveedores']=$matriz['frecuentes']['golfs']=$matriz['frecuentes']['beneficios']=$matriz['frecuentes']['promociones']=0;
		
		$matriz['unos']['wtc']=$matriz['unos']['frecuentes']=$matriz['unos']['gaviotas']=$matriz['unos']['genericos']=0;
		$matriz['unos']['afines']=$matriz['unos']['jyjs']=$matriz['unos']['administrativos']=$matriz['unos']['funcionarios']=0;
		$matriz['unos']['proveedores']=$matriz['unos']['golfs']=$matriz['unos']['beneficios']=$matriz['unos']['promociones']=0;
		
		$matriz['gaviotas']['wtc']=$matriz['gaviotas']['unos']=$matriz['gaviotas']['genericos']=$matriz['gaviotas']['frecuentes']=0;
		$matriz['gaviotas']['afines']=$matriz['gaviotas']['jyjs']=$matriz['gaviotas']['administrativos']=$matriz['gaviotas']['funcionarios']=0;
		$matriz['gaviotas']['proveedores']=$matriz['gaviotas']['golfs']=$matriz['gaviotas']['beneficios']=$matriz['gaviotas']['promociones']=0;
		
		$matriz['genericos']['wtc']=$matriz['genericos']['unos']=$matriz['genericos']['frecuentes']=$matriz['genericos']['gaviotas']=0;
		$matriz['genericos']['afines']=$matriz['genericos']['jyjs']=$matriz['genericos']['administrativos']=$matriz['genericos']['funcionarios']=0;
		$matriz['genericos']['proveedores']=$matriz['genericos']['golfs']=$matriz['genericos']['beneficios']=$matriz['genericos']['promociones']=0;
		
		$matriz['afines']['wtc']=$matriz['afines']['unos']=$matriz['afines']['frecuentes']=$matriz['afines']['gaviotas']=0;
		$matriz['afines']['genericos']=$matriz['afines']['jyjs']=$matriz['afines']['administrativos']=$matriz['afines']['funcionarios']=0;
		$matriz['afines']['proveedores']=$matriz['afines']['golfs']=$matriz['afines']['beneficios']=$matriz['afines']['promociones']=0;
		
		$matriz['jyjs']['wtc']=$matriz['jyjs']['unos']=$matriz['jyjs']['frecuentes']=$matriz['jyjs']['gaviotas']=0;
		$matriz['jyjs']['genericos']=$matriz['jyjs']['afines']=$matriz['jyjs']['administrativos']=$matriz['jyjs']['funcionarios']=0;
		$matriz['jyjs']['proveedores']=$matriz['jyjs']['golfs']=$matriz['jyjs']['beneficios']=$matriz['jyjs']['promociones']=0;
		
		$matriz['administrativos']['wtc']=$matriz['administrativos']['unos']=$matriz['administrativos']['frecuentes']=$matriz['administrativos']['gaviotas']=0;
		$matriz['administrativos']['genericos']=$matriz['administrativos']['afines']=$matriz['administrativos']['jyjs']=$matriz['administrativos']['funcionarios']=0;
		$matriz['administrativos']['proveedores']=$matriz['administrativos']['golfs']=$matriz['administrativos']['beneficios']=$matriz['administrativos']['promociones']=0;
		
		$matriz['funcionarios']['wtc']=$matriz['funcionarios']['unos']=$matriz['funcionarios']['frecuentes']=$matriz['funcionarios']['gaviotas']=0;
		$matriz['funcionarios']['genericos']=$matriz['funcionarios']['afines']=$matriz['funcionarios']['jyjs']=$matriz['funcionarios']['administrativos']=0;
		$matriz['funcionarios']['proveedores']=$matriz['funcionarios']['golfs']=$matriz['funcionarios']['beneficios']=$matriz['funcionarios']['promociones']=0;
		
		$matriz['proveedores']['wtc']=$matriz['proveedores']['unos']=$matriz['proveedores']['frecuentes']=$matriz['proveedores']['gaviotas']=0;
		$matriz['proveedores']['genericos']=$matriz['proveedores']['afines']=$matriz['proveedores']['jyjs']=$matriz['proveedores']['administrativos']=0;
		$matriz['proveedores']['funcionarios']=$matriz['proveedores']['golfs']=$matriz['proveedores']['beneficios']=$matriz['proveedores']['promociones']=0;

		$matriz['golfs']['wtc']=$matriz['golfs']['unos']=$matriz['golfs']['frecuentes']=$matriz['golfs']['gaviotas']=0;
		$matriz['golfs']['genericos']=$matriz['golfs']['afines']=$matriz['golfs']['jyjs']=$matriz['golfs']['administrativos']=0;
		$matriz['golfs']['funcionarios']=$matriz['golfs']['proveedores']=$matriz['golfs']['beneficios']=$matriz['golfs']['promociones']=0;

		$matriz['beneficios']['wtc']=$matriz['beneficios']['unos']=$matriz['beneficios']['frecuentes']=$matriz['beneficios']['gaviotas']=0;
		$matriz['beneficios']['genericos']=$matriz['beneficios']['afines']=$matriz['beneficios']['jyjs']=$matriz['beneficios']['administrativos']=0;
		$matriz['beneficios']['funcionarios']=$matriz['beneficios']['proveedores']=$matriz['beneficios']['golfs']=$matriz['beneficios']['promociones']=0;
		
		
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
			$beneficios = number_format($adb->query_result($result,0,'beneficios'),0,",",".");
			$promociones = number_format($adb->query_result($result,0,'promociones'),0,",",".");
			
			
			
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
			$matriz['wtc']['beneficios'] =$matriz['beneficios']['wtc'] = $adb->query_result($result,0,'wtc_beneficios');
			$matriz['wtc']['promociones'] =$matriz['promociones']['wtc'] = $adb->query_result($result,0,'promociones_wtc');
			
			$matriz['frecuentes']['unos'] =$matriz['unos']['frecuentes'] =$adb->query_result($result,0,'frecuentes_unos');
			$matriz['frecuentes']['gaviotas'] =$matriz['gaviotas']['frecuentes'] =$adb->query_result($result,0,'frecuentes_gaviotas');
			$matriz['frecuentes']['genericos'] =$matriz['genericos']['frecuentes'] = $adb->query_result($result,0,'frecuentes_genericos');
			$matriz['frecuentes']['afines'] =$matriz['afines']['frecuentes'] = $adb->query_result($result,0,'frecuentes_afines');
			$matriz['frecuentes']['jyjs'] =$matriz['jyjs']['frecuentes'] = $adb->query_result($result,0,'frecuentes_jyjs');
			$matriz['frecuentes']['administrativos'] =$matriz['administrativos']['frecuentes'] = $adb->query_result($result,0,'frecuentes_administrativos');
			$matriz['frecuentes']['funcionarios'] =$matriz['funcionarios']['frecuentes'] = $adb->query_result($result,0,'frecuentes_funcionarios');
			$matriz['frecuentes']['proveedores'] =$matriz['proveedores']['frecuentes'] = $adb->query_result($result,0,'frecuentes_proveedores');
			$matriz['frecuentes']['golfs'] =$matriz['golfs']['frecuentes'] = $adb->query_result($result,0,'frecuentes_golfs');
			$matriz['frecuentes']['beneficios'] =$matriz['beneficios']['frecuentes'] = $adb->query_result($result,0,'frecuentes_beneficios');
			$matriz['frecuentes']['promociones'] =$matriz['promociones']['frecuentes'] = $adb->query_result($result,0,'promociones_frecuentes');
			
			
			$matriz['unos']['gaviotas'] =$matriz['gaviotas']['unos'] =$adb->query_result($result,0,'unos_gaviotas');
			$matriz['unos']['genericos'] =$matriz['genericos']['unos'] = $adb->query_result($result,0,'unos_genericos');
			$matriz['unos']['afines'] =$matriz['afines']['unos'] = $adb->query_result($result,0,'unos_afines');
			$matriz['unos']['jyjs'] =$matriz['jyjs']['unos'] = $adb->query_result($result,0,'unos_jyjs');
			$matriz['unos']['administrativos'] =$matriz['administrativos']['unos'] = $adb->query_result($result,0,'unos_administrativos');
			$matriz['unos']['funcionarios'] =$matriz['funcionarios']['unos'] = $adb->query_result($result,0,'unos_funcionarios');
			$matriz['unos']['proveedores'] =$matriz['proveedores']['unos'] = $adb->query_result($result,0,'unos_proveedores');
			$matriz['unos']['golfs'] =$matriz['golfs']['unos'] = $adb->query_result($result,0,'unos_golfs');
			$matriz['unos']['beneficios'] =$matriz['beneficios']['unos'] = $adb->query_result($result,0,'unos_beneficios');
			$matriz['unos']['promociones'] =$matriz['promociones']['unos'] = $adb->query_result($result,0,'promociones_unos');
			
			$matriz['gaviotas']['genericos'] =$matriz['genericos']['gaviotas'] = $adb->query_result($result,0,'gaviotas_genericos');
			$matriz['gaviotas']['afines'] =$matriz['afines']['gaviotas'] = $adb->query_result($result,0,'gaviotas_afines');
			$matriz['gaviotas']['jyjs'] =$matriz['jyjs']['gaviotas'] = $adb->query_result($result,0,'gaviotas_jyjs');
			$matriz['gaviotas']['administrativos'] =$matriz['administrativos']['gaviotas'] = $adb->query_result($result,0,'gaviotas_administrativos');
			$matriz['gaviotas']['funcionarios'] =$matriz['funcionarios']['gaviotas'] = $adb->query_result($result,0,'gaviotas_funcionarios');
			$matriz['gaviotas']['proveedores'] =$matriz['proveedores']['gaviotas'] = $adb->query_result($result,0,'gaviotas_proveedores');
			$matriz['gaviotas']['golfs'] =$matriz['golfs']['gaviotas'] = $adb->query_result($result,0,'gaviotas_golfs');
			$matriz['gaviotas']['beneficios'] =$matriz['beneficios']['gaviotas'] = $adb->query_result($result,0,'gaviotas_beneficios');
			$matriz['gaviotas']['promociones'] =$matriz['promociones']['gaviotas'] = $adb->query_result($result,0,'promociones_gaviotas');

			$matriz['genericos']['afines'] =$matriz['afines']['genericos'] = $adb->query_result($result,0,'genericos_afines');
			$matriz['genericos']['jyjs'] =$matriz['jyjs']['genericos'] = $adb->query_result($result,0,'genericos_jyjs');
			$matriz['genericos']['administrativos'] =$matriz['administrativos']['genericos'] = $adb->query_result($result,0,'genericos_administrativos');
			$matriz['genericos']['funcionarios'] =$matriz['funcionarios']['genericos'] = $adb->query_result($result,0,'genericos_funcionarios');
			$matriz['genericos']['proveedores'] =$matriz['proveedores']['genericos'] = $adb->query_result($result,0,'genericos_proveedores');
			$matriz['genericos']['golfs'] =$matriz['golfs']['genericos'] = $adb->query_result($result,0,'genericos_golfs');
			$matriz['genericos']['beneficios'] =$matriz['beneficios']['genericos'] = $adb->query_result($result,0,'genericos_beneficios');
			$matriz['genericos']['promociones'] =$matriz['promociones']['genericos'] = $adb->query_result($result,0,'promociones_genericos');
			
			$matriz['afines']['jyjs'] =$matriz['jyjs']['afines'] = $adb->query_result($result,0,'afines_jyjs');
			$matriz['afines']['administrativos'] =$matriz['administrativos']['afines'] = $adb->query_result($result,0,'afines_administrativos');
			$matriz['afines']['funcionarios'] =$matriz['funcionarios']['afines'] = $adb->query_result($result,0,'afines_funcionarios');
			$matriz['afines']['proveedores'] =$matriz['proveedores']['afines'] = $adb->query_result($result,0,'afines_proveedores');
			$matriz['afines']['golfs'] =$matriz['golfs']['afines'] = $adb->query_result($result,0,'afines_golfs');
			$matriz['afines']['beneficios'] =$matriz['beneficios']['afines'] = $adb->query_result($result,0,'afines_beneficios');
			$matriz['afines']['promociones'] =$matriz['promociones']['afines'] = $adb->query_result($result,0,'promociones_afines');

			$matriz['jyjs']['administrativos'] =$matriz['administrativos']['jyjs'] = $adb->query_result($result,0,'jyjs_administrativos');
			$matriz['jyjs']['funcionarios'] =$matriz['funcionarios']['jyjs'] = $adb->query_result($result,0,'jyjs_funcionarios');
			$matriz['jyjs']['proveedores'] =$matriz['proveedores']['jyjs'] = $adb->query_result($result,0,'jyjs_proveedores');
			$matriz['jyjs']['golfs'] =$matriz['golfs']['jyjs'] = $adb->query_result($result,0,'jyjs_golfs');
			$matriz['jyjs']['beneficios'] =$matriz['beneficios']['jyjs'] = $adb->query_result($result,0,'jyjs_beneficios');
			$matriz['jyjs']['promociones'] =$matriz['promociones']['jyjs'] = $adb->query_result($result,0,'promociones_jyjs');
			

			$matriz['administrativos']['funcionarios'] =$matriz['funcionarios']['administrativos'] = $adb->query_result($result,0,'administrativos_funcionarios');
			$matriz['administrativos']['proveedores'] =$matriz['proveedores']['administrativos'] = $adb->query_result($result,0,'administrativos_proveedores');
			$matriz['administrativos']['golfs'] =$matriz['golfs']['administrativos'] = $adb->query_result($result,0,'administrativos_golfs');
			$matriz['administrativos']['beneficios'] =$matriz['beneficios']['administrativos'] = $adb->query_result($result,0,'administrativos_beneficios');
			$matriz['administrativos']['promociones'] =$matriz['promociones']['administrativos'] = $adb->query_result($result,0,'promociones_administrativos');
			
			$matriz['funcionarios']['proveedores'] =$matriz['proveedores']['funcionarios'] = $adb->query_result($result,0,'funcionarios_proveedores');
			$matriz['funcionarios']['golfs'] =$matriz['golfs']['funcionarios'] = $adb->query_result($result,0,'funcionarios_golfs');
			$matriz['funcionarios']['beneficios'] =$matriz['beneficios']['funcionarios'] = $adb->query_result($result,0,'funcionarios_beneficios');
			$matriz['funcionarios']['promociones'] =$matriz['promociones']['funcionarios'] = $adb->query_result($result,0,'promociones_funcionarios');

			$matriz['proveedores']['golfs'] =$matriz['golfs']['proveedores'] = $adb->query_result($result,0,'proveedores_golfs');
			$matriz['proveedores']['beneficios'] =$matriz['beneficios']['proveedores'] = $adb->query_result($result,0,'proveedores_golfs');
			$matriz['golfs']['beneficios'] =$matriz['beneficios']['golfs'] = $adb->query_result($result,0,'golfs_beneficios');
			$matriz['golfs']['promociones'] =$matriz['promociones']['golfs'] = $adb->query_result($result,0,'promociones_golfs');
			
			$matriz['beneficios']['promociones'] =$matriz['promociones']['beneficios'] = $adb->query_result($result,0,'promociones_beneficios');
			

			if(isset($wtc) && $wtc!=0) $porc_wtc_frecuentes = (($wtc_frecuentes/$wtc)*100)."%";
			if(isset($frecuentes) && $frecuentes!=0) $porc_frecuentes_wtc = (($wtc_frecuentes/$frecuentes)*100)."%";
			if(isset($wtc) && $wtc!=0) $porc_wtc_beneficios = (($wtc_beneficios/$wtc)*100)."%";
			if(isset($beneficios) && $beneficios!=0) $porc_beneficios_wtc = (($wtc_beneficios/$beneficios)*100)."%";
			if(isset($wtc) && $wtc!=0) $porc_wtc_gaviotas = (($wtc_gaviotas/$wtc)*100)."%";
			if(isset($gaviotas) && $gaviotas!=0) $porc_gaviotas_wtc = (($wtc_gaviotas/$gaviotas)*100)."%";
			if(isset($wtc) && $wtc!=0) $porc_wtc_unos = (($wtc_unos/$wtc)*100)."%";
			if(isset($unos) && $unos!=0) $porc_unos_wtc = (($wtc_unos/$unos)*100)."%";


			



			$json_string="[[\"Programa\",\"Todas Personas\",\"Generico\",\"Comprador Frecuente\",\"Socio Afin\",\"Los 1\",
							\"Socio J&J\",\"Programas Gaviotas\",\"Administrativo\",\"Mi Shopping\",\"Proveedor\",\"WTC Member\",
							\"Club de Golf\",\"Tarjeta Beneficios\",\"Promociones\",\"Orden\"],";
			
			$json_string.="[\"Todas Personas\",\"".$total_personas."\",\"".$this->getPctje($genericos,$total_personas)."\",
							\"".$this->getPctje($frecuentes,$total_personas)."\",\"".$this->getPctje($afines,$total_personas)."\",\"".$this->getPctje($unos,$total_personas)."\",
							\"".$this->getPctje($jyjs,$total_personas)."\",\"".$this->getPctje($gaviotas,$total_personas)."\",\"".$this->getPctje($administrativos,$total_personas)."\",
							\"".$this->getPctje($funcionarios,$total_personas)."\",\"".$this->getPctje($proveedores,$total_personas)."\",\"".$this->getPctje($wtc,$total_personas)."\",
							\"".$this->getPctje($golfs,$total_personas)."\",\"".$this->getPctje($beneficios,$total_personas)."\",\"".$this->getPctje($promociones,$total_personas)."\",\"".str_replace('.', '',$total_personas)."\"],";

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
			\"".$this->getPctje($matriz["frecuentes"]["golfs"],$frecuentes)."\",\"".$this->getPctje($matriz["frecuentes"]["beneficios"],$frecuentes)."\",\"".$this->getPctje($matriz["frecuentes"]["promociones"],$frecuentes)."\",\"".str_replace('.', '',$frecuentes)."\"],";
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
			\"".$this->getPctje($matriz["unos"]["golfs"],$unos)."\",\"".$this->getPctje($matriz["unos"]["beneficios"],$unos)."\",\"".$this->getPctje($matriz["unos"]["promociones"],$unos)."\",\"".str_replace('.', '',$unos)."\"],";
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
			$json_string.="[\"Programas Gaviotas\",\"100%\",\"".$this->getPctje($matriz["genericos"]["gaviotas"],$gaviotas)."\",
			\"".$this->getPctje($matriz["frecuentes"]["gaviotas"],$gaviotas)."\",
			\"".$this->getPctje($matriz["gaviotas"]["afines"],$gaviotas)."\",
			\"".$this->getPctje($matriz["gaviotas"]["unos"],$gaviotas)."\",
			\"".$this->getPctje($matriz["gaviotas"]["jyjs"],$gaviotas)."\",\"".$gaviotas."\",
			\"".$this->getPctje($matriz["gaviotas"]["administrativos"],$gaviotas)."\",\"".$this->getPctje($matriz["gaviotas"]["funcionarios"],$gaviotas)."\",
			\"".$this->getPctje($matriz["gaviotas"]["proveedores"],$gaviotas)."\",\"".$this->getPctje($matriz["gaviotas"]["wtc"],$gaviotas)."\",
			\"".$this->getPctje($matriz["gaviotas"]["golfs"],$gaviotas)."\",\"".$this->getPctje($matriz["gaviotas"]["beneficios"],$gaviotas)."\",\"".$this->getPctje($matriz["gaviotas"]["promociones"],$gaviotas)."\",\"".str_replace('.', '',$gaviotas)."\"],";
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
		
			$json_string.="[\"Mi Shopping\",\"100%\",\"".$this->getPctje($matriz["genericos"]["funcionarios"],$funcionarios)."\",\"".$this->getPctje($matriz["frecuentes"]["funcionarios"],$funcionarios)."\",
			\"".$this->getPctje($matriz["funcionarios"]["afines"],$funcionarios)."\",
			\"".$this->getPctje($matriz["funcionarios"]["unos"],$funcionarios)."\",
			\"".$this->getPctje($matriz["funcionarios"]["jyjs"],$funcionarios)."\",
			\"".$this->getPctje($matriz["funcionarios"]["gaviotas"],$funcionarios)."\",
			\"".$this->getPctje($matriz["funcionarios"]["administrativos"],$funcionarios)."\",\"".$funcionarios."\",
			\"".$this->getPctje($matriz["funcionarios"]["proveedores"],$funcionarios)."\",\"".$this->getPctje($matriz["funcionarios"]["wtc"],$funcionarios)."\",
			\"".$this->getPctje($matriz["funcionarios"]["golfs"],$funcionarios)."\",\"".$this->getPctje($matriz["funcionarios"]["beneficios"],$funcionarios)."\",\"".$this->getPctje($matriz["funcionarios"]["promociones"],$funcionarios)."\",\"".str_replace('.', '',$funcionarios)."\"],";

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
			\"".$wtc."\",\"".$this->getPctje($matriz["wtc"]["golfs"],$wtc)."\",\"".$this->getPctje($matriz["wtc"]["beneficios"],$wtc)."\",\"".$this->getPctje($matriz["wtc"]["promociones"],$wtc)."\",\"".str_replace('.', '',$wtc)."\"],";
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

			$json_string.="[\"Tarjeta Beneficios\",\"100%\",
			\"".$this->getPctje($matriz["beneficios"]["wtc"],$beneficios)."\",
			\"".$this->getPctje($matriz["beneficios"]["frecuentes"],$beneficios)."\",
			\"".$this->getPctje($matriz["beneficios"]["afines"],$beneficios)."\",
			\"".$this->getPctje($matriz["beneficios"]["unos"],$beneficios)."\",
			\"".$this->getPctje($matriz["beneficios"]["jyjs"],$beneficios)."\",
			\"".$this->getPctje($matriz["beneficios"]["gaviotas"],$beneficios)."\",
			\"".$this->getPctje($matriz["beneficios"]["administrativos"],$beneficios)."\",
			\"".$this->getPctje($matriz["beneficios"]["funcionarios"],$beneficios)."\",
			\"".$this->getPctje($matriz["beneficios"]["proveedores"],$beneficios)."\",
			\"".$this->getPctje($matriz["wtc"]["beneficios"],$beneficios)."\",
			\"".$this->getPctje($matriz["beneficios"]["golfs"],$beneficios)."\",
			\"".$beneficios."\",
			\"".$this->getPctje($matriz["beneficios"]["promociones"],$beneficios)."\",\"".str_replace('.', '',$beneficios)."\"],";
			
			$json_string.="[\"Promociones\",\"100%\",
			\"".$this->getPctje($matriz["promociones"]["wtc"],$promociones)."\",
			\"".$this->getPctje($matriz["promociones"]["frecuentes"],$promociones)."\",
			\"".$this->getPctje($matriz["promociones"]["afines"],$promociones)."\",
			\"".$this->getPctje($matriz["promociones"]["unos"],$promociones)."\",
			\"".$this->getPctje($matriz["promociones"]["jyjs"],$promociones)."\",
			\"".$this->getPctje($matriz["promociones"]["gaviotas"],$promociones)."\",
			\"".$this->getPctje($matriz["promociones"]["administrativos"],$promociones)."\",
			\"".$this->getPctje($matriz["promociones"]["funcionarios"],$promociones)."\",
			\"".$this->getPctje($matriz["promociones"]["proveedores"],$promociones)."\",
			\"".$this->getPctje($matriz["wtc"]["promociones"],$promociones)."\",
			\"".$this->getPctje($matriz["promociones"]["golfs"],$promociones)."\",
			\"".$this->getPctje($matriz["promociones"]["beneficios"],$promociones)."\",
			\"".$promociones."\",\"".str_replace('.', '',$promociones)."\"]";
			$json_string.="]";				
		}else{

			$json_string="[[\"Programa\",\"Todas Personas\",\"Compradores Frecuentes\",\"Beneficios\",\"Gaviotas\",\"Los 1\",\"WTC\",\"Promociones\",\"Orden\"]]";

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
		
		$query.=" WHERE 1=1 AND (gvtipomov='Gaviotas Electronicas' OR gvtipomov='Gaviotas generadas en el Canje' 
						OR gvtipomov='Gaviotas generadas por Devolucion' OR gvtipomov='Gaviotas Reintegradas'  
						OR gvtipomov='Gaviotas generadas por Boletas') ";
				
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
		/*M E*/		$nombreLocal = htmlspecialchars_decode($request->get('nombreLocal'));
		$filtrar=false;

		

		/*$desde=" CURDATE() - INTERVAL 6 MONTH";
		$hasta=" CURDATE()";*/

		$desde = date('Y-m-01', strtotime("-1 year"));
		$hasta  = date('Y-m-t');

		$createdTime = $request->get('createdtime');
		if(!empty($createdTime)) {
			$desde = DateTimeField::__convertToDBFormat($createdTime['start'],'dd-mm-yyyy');
			$hasta = DateTimeField::__convertToDBFormat($createdTime['end'],'dd-mm-yyyy');
		}

		
		$query="SELECT SUM(
		IF(lpadherido=1,1,0)
		) AS _in,
		SUM(
		IF(lpadherido=0,1,0)
		) AS _out
		FROM vtiger_account
		inner join vtiger_crmentity on crmid=accountid
		";
		
		
		$query.=" WHERE 1=1 AND deleted=0 and lpactivo=1 ";
				
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
/*M E*/		if(!empty($nombreLocal) && $nombreLocal!="") {
/*M E*/			$query.=" and accountname LIKE '%".$nombreLocal."'";
/*M E*/		}		

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


		$query="SELECT
		SUM(
		IF(lpadherido=0,TlkRVMontoNeto,0)
		) AS monto_out
		,
		SUM(
		IF(lpadherido=1,TlkRVMontoNeto,0)
		) AS monto_in
		FROM  lp_ventas_rubro lvr
    	INNER JOIN vtiger_account ON acnumerocontrato=lvr.TlkIDContrato
		inner join vtiger_crmentity on crmid=accountid
		";
		
		
		$query.=" WHERE 1=1 AND deleted=0  and lpactivo=1";
				
		if ($desde!=""){
			$query.=" AND TlkFecha >= '".$desde."' ";
		
		}
		if ($hasta!=""){
			$query.=" AND TlkFecha <= '".$hasta."' ";
		
		}

		if(!empty($familia) && $familia!="") {
			$query.=" and lpfamilia LIKE'%".$familia."'";
		}
		if(!empty($rubro) && $rubro!="") {
			$query.=" and lprubro LIKE '%".$rubro."'";
		}
		if(!empty($localizacion) && $localizacion!="") {
			$query.=" and lplocalizacion LIKE '%".$localizacion."'";
		}		
/*M E*/		if(!empty($nombreLocal) && $nombreLocal!="") {
/*M E*/			$query.=" and LocNombre LIKE '%".$nombreLocal."'";
/*M E*/		}	
		if(!empty($adherido) && $adherido!="") {
			if($adherido=="Beneficios"){
				$query.=" and lpadherido=1";	
			}else{
				$query.=" and lpadherido=0";
			}
			
		}
		//echo $query;
		
		$result=$adb->query($query);

		$monto_out=$adb->query_result($result,0,'monto_out');
		$monto_in=$adb->query_result($result,0,'monto_in');
		//$total_monto=$monto_in+$monto_out;

		//$in=round(($in*100)/$total);
		//$out=round(($out*100)/$total);

		$montos=array(array(0=>intval($monto_in)),array(0=>intval($monto_out)));
		$cantidad=array(array(0=>intval($in)),array(0=>intval($out)));
		return array($cantidad,$montos);
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
			$desde = DateTimeField::__convertToDBFormat($createdTime['start'],'dd-mm-yyyy');
			$hasta = DateTimeField::__convertToDBFormat($createdTime['end'],'dd-mm-yyyy');
		}

		


		$vista2 = $request->get('vista2');
		if(empty($vista2)) $vista2="gaviotas"; 
		if($vista2=='gaviotas'){
				$sexo = $request->get('sexo');
				$canal = $request->get('canal');
				$estatuto = $request->get('estatuto');
				$rango =htmlspecialchars_decode($request->get('rango'));
				$programa = $request->get('programa');
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
						$filtros.=" canal_activo LIKE '%".$id."%' OR";
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
				$query="SELECT SUM(
						IF(gvtipomov='Gaviotas dada de baja por Canje' OR gvtipomov='Gaviotas dada de baja por Cheque Obsequio' ,gvcantidad,0)
						) AS consumidas,
						SUM(
						IF(gvvencimiento >= CURDATE() AND (gvtipomov='Gaviotas Electronicas' OR gvtipomov='Gaviotas generadas en el Canje' 
						OR gvtipomov='Gaviotas generadas por Devolucion' OR gvtipomov='Gaviotas Reintegradas'  
						OR gvtipomov='Gaviotas generadas por Boletas')  ,gvcantidad,0)
						) AS vivas2,
						SUM(
						IF((gvtipomov='Gaviotas Electronicas' OR gvtipomov='Gaviotas generadas en el Canje' 
						OR gvtipomov='Gaviotas generadas por Devolucion' OR gvtipomov='Gaviotas Reintegradas'  
						OR gvtipomov='Gaviotas generadas por Boletas') AND gvvencimiento < CURDATE() ,gvcantidad,0)
						) AS vencidas2,
						SUM(
						IF(gvtipomov='Gaviotas anuladas' ,gvcantidad,0)
						) AS anuladas, 
						SUM(IF(gvvencimiento >= CURDATE(),gvcantidad,0)) as cantidad_vivas2,
						(SELECT SUM(vivas) FROM (
						SELECT SUM( IF(gvvencimiento >= CURDATE(),gvcantidad,0) ) AS vivas
						FROM vtiger_gaviotas ";
						if ($filtros!=""){$query.=" INNER JOIN vtiger_contactdetails ON contactid=gvcontacto ";}     		
						$query.=" WHERE 1=1  ";
							if ($hasta!=""){
								$query.=" AND gvfecha >= DATE('".$hasta."'-INTERVAL 2 YEAR)";
							
							}
							if ($hasta!=""){
								$query.=" AND gvfecha <= '".$hasta."' ";
							
							}
						$query.=" AND 
						(gvtipomov='Gaviotas Electronicas' OR gvtipomov='Gaviotas generadas en el Canje' OR gvtipomov='Gaviotas generadas por Devolucion' 
						OR gvtipomov='Gaviotas Reintegradas' OR gvtipomov='Gaviotas generadas por Boletas')
						";
						$query.=$filtros." GROUP BY gvnrogavia
						HAVING COUNT(*)=1 ) AS datos) AS cantidad_vivas,

						(
						SELECT SUM(vencidas) 
						FROM ( SELECT SUM( gvcantidad ) AS vencidas 
							FROM vtiger_gaviotas";
						if ($filtros!=""){$query.=" INNER JOIN vtiger_contactdetails ON contactid=gvcontacto ";} 
						$query.=" WHERE 1=1 ";
						//WHERE 1=1 ";
							//if ($desde!=""){$query.=" AND gvfecha >= '".$desde."' ";}
							//if ($hasta!=""){$query.=" AND gvfecha <= '".$hasta."' ";}
					    $query.=$filtros." and gvvencimiento < '".$hasta."' and gvvencimiento >= '".$desde."'";
							//GROUP BY gvnrogavia HAVING SUM(gvcantidad)>0
						$query.=" ) AS d) as vencidas

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
				$programa = $request->get('programa');
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
						$filtros.=" canal_activo LIKE '%".$id."%' OR";
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
		        //if ($filtros!=""){$query.=" INNER JOIN vtiger_contactdetails ON contactid=gvcontacto ";}     		
				if ($filtros!=""){$query.=" INNER JOIN vtiger_contactdetails ON contactid=gvcontacto ";}     		
		        $query.=" 
		        		where 1=1 AND gvvencimiento >= CURDATE() 
		             	";

					//and gvtipomov in ('Gaviotas Electronicas','Gaviotas generadas en el Canje','Gaviotas generadas por Devolucion','Gaviotas Reintegradas','Gaviotas generadas por Boletas'
		     	//if ($desde!=""){$query.=" AND gvfecha >= '".$desde."'";}
				if ($hasta!=""){$query.=" AND gvfecha <= '".$hasta."' ";}
				if ($filtros!=""){$query.=$filtros;}
		        $query.=" )
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
		             	FROM vtiger_gaviotas
						INNER JOIN vtiger_contactdetails ON contactid=gvcontacto WHERE 1=1 
		             	 ";	
		        if ($filtros!=""){$query.=$filtros;}     	 

				
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

	public function _getAnalisisCampania(Vtiger_Request $request) {
		
		$adb = PearDatabase::getInstance();

		$fecha_desde="";
		$fecha_hasta="";

		$fecha_hasta=date('Y-m-d');
		$date = strtotime( date('Y-m-01')." -11 months");
		$fecha_desde=date("Y-m-d", $date);
		
		
		$createdTime = $request->get('createdtime');
		if(!empty($createdTime)) {
			/*$fecha_desde = Vtiger_Date_UIType::getDBInsertedValue($createdTime['start']);
			$fecha_hasta = Vtiger_Date_UIType::getDBInsertedValue($createdTime['end']);*/
			$fecha_desde = DateTimeField::__convertToDBFormat($createdTime['start'],'dd-mm-yyyy');
			$fecha_hasta = DateTimeField::__convertToDBFormat($createdTime['end'],'dd-mm-yyyy');
		}
		
		$sexo = $request->get('sexo');
		$canal = $request->get('canal');
		$estatuto = $request->get('estatuto');
		$rango =htmlspecialchars_decode($request->get('rango'));
		$programa = $request->get('programa');
		$tipo = $request->get('tipo');
		$filtros_where=" WHERE 1=1 ";
		$filtros="";	
		$filtro_c="";
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
		if(!empty($tipo) && $tipo!="") {
			$tipos=explode(",", $tipo);
			$filtro_c.=" AND (";
			foreach($tipos as $id){
				//$filtros.=" FIND_IN_SET('".$id."',programa)<>0 OR";	
				$filtro_c.=" c.campaigntype LIKE '%".$id."%' OR";
			}	
			$filtro_c=rtrim($filtro_c,'OR');
			$filtro_c.=" )";
		}
		$filtros_locales="";
		$fam = htmlspecialchars_decode($request->get('familia'));
		$rub = htmlspecialchars_decode($request->get('rubro'));
		$loc = htmlspecialchars_decode($request->get('localizacion'));
		$nombreLocal = htmlspecialchars_decode($request->get('nombreLocal'));
		$formaPago = htmlspecialchars_decode($request->get('formapago'));
		$adh = htmlspecialchars_decode($request->get('adherido'));
		if($fam!=""){
			$filtros_locales .= "AND lpfamilia LIKE '%$fam%'";
		}

		
		if($rub!=""){
			$filtros_locales .= "AND lprubro LIKE '%$rub%'";
		}		
		if($loc!=""){
			$filtros_locales .= "AND lplocalizacion='$loc'";
		}
		
		if($loc!=""){
			$filtros_locales .= "AND lplocalizacion='$loc'";
		}
		
		if($adh!=""){
			$filtros_locales .= "AND lpadherido=0";
			if ($adh=="Beneficios") {
				$filtros_locales .= "AND lpadherido=1";
			}
		}
		
		if($nombreLocal!=""){
			$filtros_locales .= "AND accountid=$nombreLocal";
		}
		$join_accounts="";
		if($filtros_locales!=""){
			$join_accounts="INNER join vtiger_account va on va.acnumerocontrato=lvr.TlkIDContrato";
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
				(
					SELECT COUNT(DISTINCT cid) FROM
					(
						SELECT DISTINCT gvcontacto AS cid, gvfecha AS fecha FROM vtiger_gaviotas i 
						UNION
						SELECT distinct contactid AS cid, fecha
        				FROM compras_web 
						UNION
						SELECT DISTINCT bccontacto as cid,  bcfecha fecha 
						FROM vtiger_crmentity cre
						INNER JOIN vtiger_boletascanjeadas bc ON bc.bccontacto = cre.crmid	
						UNION
						SELECT distinct cacontacto AS cid, cafecha AS fecha
						FROM vtiger_canjes
						UNION
						SELECT distinct vacontacto AS cid,vafecha as fecha
						FROM vtiger_vales		

					) d
					inner join vtiger_contactdetails cd on d.cid=cd.contactid
					WHERE fecha>= c.lpcamdesde AND fecha<=c.closingdate
					".$filtros."
				) AS activas,
				(
					SELECT COUNT(DISTINCT cid) FROM
					(
						select contacto as cid, fec_doc as fecha
						from vtiger_articulospromociones
						UNION
						SELECT bccontacto as cid, bcfecha as fecha
						from vtiger_boletascanjeadas
					)d	
					inner join vtiger_contactdetails cd on d.cid=cd.contactid
					WHERE fecha>= c.lpcamdesde AND fecha<=c.closingdate
					".$filtros."
				) as promociones,
				0 AS reclutadas,
				(
					SELECT COUNT(DISTINCT cid) FROM
					(
						SELECT DISTINCT gvcontacto AS cid, gvfecha AS fecha FROM vtiger_gaviotas i 
						UNION
						SELECT distinct contactid AS cid, fecha
        				FROM compras_web 
						UNION
						SELECT DISTINCT bccontacto as cid,  bcfecha fecha 
						FROM vtiger_crmentity cre
						INNER JOIN vtiger_boletascanjeadas bc ON bc.bccontacto = cre.crmid	
						UNION
						SELECT distinct cacontacto AS cid, cafecha AS fecha
						FROM vtiger_canjes
						UNION
						SELECT distinct vacontacto AS cid,vafecha as fecha
						FROM vtiger_vales				
					) d
					inner join vtiger_contactdetails cd on d.cid=cd.contactid
					inner join vtiger_campaigncontrel ccr on ccr.contactid=cd.contactid 
					WHERE fecha>= c.lpcamdesde AND fecha<=c.closingdate and ccr.campaignid=c.campaignid
					".$filtros."
				) AS activas_obj,
				0 AS reclutadas_obj
				
				from vtiger_campaign c inner join vtiger_crmentity e on c.campaignid = e.crmid and e.deleted=0 WHERE 1=1 ".$filtro_c;
		/*(SELECT SUM(TlkRVMontoNeto) FROM (	
					SELECT TlkRVMontoNeto,TlkFecha
					FROM lp_ventas_rubro lvr $join_accounts 
					Where 1=1 $filtros_locales AND TlkFecha >= '".$fecha_desde."' AND TlkFecha <= '".$fecha_hasta."'
					) d
					WHERE d.TlkFecha >= c.lpcamdesde AND d.TlkFecha<=c.closingdate ) as consumo*/
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
						\"Clientes reclutados por día\",\"$ por dia\",\"$ por cliente\",
						\"Clientes activos por día \",
						\"Clientes reclutados por día \",\"$ por dia \",\"$ por cliente \",\"Cantidad de Clientes\",\"Clientes Activos en promociones\"],";
	

	while ($row = $adb->fetch_array($result)){


		/*(SELECT SUM(TlkRVMontoNeto) FROM (	
					SELECT TlkRVMontoNeto,TlkFecha
					FROM lp_ventas_rubro lvr $join_accounts 
					Where 1=1 $filtros_locales AND TlkFecha >= '".$fecha_desde."' AND TlkFecha <= '".$fecha_hasta."'
					) d
					WHERE d.TlkFecha >= c.lpcamdesde AND d.TlkFecha<=c.closingdate ) as consumo
		*/

		$sql="SELECT SUM(TlkRVMontoNeto) AS consumo
				FROM lp_ventas_rubro lvr  
				WHERE  TlkFecha >= '".$row['lpcamdesde']."' AND TlkFecha<='".$row['closingdate']."'";
		$resultado=$adb->pquery($sql,array());
		$row["consumo"]=$adb->query_result($resultado,0,'consumo');		

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
		$promociones=round($row["promociones"]);

		$usddia=0;
		$usdcliente=0;
		$usddiaobj=0;
		$usdclienteobj=0;

		if(isset($row["consumo"]) && $row["consumo"]>0){
			$consumo=$row["consumo"];
			$usddia=round($consumo/$dias);
			if(isset($row["cant_cont"]) && $row["cant_cont"]!=0){
				$usdcliente=round($consumo/$clientes);
				$usdclienteobj=round($consumo/$clientes);
			}
			$usddiaobj=round($consumo/$dias);
			
		}

		$total_enviados=($row['mailsenv']+$row['lpchimpemailssent']);
		$total_abiertos=($row['abiertos']+$row['lpchimpuniqueopens']);

		if($total_enviados==0) $total_enviados=1;
		$por_abiertos=round(($total_abiertos*100)/$total_enviados);

		$json_string.="[\"<a href='index.php?module=Campaigns&view=Detail&record=".$row['campaignid']."'>".$row['campaignname']."</a>\",
						\"".$row['lpcamdesde']."\",
						\"".$row['closingdate']."\",
						\"".$row['sms']."\",
						\"".($row['mailsenv']+$row['lpchimpemailssent'])."\",
						\"".$por_abiertos."\",
						\"".($row['lpchimpsoftbounces']+$row['lpchimphardbounces'])."\",
						\"".$activasdia."\",
						\"".$reclutadasdia."\",
						\"".$usddia."\",
						\"".$usdcliente."\",
						\"".$activasdiaobj."\",
						\"".$reclutadasdiaobj."\",\"".$usddiaobj."\",\"".$usdclienteobj."\",
						\"".$clientes."\",
						\"".$promociones."\"],";
		
	
	}

	$json_string=rtrim($json_string, ",");
	$json_string.="]";
	return $json_string;

	}
	
	public function __getAnalisisCampania(Vtiger_Request $request) {
		
		$adb = PearDatabase::getInstance();

		$fecha_desde="";
		$fecha_hasta="";

		$fecha_hasta=date('Y-m-d');
		$date = strtotime( date('Y-m-01')." -11 months");
		$fecha_desde=date("Y-m-d", $date);
		
		
		$createdTime = $request->get('createdtime');
		if(!empty($createdTime)) {
			/*$fecha_desde = Vtiger_Date_UIType::getDBInsertedValue($createdTime['start']);
			$fecha_hasta = Vtiger_Date_UIType::getDBInsertedValue($createdTime['end']);*/
			$fecha_desde = DateTimeField::__convertToDBFormat($createdTime['start'],'dd-mm-yyyy');
			$fecha_hasta = DateTimeField::__convertToDBFormat($createdTime['end'],'dd-mm-yyyy');
		}
		
		$sexo = $request->get('sexo');
		$canal = $request->get('canal');
		$estatuto = $request->get('estatuto');
		$rango =htmlspecialchars_decode($request->get('rango'));
		$programa = $request->get('programa');
		$tipo = $request->get('tipo');
		$filtros_where=" WHERE 1=1 ";
		$filtros="";	
		$filtro_c="";
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
		if(!empty($tipo) && $tipo!="") {
			$tipos=explode(",", $tipo);
			$filtro_c.=" AND (";
			foreach($tipos as $id){
				//$filtros.=" FIND_IN_SET('".$id."',programa)<>0 OR";	
				$filtro_c.=" c.campaigntype LIKE '%".$id."%' OR";
			}	
			$filtro_c=rtrim($filtro_c,'OR');
			$filtro_c.=" )";
		}
		$filtros_locales="";
		$fam = htmlspecialchars_decode($request->get('familia'));
		$rub = htmlspecialchars_decode($request->get('rubro'));
		$loc = htmlspecialchars_decode($request->get('localizacion'));
		$nombreLocal = htmlspecialchars_decode($request->get('nombreLocal'));
		$formaPago = htmlspecialchars_decode($request->get('formapago'));
		$adh = htmlspecialchars_decode($request->get('adherido'));
		if($fam!=""){
			$filtros_locales .= "AND lpfamilia LIKE '%$fam%'";
		}

		
		if($rub!=""){
			$filtros_locales .= "AND lprubro LIKE '%$rub%'";
		}		
		if($loc!=""){
			$filtros_locales .= "AND lplocalizacion='$loc'";
		}
		
		if($loc!=""){
			$filtros_locales .= "AND lplocalizacion='$loc'";
		}
		
		if($adh!=""){
			$filtros_locales .= "AND lpadherido=0";
			if ($adh=="Beneficios") {
				$filtros_locales .= "AND lpadherido=1";
			}
		}
		
		if($nombreLocal!=""){
			$filtros_locales .= "AND accountid=$nombreLocal";
		}
		$join_accounts="";
		if($filtros_locales!=""){
			$join_accounts="INNER join vtiger_account va on va.acnumerocontrato=lvr.TlkIDContrato";
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
				(SELECT  COUNT(*)
				 FROM  vtiger_campaigncontrel 
				 INNER JOIN vtiger_contactdetails ON vtiger_contactdetails.contactid=vtiger_campaigncontrel.contactid 	
					 WHERE vtiger_campaigncontrel.campaignid=c.campaignid ".$filtros." and status is not null) 
				AS mailsenvmchimp,
				(
					SELECT COUNT(DISTINCT cid) FROM
					(
						SELECT DISTINCT gvcontacto AS cid, gvfecha AS fecha FROM vtiger_gaviotas i 
						UNION
						SELECT distinct contactid AS cid, fecha
        				FROM compras_web 
						UNION
						SELECT DISTINCT bccontacto as cid,  bcfecha fecha 
						FROM vtiger_crmentity cre
						INNER JOIN vtiger_boletascanjeadas bc ON bc.bccontacto = cre.crmid	
						UNION
						SELECT distinct cacontacto AS cid, cafecha AS fecha
						FROM vtiger_canjes
						UNION
						SELECT distinct vacontacto AS cid,vafecha as fecha
						FROM vtiger_vales		

					) d
					inner join vtiger_contactdetails cd on d.cid=cd.contactid
					WHERE fecha>= c.lpcamdesde AND fecha<=c.closingdate
					".$filtros."
				) AS activas,
				(
					SELECT COUNT(DISTINCT cid) FROM
					(
						select contacto as cid, fec_doc as fecha
						from vtiger_articulospromociones
						UNION
						SELECT bccontacto as cid, bcfecha as fecha
						from vtiger_boletascanjeadas
					)d	
					inner join vtiger_contactdetails cd on d.cid=cd.contactid
					WHERE fecha>= c.lpcamdesde AND fecha<=c.closingdate
					".$filtros."
				) as promociones,
				0 AS reclutadas,
				(
					SELECT COUNT(DISTINCT cid) FROM
					(
						SELECT DISTINCT gvcontacto AS cid, gvfecha AS fecha FROM vtiger_gaviotas i 
						UNION
						SELECT distinct contactid AS cid, fecha
        				FROM compras_web 
						UNION
						SELECT DISTINCT bccontacto as cid,  bcfecha fecha 
						FROM vtiger_crmentity cre
						INNER JOIN vtiger_boletascanjeadas bc ON bc.bccontacto = cre.crmid	
						UNION
						SELECT distinct cacontacto AS cid, cafecha AS fecha
						FROM vtiger_canjes
						UNION
						SELECT distinct vacontacto AS cid,vafecha as fecha
						FROM vtiger_vales				
					) d
					inner join vtiger_contactdetails cd on d.cid=cd.contactid
					inner join vtiger_campaigncontrel ccr on ccr.contactid=cd.contactid 
					WHERE fecha>= c.lpcamdesde AND fecha<=c.closingdate and ccr.campaignid=c.campaignid
					".$filtros."
				) AS activas_obj,
				0 AS reclutadas_obj
				
				from vtiger_campaign c inner join vtiger_crmentity e on c.campaignid = e.crmid and e.deleted=0 WHERE 1=1 ".$filtro_c;
		/*(SELECT SUM(TlkRVMontoNeto) FROM (	
					SELECT TlkRVMontoNeto,TlkFecha
					FROM lp_ventas_rubro lvr $join_accounts 
					Where 1=1 $filtros_locales AND TlkFecha >= '".$fecha_desde."' AND TlkFecha <= '".$fecha_hasta."'
					) d
					WHERE d.TlkFecha >= c.lpcamdesde AND d.TlkFecha<=c.closingdate ) as consumo*/
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
						\"Clientes reclutados por día\",\"$ por dia\",\"$ por cliente\",
						\"Clientes activos por día \",
						\"Clientes reclutados por día \",\"$ por dia \",\"$ por cliente \",\"Cantidad de Clientes\",\"Clientes Activos en promociones\"],";
	

	while ($row = $adb->fetch_array($result)){


		/*(SELECT SUM(TlkRVMontoNeto) FROM (	
					SELECT TlkRVMontoNeto,TlkFecha
					FROM lp_ventas_rubro lvr $join_accounts 
					Where 1=1 $filtros_locales AND TlkFecha >= '".$fecha_desde."' AND TlkFecha <= '".$fecha_hasta."'
					) d
					WHERE d.TlkFecha >= c.lpcamdesde AND d.TlkFecha<=c.closingdate ) as consumo
		*/

		$sql="SELECT SUM(TlkRVMontoNeto) AS consumo
				FROM lp_ventas_rubro lvr  
				WHERE  TlkFecha >= '".$row['lpcamdesde']."' AND TlkFecha<='".$row['closingdate']."'";
		$resultado=$adb->pquery($sql,array());
		$row["consumo"]=$adb->query_result($resultado,0,'consumo');		

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
		$promociones=round($row["promociones"]);

		$usddia=0;
		$usdcliente=0;
		$usddiaobj=0;
		$usdclienteobj=0;

		if(isset($row["consumo"]) && $row["consumo"]>0){
			$consumo=$row["consumo"];
			$usddia=round($consumo/$dias);
			if(isset($row["cant_cont"]) && $row["cant_cont"]!=0){
				$usdcliente=round($consumo/$clientes);
				$usdclienteobj=round($consumo/$clientes);
			}
			$usddiaobj=round($consumo/$dias);
			
		}

		//mailsenvmchimp
		//$total_enviados=($row['mailsenv']+$row['lpchimpemailssent']);
		$total_enviados=($row['mailsenv']+$row['mailsenvmchimp']);
		$total_abiertos=($row['abiertos']+$row['lpchimpuniqueopens']);

		if($total_enviados==0) $total_enviados=1;
		$por_abiertos=round(($total_abiertos*100)/$total_enviados);

		$json_string.="[\"<a href='index.php?module=Campaigns&view=Detail&record=".$row['campaignid']."'>".$row['campaignname']."</a>\",
						\"".$row['lpcamdesde']."\",
						\"".$row['closingdate']."\",
						\"".$row['sms']."\",
						\"".($row['mailsenv']+$row['mailsenvmchimp'])."\",
						\"".$por_abiertos."\",
						\"".($row['lpchimpsoftbounces']+$row['lpchimphardbounces'])."\",
						\"".$activasdia."\",
						\"".$reclutadasdia."\",
						\"".$usddia."\",
						\"".$usdcliente."\",
						\"".$activasdiaobj."\",
						\"".$reclutadasdiaobj."\",\"".$usddiaobj."\",\"".$usdclienteobj."\",
						\"".$clientes."\",
						\"".$promociones."\"],";
		
	
	}

	$json_string=rtrim($json_string, ",");
	$json_string.="]";
	return $json_string;

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
			/*$fecha_desde = Vtiger_Date_UIType::getDBInsertedValue($createdTime['start']);
			$fecha_hasta = Vtiger_Date_UIType::getDBInsertedValue($createdTime['end']);*/
			$fecha_desde = DateTimeField::__convertToDBFormat($createdTime['start'],'dd-mm-yyyy');
			$fecha_hasta = DateTimeField::__convertToDBFormat($createdTime['end'],'dd-mm-yyyy');
		}
		
		$sexo = $request->get('sexo');
		$canal = $request->get('canal');
		$estatuto = $request->get('estatuto');
		$rango =htmlspecialchars_decode($request->get('rango'));
		$programa = $request->get('programa');
		$tipo = $request->get('tipo');
		$nodum = $request->get('nodum');
		$filtros_where=" WHERE 1=1 ";
		$filtros="";	
		$filtro_c="";
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
		if(!empty($tipo) && $tipo!="") {
			$tipos=explode(",", $tipo);
			$filtro_c.=" AND (";
			foreach($tipos as $id){
				//$filtros.=" FIND_IN_SET('".$id."',programa)<>0 OR";	
				$filtro_c.=" c.campaigntype LIKE '%".$id."%' OR";
			}	
			$filtro_c=rtrim($filtro_c,'OR');
			$filtro_c.=" )";
		}
		if(!empty($nodum) && $nodum!="") {
			$tipos=explode(",", $nodum);
			$filtro_c.=" AND (";
			foreach($tipos as $id){
				//$filtros.=" FIND_IN_SET('".$id."',programa)<>0 OR";	
				$filtro_c.=" c.esnodum =".$id." OR";
			}	
			$filtro_c=rtrim($filtro_c,'OR');
			$filtro_c.=" )";
		}
		$filtros_locales="";
		$fam = htmlspecialchars_decode($request->get('familia'));
		$rub = htmlspecialchars_decode($request->get('rubro'));
		$loc = htmlspecialchars_decode($request->get('localizacion'));
		$nombreLocal = htmlspecialchars_decode($request->get('nombreLocal'));
		$formaPago = htmlspecialchars_decode($request->get('formapago'));
		$adh = htmlspecialchars_decode($request->get('adherido'));
		if($fam!=""){
			$filtros_locales .= "AND lpfamilia LIKE '%$fam%'";
		}

		
		if($rub!=""){
			$filtros_locales .= "AND lprubro LIKE '%$rub%'";
		}		
		if($loc!=""){
			$filtros_locales .= "AND lplocalizacion='$loc'";
		}
		
		if($loc!=""){
			$filtros_locales .= "AND lplocalizacion='$loc'";
		}
		
		if($adh!=""){
			$filtros_locales .= "AND lpadherido=0";
			if ($adh=="Beneficios") {
				$filtros_locales .= "AND lpadherido=1";
			}
		}
		
		if($nombreLocal!=""){
			$filtros_locales .= "AND accountid=$nombreLocal";
		}
		$join_accounts="";
		if($filtros_locales!=""){
			$join_accounts="INNER join vtiger_account va on va.acnumerocontrato=lvr.TlkIDContrato";
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
				(SELECT  COUNT(*)
				 FROM  vtiger_campaigncontrel 
				 INNER JOIN vtiger_contactdetails ON vtiger_contactdetails.contactid=vtiger_campaigncontrel.contactid 	
					 WHERE vtiger_campaigncontrel.campaignid=c.campaignid ".$filtros." and status is not null) 
				AS mailsenvmchimp,
				(SELECT  COUNT(*)
				 FROM  vtiger_campaigncontrel 
				 INNER JOIN vtiger_contactdetails ON vtiger_contactdetails.contactid=vtiger_campaigncontrel.contactid 	
					 WHERE vtiger_campaigncontrel.campaignid=c.campaignid ".$filtros." and open_count is not null and open_count>0) 
				AS mailsopenmchimp,
				(
					SELECT COUNT(DISTINCT cid) FROM
					(
						SELECT DISTINCT gvcontacto AS cid, gvfecha AS fecha FROM vtiger_gaviotas i 
						UNION
						SELECT distinct contactid AS cid, fecha
        				FROM compras_web 
						UNION
						SELECT DISTINCT bccontacto as cid,  bcfecha fecha 
						FROM vtiger_crmentity cre
						INNER JOIN vtiger_boletascanjeadas bc ON bc.bccontacto = cre.crmid	
						UNION
						SELECT distinct cacontacto AS cid, cafecha AS fecha
						FROM vtiger_canjes
						UNION
						SELECT distinct vacontacto AS cid,vafecha as fecha
						FROM vtiger_vales		

					) d
					inner join vtiger_contactdetails cd on d.cid=cd.contactid
					WHERE fecha>= c.lpcamdesde AND fecha<=c.closingdate
					".$filtros."
				) AS activas,
				(
					SELECT COUNT(DISTINCT cid) FROM
					(
						select contacto as cid, fec_doc as fecha
						from vtiger_articulospromociones
						UNION
						SELECT bccontacto as cid, bcfecha as fecha
						from vtiger_boletascanjeadas
					)d	
					inner join vtiger_contactdetails cd on d.cid=cd.contactid
					WHERE fecha>= c.lpcamdesde AND fecha<=c.closingdate
					".$filtros."
				) as promociones,
				0 AS reclutadas,
				(
					SELECT COUNT(DISTINCT cid) FROM
					(
						SELECT DISTINCT gvcontacto AS cid, gvfecha AS fecha FROM vtiger_gaviotas i 
						UNION
						SELECT distinct contactid AS cid, fecha
        				FROM compras_web 
						UNION
						SELECT DISTINCT bccontacto as cid,  bcfecha fecha 
						FROM vtiger_crmentity cre
						INNER JOIN vtiger_boletascanjeadas bc ON bc.bccontacto = cre.crmid	
						UNION
						SELECT distinct cacontacto AS cid, cafecha AS fecha
						FROM vtiger_canjes
						UNION
						SELECT distinct vacontacto AS cid,vafecha as fecha
						FROM vtiger_vales				
					) d
					inner join vtiger_contactdetails cd on d.cid=cd.contactid
					inner join vtiger_campaigncontrel ccr on ccr.contactid=cd.contactid 
					WHERE fecha>= c.lpcamdesde AND fecha<=c.closingdate and ccr.campaignid=c.campaignid
					".$filtros."
				) AS activas_obj,
				0 AS reclutadas_obj
				
				from vtiger_campaign c inner join vtiger_crmentity e on c.campaignid = e.crmid and e.deleted=0 WHERE 1=1 ".$filtro_c;
		/*(SELECT SUM(TlkRVMontoNeto) FROM (	
					SELECT TlkRVMontoNeto,TlkFecha
					FROM lp_ventas_rubro lvr $join_accounts 
					Where 1=1 $filtros_locales AND TlkFecha >= '".$fecha_desde."' AND TlkFecha <= '".$fecha_hasta."'
					) d
					WHERE d.TlkFecha >= c.lpcamdesde AND d.TlkFecha<=c.closingdate ) as consumo*/
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
						\"Clientes reclutados por día\",\"$ por dia\",\"$ por cliente\",
						\"Clientes activos por día \",
						\"Clientes reclutados por día \",\"$ por dia \",\"$ por cliente \",\"Cantidad de Clientes\",\"Clientes Activos en promociones\"],";
	

	while ($row = $adb->fetch_array($result)){


		/*(SELECT SUM(TlkRVMontoNeto) FROM (	
					SELECT TlkRVMontoNeto,TlkFecha
					FROM lp_ventas_rubro lvr $join_accounts 
					Where 1=1 $filtros_locales AND TlkFecha >= '".$fecha_desde."' AND TlkFecha <= '".$fecha_hasta."'
					) d
					WHERE d.TlkFecha >= c.lpcamdesde AND d.TlkFecha<=c.closingdate ) as consumo
		*/

		$sql="SELECT SUM(TlkRVMontoNeto) AS consumo
				FROM lp_ventas_rubro lvr  
				WHERE  TlkFecha >= '".$row['lpcamdesde']."' AND TlkFecha<='".$row['closingdate']."'";
		$resultado=$adb->pquery($sql,array());
		$row["consumo"]=$adb->query_result($resultado,0,'consumo');		

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
		$promociones=round($row["promociones"]);

		$usddia=0;
		$usdcliente=0;
		$usddiaobj=0;
		$usdclienteobj=0;

		if(isset($row["consumo"]) && $row["consumo"]>0){
			$consumo=$row["consumo"];
			$usddia=round($consumo/$dias);
			if(isset($row["cant_cont"]) && $row["cant_cont"]!=0){
				$usdcliente=round($consumo/$clientes);
				$usdclienteobj=round($consumo/$clientes);
			}
			$usddiaobj=round($consumo/$dias);
			
		}

		//mailsenvmchimp
		//$total_enviados=($row['mailsenv']+$row['lpchimpemailssent']);
		$total_enviados=($row['mailsenv']+$row['mailsenvmchimp']);
		$total_abiertos=($row['abiertos']+$row['mailsopenmchimp']);

		if($total_enviados==0) $total_enviados=1;
		$por_abiertos=round(($total_abiertos*100)/$total_enviados);

		$json_string.="[\"<a href='index.php?module=Campaigns&view=Detail&record=".$row['campaignid']."'>".$row['campaignname']."</a>\",
						\"".$row['lpcamdesde']."\",
						\"".$row['closingdate']."\",
						\"".$row['sms']."\",
						\"".($row['mailsenv']+$row['mailsenvmchimp'])."\",
						\"".$por_abiertos."\",
						\"".($row['lpchimpsoftbounces']+$row['lpchimphardbounces'])."\",
						\"".$activasdia."\",
						\"".$reclutadasdia."\",
						\"".$usddia."\",
						\"".$usdcliente."\",
						\"".$activasdiaobj."\",
						\"".$reclutadasdiaobj."\",\"".$usddiaobj."\",\"".$usdclienteobj."\",
						\"".$clientes."\",
						\"".$promociones."\"],";
		
	
	}

	$json_string=rtrim($json_string, ",");
	$json_string.="]";
	return $json_string;

	}
	public function _getRankingClientes(Vtiger_Request $request) {
		
		$adb = PearDatabase::getInstance();

		

                $query="

		 		SELECT firstname,lastname,email,phone ,birthday, cnsaldoactual as actuales, cnsaldoanterior as anteriores, cndiferencia as evolucion,
		 		mailingstreet,mailingzip,mailingcity
                FROM  vtiger_contactdetails 
                INNER JOIN vtiger_contactsubdetails ON contactid=contactsubscriptionid
                LEFT JOIN vtiger_contactaddress ON vtiger_contactaddress.contactaddressid=contactid
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
        $aColumns = array( 'cndocumento','firstname','lastname','email','phone' ,'birthday', 'cnsaldoactual', 'cnsaldoanterior', 'cndiferencia','mailingstreet','mailingzip','mailingcity', 'contactid');


        /* Indexed column (used for fast and accurate table cardinality) */
        $sIndexColumn = "contactid";
         
        /* DB table to use */
        $sTable = "vtiger_contactdetails 
        INNER JOIN vtiger_contactsubdetails ON contactid=contactsubscriptionid LEFT JOIN vtiger_contactaddress ON vtiger_contactaddress.contactaddressid=contactid";
		

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
                $sOrder = " ORDER BY (cnsaldoactual) DESC";
            }
        }else{
        	$sOrder = " ORDER BY (cnsaldoactual) DESC";
        }
       
        



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
				$cambiar = 1;

			}else if($vistaSel == 'B'){
				// si la vista es boletas
				$auxActual = "ifnull((select Sum(bcprecio) FROM vtiger_boletascanjeadas WHERE `bccontacto` = `contactid` AND bcfecha BETWEEN '".$desdeActual." 00:00:00' AND '".$hastaActual." 23:59:59'),0) as 'cnsaldoactual'";
				$auxAnterior = "ifnull((select Sum(bcprecio) FROM vtiger_boletascanjeadas WHERE `bccontacto` = `contactid` AND bcfecha BETWEEN '".$desdeAnterior." 00:00:00' AND '".$hastaAnterior." 23:59:59'),0) as 'cnsaldoanterior'";
				$auxSaldoActual="(select Sum(bcprecio) FROM vtiger_boletascanjeadas WHERE `bccontacto` = `contactid` AND bcfecha BETWEEN '".$desdeActual." 00:00:00' AND '".$hastaActual." 23:59:59')";
				$auxSaldoAnterior="(select Sum(bcprecio) FROM vtiger_boletascanjeadas WHERE `bccontacto` = `contactid` AND bcfecha BETWEEN '".$desdeAnterior." 00:00:00' AND '".$hastaAnterior." 23:59:59')";
				$auxDiferencia="ifnull(ifnull($auxSaldoActual,0)-ifnull($auxSaldoAnterior,0),0) as 'cndiferencia'";			
				$cambiar = 1;
			} else if($vistaSel == 'S') {
				// si la vista es scotia
				$auxActual = "ifnull((select Sum(importe) FROM vtiger_comprasscotia WHERE `contacto` = `contactid` AND fecha BETWEEN '".$desdeActual." 00:00:00' AND '".$hastaActual." 23:59:59'),0) as 'cnsaldoactual'";
				$auxAnterior = "ifnull((select Sum(importe) FROM vtiger_comprasscotia WHERE `contacto` = `contactid` AND fecha BETWEEN '".$desdeAnterior." 00:00:00' AND '".$hastaAnterior." 23:59:59'),0) as 'cnsaldoanterior'";
				$auxSaldoActual="(select Sum(importe) FROM vtiger_comprasscotia WHERE `contacto` = `contactid` AND fecha BETWEEN '".$desdeActual." 00:00:00' AND '".$hastaActual." 23:59:59')";
				$auxSaldoAnterior="(select Sum(importe) FROM vtiger_comprasscotia WHERE `contacto` = `contactid` AND fecha BETWEEN '".$desdeAnterior." 00:00:00' AND '".$hastaAnterior." 23:59:59')";
				$auxDiferencia="ifnull(ifnull($auxSaldoActual,0)-ifnull($auxSaldoAnterior,0),0) as 'cndiferencia'";			
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

		/*
         * SQL queries
        * Get data to display
        */
       //SQL_CALC_FOUND_ROWS

			$sSelect = str_replace(" , ", " ", implode("`, `", $aColumns));		
				if($cambiar == 1){
					$aux = str_replace(" , ", " ", implode("`, `", $aColumns));
					$b = str_replace('`cnsaldoactual`',$auxActual,$aux);
					$c = str_replace('`cnsaldoanterior`',$auxAnterior,$b);
					$d = str_replace('`cndiferencia`',$auxDiferencia,$c);
					$sSelect = $d;
				}

         /*$sQuery = "
    	SELECT  `".str_replace(" , ", " ", implode("`, `", $aColumns))."`
            FROM   $sTable
            $sWhere
            $sOrder
            $sLimit
            ";*/
            $sQuery = "
    	SELECT  `".$sSelect."`
            FROM   $sTable
            $sWhere
            $sOrder
            $sLimit
            ";

            $queryAddToCampaign = "".
	        "SELECT  `".$sSelect."`
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
                "aaData" => array(),
                "consulta" => $sSelect,
                "auxActual" => $auxActual,
                "vistaSel" => $vistaSel
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
	public function oldgetRankingClientes(Vtiger_Request $request) {

		 /* Array of database columns which should be read and sent back to DataTables. Use a space where
         * you want to insert a non-database field (for example a counter or static image)
        */
        $aColumns = array( 'contactid','firstname','lastname','email','phone' ,'birthday', 'cnsaldoactual', 'cnsaldoanterior', 'cndiferencia','mailingstreet','mailingzip','mailingcity', 'contactid');        
         
        /* Indexed column (used for fast and accurate table cardinality) */
        $sIndexColumn = "contactid";
         
        /* DB table to use */
        $sTable = "vtiger_contactdetails 
        INNER JOIN vtiger_contactsubdetails ON contactid=contactsubscriptionid LEFT JOIN vtiger_contactaddress ON vtiger_contactaddress.contactaddressid=contactid";
		

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
                $sOrder = " ORDER BY (cnsaldoactual) DESC";
            }
        }else{
        	$sOrder = " ORDER BY (cnsaldoactual) DESC";
        }
       
        



        $sWhere = "WHERE 1=1 ";

        $sWhere .= " AND (cnsaldoactual IS NOT NULL AND cnsaldoanterior IS NOT NULL)";

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
		//nuevo
		$desde = $request->get('desde');
		$hasta = $request->get('hasta');		
		$cambiar = 0;
		if(!empty($desde) && !empty($hasta)) {
			$desdeActual = DateTimeField::__convertToDBFormat($desde,'dd-mm-yyyy');
			$hastaActual = DateTimeField::__convertToDBFormat($hasta,'dd-mm-yyyy');
			//el mismo periodo 1 año antes
			$desdeAnterior = DateTimeField::__convertToDBFormat(date("d-m-Y",strtotime($desde.'-1 year')),'dd-mm-yyyy');
			$hastaAnterior = DateTimeField::__convertToDBFormat(date("d-m-Y",strtotime($hasta.'-1 year')),'dd-mm-yyyy');

			$auxActual = "ifnull((select Sum(gvcantidad) FROM vtiger_gaviotas WHERE `gvcontacto` = `contactid` AND gvfecha BETWEEN '".$desdeActual." 00:00:00' AND '".$hastaActual." 23:59:59' AND ( gvtipomov = 'Gaviotas generadas en el Canje' OR gvtipomov = 'Gaviotas generadas por Devolucion' OR gvtipomov = 'Gaviotas generadas por Boletas')),0) as 'cnsaldoactual'";
			$auxAnterior = "ifnull((select Sum(gvcantidad) FROM vtiger_gaviotas WHERE `gvcontacto` = `contactid` AND gvfecha BETWEEN '".$desdeAnterior." 00:00:00' AND '".$hastaAnterior." 23:59:59' AND ( gvtipomov = 'Gaviotas generadas en el Canje' OR gvtipomov = 'Gaviotas generadas por Devolucion' OR gvtipomov = 'Gaviotas generadas por Boletas')),0) as 'cnsaldoanterior'";
			$auxSaldoActual="(select Sum(gvcantidad) FROM vtiger_gaviotas WHERE `gvcontacto` = `contactid` AND gvfecha BETWEEN '".$desdeActual." 00:00:00' AND '".$hastaActual." 23:59:59' AND ( gvtipomov = 'Gaviotas generadas en el Canje' OR gvtipomov = 'Gaviotas generadas por Devolucion' OR gvtipomov = 'Gaviotas generadas por Boletas'))";
			$auxSaldoAnterior="(select Sum(gvcantidad) FROM vtiger_gaviotas WHERE `gvcontacto` = `contactid` AND gvfecha BETWEEN '".$desdeAnterior." 00:00:00' AND '".$hastaAnterior." 23:59:59' AND ( gvtipomov = 'Gaviotas generadas en el Canje' OR gvtipomov = 'Gaviotas generadas por Devolucion' OR gvtipomov = 'Gaviotas generadas por Boletas'))";
			$auxDiferencia="ifnull(ifnull($auxSaldoActual,0)-ifnull($auxSaldoAnterior,0),0) as 'cndiferencia'";			
			$cambiar = 1;
		}        
		
		/*
         * SQL queries
        * Get data to display
        */
       //SQL_CALC_FOUND_ROWS
		$sSelect = str_replace(" , ", " ", implode("`, `", $aColumns));		
		if($cambiar == 1){
			$aux = str_replace(" , ", " ", implode("`, `", $aColumns));
			$b = str_replace('`cnsaldoactual`',$auxActual,$aux);
			$c = str_replace('`cnsaldoanterior`',$auxAnterior,$b);
			$d = str_replace('`cndiferencia`',$auxDiferencia,$c);
			$sSelect = $d;
		}
		//nuevo
         /*$sQuery = "
    	SELECT  `".str_replace(" , ", " ", implode("`, `", $aColumns))."`
            FROM   $sTable
            $sWhere
            $sOrder
            $sLimit
            ";*/
            $sQuery = "
    	SELECT  `".$sSelect."`
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
                else if ( $aColumns[$i] == 'cnsaldoactual' || $aColumns[$i] =='cnsaldoanterior' || $aColumns[$i] == 'cndiferencia' ){
                	$row[] = number_format($aRow[ $aColumns[$i] ],',','.');
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
			/*$fecha_desde = Vtiger_Date_UIType::getDBInsertedValue($createdTime['start']);
			$fecha_hasta = Vtiger_Date_UIType::getDBInsertedValue($createdTime['end']);*/
			$fecha_desde = DateTimeField::__convertToDBFormat($createdTime['start'],'dd-mm-yyyy');
			$fecha_hasta = DateTimeField::__convertToDBFormat($createdTime['end'],'dd-mm-yyyy');
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
		$mostrar_web=true;
		$mostrar_quejas=true;
		$mostrar_sorteos=true;
		$mostrar_ingresos=true;
		$mostrar_promociones = true; //Leo
		if(isset($fuente) && $fuente!=""){
			$mostrar_canjes=$mostrar_vales=$mostrar_gaviotas=$mostrar_web=false;
			$mostrar_promociones=$mostrar_quejas=$mostrar_sorteos=$mostrar_ingresos=false;
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
					case 'web':
						$mostrar_web=true;
						break;
					case 'quejas':
						$mostrar_quejas=true;
						break;
					case 'sorteos':
							$mostrar_sorteos=true;
							break;
					case 'ingresos':
							$mostrar_ingresos=true;
							break;
					case 'promociones':
							$mostrar_promociones=true;
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
					SUM(IF(MONTH(createdtime)=mes AND YEAR(e.createdtime)=anio AND web=1 ,1,0)) AS nuevos_web, 
					SUM(IF(MONTH(createdtime)=mes AND YEAR(e.createdtime)=anio ,0,1)) AS viejos
					FROM (";
		if($mostrar_canjes){				
			$query.="SELECT distinct YEAR(cafecha) AS anio, MONTH(cafecha) AS mes ,cacontacto AS contacto , 0 AS web
						FROM vtiger_canjes";
			

			$query.=" WHERE cafecha  >=  '".$fecha_desde."' AND cafecha <= '".$fecha_hasta."'";
			
			$query.=" UNION ";
		}	
		if($mostrar_gaviotas){
				$query.="
				SELECT distinct YEAR(gvfecha) AS anio,MONTH(gvfecha) AS mes , gvcontacto AS contacto , 0 AS web

        		FROM vtiger_gaviotas";
				
				$query.=" WHERE gvfecha  >=  '".$fecha_desde."' AND gvfecha <= '".$fecha_hasta."'";
				
				$query.=" UNION ";
		}		
		if($mostrar_vales){
				$query.=" 
				SELECT distinct YEAR(vafecha) AS anio,MONTH(vafecha) AS mes ,vacontacto AS contacto, 0 AS web

				FROM vtiger_vales";
				
				$query.=" WHERE vafecha  >=  '".$fecha_desde."' AND vafecha <= '".$fecha_hasta."'";
				$query.=" UNION ";

		}		
		if($mostrar_web){
				/*$query.=" 
				SELECT distinct YEAR(fecultcompraweb) AS anio,MONTH(fecultcompraweb) AS mes ,
				contactid AS contacto, COUNT(contactid) AS web

				FROM vtiger_contactdetails c
				WHERE c.canal_activo LIKE '%ESHOP%'";
				
				$query.=" AND fecultcompraweb  >=  '".$fecha_desde."' 
				AND fecultcompraweb <= '".$fecha_hasta."' GROUP BY contactid";*/
				
				
				$query.="
				SELECT distinct YEAR(fecha) AS anio,MONTH(fecha) AS mes , contactid AS contacto , 1 AS web

        		FROM compras_web";
				
				$query.=" WHERE fecha  >=  '".$fecha_desde."' AND fecha <= '".$fecha_hasta."'";
				
				$query.=" UNION ";

		}
		if($mostrar_quejas){
				$query.=" 
				SELECT distinct YEAR(createdtime) AS anio,MONTH(createdtime) AS mes ,
				contact_id AS contacto, 0 as web

				FROM vtiger_crmentity cr
				INNER JOIN vtiger_troubletickets c ON c.ticketid=cr.crmid
				WHERE contact_id IS NOT NULL";
				
				$query.=" AND createdtime  >=  '".$fecha_desde."' 
				AND createdtime <= '".$fecha_hasta."' GROUP BY contact_id";
				$query.=" UNION ";
				

		}	
		if($mostrar_sorteos){
				$query.=" 
				SELECT distinct YEAR(cr.createdtime) AS anio,MONTH(cr.createdtime) AS mes ,
				socontacto AS contacto, 0 as  web

				FROM vtiger_crmentity cr
				INNER JOIN vtiger_sorteos c ON c.socontacto=cr.crmid
				WHERE 1=1";
				
				$query.=" AND cr.createdtime  >=  '".$fecha_desde."' 
				AND cr.createdtime <= '".$fecha_hasta."' GROUP BY socontacto";
				$query.=" UNION ";
				

		}	
		if($mostrar_ingresos){
				$query.=" 
				SELECT distinct YEAR(createdtime) AS anio,MONTH(createdtime) AS mes ,
				contactid AS contacto, 0 as web

				FROM vtiger_crmentity cr
				INNER JOIN vtiger_contactdetails c ON c.contactid=cr.crmid
				WHERE programa IS NOT NULL AND programa !=''";
				
				$query.=" AND createdtime  >=  '".$fecha_desde."' 
				AND createdtime <= '".$fecha_hasta."' GROUP BY contactid";
				$query.=" UNION ";
				

		}	
		//Leo
		if($mostrar_promociones){
				$query.="
				SELECT DISTINCT YEAR( bcfecha ) AS anio, MONTH( bcfecha ) AS mes, 
				bccontacto AS contacto, 0 AS web
				
				FROM vtiger_crmentity cr
				INNER JOIN vtiger_boletascanjeadas c ON c.bccontacto = cr.crmid
				";

				/*select contacto as cid, fec_doc as fecha
						from vtiger_articulospromociones
						UNION
						SELECT bccontacto as cid, bcfecha as fecha
						from vtiger_boletascanjeadas*/
				
				$query.=" WHERE bcfecha  >=  '".$fecha_desde."' AND bcfecha <= '".$fecha_hasta."' GROUP BY bccontacto";
				
				$query.=" UNION ";
				$query.="
				SELECT DISTINCT YEAR( fec_doc ) AS anio, MONTH( fec_doc ) AS mes, 
				contacto, 0 AS web
				
				FROM vtiger_crmentity cr
				INNER JOIN vtiger_articulospromociones c ON c.contacto = cr.crmid
				";
				$query.=" WHERE fec_doc  >=  '".$fecha_desde."' AND fec_doc <= '".$fecha_hasta."' GROUP BY contacto";
				

				$query.=" UNION ";
		}			//Leo
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
		$fp = fopen("myarchivo.txt","a");
		fwrite($fp,$query.PHP_EOL);
		fclose($fp);
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
				/*$ar_compraron[$indice] = array($nombremes, (double)($row['viejos']+$row['nuevos_web']));*/
				$ar_compraron[$indice] = array($nombremes, (double)($row['total']));
		    
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
