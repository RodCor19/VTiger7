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
{*M E*}        <select multiple class="select2" style="width: 85%" name="local" id="local" onChange="javascript:actualizar();" >
{*M E*} <!--         <option value="">{vtranslate('LBL_ALL', $MODULE_NAME)}</option>-->
{*M E*}          {foreach item=local from=$locales}
{*M E*}          <option value="{$local[1]}" {if $local[1]== $idLocal}selected=""{/if}>
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
        <select multiple class="select2" style="width: 85%" name="familias" id="familias" onChange="javascript:actualizar();">
          <!--<option value="">{vtranslate('LBL_ALL', $MODULE_NAME)}</option>-->
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
        <select multiple class="select2" style="width: 85%"  name="rubro" id="rubro" onChange="javascript:actualizar();">
          <!--<option value="">{vtranslate('LBL_ALL', $MODULE_NAME)}</option>-->
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
      <span class="span3">
        <span class="pull-left" style="padding-left:10px;padding-top:5px;">
          <label for="tipo">
            Vista:
          </label>
        </span>
      </span>
      <span class="span3">
        <select class="widgetFilter" name="vista" id="vista" onChange="javascript:actualizar();">
          <option value="actual">Actual</option>
          <option value="resumen" selected="">Resumen</option>
          <option value="detalle">Detalle</option>
          <option value="ventas_ipc">Ventas Con Ajuste IPC</option>
          <option value="ventas">Ventas Sin Ajuste IPC</option>
          <!--option value="facturacion">Facturaci&oacute;n</option-->
          <option value="facturacion_detalle">Facturaci&oacute;n Detalle</option>
        </select>
      </span>
 
      <span class="span3">
        <span class="pull-left" style="padding-left:10px;padding-top:5px;">
          <label for="tipo">
            Metros Cuadrados:
          </label>
        </span>
      </span>
      <span class="span3">
        <select class="widgetFilter" name="m2" id="m2" onChange="javascript:actualizar();">
          <option value="" selected="">Todos</option>
          <option value="<9"><9</option>
          <option value="9<39" >9<39</option>
          <option value="40<70">40<70</option>
          <option value="71<120">71<120</option>
          <option value="121<200">121<200</option>
          <option value="201<399">201<399</option>
          <option value="400<800">400<800</option>
          <option value="801<">801<</option>
        </select>
      </span>
  </div>
  <div class="row-fluid">

      
      <span class="span3">
        <span class="pull-left" style="padding-left:10px;padding-top:5px;">
          <label for="tipo">
            Agrupar:
          </label>
        </span>
      </span>
      <span class="span3">
        <select class="widgetFilter" name="agrupar" id="agrupar" onChange="javascript:actualizar();">
          <option value="local" selected="">Locales</option>
          <option value="rubro">Rubro</option>
          <option value="familia">Familia</option>
        </select>
      </span>

      <span class="span3">
        <span class="pull-left" style="padding-left:10px;padding-top:5px;">
          <label for="tipo">
            Ipc (Vista Resumen y Actual):
          </label>
        </span>
      </span>
      <span class="span3">
        <select class="widgetFilter" name="ipc" id="ipc" onChange="javascript:actualizar();">
          <option value="ipcSi" selected="">Ventas con Ipc</option>
          <option value="ipcNo">Ventas sin Ipc</option>          
        </select>
      </span>
    
  </div>
    <div class="row-fluid" id="legend" style="display:none;">
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
    <div class="row-fluid" id="legend_detalle" style="display:none;">
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
            <td class="jqplot-table-legend jqplot-table-legend-label jqplot-seriesToggle" style="padding-top: 0px;">Arr Mínimo</td>
            <td class="jqplot-table-legend jqplot-table-legend-swatch jqplot-seriesToggle" style="text-align: center; padding-top: 0px;">
                <div class="jqplot-table-legend-swatch-outline">
                  <div class="jqplot-table-legend-swatch" style="background-color: #C6E0B4; border-color: #C6E0B4;margin-right:-3px;"></div>
                </div>
            </td>
            <td class="jqplot-table-legend jqplot-table-legend-label jqplot-seriesToggle" style="padding-top: 0px;">Arr Porcentual</td>
           
          </tr>
        </tbody>
      </table>
    </div>
    <div class="row-fluid" id="legend_resumen" >
      <table class="jqplot-table-legend" style="position:relative;margin-left:5px;width:358px;">
        <tbody>
          <tr class="jqplot-table-legend">
            <td class="jqplot-table-legend jqplot-table-legend-swatch jqplot-seriesToggle" style="text-align: center; padding-top: 0px;">
              <div class="jqplot-table-legend-swatch-outline">
                  <div class="jqplot-table-legend-swatch" style="background-color: #BDD7EE; border-color: #BDD7EE;margin-right:-11px;"></div>
              </div>
            </td>
            <td class="jqplot-table-legend jqplot-table-legend-label jqplot-seriesToggle" style="padding-top: 0px;padding-right:12px;">Mensual</td>

            <td class="jqplot-table-legend jqplot-table-legend-swatch jqplot-seriesToggle" style="text-align: center; padding-top: 0px;">
                <div class="jqplot-table-legend-swatch-outline">
                  <div class="jqplot-table-legend-swatch" style="background-color: #C6E0B4; border-color: #C6E0B4;margin-right:-11px;"></div>
                </div>
            </td>
            <td class="jqplot-table-legend jqplot-table-legend-label jqplot-seriesToggle" style="padding-top: 0px;">Rango de Fecha</td>
            <td class="jqplot-table-legend jqplot-table-legend-swatch jqplot-seriesToggle" style="text-align: center; padding-top: 0px;">
                
            </td>
            
           
          </tr>
        </tbody>
      </table>
    </div>

    
    <div class="  details">
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
.color5{
  background-color: #e6ccff!important;
}
th.color1,th.color2{
  color: #000!important;
}
th.color3,th.color4,th.color5{
  color: #000!important;
}


</style>

<script type="text/javascript">
var decimalSep=',';
var milSep='.';
var field_definitions=[];

function pushIfNew(obj) {
  for (var i = 0; i < field_definitions.length; i++) {
    if (field_definitions[i].name === obj.name) { // modify whatever property you need
      return;
    }
  }
  field_definitions.push(obj);
}


  

  var chartData;
  $( document ).ready(function() {

    decimalSep=getDecimalSeparator();
    milSep=getMilSeparator();

    //alert(getFirstBrowserLanguage());


    //  alert(decimalSep);

    field_definitions = [
          {name: 'Agrupar',   type: 'string',   filterable: true, rowLabelable: true},
          {name: 'Local',   type: 'string',   filterable: true, rowLabelable: true},
          {name: 'Min $',   type: 'float',    filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,milSep,decimalSep)}},
          {name: 'm2',   type: 'float',    filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,2,milSep,decimalSep)}},
          {name: 'Min $ / m2',   type: 'float',    filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,milSep,decimalSep)}},
          {name: 'Venta',   type: 'float',    filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,milSep,decimalSep)}},
          {name: 'Arr %',   type: 'float',    filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,milSep,decimalSep)}},
          {name: 'Arr Total',   type: 'float',    filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,milSep,decimalSep)}},
          {name: 'Arr Total / Vta (%)',   type: 'string',    filterable: true, rowLabelable: true},
          {name: 'Venta ',   type: 'float',    filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,milSep,decimalSep)}},
          {name: 'Vta/m2',   type: 'float',    filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,milSep,decimalSep)}},
          {name: 'Arr % ',   type: 'float',    filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,milSep,decimalSep)}},
          {name: 'Arr Total ',   type: 'float',    filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,milSep,decimalSep)}},
          {name: 'Arr Total / Vta (%) ',   type: 'string',    filterable: true, rowLabelable: true},
          {name: 'Vta',   type: 'string',    filterable: true, rowLabelable: true},
          {name: ' Arr Total',   type: 'string',    filterable: true, rowLabelable: true},
          {name: 'Meses',   type: 'float',    filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,milSep,decimalSep)}},
          {name: 'Meses ',   type: 'float',    filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,milSep,decimalSep)}},
          {name: 'Arr Prom Mensual',   type: 'float',    filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,milSep,decimalSep)}},
          {name: 'Arr Prom Mensual ',   type: 'float',    filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,milSep,decimalSep)}},
          {name: 'Arr Prom Mensual',   type: 'float',    filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,milSep,decimalSep)}},
          {name: 'Arr Prom / m2',   type: 'float',    filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,milSep,decimalSep)}},
          {name: 'Facturacion Mensual',   type: 'float',    filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,milSep,decimalSep)}},
          {name: 'Facturacion Mensual / m2',   type: 'float',    filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,milSep,decimalSep)}},  
          {name: 'Facturacion',   type: 'float',    filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,milSep,decimalSep)}},
          {name: 'Facturacion / Vta (%) ',   type: 'string',    filterable: true, rowLabelable: true}, 
          {name: ' Facturacion',   type: 'float',    filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,milSep,decimalSep)}},
          {name: ' Facturacion / Vta (%) ',   type: 'string',    filterable: true, rowLabelable: true}, 
          {name: 'Fondo Prom',   type: 'float',    filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,milSep,decimalSep)}},
          {name: 'Gastos Comunes',   type: 'float',    filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,milSep,decimalSep)}},
          {name: 'Otros',   type: 'float',    filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,milSep,decimalSep)}},
          {name: 'Pago Cta',   type: 'float',    filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,milSep,decimalSep)}},
          {name: 'Fondo Prom  ',   type: 'float',    filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,milSep,decimalSep)}},
          {name: 'Gastos Comunes  ',   type: 'float',    filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,milSep,decimalSep)}},
          {name: 'Otros  ',   type: 'float',    filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,milSep,decimalSep)}},
          {name: 'Pago Cta  ',   type: 'float',    filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,milSep,decimalSep)}},
        {name: 'Facturacion Total',   type: 'float',    filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,milSep,decimalSep)}},
      {name: 'Facturacion Total ',   type: 'float',    filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,milSep,decimalSep)}},
      {name: 'Facturacion Total / m2',   type: 'float',    filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,milSep,decimalSep)}}, 
      {name: 'Facturacion Total / m2 ',   type: 'float',    filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,milSep,decimalSep)}},  
      {name: 'Arr Porcentual',   type: 'float',    filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,milSep,decimalSep)}},
      {name: 'Arr Porcentual  ',   type: 'float',    filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,milSep,decimalSep)}},
      {name: 'Arr Minimo',   type: 'float',    filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,milSep,decimalSep)}}, 
      {name: 'Arr Minimo  ',   type: 'float',    filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,milSep,decimalSep)}}, 
      {name: 'Venta  ',   type: 'float',    filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,milSep,decimalSep)}}, 
      {name: 'Facturacion / Vta(%)',   type: 'float',    filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,milSep,decimalSep)}},      
      
     
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
    //var jData ={/literal}{$DATA}{literal};
    //chartData = JSON.parse(jData);
    //chartData = jData;
    //graficar();

    actualizar();
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
      
    var vista = jQuery("#vista").val();
    var agrupar = jQuery("#agrupar").val();
    var filas='';
    switch(vista) {
        case 'actual':
            filas=['Local','Min $','m2', 'Min $ / m2','Venta','Arr %','Meses','Arr Prom Mensual','Arr Total','Arr Total / Vta (%)',' Facturacion',' Facturacion / Vta (%) ','Facturacion','Facturacion / Vta (%) ','Venta ','Arr % ','Meses ','Arr Prom Mensual ','Arr Total ','Arr Total / Vta (%) ','Vta',' Arr Total','Orden'];
            nroOrden=22;
            ordenNumerico=[1,2,3,4,5,6,7,8,9,10,11,12,13,14,17,18,19,20,21];
            if(agrupar!='local'){
              filas.unshift('Agrupar');
              nroOrden=23;
              ordenNumerico=[2,3,4,5,6,7,8,9,10,11,12,13,14,15,18,19,20,21,22];
            }            
            break;
        case 'resumen':
            filas=['Local','Min $','m2', 'Min $ / m2','Arr Prom Mensual ','Arr Prom / m2','Facturacion Mensual','Facturacion Mensual / m2','Facturacion','Venta ','Vta/m2','Arr % ','Arr Total ','Arr Total / Vta (%) ','Facturacion / Vta (%) ','Orden'];
            nroOrden=15;
            ordenNumerico=[1,2,3,4,5,6,7,8,9,10,11,12,13,14];
            if(agrupar!='local'){
              filas.unshift('Agrupar');
              nroOrden=16;
              ordenNumerico=[2,3,4,5,6,7,8,9,10,11,12,13,14,15];
            }            
            break;
        case 'facturacion_detalle':
            filas=['Local','m2', 'Arr Minimo  ', 'Arr Porcentual  ','Fondo Prom  ','Gastos Comunes  ','Otros  ','Pago Cta  ','Facturacion Total','Venta  ','Facturacion / Vta(%)', 'Arr Minimo', 'Arr Porcentual', 'Fondo Prom','Gastos Comunes','Otros','Pago Cta','Facturacion Total ','Venta ','Facturacion / Vta (%) ','Orden'];
            nroOrden = 20;
            ordenNumerico=[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19];
            if(agrupar!='local'){
              filas.unshift('Agrupar');
              nroOrden=21;
              ordenNumerico=[2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20];
            }            
            break;
        case 'detalle':
        case 'ventas':
        case 'ventas_ipc':
        case 'facturacion':
            filas=[];
            ordenNumerico=[];
            for (var i = 0;i < chartData[0].length;  i++) {
              filas.push(chartData[0][i]);
              ordenNumerico.push(i+1);

              pushIfNew({name: chartData[0][i],   type: 'float',    filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,milSep,decimalSep)}});

            }
            filas.push('Orden');
            nroOrden=i;

            break;
        
    }

      setupPivot({json: chartData, fields: field_definitions, filters: {}, rowLabels:filas,columnLabels:[] ,summaries:[]})
          

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
      
      vista = jQuery("#vista").val();

      if(vista == 'detalle' || vista == 'ventas'|| vista == 'ventas_ipc'|| vista == 'facturacion' ){
        total=jQuery( ".row-labelable:checked" ).size() ;

        jQuery('.row-labelable:checked').each(function(index){
          colName=jQuery(this).attr('data-field');
          if(colName=='Local' || colName=='Agrupar'){
            column = { 
             sClass: 'color1'
            };  
      console.log("aca");
          }else{
            if(index<(total/2)){
              column = { 
               sClass: 'color2'
              };
            }else{
              column = { 
               sClass: 'color3'
              };
            }

          }
          aoColumns.push(column);
        });  
        

      }else{

        jQuery('.row-labelable:checked').each(function(index){
            //display_fields.push(jQuery(this).attr('data-field'));
            /*'Local','Min $','m2', 'Min $ / m2','Venta','Arr %','Arr Total','Arr Total / Vta (%)','Venta ','Arr % ','Arr Total ','Arr Total / Vta (%) ','Vta','% Arr Total'*/
            //console.log(jQuery(this).attr('data-field'));
            colName=jQuery(this).attr('data-field');
            if(colName=='Local' || colName == 'Arr Porcentual  ' || colName == 'Arr Minimo  ' || colName == 'Facturacion Mensual' || colName == 'Facturacion Total' || colName == 'Facturacion Total / m2' || colName == 'Fondo Prom  ' || colName == 'Gastos Comunes  ' || colName == 'Otros  ' || colName == 'Pago Cta  ' || colName == 'Facturacion Mensual / m2' || colName=='Agrupar' ||  colName=='Min $'|| colName=='m2'|| colName=='Min $ / m2' 
      || colName == 'Venta  ' || colName == 'Facturacion / Vta(%)'){
              var column = { 
               sClass: 'color1'
              };  
            }else if(colName=='Venta' || colName=='Arr %' || colName=='Arr Total' || colName==' Facturacion' || colName==' Facturacion / Vta (%) ' || colName=='Arr Total / Vta (%)'|| colName=='Meses'|| colName=='Arr Prom Mensual'){
                var column = { 
                 sClass: 'color2'
                };
            }else if(colName=='Venta ' || colName=='Arr % ' || colName=='Arr Total ' || colName=='Arr Total / Vta (%) ' || colName=='Meses '|| colName=='Arr Prom Mensual '|| colName=='Vta/m2'|| colName=='Arr Prom / m2'){
               if((colName=='Arr Prom Mensual ' || colName=='Arr Prom / m2') && (vista=="resumen" || vista == "facturacion_detalle") ){
                
                var column = { 
                 sClass: 'color1'
                };
              }else{
                var column = { 
                 sClass: 'color3'
                };
              }  
            }else if(colName=='VC' || colName==" Arr Total"){
              var column = { 
               sClass: 'color4'
              };
            }else{
                var column = { 
               sClass: 'color3'
              };  
              
              
            }
            
            aoColumns.push(column);
        });
      }  
      agrupar = jQuery("#agrupar").val();
      ordenDefault=[[0,'asc']];
      if(agrupar!='local'){
        ordenDefault=[[0,'asc'],[1,'asc']];
      }
    console.log("nro Orden: " + nroOrden);
       oTable = jQuery('#results > table').dataTable({
        "sDom": "<'row'<'span6'l><'span6'f>>t<'row'<'span6'i><'span6'p>>",
        "iDisplayLength": 20,
        "aLengthMenu": [[20, 40, 60, -1], [20, 40, 60, "Todos"]],
        "sPaginationType": "bootstrap",
        "aaSorting": ordenDefault,
        "aaSortingFixed": [[nroOrden,'asc']],
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
            {"sType": "numeric-comma", "aTargets": ordenNumerico }, 
            { "bVisible": false, "aTargets": [ nroOrden ] }
        ],
          "aoColumns": aoColumns
      });

     // var oSettings = oTable.fnSettings();
     

      
    }};
  jQuery('#pivot-demo').empty();
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

    var nombreLocal_str ="";
    var familia_str ="";
    var rubro_str ="";

    var familia = jQuery("#familias").val();
    if (familia!== null)familia_str=familia.join(',');
    var rubro = jQuery("#rubro").val();
    if (rubro!== null)rubro_str=rubro.join(',');
    var localizacion = jQuery("#localizacion").val();
    var adherido = jQuery("#adherido").val();
    var nombreLocal = jQuery("#local").val();
    if (nombreLocal!== null)nombreLocal_str=nombreLocal.join(',');
    
    var vista = jQuery("#vista").val();
    var agrupar = jQuery("#agrupar").val();
    var m2 = jQuery("#m2").val();
    var ipc = jQuery("#ipc").val();

    jQuery("#legend").hide();      
    jQuery("#legend_detalle").hide();      
    jQuery("#legend_resumen").hide();      

    if(vista=="detalle"){
      jQuery("#legend_detalle").show();      
      //jQuery("#legend").hide();      
    }else if(vista == "resumen" || vista == "facturacion_detalle"){
      jQuery("#legend_resumen").show();      
    }else{
      jQuery("#legend").show();      
    }


    jQuery.ajax({
           async: false,
           data: {'createdtime':createdtime,'familia':familia_str, 'rubro':rubro_str, 'localizacion': localizacion, 'adherido':adherido, 'nombreLocal':nombreLocal_str, 'vista':vista, 'm2':m2, 'agrupar':agrupar, 'ipc':ipc},
           url:  'index.php?module=Analisis&action=Contratos',
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