<?php 


class Analisis_Filtros_View extends Vtiger_Index_View {


	public function process(Vtiger_Request $request){
		$mode = $request->getMode();
		if($mode == 'getdata'){
			echo json_encode($this->getFiltros($request));
			return;
		}
		if($mode == 'create'){
			echo json_encode($this->guardarFiltro($request));
			return;
		}
		if($mode == 'delete'){

		}
	}

	public function getFiltros($request){
		global $log;
		$data = array();
		$adb = PearDatabase::getInstance(); 
		$currentUserModel = Users_Record_Model::getCurrentUserModel();
		$idUser = $currentUserModel->getId();
		$tabla = $request->get('tabla');
		$resultSet = $adb->pquery("SELECT * FROM lp_analisis_filtros WHERE usuario = ? AND tabla = ? ",array($idUser,$tabla));
		if( $adb->num_rows($resultSet) ){
			while( $row = $adb->fetch_array($resultSet) ){
				$data[] = array(
					'usuario'	=>	$row['usuario'],
					'nombre'	=>  $this->parseTildes($row['nombre']),
					'columnas'	=>  $this->parseColumnasFilas($row['columnas']),
					'filas'		=>	$this->parseColumnasFilas($row['filas']),
					'filtros'	=>  $this->parseFiltros($row['filtros']),
					'id'		=>	$row['id']
					);
				$log->debug(json_encode($row));
			}
		}
		$log->debug("Return:: ".json_encode(array('success'=>true,'data'=>$data)));
		return array('success'=>true,'data'=>$data);
	}


	/*
	* Funcion para parsear las columnas obtenidas de la base de datos
	*/
	public function parseColumnasFilas($data){
		return explode("|##|", $data);
	}
	
	/*
	* Funcion para parsear los filtros obtenidos en la base de datos
	*/
	public function parseFiltros($data){
		global $log;
		$log->debug($data);
		$ret = array();
		$arr = explode("|##|", $data);	
		foreach ($arr as $single) {
			$e = explode(" : ", $single);
			$ret[$e[0]] = $e[1];
		}
		return $ret;
	}

	/*
	* Funcion para parsear los filtros a string para guardarlos en la base de datos
	*/
	public function filtrosToDb($data){
		$stringReturn = "";
		foreach ($data as $key => $value) {
			$stringReturn .= $key." : ".$value."|##|";
		}
		$stringReturn = rtrim($stringReturn,"|##|");
		return $stringReturn;
	}
	/*
	*	Funcion para parsear las columnas y filas a string para guardalos en la base de datos
	*/
	public function columnasFilasToDb($data){		
		$stringReturn = "";
		if($data != ""){
			foreach ($data as $single) {
				$stringReturn .= $single."|##|";
			}
			$stringReturn = rtrim($stringReturn,"|##|");
		}
		return $stringReturn;
	}

	public function guardarFiltro(Vtiger_Request $request){
		global $log;
		$log->debug("En la guardarFiltro");
		$adb = PearDatabase::getInstance();
		$currentUserModel = Users_Record_Model::getCurrentUserModel();
		$usuario = $currentUserModel->getId();
		$datos = $request->get('datos');
		$datos['usuario'] = $usuario;	
		$log->debug("Datos::".json_encode($datos));
		$existe = $adb->pquery("SELECT 1 FROM lp_analisis_filtros WHERE nombre = ? AND tabla = ? AND usuario = ? ",array($datos['nombre'],$datos['tabla'],$usuario));
		if($adb->num_rows($existe)){
			return $this->modificarFiltro($datos);
		}
		else{
			return $this->crearFiltro($datos);
		}
	}
	
	public function eliminarFiltro(){

	}

	public function modificarFiltro($datos){
		global $log;
		$adb = PearDatabase::getInstance();
		//Checkear que sea del mismo usuario
		$nombre = $datos['nombre'];	
		$usuario = $datos['usuario'];
		$cols = $this->columnasFilasToDb($datos['columnas']);
		$rows = $this->columnasFilasToDb($datos['filas']);
		$filtros = $this->filtrosToDb($datos['filtros']);
		$tabla = $datos['tabla'];
		$params = array($rows,$cols,$filtros,$nombre,$tabla,$usuario);	
		$result = $adb->pquery("UPDATE lp_analisis_filtros SET filas = ? ,columnas = ? ,filtros = ? WHERE nombre = ? AND tabla = ? AND usuario = ? ",array($params));
		return array('success'=>true,'data'=>$datos);
	}
	public function crearFiltro($datos){
		global $log;		
		$adb = PearDatabase::getInstance();
		$result = $adb->pquery("SELECT IFNULL(MAX(id)+1,1) AS id FROM lp_analisis_filtros",array());
		$row = $adb->fetch_array($result);
		$id = $datos['id'] = $row['id'];
		$nombre = $datos['nombre'];	
		$usuario = $datos['usuario'];
		$cols = $this->columnasFilasToDb($datos['columnas']);
		$rows = $this->columnasFilasToDb($datos['filas']);
		$filtros = $this->filtrosToDb($datos['filtros']);
		$tabla = $datos['tabla'];
		$params = array($id,$nombre,$rows,$cols,$filtros,$usuario,$tabla);		
		$result = $adb->pquery("INSERT INTO lp_analisis_filtros (id,nombre,filas,columnas,filtros,usuario,tabla) 
			VALUES(?,?,?,?,?,?,?)",$params);
		$log->debug(json_encode($datos));
		return array('success'=>true,'data'=>$datos);
	}

	function parseTildes($texto){
		return str_replace(	array('&ntilde;','&aacute;','&eacute;','&iacute;','&oacute;','&uacute;','&Aacute;','&Eacute;','&Icute;','&Ocute;','&Ucute;'),array('ñ','á','é','í','ó','ú','Á','É','Í','Ó','Ú'),$texto);
	}

}

?>