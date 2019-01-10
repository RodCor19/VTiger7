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
  <div class="row-fluid detailViewTitle">
    <span class="recordLabel font-x-x-large textOverflowEllipsis span pushDown" title="gika"><span class="">Pareto de los Locales</span>&nbsp;</span>
   
  </div>
  <div class="row-fluid detailViewTitle">
  <span class="row-fluid">
    <span class="muted">(eje x = cantidad de locales)</span>
  </span></div>
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
          <option value="ventas" selected="">Ventas</option>
          <option value="arr">Arrendamiento</option>
          <option value="m2">M2</option>
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
            Formato:
          </label>
        </span>
      </span>
      <span class="span3">
        <select class="widgetFilter" name="formato" id="formato" onChange="javascript:elegirFormato();">
          <option value="grafica" selected="">Gráfica</option>
          <option value="tabla">Tabla</option>
        </select>
      </span>
    </div>    
  <div id="dashChartLoader" style="text-align:center;"><img src="layouts/vlayout/skins/softed/images/loading.gif" border="0" align="absmiddle"></div>
    <div id="" class="  details">

     {strip}
      <input class="widgetData" type=hidden value='{Vtiger_Util_Helper::toSafeHTML(ZEND_JSON::encode($DATA))}' />
      <div id="widgetChartContainer" class="widgetChartContainer" style="height:250px;width:85%;padding-left:10px;padding-top:10px;"></div>
    {/strip}

    <div class="row-fluid">
        <div class="span8" style="margin-left:5px;">
       <a class="btn addButton" id="bot-exportar-excel" href="#" style="padding:4px 6px;float:left;font-weight:bold;margin-right:5px;font-family:'Helvetica Neue', Helvetica, Arial, sans-serif;" onclick=" tableToExcel('result','Pareto Locales');">  Exportar a Excel  </a>
            <button style="float:left;" class="btn addButton" onclick="guardarAnalisis();"><i class="icon-download icon-white"></i>&nbsp;<strong>Guardar para Análisis de Datos</strong></button>
            <button id="widgetChartContainerView" style="float:left;display:none;margin-left:10px;"class="btn addButton" onclick=""><i class="icon-signal icon-white"></i>&nbsp;<strong>  Ver Análisis</strong></button>
            <div id="widgetChartContainerLoader" style="text-align:center;display:none;"><img src="layouts/vlayout/skins/softed/images/loading.gif" border="0" align="absmiddle"></div>
            <button id="resultView" style="float:left;display:none;margin-left:10px;"class="btn addButton" onclick=""><i class="icon-signal icon-white"></i>&nbsp;<strong>  Ver Análisis</strong></button>
            <div id="resultLoader" style="text-align:center;display:none;"><img src="layouts/vlayout/skins/softed/images/loading.gif" border="0" align="absmiddle"></div>
        </div>
        <form action="download.php" method="post" target="_blank" id="FormularioExportacion">
            <input type="hidden" id="datos_a_enviar" name="datos_a_enviar" />
            <input type="hidden" id="nombre_a_enviar" name="nombre_a_enviar" />
            <input type="hidden" id="is_submited" name="is_submited" />
          </form>
    </div>
    </div>
</div>

{literal}

<script type="text/javascript">
 
 
function  getChartRelatedData() {
      var jData = $('.widgetData').val();
      var data = JSON.parse(jData);
      /*var chartDataAux = [];
      for(var index in data) {
        var row = data[index];
        var rowData = [row.name, parseInt(row.count), row.id];
        chartDataAux.push(rowData);
      }*/
      return data;
    }
  var chartData;var chartpar1=null;
  $( document ).ready(function() {
    //jQuery.noConflict();   
    actualizar();   
    //chartData = getChartRelatedData();
     //jQuery("#dashChartLoader").hide();    
    //graficar();
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
  var arr=null;
  function graficar(){
      
     if (chartpar1) {
        chartpar1.destroy();
      }  

      jQuery('#widgetChartContainer').empty();

    /*jQuery.jqplot.sprintf.thousandsSeparator = '.';
    jQuery.jqplot.sprintf.decimalMark = ',';*/

    chartpar1 = jQuery.jqplot('widgetChartContainer', chartData, 
      { 
        animate: true,
        animateReplot: true,
        title:"Pareto de los Cuentas", 
        axes:{
            xaxis:{
                //tickOptions: { formatString: ' %Y/%m' },
                autoscale:true,
                min:0,
                label: "Cantidad de Locales",
                tickOptions:{formatString: "%'.0f"},
                 tickInterval: 10
            },
            yaxis:{  
               //renderer:jQuery.jqplot.DateAxisRenderer ,    
              autoscale:true,
              tickOptions:{formatString: "% %.0f"},
              min:0, max:100
            }
        },
        seriesColors:['#17BDB8', '#5D9FB8', '#73C774', '#C7754C', '#17BDB8'],
          highlighter: {
              show: true, 
              showLabel: true, 
              tooltipAxes: 'yx',
              sizeAdjust: 7.5 , tooltipLocation : 'nw',
              formatString:"%s / Locales : %s"
          },
                // Set default options on all series, turn on smoothing.
        seriesDefaults: {
            rendererOptions: {
                smooth: true
            }
        },
       noDataIndicator: {
        show: true,
        // Here, an animated gif image is rendered with some loading text.
        indicator: 'No hay datos disponibles..'
      },

       series:[
              {
                lineWidth:2
              }
            ]
      }
    );

      


  }
  
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
    var m2 = jQuery("#m2").val();
    
    jQuery("#dashChartLoader").show();    
    jQuery("#widgetChartContainer").hide(); 
    jQuery.ajax({
           async: false,
           data: {'createdtime':createdtime,'familia':familia_str, 'rubro':rubro_str, 'localizacion': localizacion, 'adherido':adherido, 'nombreLocal':nombreLocal_str, 'vista':vista, 'm2':m2},
           url:  'index.php?module=Analisis&action=ParetoCuentas',
           dataType:"json",
           success: function(data) {
             arr=data;
              //var data = JSON.parse(arr);
              var chartDataAux = [];
              var arrayLength = data[0][0].length;
              if(arrayLength>0){
                chartData=data;
              }else{
                chartData=chartDataAux;
              }
              jQuery("#dashChartLoader").hide();    
              jQuery("#widgetChartContainer").show();  
              elegirFormato();
              
           },
            error: function (xhr, ajaxOptions, thrownError) {
              console.log(thrownError);
          }
         });
  }
  function elegirFormato(){
    var formato = jQuery("#formato").val();
    if(formato=='grafica'){
      chartData=arr[0];
      graficar();
      jQuery("#bot-exportar-excel").hide();
    }else{
      chartData=arr[1];
      graficarTabla(chartData);
      jQuery("#bot-exportar-excel").show();
    }
    
  }
  function guardarAnalisis(){
    var formato = jQuery("#formato").val();
    if(formato=='grafica'){
      saveImage('widgetChartContainer','Pareto de Locales');
    }else{
      saveExcel('result','result','Pareto de Locales');
    }
    
  }

  function graficarTabla (chartData)
{
    // Muestro los datos en formato de tabla
    // Uso las clases de Bootstrap para darle estilo
    jQuery('#widgetChartContainer').empty().css('height', 'auto');
    var vista = jQuery("#vista option:selected").text();
    
    var $tabla = jQuery('<table id="result" class="table table-striped table-condensed dataTable" cellspacing="0" width="75%"><thead><tr></tr></thead><tbody><tr></tr></tbody></table>'), // Creo DOM de la tabla con jQuery
        $cabecera = $tabla.find('thead > tr'), // Ref jQuery a fila de cabecera
        $cuerpo = $tabla.find('tbody'), // Ref jQuery al cuerpo
        simbolo = '';

    // Construyo la cabecera
    if (chartData.length > 0)
    {
        $cabecera.append('<th style="color:#0070BA">Local</th>');
        $cabecera.append('<th style="color:#0070BA">% Participación</th>');
        $cabecera.append('<th style="color:#0070BA">'+vista+'</th>');

        /*for (var i = 0, l = chartData.length; i < l; i++)
        {
            $cabecera.append('<th>' + pactual[i][0] + '</th>');
        }*/
    }

    

    // Armo las filas
    
    if (chartData.length > 0)
    {
        var fant = '<tr>';

        for (var i = 0, l = chartData.length; i < l-1; i++)
        {
            fant += '<td>' + chartData[i][0] + '</td>';
            fant += '<td>' + formatoMoneda(chartData[i][1]) + '</td>';
            fant += '<td>' + formatoMoneda(chartData[i][2]) + '</td>';
          fant += '</tr><tr>';
        }

        

        $cuerpo.append(fant);
    }

    
    // Inserto la tabla en el DOM    
    jQuery('#widgetChartContainer').append($tabla);

    // Dejo visible al botón "Exportar a Excel"
    //jQuery("#bot-exportar-excel").show();
}
function formatoMoneda (val)
{
  /*  var arr = [],
        str = valor + "";

    while (str.length > 3)
    {
        arr.push(str.substr(-3, 3));
        str = str.substr(0, str.length - 3);
    }

    arr.push(str);
    arr.reverse();
    return arr.join('.');*/
      num=parseFloat(val);
    var p = num.toFixed(2).split(".");
    return p[0].split("").reverse().reduce(function(acc, num, i, orig) {
        return  num + (i && !(i % 3) ? "." : "") + acc;
    }, "") + "," + p[1]; 
}

</script>


{/literal}