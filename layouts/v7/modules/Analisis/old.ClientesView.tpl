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
  <div class="row-fluid detailViewTitle" style="padding-bottom:10px;">
    <span class="recordLabel font-x-x-large textOverflowEllipsis span pushDown" title="gika"><span class="">Clientes por Mes</span>&nbsp;</span>
    <span class="row-fluid">
      <span class="muted">¿Cuántos clientes tenemos por mes? Cuántos nuevos?</span><br>
     <span class="muted"> Clientes activos = los que han generado gaviotas, canjes o vales, o fueron activos en la Web (compra, newsletter), o participaron en sorteos, esos últimos 24 meses
      </span>
    </span>
  </div>
  <div class="detailViewInfo row-fluid">
    <div class="row-fluid">
      <span class="span2">
        <span class="pull-left" style="padding-left:10px;padding-top:5px;">
          <label for="createdtime">
        Fecha:
          </label>
        </span>
      </span>
      <span class="span3">
        <input type="text" name="createdtime" id="createdtime" value="{$date_range}" class="dateRange widgetFilter dateField" data-date-format="dd-mm-yyyy">
      </span>
    
      <span class="span3">
        <span class="pull-left" style="padding-left:10px;padding-top:5px;">
          <label for="edades">
            Fuente:
          </label>
        </span>
      </span>
      <span class="span4">
        <select class="select2" multiple name="fuente" id="fuente" style="width: 73%"  onChange="javascript:actualizar();">
          <option value="canjes" selected>Canjes</option>
          <option value="gaviotas" selected>Gaviotas</option>
          <option value="vales" selected>Vales</option>
          <option value="web" selected>Web</option>
          <option value="quejas" selected>Quejas</option>
          <option value="sorteos" selected>Sorteos</option>
          <option value="ingresos" selected>Ingresos</option>
        </select>
      </span>
    </div>    <div class="row-fluid">
      <span class="span2">
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
   
      <span class="span3">
        <span class="pull-left" style="padding-left:10px;padding-top:5px;">
          <label for="edades">
            Rango de Edades:
          </label>
        </span>
      </span>
      <span class="span4">
        {strip}
        {assign var=PICKLIST_VALUES value=$edades}
        <select id="edades" multiple class="select2" name="edades" style="width: 73%" onChange="javascript:actualizar();">
            {foreach item=PICKLIST_VALUE from=$PICKLIST_VALUES}
                <option value="{Vtiger_Util_Helper::toSafeHTML($PICKLIST_VALUE)}">{$PICKLIST_VALUE}</option>
            {/foreach}
        </select>
        {/strip}
      </span>
    </div>
    
     <div class="row-fluid" style="padding-top:5px">
      <span class="span2">
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
   
      <span class="span3">
        <span class="pull-left" style="padding-left:10px;padding-top:5px;">
          <label for="tipo">
            Estatuto:
          </label>
        </span>
      </span>
      <span class="span4">
         {strip}
        {assign var=PICKLIST_VALUES value=$estatutos}
        <select id="estatuto" multiple class="select2" name="estatuto" style="width: 73%" onChange="javascript:actualizar();">
            {foreach item=PICKLIST_VALUE from=$PICKLIST_VALUES}
                <option value="{Vtiger_Util_Helper::toSafeHTML($PICKLIST_VALUE)}">{$PICKLIST_VALUE}</option>
            {/foreach}
        </select>
        {/strip}
      </span>
    </div>
    <div class="row-fluid" style="padding:5px 0">
      <span class="span2">
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

    <div class="  details">
     {strip}
    {if count($DATA) gt 0 }
      <input class="widgetData" type=hidden value='{Vtiger_Util_Helper::toSafeHTML(ZEND_JSON::encode($DATA))}' />
      <div id="dashChartLoader" style="text-align:center;"><img src="layouts/vlayout/skins/softed/images/loading.gif" border="0" align="absmiddle"></div>
      <div id="widgetChartContainer" style="height:400px;width:85%"></div>
      <div id="widgetChartContainer2" style="height:400px;width:85%"></div>
    {else}
      <span class="noDataMsg">
        {vtranslate('LBL_NO')} {vtranslate($MODULE_NAME, $MODULE_NAME)} {vtranslate('LBL_MATCHED_THIS_CRITERIA')}
      </span>
    {/if}
    {/strip}
    </div>
  </div>
</div>
{literal}
<script type="text/javascript">
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
    var tipo = jQuery('#tipo').children('option:selected').val();
    
    var edad = jQuery('#edades').val();
    var canal = jQuery('#canal').val();
    var estatuto = jQuery('#estatuto').val();
    var programa = jQuery('#programa').val();
    var sexo = jQuery('#sexo').val();
    var fuente = jQuery('#fuente').val();
    //var sexo = jQuery('#sexo').children('option:selected').val();
    
    var sexo_str="";
    var fuente_str="";
    var edad_str="";
    var canal_str="";
    var estatuto_str="";
    var programa_str="";


    if (sexo!== null)sexo_str=sexo.join(',');
    if (fuente!== null)fuente_str=fuente.join(',');
    if (estatuto!== null)estatuto_str=estatuto.join(',');
    if (edad!== null)edad_str=edad.join(',');
    if (canal!== null)canal_str=canal.join(',');
    if (programa!== null)programa_str=programa.join(',');


    jQuery("#dashChartLoader").show();    
    jQuery("#widgetChartContainer").hide();    
    
    jQuery.ajax({
           async: true,
           data: {'createdtime':createdtime,'tipo':tipo,'rango':edad_str,'sexo':sexo_str,'canal':canal_str,'programa':programa_str,'estatuto':estatuto_str,'fuente':fuente_str},
           url:  'index.php?module=Analisis&view=Clientes&mode=Ajax',
           dataType:"json",
           success: function(data) {
                arr=data;
                //chartData = JSON.parse(data);
                chartData =arr;
                jQuery("#dashChartLoader").hide();    
                jQuery("#widgetChartContainer").show();    
                graficar();

            
           },
            error: function (xhr, ajaxOptions, thrownError) {
              console.log(thrownError);
          }
         });
  }
   function  getChartRelatedData() {
      var jData = $('.widgetData').val();
      var data = JSON.parse(jData);
      var chartDataAux = [];
      for(var index in data) {
        var row = data[index];
        var rowData = [row.name, parseInt(row.count), row.id];
        chartDataAux.push(rowData);
      }
      return chartDataAux;
    }
    var chartData;
    $( document ).ready(function() {

      //chartData = getChartRelatedData();
      var jData = $('.widgetData').val();
      chartData = JSON.parse(jData);
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
          jQuery("#dashChartLoader").focus(); 
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
  function dashToSlash(string){
    var response = string.replace(/-/g,"/");
    //The slash-g bit says: do this more than once
    return response;
  }
  function invertirFecha(date) {
    var d = date.getDate();
    var m = date.getMonth() + 1;
    var y = date.getFullYear();
    return '' + (d <= 9 ? '0' + d : d)  + '-' + (m<=9 ? '0' + m : m) + '-' + y;
  }
  var chartexm1,chartexm2=null;

  function graficar(){
      if (chartexm1) {
        chartexm1.destroy();
      }  
       
      
      jQuery('#widgetChartContainer').empty();
      jQuery('#widgetChartContainer2').empty();
      var dateRangeVal = jQuery('.dateRange').val();
      var dateRangeValComponents = dateRangeVal.split(',');
      desde=dateRangeValComponents[0];
      hasta=dateRangeValComponents[1];
      from = desde.split("-");
      to = hasta.split("-");
      f = new Date(from[2], from[1] - 1, from[0]);
      t = new Date(to[2], to[1] - 1, to[0]);
      var ant_desde=new Date(f);
      var ant_hasta=new Date(t);
      ant_desde.setMonth(ant_desde.getMonth() - 12);
      ant_hasta.setMonth(ant_hasta.getMonth() - 12);
      var anterior_desde=invertirFecha(ant_desde);
      var anterior_hasta=invertirFecha(ant_hasta);
      var current_desde=desde;
      var current_hasta=hasta;

      chartexm1 = jQuery.jqplot('widgetChartContainer', chartData, 
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
                    /*show: true,
                    location: 'ne'*/
                    
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