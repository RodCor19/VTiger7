{strip}
<div class="plot" id="chart"></div>
{/strip}
{literal}
<script type="text/javascript">
  $(document).ready(function(){
    $.jqplot.config.enablePlugins = true;
    var plot1 = $.jqplot('chart', [{/literal}{json_encode($valores)}{literal}], {
        axes:{
            xaxis:{
                renderer:$.jqplot.DateAxisRenderer, 
                rendererOptions:{
                    tickRenderer:$.jqplot.CanvasAxisTickRenderer
                },
                tickOptions:{formatString:'%m\'%Y'},
                label: '{/literal}{vtranslate("LBL_MONTHS_TYPE", $MODULE_NAME)}{literal}',
                interval: '1 month'
            },
            yaxis:{
                rendererOptions:{
                    tickRenderer:$.jqplot.CanvasAxisTickRenderer
                },
                tickOptions: {
                    formatString: "$%'d"
                },
            }
        },
        cursor:{
            zoom:true,
            looseZoom: true
        }
    });
 
});
</script>
{/literal}