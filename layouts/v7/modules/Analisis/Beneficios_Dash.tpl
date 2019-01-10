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

<div class="dashboardWidgetHeader">
    {include file="dashboards/WidgetHeader.tpl"|@vtemplate_path:$MODULE_NAME}
</div>
<div class="dashboardWidgetContent">
  <div class="detailViewContainer">
    <div class="detailViewInfo row-fluid">
      <div id="dashChartLoaderBeneficios" style="text-align:center;"><img src="layouts/vlayout/skins/softed/images/loading.gif" border="0" align="absmiddle"></div>
      <div id="" class="  details">
      {if count($DATA) gt 0 }
        <input class="widgetDataBeneficios" type=hidden value='{Vtiger_Util_Helper::toSafeHTML(ZEND_JSON::encode($DATA))}' />
        <div id="widgetChartContainerBeneficios" class="widgetChartContainerBeneficios" style="height:250px;width:45%;float:left;"></div>
        <div id="widgetChartContainer2Beneficios" class="widgetChartContainer2Beneficios" style="height:250px;width:45%;float:left;"></div>
      {else}
        <span class="noDataMsg">
          {vtranslate('LBL_NO')} {vtranslate($MODULE_NAME, $MODULE_NAME)} {vtranslate('LBL_MATCHED_THIS_CRITERIA')}
        </span>
      {/if}
      </div>
    </div>
  </div>
</div>

{literal}

<script type="text/javascript">
 
 
function  getChartRelatedData() {
      var jData = jQuery('.widgetDataBeneficios').val();
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
  jQuery( document ).ready(function() {
    //jQuery.noConflict();      
    chartData = getChartRelatedData();

     jQuery("#dashChartLoaderBeneficios").hide();    
    graficar();
   
  });
  
  var oTable=null;
  function graficar(){
      
     if (chartpar1) {
        chartpar1.destroy();
      }  

      jQuery('#widgetChartContainerBeneficios').empty();
      jQuery('#widgetChartContainer2Beneficios').empty();

    /*jQuery.jqplot.sprintf.thousandsSeparator = '.';
    jQuery.jqplot.sprintf.decimalMark = ',';*/
    var total= chartData[0][0][0]+chartData[0][1][0];
    (function(jQuery) { jQuery.jqplot.LabelFormatter = function(format, val){ return  (val / total * 100) + '%'; }; })(jQuery); 

    jQuery.jqplot.LabelFormatter = function(format, val) {
        return  (val / total * 100).toFixed(0) + '%';
    };
     jQuery.jqplot.LabelFormatter2 = function(format, val) {
       return accounting.formatNumber(val,0,'.',',');
    };

    jQuery.jqplot.config.enablePlugins = true;
    s1=chartData[0][0];
    s2=chartData[0][1];
    var pLabels1 = []; // arrays for each inner label
    var pLabels2 = [];
     for (var i = 0; i < s1.length; i++){
        pLabels1.push(s1[i]);
        pLabels2.push(s2[i]);

    }   

    chartpar1 = jQuery.jqplot('widgetChartContainerBeneficios', chartData[0], 
      { 
        animate: true,
        animateReplot: true,
        title:"Programa Beneficios", 
        stackSeries: true,
        axes: {
          xaxis: {
              renderer: jQuery.jqplot.CategoryAxisRenderer,
              tickOptions: {
                  show: false
              },
          },
          yaxis: {
            max:total,
            min:0,
             tickOptions: {formatString: '%s',
                formatter: jQuery.jqplot.LabelFormatter2}
               
          }
        },
        seriesColors:['#00B4E6', '#262673', '#73C774', '#C7754C', '#17BDB8'],
        seriesDefaults:{
          renderer:jQuery.jqplot.BarRenderer,
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
                    formatter: jQuery.jqplot.LabelFormatter2
                }
              }, 
              {
                lineWidth:4,label:'OUT',pointLabels:{
                    show:true,
                    labels:pLabels2,
                    labelsFromSeries:false,
                    formatString: '%s',
                    formatter: jQuery.jqplot.LabelFormatter2                }
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
    (function(jQuery) { jQuery.jqplot.LabelFormatter = function(format, val){ return  (val / total2 * 100) + '%'; }; })(jQuery); 

    jQuery.jqplot.LabelFormatter3 = function(format, val) {
        return  (val / total2 * 100).toFixed(0) + '%';
    };
   

    chartpar1 = jQuery.jqplot('widgetChartContainer2Beneficios', chartData[1], 
      { 
        animate: true,
        animateReplot: true,
        title:"Consumo", 
        stackSeries: true,
        axes: {
          xaxis: {
              renderer: jQuery.jqplot.CategoryAxisRenderer,
              tickOptions: {
                  show: false
              },
          },
          yaxis: {
            max:total2,
            min:0,
             tickOptions: {formatString: '%s',
                formatter: jQuery.jqplot.LabelFormatter3}
               
          }
        },
        seriesColors:['#00B4E6', '#262673', '#73C774', '#C7754C', '#17BDB8'],
        seriesDefaults:{
          renderer:jQuery.jqplot.BarRenderer,
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
                    formatter: jQuery.jqplot.LabelFormatter2
                }
              }, 
              {
                lineWidth:4,label:'OUT',pointLabels:{
                    show:true,
                    labels:pLabelsm2,
                    labelsFromSeries:false,
                    formatString: '%s',
                    formatter: jQuery.jqplot.LabelFormatter2                }
              }
         
         
            ]
      }
    );

      


  }
  

</script>


{/literal}