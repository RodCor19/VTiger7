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
    <span class="recordLabel font-x-x-large textOverflowEllipsis span pushDown" title="gika"><span class="">Contrato y Venta</span>&nbsp;</span>
    <span class="row-fluid">
      <span class="muted"></span>
    </span>
  </div>
  <div class="row-fluid detailViewTitle"><span class="row-fluid"><span class="muted"></span></span></div>
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
      <table class="jqplot-table-legend" style="position:relative;margin-left:5px;width:458px;">
        <tbody>
          <tr class="jqplot-table-legend">
            <td class="jqplot-table-legend jqplot-table-legend-swatch jqplot-seriesToggle" style="text-align: center; padding-top: 0px;">
              <div class="jqplot-table-legend-swatch-outline">
                  <div class="jqplot-table-legend-swatch" style="background-color: #BDD7EE; border-color: #BDD7EE;margin-right:-3px;"></div>
              </div>
            </td>
            <td class="jqplot-table-legend jqplot-table-legend-label jqplot-seriesToggle" style="padding-top: 0px;padding-right:12px;">Contrato</td>

            <td class="jqplot-table-legend jqplot-table-legend-swatch jqplot-seriesToggle" style="text-align: center; padding-top: 0px;">
                <div class="jqplot-table-legend-swatch-outline">
                  <div class="jqplot-table-legend-swatch" style="background-color: #F8CBAD; border-color: #F8CBAD;margin-right:-3px;"></div>
                </div>
            </td>
            <td class="jqplot-table-legend jqplot-table-legend-label jqplot-seriesToggle" style="padding-top: 0px;">Período n-1</td>
            <td class="jqplot-table-legend jqplot-table-legend-swatch jqplot-seriesToggle" style="text-align: center; padding-top: 0px;">
                <div class="jqplot-table-legend-swatch-outline">
                  <div class="jqplot-table-legend-swatch" style="background-color: #C6E0B4; border-color: #C6E0B4;margin-right:-3px;"></div>
                </div>
            </td>
            <td class="jqplot-table-legend jqplot-table-legend-label jqplot-seriesToggle" style="padding-top: 0px;">Período n</td>
           <td class="jqplot-table-legend jqplot-table-legend-swatch jqplot-seriesToggle" style="text-align: center; padding-top: 0px;">
                <div class="jqplot-table-legend-swatch-outline">
                  <div class="jqplot-table-legend-swatch" style="background-color: #BFBFBF; border-color: #BFBFBF;margin-right:-3px;"></div>
                </div>
            </td>
            <td class="jqplot-table-legend jqplot-table-legend-label jqplot-seriesToggle" style="padding-top: 0px;">Evolución</td>
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
            <a class="btn addButton" href="#" style="padding:4px 6px;float:left;font-weight:bold;margin-right:5px;font-family:'Helvetica Neue', Helvetica, Arial, sans-serif;" onclick=" tableToExcel('pivot-table','Contratos');">  Exportar a Excel  </a>
            <button style="float:left;" class="btn addButton" onclick="saveExcel('results','results','Contratos');"><i class="icon-download icon-white"></i>&nbsp;<strong>Guardar para Análisis de Datos</strong></button>
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
  background-color: #BDD7EE!important;
}
.color2{
  background-color: #F8CBAD!important;
}
.color3{
  background-color: #C6E0B4!important;
}
.color4{
  background-color: #BFBFBF!important;
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
          {name: 'Local',   type: 'string',   filterable: true, rowLabelable: true},
          {name: 'Min $',   type: 'float',    filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,'.',',')}},
          {name: 'm2',   type: 'float',    filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,'.',',')}},
          {name: 'Min $ / m2',   type: 'float',    filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,'.',',')}},
          {name: 'Venta Contable',   type: 'float',    filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,'.',',')}},
          {name: 'Arr %',   type: 'float',    filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,'.',',')}},
          {name: 'Arr Total',   type: 'float',    filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,'.',',')}},
          {name: 'Arr Total / VC (%)',   type: 'float',    filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,2,'.',',')}},
          {name: 'Venta Contable ',   type: 'float',    filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,'.',',')}},
          {name: 'Arr % ',   type: 'float',    filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,'.',',')}},
          {name: 'Arr Total ',   type: 'float',    filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,'.',',')}},
          {name: 'Arr Total / VC (%) ',   type: 'float',    filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,2,'.',',')}},
          {name: 'VC',   type: 'float',    filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,'.',',')}},
          {name: ' Arr Total',   type: 'float',    filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,'.',',')}},
          
        {name: 'Orden', type: 'int', filterable: false, pseudo: true,
            pseudoFunction: function(row){
              var ret="";
              switch (row.Local) {
                  case "Total":
                      ret = 1;
                      break;
                  
                  default:
                    ret=0;
                    break;    
              }
              return ret;
          }},
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
      
      setupPivot({json: chartData, fields: field_definitions, filters: {}, rowLabels:['Local','Min $','m2', 'Min $ / m2','Venta Contable','Arr %','Arr Total','Arr Total / VC (%)','Venta Contable ','Arr % ','Arr Total ','Arr Total / VC (%) ','VC',' Arr Total','Orden'],columnLabels:[] ,summaries:[]})
          

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
          /*'Local','Min $','m2', 'Min $ / m2','Venta Contable','Arr %','Arr Total','Arr Total / VC (%)','Venta Contable ','Arr % ','Arr Total ','Arr Total / VC (%) ','VC','% Arr Total'*/
          console.log(jQuery(this).attr('data-field'));
          colName=jQuery(this).attr('data-field');
          if(colName=='Local' || colName=='Min $'|| colName=='m2'|| colName=='Min $ / m2' ){
            var column = { 
             sClass: 'color1'
            };  
          }else if(colName=='Venta Contable' || colName=='Arr %' || colName=='Arr Total' || colName=='Arr Total / VC (%)'){
            var column = { 
             sClass: 'color2'
            };
          }else if(colName=='Venta Contable ' || colName=='Arr % ' || colName=='Arr Total ' || colName=='Arr Total / VC (%) '){
            var column = { 
             sClass: 'color3'
            };
          }else if(colName=='VC' || colName==" Arr Total"){
            var column = { 
             sClass: 'color4'
            };
          }else{
            var column = { 
             sClass: 'color5'
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
        "aaSortingFixed": [[14,'asc']],
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
            {"sType": "numeric-comma", "aTargets": [1,2,3,4,5,6,7,8,9,10,11,12,13] }, 
            { "bVisible": false, "aTargets": [ 14 ] }
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



    var familia = jQuery("#familias").val();
    var rubro = jQuery("#rubro").val();
    var localizacion = jQuery("#localizacion").val();
    var adherido = jQuery("#adherido").val();
    var nombreLocal = jQuery("#local").val();

    jQuery.ajax({
           async: false,
           data: {'createdtime':createdtime,'familia':familia, 'rubro':rubro, 'localizacion': localizacion, 'adherido':adherido, 'nombreLocal':nombreLocal},
           url:  'index.php?module=Analisis&view=Contratos&mode=Ajax',
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