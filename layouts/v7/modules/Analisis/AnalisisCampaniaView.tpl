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



<div class="detailViewContainer">
  <div class="row-fluid detailViewTitle"  style="padding-bottom:10px;">
    <span class="recordLabel font-x-x-large textOverflowEllipsis span pushDown" title="gika"><span class="">Analísis de Campañas</span>&nbsp;</span>
    <span class="row-fluid">
      <span class="muted">Desempeño global: todos los clientes, todos los locales</span><br>
      <span class="muted">Desempeño objetivo: los clientes de la campaña, los locales de la campaña</span>
    </span>
  </div>
  <div class="row-fluid detailViewTitle"><span class="row-fluid"><span class="muted">¿Los mails son abiertos? Activan el consumo de a los clientes?</span><br><span class="muted">
Clientes activos = = los que han generado gaviotas, canjes o vales, o fueron activos en la Web (compra, newsletter), esos últimos 24 meses
</span></span></div>
  <div class="detailViewInfo row-fluid">
       
    <div class="detailViewInfo row-fluid">
        <div class="row-fluid">
      <span class="span3">
        <span class="pull-left" style="padding-left:10px;padding-top:5px;">
          <label for="createdtime">
        Fecha entre:
          </label>
        </span>
      </span>
      <span class="span3">
        <input type="text" name="createdtime" id="createdtime" value="{$date_range}" class="dateRange widgetFilter dateField" data-date-format="dd-mm-yyyy">
      </span>
       <span class="span3">
        <span class="pull-left" style="padding-left:10px;padding-top:5px;">
          <label for="edades">
            Sexo:
          </label>
        </span>
      </span>
      <span class="span3">
        <select class="select2" multiple name="sexo" id="sexo" style="width: 73%"  onChange="javascript:actualizar();">
          
          <option value="F">
              Femenino
            </option>
         <option value="M">
              Masculino
            </option>
         <option value="-">
              Sin Dato
            </option> 
        </select>
      </span>
    </div>
    <div class="row-fluid">
      <span class="span3">
        <span class="pull-left" style="padding-left:10px;padding-top:5px;">
          <label for="edades">
            Rango de Edades:
          </label>
        </span>
      </span>
      <span class="span3">
        {strip}
        {assign var=PICKLIST_VALUES value=$edades}
        <select id="edades" multiple class="select2" name="edades" style="width: 73%" onChange="javascript:actualizar();">
            {foreach item=PICKLIST_VALUE from=$PICKLIST_VALUES}
                <option value="{Vtiger_Util_Helper::toSafeHTML($PICKLIST_VALUE)}">{$PICKLIST_VALUE}</option>
            {/foreach}
        </select>
        {/strip}
      </span>
      <span class="span3">
        <span class="pull-left" style="padding-left:10px;padding-top:5px;">
          <label for="tipo">
            Canal Activo:
          </label>
        </span>
      </span>
      <span class="span3">
        {strip}
        {assign var=PICKLIST_VALUES value=$canales}
        <select id="canal" multiple class="select2" name="canal" style="width: 73%" onChange="javascript:actualizar();">
            {foreach item=PICKLIST_VALUE from=$PICKLIST_VALUES}
                <option value="{Vtiger_Util_Helper::toSafeHTML($PICKLIST_VALUE)}">{$PICKLIST_VALUE}</option>
            {/foreach}
        </select>
        {/strip}
      </span>
    </div>
    
    
    
    <div class="row-fluid" style="padding-top:5px;padding-bottom:5px;">
      <span class="span3">
        <span class="pull-left" style="padding-left:10px;padding-top:5px;">
          <label for="tipo">
            Estatuto:
          </label>
        </span>
      </span>
      <span class="span3">
         {strip}
        {assign var=PICKLIST_VALUES value=$estatutos}
        <select id="estatuto" multiple class="select2" name="estatuto" style="width: 73%" onChange="javascript:actualizar();">
            {foreach item=PICKLIST_VALUE from=$PICKLIST_VALUES}
                <option value="{Vtiger_Util_Helper::toSafeHTML($PICKLIST_VALUE)}">{$PICKLIST_VALUE}</option>
            {/foreach}
        </select>
        {/strip}
      </span>
     <span class="span3">
        <span class="pull-left" style="padding-left:10px;padding-top:5px;">
          <label for="tipo">
            Programas:
          </label>
        </span>
      </span>
      <span class="span3">
        {strip}
          {assign var=PICKLIST_VALUES value=$programas}
          <select id="programa" multiple class="select2" name="programa" style="width: 73%" onChange="javascript:actualizar();">
              {foreach item=PICKLIST_VALUE from=$PICKLIST_VALUES}
                  <option value="{Vtiger_Util_Helper::toSafeHTML($PICKLIST_VALUE)}">{$PICKLIST_VALUE}</option>
              {/foreach}
          </select>
        {/strip}
      </span>
    </div>
    <div class="row-fluid" style="padding-top:5px;padding-bottom:5px;">
      <span class="span3">
        <span class="pull-left" style="padding-left:10px;padding-top:5px;">
          <label for="tipo">
            Tipo de Campaña:
          </label>
        </span>
      </span>
      <span class="span3">
         {strip}
        {assign var=PICKLIST_VALUES value=$tipos}
        <select id="tipo" multiple class="select2" name="tipo" style="width: 73%" onChange="javascript:actualizar();">
            {foreach item=PICKLIST_VALUE from=$PICKLIST_VALUES}
                <option value="{Vtiger_Util_Helper::toSafeHTML($PICKLIST_VALUE)}">{$PICKLIST_VALUE}</option>
            {/foreach}
        </select>
        {/strip}
      </span>
      <span class="span3">
        <span class="pull-left" style="padding-left:10px;padding-top:5px;">
          <label for="nodum">
            Creada en Nodum:
          </label>
        </span>
      </span>
      <span class="span3">
         {strip}
        
        <select id="nodum" multiple class="select2" name="nodum" style="width: 73%" onChange="javascript:actualizar();">
                <option value="1">Sí</option>
                <option value="0">No</option>
        </select>
        {/strip}
      </span>
    </div>
    <div class="row-fluid">

      <span class="span3">
        <span class="pull-left" style="padding-left:10px;padding-top:5px;">
          <label for="edades">
            Familias:
          </label>
        </span>
      </span>

      <span class="span3">
        <select class="widgetFilter" name="familias" id="familias" onChange="javascript:actualizar();">
          <option value="">{vtranslate('LBL_ALL', $MODULE_NAME)}</option>
          {foreach key=USER_ID item=USER_NAME from=$familias}
          <option value="{$USER_NAME}">
              {$USER_NAME}
            </option>
          {/foreach}
        </select>

        
      </span>
    
      <span class="span3">
        <span class="pull-left" style="padding-left:10px;padding-top:5px;">
          <label for="edades">
            Rubros:
          </label>
        </span>
      </span>
      <span class="span3">
        <select class="widgetFilter" name="rubro" id="rubro" onChange="javascript:actualizar();">
          <option value="">{vtranslate('LBL_ALL', $MODULE_NAME)}</option>
          {foreach key=USER_ID item=USER_NAME from=$rubros}
          <option value="{$USER_NAME}">
              {$USER_NAME}
            </option>
          {/foreach}
        </select>
      </span>
    </div>
     <div class="row-fluid">
      <span class="span3">
        <span class="pull-left" style="padding-left:10px;padding-top:5px;">
          <label for="tipo">
            Locales Adheridos:
          </label>
        </span>
      </span>
      <span class="span3">
        <select class="widgetFilter" name="adherido" id="adherido" onChange="javascript:actualizar();">
          <option value="">{vtranslate('LBL_ALL', $MODULE_NAME)}</option>
          {foreach key=USER_ID item=USER_NAME from=$adheridos}
          <option value="{$USER_NAME}">
              {$USER_NAME}
            </option>
          {/foreach}
        </select>
      </span>
    
      <span class="span3">
        <span class="pull-left" style="padding-left:10px;padding-top:5px;">
          <label for="tipo">
            Localización:
          </label>
        </span>
      </span>
      <span class="span3">
        <select class="widgetFilter" name="localizacion" id="localizacion" onChange="javascript:actualizar();">
          <option value="">{vtranslate('LBL_ALL', $MODULE_NAME)}</option>
          {foreach key=USER_ID item=USER_NAME from=$localizacion}
          <option value="{$USER_NAME}">
              {$USER_NAME}
            </option>
          {/foreach}
        </select>
      </span>
</div>

    <div class="row-fluid">
{*M E*}      <span class="span3">
{*M E*}        <span class="pull-left" style="padding-left:10px;padding-top:5px;">
{*M E*}          <label for="local">
{*M E*}            Locales:
{*M E*}          </label>
{*M E*}        </span>
{*M E*}      </span>
{*M E*}      <span class="span3">
{*M E*}        <select class="widgetFilter" name="local" id="local" onChange="javascript:actualizar();">
{*M E*}          <option value="">{vtranslate('LBL_ALL', $MODULE_NAME)}</option>
{*M E*}          {foreach item=local from=$locales}
{*M E*}          <option value="{$local[1]}">
{*M E*}              {$local[0]}
{*M E*}            </option>
{*M E*}          {/foreach}
{*M E*}        </select>
{*M E*}      </span>
{*M E*}      <span class="span3">
{*M E*}        <span class="pull-left" style="padding-left:10px;padding-top:5px;">
{*M E*}          <label for="local">
{*M E*}            Forma de Pago:
{*M E*}          </label>
{*M E*}        </span>
{*M E*}      </span>
{*M E*}      <span class="span3">
{*M E*}        <select class="widgetFilter" name="formapago" id="formapago" onChange="javascript:actualizar();">
{*M E*}          <option value="" selected="selected">Todas</option>
                  {foreach item=forma from=$forma_pago}
{*M E*}          <option value="{$forma[1]}">
{*M E*}              {$forma[0]}
{*M E*}            </option>
{*M E*}          {/foreach}
{*M E*}        </select>
{*M E*}      </span>
    </div>
    <div class="row-fluid">
      <table class="jqplot-table-legend" style="position:relative;margin-left:5px;width:458px;">
        <tbody>
          <tr class="jqplot-table-legend">
            <td class="jqplot-table-legend jqplot-table-legend-swatch jqplot-seriesToggle" style="text-align: center; padding-top: 0px;">
              <div class="jqplot-table-legend-swatch-outline">
                  <div class="jqplot-table-legend-swatch" style="background-color: #D9D9FA; border-color: #D9D9FA;margin-right:-3px;"></div>
              </div>
            </td>
            <td class="jqplot-table-legend jqplot-table-legend-label jqplot-seriesToggle" style="padding-top: 0px;padding-right:12px;">Campaña</td>
            <td class="jqplot-table-legend jqplot-table-legend-swatch jqplot-seriesToggle" style="text-align: center; padding-top: 0px;">
                <div class="jqplot-table-legend-swatch-outline">
                  <div class="jqplot-table-legend-swatch" style="background-color: #CACAFC; border-color: #CACAFC;margin-right:-3px;"></div>
                </div>
            </td>
            <td class="jqplot-table-legend jqplot-table-legend-label jqplot-seriesToggle" style="padding-top: 0px;">Mensajes Enviados</td>
            <td class="jqplot-table-legend jqplot-table-legend-swatch jqplot-seriesToggle" style="text-align: center; padding-top: 0px;">
                <div class="jqplot-table-legend-swatch-outline">
                  <div class="jqplot-table-legend-swatch" style="background-color: #9393FF; border-color: #9393FF;margin-right:-3px;"></div>
                </div>
            </td>
            <td class="jqplot-table-legend jqplot-table-legend-label jqplot-seriesToggle" style="padding-top: 0px;">Desempeño Global</td>
           <td class="jqplot-table-legend jqplot-table-legend-swatch jqplot-seriesToggle" style="text-align: center; padding-top: 0px;">
                <div class="jqplot-table-legend-swatch-outline">
                  <div class="jqplot-table-legend-swatch" style="background-color: #4E4EFC; border-color: #4E4EFC;margin-right:-3px;"></div>
                </div>
            </td>
            <td class="jqplot-table-legend jqplot-table-legend-label jqplot-seriesToggle" style="padding-top: 0px;">Desempeño Objetivo</td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="  details">
     {strip}
    {if count($DATA) gt 0 }
      <span id="pivot-detail" style="margin: 10px;"></span>
      <ul class="dropdown-menu stop-propagation" style="display:none;">
            <div id="row-label-fields"></div>
          </ul>
       <div id="results" style="margin: 10px;"></div>
       <div style="float:left;">
            <input type="hidden" name="min" id="min">
          </div>
          <div style="float:left;">
            <input type="hidden" name="max" id="max">
          </div>
    {else}
      <span class="noDataMsg">
        {vtranslate('LBL_NO')} {vtranslate($MODULE_NAME, $MODULE_NAME)} {vtranslate('LBL_MATCHED_THIS_CRITERIA')}
      </span>
    {/if}
    {/strip}
    <div class="row-fluid">
        <div class="span8" style="margin-left:5px;">
            <a class="btn addButton" href="#" style="padding:4px 6px;float:left;font-weight:bold;margin-right:5px;font-family:'Helvetica Neue', Helvetica, Arial, sans-serif;" onclick=" tableToExcel('pivot-table','Analisis de las Campañas');">  Exportar a Excel  </a>
            <button style="float:left;" class="btn addButton" onclick="saveExcel('results','results','Analisis de las Campañas');"><i class="icon-download icon-white"></i>&nbsp;<strong>Guardar para Análisis de Datos</strong></button>
            <button id="resultsView" style="float:left;display:none;margin-left:10px;"class="btn addButton" onclick=""><i class="icon-signal icon-white"></i>&nbsp;<strong>  Ver Análisis</strong></button>
            <div id="resultsLoader" style="text-align:center;display:none;"><img src="layouts/vlayout/skins/softed/images/loading.gif" border="0" align="absmiddle"></div>
        </div>
         <form action="download.php" method="post" target="_blank" id="FormularioExportacion">
            <input type="hidden" id="datos_a_enviar" name="datos_a_enviar" />
            <input type="hidden" id="nombre_a_enviar" name="nombre_a_enviar" />
            <input type="hidden" id="is_submited" name="is_submited" />
          </form>
    </div>
    </div>
  </div>
</div>

{literal}
<style type="text/css">
.color1{
  background-color: #D9D9FA!important;
}
.color2{
  background-color: #CACAFC!important;
}
.color3{
  background-color: #9393FF!important;
}
.color4{
  background-color: #4E4EFC!important;
}
th.color1,th.color2{
  color: #000!important;
}
th.color3,th.color4{
  color: #000!important;
}
</style>

<script type="text/javascript">
  var field_definitions = [
          {name: 'Campaña',   type: 'string',   filterable: true, rowLabelable: true},
          {name: 'Desde',   type: 'string',   filterable: true, rowLabelable: true},
          {name: 'Clientes activos por día',   type: 'string',   filterable: true, rowLabelable: true},
          {name: 'Clientes reclutados por día',   type: 'string',   filterable: true, rowLabelable: true},
          {name: '$ por dia',   type: 'string',   filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,'.',',')}},
          {name: '$ por cliente',   type: 'string',   filterable: true, rowLabelable: true},
          {name: 'Clientes activos por día ',   type: 'string',   filterable: true, rowLabelable: true},
          {name: 'Clientes reclutados por día ',   type: 'string',   filterable: true, rowLabelable: true},
          {name: '$ por dia ',   type: 'float',   filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,'.',',')}},
          {name: '$ por cliente ',  type: 'float',   filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,'.',',')}},
          {name: 'Hasta',   type: 'string',   filterable: true, rowLabelable: true},
          {name: 'SMS Enviados', type: 'float',   filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,'.',',')}},
          {name: 'Mensajes Enviados', type: 'float',   filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,'.',',')}},
          {name: '% Mails Abiertos', type: 'float',   filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,'.',',')}},
          {name: 'Clientes venidos', type: 'float',   filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,'.',',')}},
          {name: 'Consumo por Cliente', type: 'float',   filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,'.',',')}},
          {name: 'Total Clientes venidos', type: 'float',   filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,'.',',')}},
          {name: 'Total Consumo por Clientes', type: 'float',   filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,'.',',')}},
          {name: 'Cantidad de Clientes', type: 'float',   filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,'.',',')}},
          {name: 'Cantidad Rebotados', type: 'float',   filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,'.',',')}},
          {name: 'Clientes Activos en promociones', type: 'float',   filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,'.',',')}},
          
          ]   
 

  var chartData;
  $( document ).ready(function() {
    jQuery.extend( jQuery.fn.dataTableExt.oSort, {
        "numeric-comma-pre": function ( a ) {
            var x = (a == "-") ? 0 : a.replace( /\./g, "" );
            x = (a == "-") ? 0 : x.replace( /,/, "." );
            return parseFloat( x );
        },
     
        "numeric-comma-asc": function ( a, b ) {
            return ((a < b) ? -1 : ((a > b) ? 1 : 0));
        },
     
        "numeric-comma-desc": function ( a, b ) {
            return ((a < b) ? 1 : ((a > b) ? -1 : 0));
        }
    } );
    //jQuery.noConflict();      
    //chartData = getChartRelatedData();
    var jData ={/literal}{$DATA}{literal};
    //chartData = JSON.parse(jData);
    chartData = jData;
    graficar();

    var dateRangeElement = jQuery('.dateRange');
      var dateChanged = false;
      if(dateRangeElement.length <= 0) {
        return;
      }
      var customParams = {
        calendars: 3,
        mode: 'range',
        className : 'rangeCalendar',
        onChange: function(formated) {
          dateChanged = true;
          var element = jQuery(this).data('datepicker').el;
          jQuery(element).val(formated);
        },
        onHide : function() {
          if(dateChanged){
            actualizar();
            dateChanged = false;
          }
        },
        onBeforeShow : function(elem) {
          jQuery(elem).css('z-index','3');
        }
      }
      dateRangeElement.addClass('dateField').attr('data-date-format',"dd-mm-yyyy");
      app.registerEventForDatePickerFields(dateRangeElement,false,customParams);
      jQuery(".dateRange").keydown(function (e)
      {
          e.preventDefault();
      });
    
  });
  
  var oTable=null;
  function graficar(){
      
      setupPivot({json: chartData, fields: field_definitions, filters: {}, rowLabels:['Campaña', 'Desde', 'Hasta',
               'SMS Enviados', 'Mensajes Enviados', '% Mails Abiertos','Cantidad Rebotados','Clientes activos por día','$ por dia','Clientes Activos en promociones',
                'Clientes activos por día ',
                'Cantidad de Clientes'],columnLabels:[] ,summaries:[]})
          

      // prevent dropdown from closing after selection
      jQuery('.stop-propagation').click(function(event){
        event.stopPropagation();
      });
     
     
      jQuery('#min').keyup( function() { oTable.fnDraw(); } );
      jQuery('#max').keyup( function() { oTable.fnDraw(); } );

      


  }
  function setupPivot(input){
    input.callbacks = {afterUpdateResults: function(){
       // columns array
      var aoColumns = [];
      //aoColumns.push(null);
      //
      
      var total=null;
      var nTotal=0;
      
      jQuery('.row-labelable:checked').each(function(index){
          //display_fields.push(jQuery(this).attr('data-field'));
          console.log(jQuery(this).attr('data-field'));
          colName=jQuery(this).attr('data-field');
          if(colName=='Campaña' || colName=='Desde'|| colName=='Hasta' ){
            var column = { 
             sClass: 'color1'
            };  
          }else if(colName=='SMS Enviados' || colName=='Mensajes Enviados' || colName=='% Mails Abiertos' || colName=='Cantidad Rebotados'){
            var column = { 
             sClass: 'color2'
            };
          }else if(colName=='Clientes activos por día' || colName== "Clientes reclutados por día" || colName=="% Descuento" || colName=="$ por dia" || colName=="$ por cliente" || colName=="Clientes Activos en promociones"){
            var column = { 
             sClass: 'color3'
            };
          }else if(colName=='Clientes activos por día ' || colName=="Clientes reclutados por día " || colName=="Cantidad de Clientes" || colName=="$ por dia " || colName=="$ por cliente " ){
            var column = { 
             sClass: 'color4'
            };
          }
          
          aoColumns.push(column);
      });
       oTable = jQuery('#results > table').dataTable({
        "sDom": "<'row'<'span6'l><'span6'f>>t<'row'<'span6'i><'span6'p>>",
        "iDisplayLength": 20,
        "aLengthMenu": [[20, 40, 60, -1], [20, 40, 60, "Todos"]],
        "sPaginationType": "bootstrap",
        "aaSorting": [ [0,'asc'] ],
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

        },
        
        "aoColumnDefs": [
            {"sType": "numeric-comma", "aTargets": [3,4,5,6,7,8,9,10] }, //define data type for specified columns
        ],
          "aoColumns": aoColumns
      });

     // var oSettings = oTable.fnSettings();
     

      
    }};
    jQuery('#pivot-demo').pivot_display('setup', input);
   
  };

  function actualizar(){
  
    var dateRangeVal = jQuery('.dateRange').val();
    //If not value exists for date field then dont send the value
    if(dateRangeVal.length <= 0) {
      return true;
    }
    var dateRangeValComponents = dateRangeVal.split(',');
    var createdtime = {};
    createdtime.start = dateRangeValComponents[0];
    createdtime.end = dateRangeValComponents[1];

    var edad = jQuery('#edades').val();
    var canal = jQuery('#canal').val();
    var estatuto = jQuery('#estatuto').val();
    var tipo = jQuery('#tipo').val();
    var nodum = jQuery('#nodum').val();
    var sexo = jQuery('#sexo').val();
    //var sexo = jQuery('#sexo').children('option:selected').val();
    var programa = jQuery('#programa').val();

    var sexo_str="";
    var edad_str="";
    var canal_str="";
    var estatuto_str="";
    var programa_str="";
    var tipo_str="";
    var nodum_str="";

    if (sexo!== null)sexo_str=sexo.join(',');
    if (estatuto!== null)estatuto_str=estatuto.join(',');
    if (tipo!== null)tipo_str=tipo.join(',');
    if (nodum!== null)nodum_str=nodum.join(',');
    if (edad!== null)edad_str=edad.join(',');
    if (canal!== null)canal_str=canal.join(',');
    if (programa!== null)programa_str=programa.join(',');


    var familia = jQuery("#familias").val();
    var rubro = jQuery("#rubro").val();
    var localizacion = jQuery("#localizacion").val();
    var adherido = jQuery("#adherido").val();
    var nombreLocal = jQuery("#local").val();
    var formapago = jQuery("#formapago").val();

    jQuery.ajax({
           async: false,
           data: {'createdtime':createdtime,'rango':edad_str,'sexo':sexo_str,'canal':canal_str,'programa':programa_str,'estatuto':estatuto_str,'tipo':tipo_str,'nodum':nodum_str,'familia':familia, 'rubro':rubro, 'localizacion': localizacion, 'adherido':adherido, 'nombreLocal':nombreLocal, 'formapago':formapago},
           url:  'index.php?module=Analisis&view=AnalisisCampania&mode=Ajax',
           dataType:"json",
           success: function(data) {
             arr=data;
              //var data = JSON.parse(arr);
              var chartDataAux = [];
              var arrayLength = data[0].length;
              if(arrayLength>0){
                
                chartData=data;
                graficar();
              }else{
                chartData=chartDataAux;
                  graficar();
              }
           },
            error: function (xhr, ajaxOptions, thrownError) {
              console.log(thrownError);
          }
         });
  }

</script>


{/literal}