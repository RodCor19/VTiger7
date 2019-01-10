<?php 

error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set("display_errors", 1);
class Analisis_VentasPorMes_View extends Vtiger_Index_View {

	public function preProcess(Vtiger_Request $request, $display = true) {
		$viewer = $this->getViewer($request);
		$viewer->assign('MODULE_NAME', $request->getModule());

		parent::preProcess($request, false);

		if($display) {
			$this->preProcessDisplay($request);
		}

		$currentUser = Users_Record_Model::getCurrentUserModel();
		$viewer = $this->getViewer($request);
		$viewer->assign('CURRENT_USER', $currentUserModel);

		//$respuesta=$this->getData($request);
		
		$moduleModel = Vtiger_Module_Model::getInstance("Analisis");

		//FILTROS
		$adb = PearDatabase::getInstance();
		//Familias
		$familias = array();
		$sql="SELECT DISTINCT lpfamilia FROM vtiger_lpfamilia order by lpfamilia asc";
		$result=$adb->query($sql);
		$no_of_rows=$adb->num_rows($result);
		if($no_of_rows!=0){
			while($row = $adb->fetch_array($result)){
			    $familias[] = $row['lpfamilia'];
			}
		}
		$viewer->assign('familias', $familias);

		//Locales
		$locales = array();
		$sql="SELECT DISTINCT accountname as nombre, accountid as id 
				  FROM vtiger_account inner join vtiger_crmentity on crmid=accountid 
				  INNER JOIN lp_ventas_rubro ON acnumerocontrato=TlkIDContrato
					where lpactivo=1 and deleted=0 order by accountname asc";
		$result=$adb->query($sql);
		$no_of_rows=$adb->num_rows($result);
		if($no_of_rows!=0){
			while($row = $adb->fetch_array($result)){
			    $locales[] = array($row['nombre'], $row["id"]);
			}
		}
		$viewer->assign('locales', $locales);

		//rubros
		$rubros = array();
		$sql="SELECT DISTINCT lprubro FROM vtiger_lprubro order by lprubro asc";
		$result=$adb->query($sql);
		$no_of_rows=$adb->num_rows($result);
		if($no_of_rows!=0){
			while($row = $adb->fetch_array($result)){
			    $rubros[] = $row['lprubro'];
			}
		}
		$viewer->assign('rubros', $rubros);
		
		//localizacion
		$localizacion = array();
		$sql="SELECT DISTINCT lplocalizacion FROM vtiger_lplocalizacion order by lplocalizacion asc";
		$result=$adb->query($sql);
		$no_of_rows=$adb->num_rows($result);
		if($no_of_rows!=0){
			while($row = $adb->fetch_array($result)){
			    $localizacion[] = $row['lplocalizacion'];
			}
		}
		$viewer->assign('localizacion', $localizacion);
		//adheridos
		$adheridos = array('Beneficios','No Beneficios');
		$viewer->assign('adheridos', $adheridos);
		
	}

	protected function preProcessTplName(Vtiger_Request $request) {
		return 'VentasPorMesViewPreProcess.tpl';
	}

	public function getHeaderScripts(Vtiger_Request $request) {
		$headerScriptInstances = parent::getHeaderScripts($request);
		$moduleName = $request->getModule();

		$jsFileNames = array(
			'~/libraries/jquery/gridster/jquery.gridster.min.js',
			'~/libraries/jquery/jqplot/jquery.jqplot.min.js',
			'~/libraries/jquery/jqplot/jquery.jqplot.min.js',
			'~/libraries/jquery/jqplot/plugins/jqplot.canvasTextRenderer.min.js',
			'~/libraries/jquery/jqplot/plugins/jqplot.canvasAxisTickRenderer.min.js',
			'~/libraries/jquery/jqplot/plugins/jqplot.categoryAxisRenderer.min.js',
			'~/libraries/jquery/jqplot/plugins/jqplot.pointLabels.min.js',
			'~/libraries/jquery/jqplot/plugins/jqplot.canvasAxisLabelRenderer.min.js',
			'~/libraries/jquery/jqplot/plugins/jqplot.canvasTextRenderer.min.js',
			'~/libraries/jquery/jqplot/plugins/jqplot.highlighter.min.js',
			'~/libraries/jquery/jqplot/plugins/jqplot.enhancedLegendRenderer.min.js',
			
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
		$viewer = $this->getViewer($request);
		$currentUserModel = Users_Record_Model::getCurrentUserModel();
		$viewer->assign('CURRENT_USER', $currentUserModel);

		$viewer->view('VentasPorMesView.tpl', $request->getModule());
	}


}


 ?>