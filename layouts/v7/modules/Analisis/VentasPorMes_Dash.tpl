{strip}
<div class="dashboardWidgetHeader">
    {include file="dashboards/WidgetHeader.tpl"|@vtemplate_path:$MODULE_NAME} 
</div>
<div class="dashboardWidgetContent" style="height:150px!important;">
<style type="text/css">
.jqplot-point-label{
  font-size: 1.25em;
  color: #CCC;
}
.jqplot-table-legend{
  font-size: 12px;
}
.jqplot-xaxis-tick{
  /*display: none;*/
}
</style>


<div class="detailViewContainer">
  <div class="row-fluid detailViewTitle">
   
   <div id="dashChartLoader" style="text-align:center;"><img src="layouts/vlayout/skins/softed/images/loading.gif" border="0" align="absmiddle"></div>

    <div class="span12" style="overflow: hidden">
        <input class="widgetData" type=hidden value='{Vtiger_Util_Helper::toSafeHTML(ZEND_JSON::encode($DATA))}' />
        <div id="widgetChartContainerVM" style="height:250px;width:85%"></div>
        <div id="widgetChartContainer2VMVM" style="height:250px;width:85%"></div>
    </div>

  </div>
</div>
  </div>
{literal}

<script type="text/javascript">

    function diffMeses(){
        if (jQuery("#createdtime").val()!=""){
            var aFecha1 = jQuery("#fecha1").val().split("-"); 
            var aFecha2 = jQuery("#fecha2").val().split("-"); 
            var fFecha1 = Date.UTC(aFecha1[0],aFecha1[1]-1,aFecha1[2]); 
            var fFecha2 = Date.UTC(aFecha2[0],aFecha2[1]-1,aFecha2[2]); 
            var dif = fFecha2 - fFecha1;
            var dias = Math.floor(dif / (1000 * 60 * 60 * 24)); 
            console.log(dias);
            if (dias>364 || dias < 0)
                return false;
        }
        return true;
    }

    function getDatos(){

        var fecha = "actual";
        
        var unidades = "pesos";
        
        var acumulado = "false";

        var perimetro = "false";

        var familia = "";
        var rubro = "";
        var localizacion = "";
        var adherido = "";
        var nombreLocal = "";
        var formapago = "";

        var ret;

        jQuery.ajax({
            async: true,
/*M E*/            data:  {'unidades':unidades, 'acumulado': acumulado, 'perimetro': perimetro, 'familia':familia, 'rubro':rubro, 'localizacion': localizacion, 'adherido':adherido, 'fecha':fecha, 'nombreLocal':nombreLocal, 'formapago':formapago},
            url: 'index.php?module=Analisis&action=FiltrarGraficaMes',
            type:  'post',
            success:  function (response) {
                ret=  JSON.parse(response);
                arr=ret;
                var arrayLength = arr[0][0].length;
                actual = arr[1];
                anterior = arr[2];
                jQuery("#dashChartLoader").hide();    
                jQuery("#widgetChartContainerVM").show();
                jQuery('.widgetData').val(JSON.stringify(arr[0]));
                var jData = jQuery('.widgetData').val();
      chartData = JSON.parse(jData)

      var yaxislbl = '$';
      if(jQuery("#radioUnidades").attr('checked')=="checked"){
          yaxislbl  = 'U';
      }        
      
      formato="%'.0f";
      if(jQuery("#radioIpc").attr('checked')=="checked"){
        formato="%'.2f";
      }  

      if (chartexm1VM) {
          chartexm1VM.destroy();
      }  
      
      jQuery('#widgetChartContainerVM').empty();
      jQuery('#widgetChartContainer2VM').empty();
      chartexm1VM = jQuery.jqplot('widgetChartContainerVM', chartData,
      { 
          animate: true,
          animateReplot: true,
          title:"Ventas", 
          axes:{
              xaxis:{
                  renderer: $.jqplot.CategoryAxisRenderer
              },
              
            yaxis:{
                autoscale:true,
                tickOptions:{showGridline:false,formatString: formato},          
                labelOptions: {
                    fontSize: '30pt'
                },
                label: yaxislbl,min:0,
              }
          },
          seriesColors:['#FB9869', '#5D9FB8'],
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
                      /*show: true,
                      location: 'ne'*/
                      
              renderer: jQuery.jqplot.EnhancedLegendRenderer,
              show: true, 
              location: 's', 
              placement: 'outsideGrid',
              marginTop:'15px',
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
                lineWidth:4,highlighter: {formatString: yaxislbl+' = %s'},label:'<span style="color:#FB9869; border: 1px solid"> '+actual+'</span>', yaxis: 'yaxis',
              }, 
              {
                  lineWidth:4,highlighter: {formatString: yaxislbl+' = %s'},label:'<span style="color:#5D9FB8; border: 1px solid"> '+anterior+'</span>', yaxis: 'yaxis',
              },
            {yaxis: 'yaxis'}
           
          ]
      }
    );
            }
        });/*
        console.log(ret);*/
        return ret;
    } 

    var chartData;var chartpar1=null;
    $( document ).ready(function() {
        jQuery("#dashChartLoader").hide();   
        //No hacer así, se llama 3 veces a la consulta
        /*jQuery("#radioPesos").click();
        jQuery("#radioNoAcumulado").click();
        jQuery("#radioNoPerimetro").click();*/
        actualizar();
        var dateRangeElement = jQuery('.dateRange');
        var dateChanged = false;
        if(dateRangeElement.length <= 0) {
            return;
        }
        var customParams = {
            calendars: 1,
            mode: 'datepicker',
            className : 'rangeCalendar',
            onChange: function(formated) {
                dateChanged = true;
                var element = jQuery(this).data('datepicker').el;
                jQuery(element).val(formated);
                jQuery("#dashChartLoader").focus();
            },
            onHide : function() {
                if(dateChanged){
                    if (jQuery("#fecha1").val() != "" && jQuery("#fecha2").val() != "") {
                        if (diffMeses()) {
                            actualizar();
                            dateChanged = false;
                        }
                        else{
                            var bootBoxModal = bootbox.alert("Se deben seleccionar un maximo de 12 meses");
                                bootBoxModal.on('hidden',function(e){
                                if(jQuery('#globalmodal').length > 0) {
                                    jQuery('body').addClass('modal-open');
                                }
                            })
                            jQuery("#createdtime").val("");
                        }
                    }
                    else{
                      jQuery(".datepicker").hide();
                    }
                }
            },
            onBeforeShow : function(elem) {
                jQuery(elem).css('z-index','3');
            },  
        }
        dateRangeElement.addClass('dateField').attr('data-date-format',"yyyy-mm-dd");
        app.registerEventForDatePickerFields(dateRangeElement,false,customParams);  
        jQuery(".dateRange").keydown(function (e)
        {
            e.preventDefault();
        });

    });

    var chartexm1VM,chartexm2=null;
  
    var actual, anterior;

    function actualizar(){
        jQuery("#dashChartLoader").show();    
        jQuery("#widgetChartContainerVM").hide(); 
        arr=getDatos();
     
    }


  function graficar(){
      var jData = jQuery('.widgetData').val();
      chartData = JSON.parse(jData)

      var yaxislbl = '$';
      if(jQuery("#radioUnidades").attr('checked')=="checked"){
          yaxislbl  = 'U';
      }        
      
      formato="%'.0f";
      if(jQuery("#radioIpc").attr('checked')=="checked"){
        formato="%'.2f";
      }  

      if (chartexm1VM) {
          chartexm1VM.destroy();
      }  
      
      jQuery('#widgetChartContainerVM').empty();
      jQuery('#widgetChartContainer2VM').empty();
      chartexm1VM = jQuery.jqplot('widgetChartContainerVM', chartData,
      { 
          animate: true,
          animateReplot: true,
          title:"Ventas", 
          axes:{
              xaxis:{
                  renderer: $.jqplot.CategoryAxisRenderer
              },
              
            yaxis:{
                autoscale:true,
                tickOptions:{showGridline:false,formatString: formato},          
                labelOptions: {
                    fontSize: '30pt'
                },
                label: yaxislbl,min:0,
              }
          },
          seriesColors:['#FB9869', '#5D9FB8'],
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
                      /*show: true,
                      location: 'ne'*/
                      
              renderer: jQuery.jqplot.EnhancedLegendRenderer,
              show: true, 
              location: 's', 
              placement: 'outsideGrid',
              marginTop:'15px',
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
                lineWidth:4,highlighter: {formatString: yaxislbl+' = %s'},label:'<span style="color:#FB9869; border: 1px solid"> '+actual+'</span>', yaxis: 'yaxis',
              }, 
              {
                  lineWidth:4,highlighter: {formatString: yaxislbl+' = %s'},label:'<span style="color:#5D9FB8; border: 1px solid"> '+anterior+'</span>', yaxis: 'yaxis',
              },
            {yaxis: 'yaxis'}
           
          ]
      }
    );


  }

</script>


{/literal}