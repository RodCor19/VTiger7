{strip}
<div class="dashboardWidgetHeader">
  {include file="dashboards/WidgetHeader.tpl"|@vtemplate_path:$MODULE_NAME}
</div>
<div class="dashboardWidgetContent">
  <div class="detailViewContainer">
    <div class="detailViewInfo row-fluid">
      <div id="dashChartLoaderPareto" style="text-align:center;">
        <img src="layouts/vlayout/skins/softed/images/loading.gif" border="0" align="absmiddle">
      </div>
      <div id="" class="  details">
        {if count($DATA) gt 0 }
          <input class="widgetDataPareto" type=hidden value='{Vtiger_Util_Helper::toSafeHTML(ZEND_JSON::encode($DATA))}' />
          <div id="widgetChartContainerPareto" class="widgetChartContainerPareto" style="height:250px;width:85%"></div>
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
      var jData = $('.widgetDataPareto').val();
      var data = JSON.parse(jData);
      return data;
    }
  
  var chartData;var chartpar1=null;

  $( document ).ready(function() {
    chartData = getChartRelatedData();
     jQuery("#dashChartLoaderPareto").hide();    
    graficar();
    
  });
  
  var oTable=null;
  function graficar(){
    chartData = getChartRelatedData();
     jQuery("#dashChartLoaderPareto").hide();  
      
     if (chartpar1) {
        chartpar1.destroy();
      }  

      jQuery('#widgetChartContainerPareto').empty();

    chartpar1 = jQuery.jqplot('widgetChartContainerPareto', chartData, 
      { 
        animate: true,
        animateReplot: true,
        title:"Pareto de los Clientes", 
        axes:{
            xaxis:{
                //tickOptions: { formatString: ' %Y/%m' },
                autoscale:true,
                min:0,
                label: "Cantidad de Clientas",
                tickOptions:{formatString: "%'.0f"},
                 tickInterval: 500
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
              formatString:"%s / Clientas : %s"
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

</script>


{/literal}
{/strip}