<?

error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set("display_errors", 1);

echo "acaaaa";
class Analisis_LoadDashboard_Action extends Vtiger_Action_Controller {

	function checkPermission(Vtiger_Request $request) {
		return;
	}


public function LoadDashboard(Vtiger_Request $request) {
echo $tipo=$request->get('type');

if($tipo=='MailsRebotados'){
	//$fp = fopen("myarchivo.txt","a");
	
	$adb = PearDatabase::getInstance();

	 $query="select destinatario,firstname,lastname,mobile,lpdocumento,asunto,
	 		 DATE_FORMAT(fecha_error, '%d/%m/%Y') as fecha_error,lplcnombre as local
	  		 from vtiger_lp_errores_correos
			inner join vtiger_contactdetails on destinatario=email
			LEFT JOIN vtiger_local ON vtiger_local.localid=lplocal
			where fecha_error>=date_add(now(),interval -30 day)";

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
	$json_string="[[\"Correo\",\"Nombre\",\"Documento\",\"Celular\",\"Asunto\",\"Fecha\",\"Local de Preferencia\"],";
	$ar[]=array('Correo','Nombre','Documento','Celular','Asunto','Fecha','Local de Preferencia');
	if($no_of_rows!=0){
	  $total=0;
	  while($row = $adb->fetch_array($result)){
	    	$json_string.="[\"".$row['destinatario']."\",\"".$row['firstname']." ".$row['lastname']."\",\"".$row['lpdocumento']."\",\"".$row['mobile']."\",\"".$row['asunto']."\",\"".$row['fecha_error']."\",\"".$row['local']."\"],";
	    	$ar[]=array($row['destinatario'],$row['firstname']." ".$row['lastname'],$row['lpdocumento']."",$row['mobile']."",$row['asunto']."",$row['fecha_error'],$row['local']."");
	//    	$ar[]=array("\"".$row['destinatario']."\",\"".$row['firstname']." ".$row['lastname']."\",\"".$row['lpdocumento']."\",\"".$row['mobile']."\",\"".$row['asunto']."\",\"".$row['fecha_error']."\",\"".$row['local']."\"");
	  }
	}
	$json_string=rtrim($json_string, ",");
	$json_string.="]";

	
	//echo $json_string;
	echo json_encode($ar);
		
}
}
?>