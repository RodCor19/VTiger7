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
<style type="text/css">
.jqplot-point-label{
  font-size: 1.25em;
  color: #CCC;
}
.jqplot-table-legend{
  font-size: 12px;
}
.jqplot-xaxis-tick{
  display: none;
}
</style>


<div class="detailViewContainer">
  <div class="row-fluid detailViewTitle">
    <span class="recordLabel font-x-x-large textOverflowEllipsis span pushDown" title="gika"><span class="">Programas Beneficios</span>&nbsp;</span>
  </div><div class="row-fluid detailViewTitle"><span class="row-fluid"><span class="muted">¿Cuál es el peso del programa Beneficios?
</span></span></div>
  <div class="detailViewInfo row-fluid">
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
     <div class="row-fluid" style="display:none;">
      
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
{*M E*}          <option value="{$local}">
{*M E*}              {$local}
{*M E*}            </option>
{*M E*}          {/foreach}
{*M E*}        </select>
{*M E*}      </span>
  </div>
  <div class="row-fluid">
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
          <label for="createdtime">
          Rango de Fechas (Consumo):
          </label>
        </span>
      </span>
      <span class="span3">
        <input type="text" name="createdtime" id="createdtime" value="{$date_range}" class="dateRange widgetFilter dateField" data-date-format="dd-mm-yyyy">
      </span>
      <span class="span3" style="display:none;">
        <span class="pull-left" style="padding-left:10px;padding-top:5px;">
          <label for="tipo">
            Locales Adheridos:
          </label>
        </span>
      </span>
      <span class="span3"  style="display:none;">
        <select class="widgetFilter" name="adherido" id="adherido" onChange="javascript:actualizar();">
          <option value="">{vtranslate('LBL_ALL', $MODULE_NAME)}</option>
          {foreach key=USER_ID item=USER_NAME from=$adheridos}
          <option value="{$USER_NAME}">
              {$USER_NAME}
            </option>
          {/foreach}
        </select>
      </span>
    </div>
    <div id="dashChartLoader" style="text-align:center;"><img src="layouts/vlayout/skins/softed/images/loading.gif" border="0" align="absmiddle"></div>
    <div id="" class="  details">

     {strip}
    {if count($DATA) gt 0 }
      <input class="widgetData" type=hidden value='{Vtiger_Util_Helper::toSafeHTML(ZEND_JSON::encode($DATA))}' />
      <div id="widgetChartContainer" class="widgetChartContainer" style="height:250px;width:45%;float:left;"></div>
      <div id="widgetChartContainer2" class="widgetChartContainer2" style="height:250px;width:45%;float:left;"></div>
    {else}
      <span class="noDataMsg">
        {vtranslate('LBL_NO')} {vtranslate($MODULE_NAME, $MODULE_NAME)} {vtranslate('LBL_MATCHED_THIS_CRITERIA')}
      </span>
    {/if}
    {/strip}

    <div class="row-fluid">
        <div class="span8" style="margin-left:5px;">
            <button style="float:left;" class="btn addButton" onclick="saveImage('widgetChartContainer','Programa Beneficios');"><i class="icon-download icon-white"></i>&nbsp;<strong>Guardar para Análisis de Datos</strong></button>
            <button id="widgetChartContainerView" style="float:left;display:none;margin-left:10px;"class="btn addButton" onclick=""><i class="icon-signal icon-white"></i>&nbsp;<strong>  Ver Análisis</strong></button>
            <div id="widgetChartContainerLoader" style="text-align:center;display:none;"><img src="layouts/vlayout/skins/softed/images/loading.gif" border="0" align="absmiddle"></div>
        </div>
    </div>
    </div>
  </div>
</div>

{literal}

<script type="text/javascript">
 
 
function  getChartRelatedData() {
      var jData = $('.widgetData').val();
      //jData="[[[40598],[10639]],[[48519994],[17938649]]]";
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
    chartData = getChartRelatedData();

     jQuery("#dashChartLoader").hide();    
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
         $(".dateRange").keydown(function (e)
        {
            e.preventDefault();
        });
    
  });
  
  var oTable=null;
  function graficar(){
      
     if (chartpar1) {
        chartpar1.destroy();
      }  

      jQuery('#widgetChartContainer').empty();
      jQuery('#widgetChartContainer2').empty();

    /*jQuery.jqplot.sprintf.thousandsSeparator = '.';
    jQuery.jqplot.sprintf.decimalMark = ',';*/
    var total= chartData[0][0][0]+chartData[0][1][0];
    (function($) { $.jqplot.LabelFormatter = function(format, val){ 
      return  (val / total * 100) + '%'; 
    }; })(jQuery); 

    $.jqplot.LabelFormatter = function(format, val) {
        return  (val / total * 100).toFixed(0) + '%';
    };
     $.jqplot.LabelFormatter2 = function(format, val) {
        return accounting.formatNumber(val,0,'.',',');
    };

    $.jqplot.config.enablePlugins = true;
    s1=chartData[0][0];
    s2=chartData[0][1];
    var pLabels1 = []; // arrays for each inner label
    var pLabels2 = [];
     for (var i = 0; i < s1.length; i++){
        pLabels1.push(s1[i]);
        pLabels2.push(s2[i]);

    }   

    chartpar1 = jQuery.jqplot('widgetChartContainer', chartData[0], 
      { 
        animate: true,
        animateReplot: true,
        title:"Programa Beneficios", 
        stackSeries: true,
        axes: {
          xaxis: {
              renderer: $.jqplot.CategoryAxisRenderer,
              tickOptions: {
                  show: false
              },
          },
          yaxis: {
            max:total,
            min:0,
             tickOptions: {formatString: '%s',
                formatter: $.jqplot.LabelFormatter}
               
          }
        },
        seriesColors:['#00B4E6', '#262673', '#73C774', '#C7754C', '#17BDB8'],
        seriesDefaults:{
          renderer:$.jqplot.BarRenderer,
          rendererOptions: {
              barMargin: 30,
              highlightMouseDown: true   ,
              smooth:true,
              barWidth: 100
          }
        },
       noDataIndicator: {
        show: true,
        indicator: 'No hay datos disponibles..'
      }
      ,
          highlighter: {
              show: true, 
              tooltipContentEditor: function (str, seriesIndex, pointIndex, plot) {
                  if(seriesIndex!=6){
                    var item = plot.data[seriesIndex][pointIndex];
                    var porcentaje=(item*100)/total;
                    porcentaje=Math.round(porcentaje);
                    var html = "<div>"+porcentaje+" %</div>";
                    return html;
                  }else{
                    return null;
                  }
              },
              sizeAdjust:5,
              tooltipLocation:'e' 
          }
      ,
      cursor: {
        show: false,
        
      },
      legend: {
          renderer: jQuery.jqplot.EnhancedLegendRenderer,
          show: true, 
          location: 's', 
          placement: 'outsideGrid',
          marginTop:'5px',
          // Breaks the ledgend into horizontal.
          rendererOptions: {
            numberRows: '1',
            numberColumns: '3'
          },
          seriesToggle: true
          
      },
       series:[
              {
                lineWidth:4,label:'IN',pointLabels:{
                    show:true,
                    labels:pLabels1,
                    labelsFromSeries:false,
                    formatString: '%s',
                    formatter: $.jqplot.LabelFormatter2
                }
              }, 
              {
                lineWidth:4,label:'OUT',pointLabels:{
                    show:true,
                    labels:pLabels2,
                    labelsFromSeries:false,
                    formatString: '%s',
                    formatter: $.jqplot.LabelFormatter2                }
              }
         
         
            ]
      }
    );

    sm1=chartData[1][0];
    sm2=chartData[1][1];
    var pLabelsm1 = []; // arrays for each inner label
    var pLabelsm2 = [];
     for (var i = 0; i < sm1.length; i++){
        pLabelsm1.push(sm1[i]);
        pLabelsm2.push(sm2[i]);

    }   

    var total2= chartData[1][0][0]+chartData[1][1][0];
    (function($) { $.jqplot.LabelFormatter = function(format, val){ 
      return (val / total2 * 100) + '%'; 
    }; })(jQuery); 

    $.jqplot.LabelFormatter3 = function(format, val) {
        return  (val / total2 * 100).toFixed(0) + '%';
    };
   

    chartpar1 = jQuery.jqplot('widgetChartContainer2', chartData[1], 
      { 
        animate: true,
        animateReplot: true,
        title:"Consumo", 
        stackSeries: true,
        axes: {
          xaxis: {
              renderer: $.jqplot.CategoryAxisRenderer,
              tickOptions: {
                  show: false
              },
          },
          yaxis: {
            max:total2,
            min:0,
             tickOptions: {formatString: '%s',
                formatter: $.jqplot.LabelFormatter3}
               
          }
        },
        seriesColors:['#00B4E6', '#262673', '#73C774', '#C7754C', '#17BDB8'],
        seriesDefaults:{
          renderer:$.jqplot.BarRenderer,
          rendererOptions: {
              barMargin: 30,
              highlightMouseDown: true   ,
              smooth:true,
              barWidth: 100
          }
        },
       noDataIndicator: {
        show: true,
        indicator: 'No hay datos disponibles..'
      }
      ,
          highlighter: {
              show: true, 
              tooltipContentEditor: function (str, seriesIndex, pointIndex, plot) {
                  if(seriesIndex!=6){
                    var item = plot.data[seriesIndex][pointIndex];
                    var porcentaje=(item*100)/total2;
                    porcentaje=Math.round(porcentaje);
                    var html = "<div>"+porcentaje+" %</div>";
                    return html;
                  }else{
                    return null;
                  }
              },
              sizeAdjust:5,
              tooltipLocation:'e' 
          }
      ,
      cursor: {
        show: false,
        
      },
      legend: {
          renderer: jQuery.jqplot.EnhancedLegendRenderer,
          show: true, 
          location: 's', 
          placement: 'outsideGrid',
          marginTop:'5px',
          // Breaks the ledgend into horizontal.
          rendererOptions: {
            numberRows: '1',
            numberColumns: '3'
          },
          seriesToggle: true
          
      },
       series:[
              {
                lineWidth:4,label:'IN',pointLabels:{
                    show:true,
                    labels:pLabelsm1,
                    labelsFromSeries:false,
                    formatString: '%s',
                    formatter: $.jqplot.LabelFormatter2
                }
              }, 
              {
                lineWidth:4,label:'OUT',pointLabels:{
                    show:true,
                    labels:pLabelsm2,
                    labelsFromSeries:false,
                    formatString: '%s',
                    formatter: $.jqplot.LabelFormatter2                }
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


    var familia = jQuery('#familias').children('option:selected').val();
    var rubro = jQuery('#rubro').children('option:selected').val();
    var adherido = jQuery('#adherido').children('option:selected').val();
    var localizacion = jQuery('#localizacion').children('option:selected').val();
    var nombreLocal = jQuery('#local').children('option:selected').val();
     jQuery("#dashChartLoader").show();    
    jQuery("#widgetChartContainer").hide(); 
    jQuery("#widgetChartContainer2").hide(); 
    jQuery.ajax({
           async: true,
/*M E*/            data: {'createdtime':createdtime,'familia':familia,'rubro':rubro,'adherido':adherido,'localizacion':localizacion, 'nombreLocal':nombreLocal},
           url:  'index.php?module=Analisis&view=Beneficios&mode=Ajax',
           dataType:"json",
           success: function(data) {
             arr=data;
              //var data = JSON.parse(arr);
              var chartDataAux = [];
              var arrayLength = data[0].length;
              if(arrayLength>0){
                
                chartData=data;
                //chartData="[[[40598],[10639]],[[48519994],[17938649]]]";
                 jQuery("#dashChartLoader").hide();    
                 jQuery("#widgetChartContainer").show();  
                 jQuery("#widgetChartContainer2").show();  
                graficar();
              }else{
                chartData=chartDataAux;
                 jQuery("#dashChartLoader").hide();    
                jQuery("#widgetChartContainer").show();  
                jQuery("#widgetChartContainer2").show();  
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