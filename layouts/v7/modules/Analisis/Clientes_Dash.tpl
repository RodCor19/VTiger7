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
<div class="dashboardWidgetHeader">
    {include file="dashboards/WidgetHeader.tpl"|@vtemplate_path:$MODULE_NAME}
</div>
<div class="dashboardWidgetContent">
    <div class="  details">
        {if count($DATA) gt 0 }
            <input class="widgetDataClientes" type=hidden value='{Vtiger_Util_Helper::toSafeHTML(ZEND_JSON::encode($DATA))}' />
            <div id="dashChartLoaderClientes" style="text-align:center;"><img src="layouts/vlayout/skins/softed/images/loading.gif"   border="0" align="absmiddle"></div>
            <div id="widgetChartContainerClientes" style="height:400px;width:85%"></div>
            <div id="widgetChartContainer2Clientes" style="height:400px;width:85%"></div>
        {else}
            <span class="noDataMsg">
                {vtranslate('LBL_NO')} {vtranslate($MODULE_NAME, $MODULE_NAME)} {vtranslate('LBL_MATCHED_THIS_CRITERIA')}
            </span>
        {/if}
    </div>
</div>
{literal}
<script type="text/javascript">

    var chartData;
    $( document ).ready(function() {
        var jData = $('.widgetDataClientes').val();
        chartData = JSON.parse(jData);
        jQuery("#dashChartLoaderClientes").hide();    
        graficar();

    });
    var chartexm1,chartexm2=null;

    function graficar(){
        if (chartexm1) {
            chartexm1.destroy();
        }

        chartexm1 = jQuery.jqplot('widgetChartContainerClientes', chartData, 
        { 
            animate: true,
            animateReplot: true,
            title:"Clientes", 
            axes:{
                xaxis:{
                    renderer: jQuery.jqplot.CategoryAxisRenderer
                },
            
            yaxis:{
                autoscale:true,
                tickOptions:{showGridline:false,formatString: "%'.0f"},
                labelRenderer: jQuery.jqplot.CanvasAxisLabelRenderer,
                label: "Total",min:0,
            }
        },
        seriesColors:['#FB9869', '#5D9FB8', '#73C774', '#C7754C', '#17BDB8'],
            highlighter: {
                show: true, 
                showLabel: true, 
                tooltipAxes: 'y',
                sizeAdjust: 7.5 , tooltipLocation : 'nw',
            },
                // Set default options on all series, turn on smoothing.
        seriesDefaults: {
            rendererOptions: {
                smooth: true
            }
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
        noDataIndicator: {
            show: true,
            // Here, an animated gif image is rendered with some loading text.
            indicator: 'No hay datos disponibles..'
        },
        series:[
            {
                lineWidth:4,highlighter: {formatString:"Total = %s"},label:'Clientes Nuevos', yaxis: 'yaxis',
            }, 
            {
                lineWidth:4,highlighter: {formatString:"Total = %s"},label:'Clientes Registrados', yaxis: 'yaxis',
            },
         {yaxis: 'yaxis'}
         
        ]
      }
    );


  }
</script>
{/literal}
{/strip}