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
  /*display: none;*/
}
</style>
<div class="detailViewContainer">
<div class="row-fluid detailViewTitle"  style="padding-bottom:10px;">
    <span class="recordLabel font-x-x-large textOverflowEllipsis span pushDown" title="gika"><span class="">Desempeño de la Promoción</span>&nbsp;</span>
  </div>
<div class="row-fluid">
    <span class="span3">
      <span class="pull-left" style="padding-left:10px;padding-top:5px;">
        <label for="createdtime">
      Fecha entre:
        </label>
      </span>
    </span>
    <span class="span3"><input type="text" name="createdtime" id="createdtime" value="{$date_range}" class="dateRange widgetFilter dateField" data-date-format="dd-mm-yyyy">
    <input type="hidden" name="rangomes" id="rangomes" value="{$date_range}" >
    <input type="hidden" name="rangodia" id="rangodia" value="{$date_dia}" >
    </span>
    <span class="span3">
        <span class="pull-left" style="padding-left:10px;padding-top:5px;">
          <label for="tipo">
            Promoción:
          </label>
        </span>
      </span>
      <span class="span3">
        {strip}
          {assign var=PICKLIST_VALUES value=$promociones}
          <select id="promocion" multiple class="select2" name="promocion" style="width: 73%" onChange="javascript:actualizar();">
              {foreach item=PICKLIST_VALUE from=$PICKLIST_VALUES}
                  <option value="{Vtiger_Util_Helper::toSafeHTML($PICKLIST_VALUE)}">{$PICKLIST_VALUE}</option>
              {/foreach}
          </select>
        {/strip}
      </span>
  </div>  
<div class="row-fluid">
      <span class="span3">
        <span class="pull-left" style="padding-left:10px;padding-top:5px;">
          <label for="tipo">
            Día o Mes:
          </label>
        </span>
      </span>
      <span class="span3">
        <select class="widgetFilter" name="diaoMes" id="diaoMes" onChange="javascript:actualizar();">
          <option value="mes">Mes</option>          
          <option value="dia">Dia</option>
          
        </select>
      </span>
    
      <span class="span3">
        <span class="pull-left" style="padding-left:10px;padding-top:5px;">
          <label for="tipo">
            Monto o Cantidad:
          </label>
        </span>
      </span>
      <span class="span3">
        <select class="widgetFilter" name="montoCantidad" id="montoCantidad" onChange="javascript:actualizar();">
          <option value="monto">
          Monto
          </option>
          <option value="cantidad">
          Cantidad
          </option>
    
        </select>
      </span>
</div>
   <div id="dashChartLoader" style="text-align:center;"><img src="layouts/vlayout/skins/softed/images/loading.gif" border="0" align="absmiddle"></div>

    <div class="span12" style="overflow: hidden">
        <input class="widgetData" type=hidden value='{Vtiger_Util_Helper::toSafeHTML(ZEND_JSON::encode($DATA))}' />
        <div id="widgetChartContainer" style="height:500px;width:92%"></div>
        <div id="widgetChartContainer2" style="height:400px;width:92%"></div>
    </div>

  </div>
</div>

{literal}

<script type="text/javascript">

    function diffMeses(){
        if (jQuery("#createdtime").val()!=""){
            var aFecha1 = jQuery("#fecha1").val().split("-");
            var fFecha1 = Date.UTC(aFecha1[0],aFecha1[1]-1,aFecha1[2]); 
            var fFecha2 = Date.UTC(aFecha2[0],aFecha2[1]-1,aFecha2[2]);
            console.log("esta es la fecha actual: " + fFecha1);
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
        if (jQuery("#fecha1").val() != "")
            fecha = jQuery("#fecha1").val();

        var diaoMes;
        diaoMes = jQuery("#diaoMes").val();

        if(diaoMes!=diaoMesPrev){
          //Hay que resetear las fechas.
          if(diaoMes=='dia'){
            jQuery("#createdtime").val(jQuery("#rangodia").val());  
            /*var element = jQuery(jQuery(".dateRange")).data('datepicker').el;
            jQuery(element).val(jQuery("#rangodia").val());*/
          }else{
            //jQuery("#createdtime").val(jQuery("#rangomes").val());
            var element = jQuery(jQuery(".dateRange")).data('datepicker').el;
            jQuery(element).val(jQuery("#rangomes").val());
          }
          
        }
        diaoMesPrev=diaoMes;


        var montoCantidad;
        montoCantidad = jQuery("#montoCantidad").val();   

        var dateRangeVal = jQuery('.dateRange').val();
        //If not value exists for date field then dont send the value
        if(dateRangeVal.length <= 0) {
          return true;
        }
        var dateRangeValComponents = dateRangeVal.split(',');
        var createdtime = {};
        createdtime.start = dateRangeValComponents[0];
        createdtime.end = dateRangeValComponents[1];

        var promocion = jQuery('#promocion').val();
        var promocion_str="";
        if (promocion!== null)promocion_str=promocion.join(',');

        var ret;

        jQuery.ajax({
            async: false,
            data:  {'diaoMes':diaoMes,'montoCantidad':montoCantidad,'createdtime':createdtime,'promocion':promocion_str},
            url: 'index.php?module=Analisis&action=FiltrarGraficaDiaoMes',
            type:  'post',
            success:  function (response) {
                ret=  JSON.parse(response);
                // console.log(response);
            }
        });/*
        console.log(ret);*/
        // console.log("esto es ret" + ret);
        return ret;
    } 

    var chartData;var chartpar1=null;
    var diaoMesPrev='mes';
    jQuery( document ).ready(function() {
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
            if (diffDias()) {
                actualizar();
                dateChanged = false;
            }
            else{
                diaoMes = jQuery("#diaoMes").val();
                error="Se deben seleccionar un máximo de 2 años";
                if(diaoMes=='dia'){
                  error="Se deben seleccionar un máximo de 2 meses";
                }
                var bootBoxModal = bootbox.alert(error);
                    bootBoxModal.on('hidden',function(e){
                    if(jQuery('#globalmodal').length > 0) {
                        jQuery('body').addClass('modal-open');
                    }
                })
                jQuery("#createdtime").val("");
            }
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

    function diffDias(){
        if (jQuery("#createdtime").val()!=""){
            var aFechas = jQuery("#createdtime").val().split(',');
            var aFecha1 = aFechas[0].split('-'); 
            var aFecha2 = aFechas[1].split('-'); 
            var fFecha1 = Date.UTC(aFecha1[0],aFecha1[1]-1,aFecha1[2]); 
            var fFecha2 = Date.UTC(aFecha2[0],aFecha2[1]-1,aFecha2[2]); 
            var dif = fFecha2 - fFecha1;
            var dias = Math.floor(dif / (1000 * 60 * 60 * 24)); 
            console.log(dias);
            diaoMes = jQuery("#diaoMes").val();
            if (diaoMes=='dia'){
              if(dias>60)
                return false
            }else{
              if (dias > 728)
                return false
            }
            
        }
        return true;
    }
    var chartexm1,chartexm2=null;
  
    var promos, anterior, series, cadapromo, color;
    

    var yaxislbl  = '$';

   
// se ejecuta cada vez que se cambia alguno de los filtros
    function actualizar(){
        jQuery("#dashChartLoader").show();    
        jQuery("#widgetChartContainer").hide(); 
        // se llama la funcion getDatos()
        arr=getDatos();



        // se obtienen las promociones devueltas por getDatos()

        promos = arr[1];
        if(promos == null)
          promos = "";
        var eachcolor;
        console.log(promos);

        // se generan tantos colores aleatorios como cantidad de promociones haya para mostrar
        for (i = 0;i <=promos.length;i++){

          r = Math.floor((Math.random() * 255) + 0);
          g = Math.floor((Math.random() * 255) + 0);
          b = Math.floor((Math.random() * 255) + 0);

          // se generan las lineas para cada promocion
          if(i==0){

            eachcolor = 'rgb('+ r +','+ g +','+ b +')';
            color = eachcolor;

            series = [{
                lineWidth:4,highlighter: {formatString: yaxislbl+' = %s'},label:'<span style="color:'+eachcolor+'; border: 1px solid"> '+promos[i]+'</span>', yaxis: 'yaxis',
                }];
            console.log(eachcolor);
          }
          else{

            eachcolor = 'rgb('+ r +','+ g +','+ b +')';
            color = color.concat('; rgb('+ r +','+ g +','+ b +')');

            series = series.concat([{
                lineWidth:4,highlighter: {formatString: yaxislbl+' = %s'},label:'<span style="color:'+ eachcolor +'; border: 1px solid"> '+promos[i]+'</span>', yaxis: 'yaxis',
                }]);
            
     
            
          }

            
            
        }
     
        color = color.split(";");

        series.concat({yaxis: 'yaxis'});

      
       
        jQuery("#dashChartLoader").hide();    
        jQuery("#widgetChartContainer").show();
        jQuery('.widgetData').val(JSON.stringify(arr[0]));
        graficar(series);
    }


  function graficar(){
      var jData = jQuery('.widgetData').val();
      chartData = JSON.parse(jData)

      var yaxislbl = 'cant.';
      var montoCantidad;

      //eje y, si se eligio monto, muestra signo de pesos, si se eligio cantidad muestra "cant"
      montoCantidad = jQuery("#montoCantidad").val(); 
      if(montoCantidad == 'monto'){
          yaxislbl  = '$';
      }        
      
      formato="%'.0f";
      if(jQuery("#radioIpc").attr('checked')=="checked"){
        formato="%'.2f";
      }  

      if (chartexm1) {
          chartexm1.destroy();
      }  
      
      jQuery('#widgetChartContainer').empty();
      jQuery('#widgetChartContainer2').empty();

      chartexm1 = jQuery.jqplot('widgetChartContainer', chartData,
      { 
          
          animate: true,
          animateReplot: true,
          title:"Ventas", 
          axes:{
              xaxis:{tickOptions:{ 
            angle: -30
          },
          tickRenderer:$.jqplot.CanvasAxisTickRenderer,
          labelOptions:{
            fontFamily:'Helvetica',
            fontSize: '14pt'
          },
                  renderer: $.jqplot.CategoryAxisRenderer
              },
              
            yaxis:{
                tickOptions:{ 
                  angle: -30
                },
                autoscale:true,
                tickOptions:{showGridline:false,formatString: formato},          
                labelOptions: {
                    fontSize: '14pt'
                },
                label: yaxislbl,min:0,
              
              }
          },
          seriesColors:color,
              highlighter: {
                  show: true, 
                  showLabel: true, 
                  tooltipAxes: 'y',
                  sizeAdjust: 10 , tooltipLocation : 'nw',
              },
                  // Set default options on all series, turn on smoothing.
          seriesDefaults: {
              rendererOptions: {
                  smooth: true              }
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
                  numberRows: '10',
                  numberColumns: '5'
              },
              seriesToggle: true
              
          },
          noDataIndicator: {
            show: true,
            // Here, an animated gif image is rendered with some loading text.
            indicator: 'No hay datos disponibles..'
          },
          series:
              series
      }
    );

  }

</script>


{/literal}