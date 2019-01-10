{*<!--
/*********************************************************************************
  ** The contents of this file are subject to the vtiger CRM Public License Version 1.0
   * ("License"); You may not use this file except in compliance with the License
   * The Original Code is:  vtiger CRM Open Source
   * The Initial Developer of the Original Code is vtiger.
   * Portions created by vtiger are Copyright (C) vtiger.
   * All Rights Reserved.
  *
 ********************************************************************************/
-->*}
{strip}
{include file="Header.tpl"|vtemplate_path:$MODULE_NAME}
{include file="BasicHeader.tpl"|vtemplate_path:$MODULE_NAME}

<div class="bodyContents">
	<div class="mainContainer row-fluid">
		<div class="span2 row-fluid">
			{include file="SideBar.tpl"|vtemplate_path:$MODULE_NAME}
		</div>
		<div class="contentsDiv span10 marginLeftZero">

{/strip}
{literal}

<script type="text/javascript">
var from_page = jQuery('#from_page').val();
var plot=null;
var arr = "";
var carga=0;

var oTable=null;

// Define the structure of fields, if this is not defined then all fields will be assumed
// to be strings.  Name must match csv header row (which must exist) in order to parse correctly.
var fields = [
    // filterable fields
    {name: 'Correo', type: 'string', filterable: true},
    {name: 'Nombre', type: 'string', filterable: true},
    {name: 'Celular', type: 'string', filterable: true},
  {name: 'Documento', type: 'string', filterable: true},
    {name: 'Asunto',      type:'string',   filterable: true},
    {name: 'Fecha',      type:'string',   filterable: true},
  
]


var field_definitions = [{name: 'Correo',   type: 'string',   filterable: true, rowLabelable: true},
        {name: 'Nombre',   type: 'string',   filterable: true, rowLabelable: true},
        {name: 'Celular',   type: 'string',   filterable: true, rowLabelable: true},
    {name: 'Documento',   type: 'string',   filterable: true, rowLabelable: true},
        {name: 'Asunto', type: 'string',   filterable: true, rowLabelable: true},
        {name: 'Fecha', type: 'string',   filterable: true, rowLabelable: true},
        {name: 'Local de Preferencia', type: 'string',   filterable: true, rowLabelable: true},
  
        ]   

  
  jQuery(document).ready(function() {
        
        //MailsRebotados();
  
          setupPivot({json: arr, fields: field_definitions, filters: {}, rowLabels:["Correo","Nombre","Celular","Asunto","Fecha","Local de Preferencia"],columnLabels:[] ,summaries:[]})
        

          // prevent dropdown from closing after selection
          jQuery('.stop-propagation').click(function(event){
            event.stopPropagation();
          });
         
         
          /* Add event listeners to the two range filtering inputs */
          jQuery('#min').keyup( function() { oTable.fnDraw(); } );
          jQuery('#max').keyup( function() { oTable.fnDraw(); } );
          

          jQuery('#prod_rep').click(function(event){
            jQuery('#pivot-demo').pivot_display('reprocess_display', {rowLabels:["Nombre","Categoría","Marca","CN","PCS","AR","C20","B","O","OB","MS","Desde","Hasta","Cantidad"],filters: {Rep:1}})
          });

          jQuery('#mostrar_todos').click(function(event){
            jQuery('#pivot-demo').pivot_display('reprocess_display', {rowLabels:["Nombre","Categoría","Marca","CN","PCS","AR","C20","B","O","OB","MS","Desde","Hasta","Cantidad"]})
          });

          jQuery('#excel').click(function(event){
            event.preventDefault();
            tableToExcel('pivot-table','Correos Rebotados');
           
          });

  });
  function setupPivot(input){
    input.callbacks = {afterUpdateResults: function(){
      // columns array
      //var aoColumns = [];
      //aoColumns.push(null);
      
      /*jQuery('.row-labelable:checked').each(function(index){
          //display_fields.push(jQuery(this).attr('data-field'));
          console.log(jQuery(this).attr('data-field'));
          colName=jQuery(this).attr('data-field');
         
          aoColumns.push(column);
      });*/

       oTable = jQuery('#results > table').dataTable({
        "sDom": "<'row'<'span6'l><'span6'f>>t<'row'<'span6'i><'span6'p>>",
        "iDisplayLength": -1,
        "aLengthMenu": [[20, 40, 60, -1], [20, 40, 60, "Todos"]],
        "sPaginationType": "bootstrap",
        "oLanguage": {
          "sLengthMenu": "_MENU_ Registros por Pagina",
          "sZeroRecords": "No hay datos disponibles",
          "sInfo": "Viendo desde _START_ a _END_ de _TOTAL_ registros",
          "sInfoEmpty": "No hay datos disponibles",
          "sNext": "Siguiente",
          "sLast": "Última",
          "sFirst": "Primera",
           "sPrevious": "Anterior",
           "sLoadingRecords": "Cargando ...",
           "sSearch": "Filtrar por:"

        }
        //"aoColumns": aoColumns
        
      });

     // var oSettings = oTable.fnSettings();
      
    }};
    
    //jQuery('#pivot-demo').pivot_display('setup', input);
   
  };


function MailsRebotados(){
   //jQuery("#dashChartLoader2").show();
    jQuery.ajax({
       async: false,
       data: {},
       url:  'index.php?module=Analisis&view=MailsRebotados&mode=data',
       dataType:"json",
       success: function(data) {
         arr=data;
        jQuery("#dashChartLoader2").hide();    
       },
        error: function (xhr, ajaxOptions, thrownError) {
          console.log(thrownError);
      }
     });
}


</script>
<style type="text/css">
.small {
  font-size: 100%;
}
.modal-body p{
  font-size: 14px;
}
</style>
{/literal}

<table width="100%" border="0" cellpadding="0" cellspacing="0" style="vertical-align:top;top:0;">
  <tbody>
    <input type="hidden" name="from_page" id="from_page" value="<?echo $from_page;?>"/>
    <tr>
      <td colspan=6>
        <span class="genHeaderSmall">Correos Rebotados - Ultimos 30 dias</span>
        <button class="btn btn-info" data-toggle="modal" data-target="#myModal" style="float:right;">
          Ayuda
        </button>
      </td>
      
    </tr>
   
    <tr>
      <td><div id="dashChartLoader2" style="text-align:center;"><img src="<?php echo vtiger_imageurl('ajax-loader.gif', $theme) ?>" border="0" align="absmiddle"></div></td>
    </tr>
    <tr>
      <td style="padding:5px 0;">
            <div class="subnav">
      <ul class="nav nav-pills">
        <li class="dropdown">
          <a class="dropdown-toggle" data-toggle="dropdown" href="#">
            Filtros
            <b class="caret"></b>
          </a>
          <ul class="dropdown-menu stop-propagation" style="overflow:auto;max-height:450px;padding:10px;">
            <div id="filter-list"></div>
          </ul>
        </li>
        <li class="dropdown">
          <a class="dropdown-toggle" data-toggle="dropdown" href="#">
            Columnas
            <b class="caret"></b>
          </a>
          <ul class="dropdown-menu stop-propagation" style="overflow:auto;max-height:450px;padding:10px;">
            <div id="row-label-fields"></div>
          </ul>
        </li>
       
        <li class="dropdown" >
          <a class="dropdown-toggle" data-toggle="dropdown" id="excel" href="#">
            Exportar Excel
            <b class="caret"></b>
          </a>
        </li>
        <li class="dropdown pull-right">
          <a class="dropdown-toggle" data-toggle="dropdown" href="#">
            Reportes predefinidos
            <b class="caret"></b>
          </a>
          <ul class="dropdown-menu">

          </ul>
        </li>
        
      </ul>
    </div>
   <form action="download.php" method="post" target="_blank" id="FormularioExportacion">
            <input type="hidden" id="datos_a_enviar" name="datos_a_enviar" />
            <input type="hidden" id="nombre_a_enviar" name="nombre_a_enviar" />
            <input type="hidden" id="is_submited" name="is_submited" />
          </form>

      </td>
    </tr>
      <tr>
      <td colspan=6 style="padding:10px 0;">
        <span id="pivot-detail"></span>
      </td></tr>
       <tr>
        <td colspan=6>
          
          <div id="results"></div>
          <div style="float:left;">
            <input type="hidden" name="min" id="min">
          </div>
          <div style="float:left;">
            <input type="hidden" name="max" id="max">
          </div>  
        </td>
      </tr>
       <tr>
      <td colspan=6 style="padding-top:30px;">
        <input title="Guardar para Analisis de Datos"  style="float:left;" class="crmButton small save" onclick="saveExcel('table','pivot-table','rebotados');" type="button" name="button" value="  Guardar para Analisis de Datos " style="width: 190px;">
        <input id="tableView" title="Ver Analisis" style="float:left;display:none;" class="crmButton small save" onclick="" type="button" name="button" value="  Ver Analisis " style="width: 190px;">          
        <div id="tableLoader" style="display:none;text-align:center;float:left;"><img src="<?php echo vtiger_imageurl('ajax-loader.gif', $theme) ?>" border="0" align="absmiddle"></div>
      </td>
    </tr>
  </tbody>
 </table>   
