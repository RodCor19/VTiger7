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

{if $PERMISO eq '0'}

<div class="detailViewContainer">
  <div class="row-fluid detailViewTitle">
    <span class="recordLabel font-x-x-large textOverflowEllipsis span pushDown" title="gika"><span class="">Mails por Persona</span>&nbsp;</span>
  </div>
  <div class="row-fluid detailViewTitle"><span class="row-fluid"><span class="muted">¿A cuántas personas hemos mandado 1 mail, 2 mails, etc.? Está bien la cantidad de mails que estamos mandando?
</span></span></div>
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
    <div class="row-fluid" style="padding:5px 0;">
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
    <div class="  details">
    {strip}
    {if count($DATA) gt 0 }
      <input class="widgetData" type=hidden value='{Vtiger_Util_Helper::toSafeHTML(ZEND_JSON::encode($DATA))}' />
      <div id="widgetChartContainer" class="widgetChartContainer" style="height:250px;width:85%"></div>
    {else}
      <span class="noDataMsg">
        {vtranslate('LBL_NO')} {vtranslate($MODULE_NAME, $MODULE_NAME)} {vtranslate('LBL_MATCHED_THIS_CRITERIA')}
      </span>
    {/if}
    {/strip}
    <div class="row-fluid">
        <div class="span8" style="margin-left:5px;">
            <button style="float:left;" class="btn addButton" onclick="saveImage('widgetChartContainer','Mails por Fecha');"><i class="icon-download icon-white"></i>&nbsp;<strong>Guardar para Análisis de Datos</strong></button>
            <button id="widgetChartContainerView" style="float:left;display:none;margin-left:10px;"class="btn addButton" onclick=""><i class="icon-signal icon-white"></i>&nbsp;<strong>  Ver Análisis</strong></button>
            <div id="widgetChartContainerLoader" style="text-align:center;display:none;"><img src="layouts/vlayout/skins/softed/images/loading.gif" border="0" align="absmiddle"></div>
        </div>
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

    var edad = jQuery('#edades').val();
    var canal = jQuery('#canal').val();
    var estatuto = jQuery('#estatuto').val();
    var programa = jQuery('#programa').val();
    var sexo = jQuery('#sexo').val();
    //var sexo = jQuery('#sexo').children('option:selected').val();
    
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
    
    jQuery.ajax({
           async: false,
           data: {'createdtime':createdtime,'edad':edad_str,'canal':canal_str,'sexo':sexo_str,'estatuto':estatuto_str,'programa':programa_str},
           url:  'index.php?module=Analisis&view=MailsFecha&mode=Ajax',
           dataType:"json",
           success: function(data) {
             arr=data;
              //var data = JSON.parse(arr);
              var chartDataAux = [];
              var arrayLength = data[0].length;
              if(arrayLength>0){
                /*for (var index = 0; index < arrayLength; index++) {
                  var row = data[0][index];
                  var rowData = [row["name"], parseInt(row["count"]), row["id"]];
                  chartDataAux.push(rowData);
                }*/
                chartData=data;
                graficar();
              }else{
                chartData=chartDataAux;
                  graficar();
              }
           },
            error: function (xhr, ajaxOptions, thrownError) {
              console.log(thrownError);
          }
         });
  }
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
    var chartData;var chartmf=null;
    $( document ).ready(function() {

      chartData = getChartRelatedData();
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

  function graficar(){
     var total = 0;
      
      /*$.each( chartData, function( index, value ){
          total += parseInt(value[1]);
      });
    */
     var myLabels = [];
     var arrayLength = chartData.length;
      if(arrayLength>0){
        myLabels = $.map( chartData, function( value, index ) {
          return value[1] ;
        });
      }

      if (chartmf) {
        chartmf.destroy();
      }  

      jQuery('#widgetChartContainer').empty();
        
     
     //chartmf = $(".widgetChartContainer").jqplot([chartData], {
      chartmf= jQuery.jqplot('widgetChartContainer',[chartData], {    
          animate: !jQuery.jqplot.use_excanvas,
          seriesDefaults:{
            renderer:jQuery.jqplot.BarRenderer,
            rendererOptions: {
              showDataLabels: true,barWidth: 10, 
              dataLabels: myLabels
            }
            ,pointLabels: { show: true, location: 'n', edgeTolerance: 5 }
          },
         
          axesDefaults: {
              tickRenderer: $.jqplot.CanvasAxisTickRenderer ,
              tickOptions: {
               fontSize: '10pt'
              }
          },
          axes: {
            xaxis: {
              renderer: $.jqplot.CategoryAxisRenderer,autoscale:true,
              label: "Emails",
            }       ,
            
          yaxis:{
            labelRenderer: jQuery.jqplot.CanvasAxisLabelRenderer,autoscale:true,
            label: "Personas",
          }     
          },
          noDataIndicator: {
            show: true,
            // Here, an animated gif image is rendered with some loading text.
            indicator: 'No hay datos disponibles..'
          }
        });
  }
</script>
{/literal}
{else}
  
  <div class="detailViewContainer">
    <div class="row-fluid detailViewTitle">
      <span class="recordLabel font-x-x-large textOverflowEllipsis span pushDown" title="gika"><span class="">Elija una de las gráficas de la izquierda</span>&nbsp;</span>
    </div>
  </div>
{/if}  