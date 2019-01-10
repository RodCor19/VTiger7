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
    <span class="recordLabel font-x-x-large textOverflowEllipsis span pushDown" title="gika"><span class="">Gaviotas</span>&nbsp;</span>
    <span class="row-fluid"><span class="muted">Movimientos de las Gaviotas</span></span>
  </div>
  <div class="row-fluid detailViewTitle"><span class="row-fluid">
    <span class="muted">¿Cuál es el consumo & costo de las gaviotas? Muchas se vencen?  
    </span><br>
    <span class="muted">Consumidas, anuladas, vencidas = son movimientos en el rango de fecha elegido. (No tomamos en cuenta la fecha de generación de esas gaviotas)</span><br>
    <span class="muted">Vivas = vivas al final del rango de fecha. No tomamos en cuenta la primera fecha del rango, sino necesariamente 2 años</span>
   
    </span></div>
  <div class="detailViewInfo row-fluid">
     <div class="row-fluid">
      <span class="span3">
        <span class="pull-left" style="padding-left:10px;padding-top:5px;">
          <label for="createdtime">
        Fecha de Envío entre:
          </label>
        </span>
      </span>
      <span class="span3">
        <input type="text" name="createdtime" id="createdtime" value="{$date_range}" class="dateRange widgetFilter dateField" data-date-format="dd-mm-yyyy">
      </span>
       <span class="span3">
           <span class="pull-left" style="padding-left:10px;padding-top:5px;">
          <label for="edades">
            Unidad:
          </label>
        </span>
        </span>
      </span>
      <span class="span3">
         <select class="widgetFilter" name="vista" id="vista" onChange="javascript:actualizar();">  
          <option selected value="%">
              Porcentaje
            </option>
         <option value="val">
              Valor
            </option>
        </select>
      </span>
    </div>
   
    <div class="row-fluid">
      <span class="span3">
        <span class="pull-left" style="padding-left:10px;padding-top:5px;">
          <label for="edades">
            Vista:
          </label>
        </span>
      </span>
      <span class="span3">
         <select class="widgetFilter" name="vista2" id="vista2" onChange="javascript:actualizar();">  
          <option selected value="gaviotas">
              Gaviotas
            </option>
         <option value="clientes">
              Clientes
            </option>
         
        </select>
      </span>
      <span class="span3">
        <span class="pull-left" style="padding-left:10px;padding-top:5px;">
          <label for="edades">
            Sexo:
          </label>
        </span>
      </span>
      <span class="span3">
        <select class="select2" multiple name="sexo" id="sexo" style="width: 73%"  onChange="javascript:actualizar();">
          
          <option value="F">
              Femenino
            </option>
         <option value="M">
              Masculino
            </option>
         <option value="-">
              Sin Dato
            </option> 
        </select>
      </span>
     </div> 
     <div class="row-fluid">
      <span class="span3">
        <span class="pull-left" style="padding-left:10px;padding-top:5px;">
          <label for="edades">
            Rango de Edades:
          </label>
        </span>
      </span>
      <span class="span3">
        {strip}
        {assign var=PICKLIST_VALUES value=$edades}
        <select id="edades" multiple class="select2" name="edades" style="width: 73%" onChange="javascript:actualizar();">
            {foreach item=PICKLIST_VALUE from=$PICKLIST_VALUES}
                <option value="{Vtiger_Util_Helper::toSafeHTML($PICKLIST_VALUE)}">{$PICKLIST_VALUE}</option>
            {/foreach}
        </select>
        {/strip}
      </span>
      <span class="span3">
        <span class="pull-left" style="padding-left:10px;padding-top:5px;">
          <label for="tipo">
            Canal Activo:
          </label>
        </span>
      </span>
      <span class="span3">
        {strip}
        {assign var=PICKLIST_VALUES value=$canales}
        <select id="canal" multiple class="select2" name="canal" style="width: 73%" onChange="javascript:actualizar();">
            {foreach item=PICKLIST_VALUE from=$PICKLIST_VALUES}
                <option value="{Vtiger_Util_Helper::toSafeHTML($PICKLIST_VALUE)}">{$PICKLIST_VALUE}</option>
            {/foreach}
        </select>
        {/strip}
      </span>
    </div>
    
    
    
    <div class="row-fluid" style="padding-top:5px;padding-bottom:5px;">
      <span class="span3">
        <span class="pull-left" style="padding-left:10px;padding-top:5px;">
          <label for="tipo">
            Estatuto:
          </label>
        </span>
      </span>
      <span class="span3">
         {strip}
        {assign var=PICKLIST_VALUES value=$estatutos}
        <select id="estatuto" multiple class="select2" name="estatuto" style="width: 73%" onChange="javascript:actualizar();">
            {foreach item=PICKLIST_VALUE from=$PICKLIST_VALUES}
                <option value="{Vtiger_Util_Helper::toSafeHTML($PICKLIST_VALUE)}">{$PICKLIST_VALUE}</option>
            {/foreach}
        </select>
        {/strip}
      </span>
     <span class="span3">
        <span class="pull-left" style="padding-left:10px;padding-top:5px;">
          <label for="tipo">
            Programas:
          </label>
        </span>
      </span>
      <span class="span3">
        {strip}
          {assign var=PICKLIST_VALUES value=$programas}
          <select id="programa" multiple class="select2" name="programa" style="width: 73%" onChange="javascript:actualizar();">
              {foreach item=PICKLIST_VALUE from=$PICKLIST_VALUES}
                  <option value="{Vtiger_Util_Helper::toSafeHTML($PICKLIST_VALUE)}">{$PICKLIST_VALUE}</option>
              {/foreach}
          </select>
        {/strip}
      </span>
    </div>
    <div id="dashChartLoader" style="text-align:center;"><img src="layouts/vlayout/skins/softed/images/loading.gif" border="0" align="absmiddle"></div>
    <div  class="details">
       <div id="details">      
      {strip}
        {if count($DATA) gt 0 }
          <input class="widgetData" type=hidden value='{Vtiger_Util_Helper::toSafeHTML(ZEND_JSON::encode($DATA))}' />
           <div id="widgetChartContainer" class="  details" style="height:250px;width:50%;float:left;"></div>
              <div id="widgetChartContainer2" class=" details" style="height:250px;width:50%;float:left;"></div>
        {else}
          <span class="noDataMsg">
            {vtranslate('LBL_NO')} {vtranslate($MODULE_NAME, $MODULE_NAME)} {vtranslate('LBL_MATCHED_THIS_CRITERIA')}
          </span>
        {/if}
      {/strip}
      </div>
       <div class="row-fluid">
          <div class="span6" style="margin-left:5px;">
              <button style="float:left;" class="btn addButton" onclick="saveImage('widgetChartContainer','Movimientos de Gaviotas');"><i class="icon-download icon-white"></i>&nbsp;<strong>Guardar para Análisis de Datos</strong></button>
              <button id="widgetChartContainerView" style="float:left;display:none;margin-left:10px;"class="btn addButton" onclick=""><i class="icon-signal icon-white"></i>&nbsp;<strong>  Ver Análisis</strong></button>
              <div id="widgetChartContainerLoader" style="text-align:center;display:none;"><img src="layouts/vlayout/skins/softed/images/loading.gif" border="0" align="absmiddle"></div>
          </div>
          <div class="span6" style="margin-left:5px;">
              <button style="float:left;" class="btn addButton" onclick="saveImage('widgetChartContainer2','Movimientos de Gaviotas');"><i class="icon-download icon-white"></i>&nbsp;<strong>Guardar para Análisis de Datos</strong></button>
              <button id="widgetChartContainer2View" style="float:left;display:none;margin-left:10px;"class="btn addButton" onclick=""><i class="icon-signal icon-white"></i>&nbsp;<strong>  Ver Análisis</strong></button>
              <div id="widgetChartContainer2Loader" style="text-align:center;display:none;"><img src="layouts/vlayout/skins/softed/images/loading.gif" border="0" align="absmiddle"></div>
          </div>
      </div>

    </div>
    

     

   



    </div>
  </div>

{literal}

<script type="text/javascript">
 var vista='%';
 
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
  var chartData;var chartpar1=null;var chartpar2=null;
  $( document ).ready(function() {
    //jQuery.noConflict();      
    chartData = getChartRelatedData();
     jQuery("#dashChartLoader").hide();    
    graficar("");
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
  
  function graficar(tipo){
      
     if (chartpar1) {
        chartpar1.destroy();
      } if (chartpar2) {
        chartpar2.destroy();
      }  

      jQuery('#widgetChartContainer').empty();
      jQuery('#widgetChartContainer2').empty();
     // jQuery.jqplot.sprintf.thousandsSeparator = '.';
    /*jQuery.jqplot.sprintf.thousandsSeparator = '.';
    jQuery.jqplot.sprintf.decimalMark = ',';*/
    //var total= chartData[0][0]+chartData[1][0]+chartData[2][0]+chartData[3][0];
    /*(function($) { $.jqplot.LabelFormatter = function(format, val){ return  (val / total * 100) + '%'; }; })(jQuery); 

    $.jqplot.LabelFormatter = function(format, val) {
        if(vista=='%'){
          return  (val / total * 100).toFixed(0) + '%';
        }else{
          return val.toFixed(0);
        }
        
    };
     $.jqplot.LabelFormatter2 = function(format, val) {
        return  val;
    };

    $.jqplot.config.enablePlugins = true;*/
    /*s1=chartData[0];
    s2=chartData[1];
    s3=chartData[2];
    s4=chartData[3];
    var pLabels1 = []; // arrays for each inner label
    var pLabels2 = [];
    var pLabels3 = [];
    var pLabels4 = [];
     for (var i = 0; i < s1.length; i++){
        pLabels1.push(s1[i]);
        pLabels2.push(s2[i]);
        pLabels3.push(s3[i]);
        pLabels4.push(s4[i]);
    } */  
     ver='value'; 
     if(vista=='%'){
        ver='percent'
    }
    


     chartpar1 = jQuery.jqplot ('widgetChartContainer', [chartData[0]], 
    {  
      animate: true,
      animateReplot: true,
      title:"Gaviotas Generadas", 
      seriesDefaults: {
        // Make this a pie chart.
        renderer: $.jqplot.PieRenderer, 
        rendererOptions: {
          // Put data labels on the pie slices.
          // By default, labels show the percentage of the slice.
          showDataLabels: true,
          dataLabels: ver,
          dataLabelThreshold: 0, 
          
        },
        //pointLabels: { show: true, formatString: '%s', formatter: $.jqplot.LabelFormatter }
      }, 
      legend: { show:true, location: 'e',seriesToggle: true}
      ,
       noDataIndicator: {
        show: true,
        indicator: 'No hay datos disponibles..'
      },
      grid: {
            drawBorder: false, 
            drawGridlines: false,
            background: '#ffffff',
            shadow:false
        },
    }
  );

  if(tipo!="clientes")   {
    chartpar2 = jQuery.jqplot ('widgetChartContainer2', [chartData[1]], 
      {  
        animate: true,
        animateReplot: true,
        title:"Costo Correspondiente", 
        seriesDefaults: {
          // Make this a pie chart.
          renderer: $.jqplot.PieRenderer, 
          rendererOptions: {
            // Put data labels on the pie slices.
            // By default, labels show the percentage of the slice.
            showDataLabels: true,
            dataLabels: ver,
            dataLabelThreshold: 0, 
          },
        }, 

        legend: { show:true, location: 'e',seriesToggle: true}
        ,
         noDataIndicator: {
          show: true,
          indicator: 'No hay datos disponibles..'
        },
        grid: {
              drawBorder: false, 
              drawGridlines: false,
              background: '#ffffff',
              shadow:false
          },
      }
    );
  }

  /*$('#widgetChartContainer2').bind('jqplotDataHighlight', function (ev, seriesIndex, pointIndex, data) { 
    document.getElementById('widgetChartContainer2').title = data;
  }); 
  $('#widgetChartContainer').bind('jqplotDataHighlight', function (ev, seriesIndex, pointIndex, data) { 
    document.getElementById('widgetChartContainer').title = data;
  });*/ 

    /*chartpar1 = jQuery.jqplot('widgetChartContainer', chartData, 
      { 
        animate: true,
        animateReplot: true,
        title:"Programa Gaviotas", 
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
                    if(vista=='%'){
                      porcentaje=(item*100)/total;
                      porcentaje=Math.round(porcentaje);
                      porcentaje+=" %";
                    }else{
                      porcentaje=item;
                    }

                    

                    var html = "<div>"+porcentaje+"</div>";
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
            numberColumns: '4'
          },
          seriesToggle: true
          
      },
       series:[
              {
                lineWidth:4,label:'Vencidas',pointLabels:{
                    show:true,
                    labels:pLabels1,
                    labelsFromSeries:false,
                    formatString: '%s',
                    formatter: $.jqplot.LabelFormatter
                }
              }, 
              {
                lineWidth:4,label:'Consumidas',pointLabels:{
                    show:true,
                    labels:pLabels2,
                    labelsFromSeries:false,
                    formatString: '%s',
                    formatter: $.jqplot.LabelFormatter                }
              },
              {
                lineWidth:4,label:'Vivas',pointLabels:{
                    show:true,
                    labels:pLabels3,
                    labelsFromSeries:false,
                    formatString: '%s',
                    formatter: $.jqplot.LabelFormatter                }
              },
              {
                lineWidth:4,label:'Anuladas',pointLabels:{
                    show:true,
                    labels:pLabels4,
                    labelsFromSeries:false,
                    formatString: '%s',
                    formatter: $.jqplot.LabelFormatter                }
              }
         
         
            ]
      }
    );*/

      


  }
  
  function actualizar(){
    vista = jQuery('#vista').children('option:selected').val();
    var vista2 = jQuery('#vista2').children('option:selected').val();
    
    var tipo = jQuery('#tipo').children('option:selected').val();
    var edad = jQuery('#edades').val();
    var canal = jQuery('#canal').val();
    var estatuto = jQuery('#estatuto').val();
    var sexo = jQuery('#sexo').val();
    //var sexo = jQuery('#sexo').children('option:selected').val();
    var programa = jQuery('#programa').val();

    var sexo_str="";
    var edad_str="";
    var canal_str="";
    var estatuto_str="";
    var programa_str="";

    if (sexo!== null)sexo_str=sexo.join(',');
    if (estatuto!== null)estatuto_str=estatuto.join(',');
    if (edad!== null)edad_str=edad.join(',');
    if (canal!== null)canal_str=canal.join(',');
     if (programa!== null)programa_str=programa.join(',');
    
    jQuery("#dashChartLoader").show();    
    jQuery("#widgetChartContainer").hide(); 
    jQuery("#widgetChartContainer2").hide(); 

    
    var dateRangeVal = jQuery('.dateRange').val();
    //If not value exists for date field then dont send the value
    if(dateRangeVal.length <= 0) {
      return true;
    }
    var dateRangeValComponents = dateRangeVal.split(',');
    var createdtime = {};
    createdtime.start = dateRangeValComponents[0];
    createdtime.end = dateRangeValComponents[1];

    jQuery.ajax({
           async: true,
           data: {'createdtime':createdtime,'rango':edad_str,'sexo':sexo_str,'canal':canal_str,'programa':programa_str,'estatuto':estatuto_str,'vista2':vista2},
           url:  'index.php?module=Analisis&view=Gaviotas&mode=Ajax',
           dataType:"json",
           success: function(data) {
             arr=data;
              //var data = JSON.parse(arr);
              var chartDataAux = [];
              var arrayLength = data[0].length;
              if(arrayLength>0){
                
                chartData=data;
                 jQuery("#dashChartLoader").hide();    
                jQuery("#widgetChartContainer").show();  
                jQuery("#widgetChartContainer2").show();  
                graficar(vista2);
              }else{
                chartData=chartDataAux;
                 jQuery("#dashChartLoader").hide();    
                jQuery("#widgetChartContainer").show();  
                  graficar(vista2);
              }
           },
            error: function (xhr, ajaxOptions, thrownError) {
              console.log(thrownError);
          }
         });
  }

</script>


{/literal}