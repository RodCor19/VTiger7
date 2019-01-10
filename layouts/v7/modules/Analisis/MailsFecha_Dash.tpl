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
    <div class="detailViewContainer">
        <div class="detailViewInfo row-fluid">
            <div class="  details">
                {if count($DATA) gt 0 }
                    <input class="widgetDataMPP" type=hidden value='{Vtiger_Util_Helper::toSafeHTML(ZEND_JSON::encode($DATA))}' />
                    <div id="widgetChartContainerMPP" class="widgetChartContainerMPP" style="height:250px;width:85%"></div>
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
    var jData = jQuery('.widgetDataMPP').val();
    var data = JSON.parse(jData);
    return data;
}
var chartData;var chartmf=null;
jQuery( document ).ready(function() {
    chartData = getChartRelatedData();
    graficar();
});

function graficar(){
    var total = 0;

    var myLabels = [];
    var arrayLength = chartData.length;
    if(arrayLength>0){
        myLabels = jQuery.map( chartData, function( value, index ) {
            return value[1] ;
        });
    }

    if (chartmf) {
        chartmf.destroy();
    }  

    jQuery('#widgetChartContainerMPP').empty();

    chartmf= jQuery.jqplot('widgetChartContainerMPP',[chartData], {    
        animate: !jQuery.jqplot.use_excanvas,
        seriesDefaults:{
            renderer:jQuery.jqplot.BarRenderer,
            rendererOptions: {
                showDataLabels: true,barWidth: 1, 
                dataLabels: myLabels
            },
            pointLabels: { show: true, location: 'n', edgeTolerance: 10 }
        },

        axesDefaults: {
            tickRenderer: jQuery.jqplot.CanvasAxisTickRenderer ,
            tickOptions: {
                fontSize: '10pt'
            }
        },
        axes: {
            xaxis: {
                renderer: jQuery.jqplot.CategoryAxisRenderer,autoscale:true,
                label: "Emails",
                min:0
            },
            yaxis:{
                labelRenderer: jQuery.jqplot.CanvasAxisLabelRenderer,autoscale:true,
                label: "Personas",
                min:0
            }     
        },
        noDataIndicator: {
            show: true,
            indicator: 'No hay datos disponibles..'
        }
    });
} 
</script>
{/literal}
{/strip}