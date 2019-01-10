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
  <div class="row-fluid detailViewTitle">
    <span class="recordLabel font-x-x-large textOverflowEllipsis span pushDown" title="gika"><span class="">Pareto de los Clientes</span>&nbsp;</span>
    <span class="row-fluid"><span class="muted">Gaviotas generadas durante los últimos 24 meses.</span></span>
  </div>
  <div class="row-fluid detailViewTitle">
  <span class="row-fluid">
    <span class="muted">¿Cuánto pesan los clientes más importantes? Los clientes periféricos?</span><br>
    <span class="muted">Gaviotas generadas los últimos 24 meses</span><br>
    <span class="muted">(eje x = cantidad de clientes activos)</span>
  </span></div>
  <div class="detailViewInfo row-fluid">
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
    <div class="row-fluid" style="padding-bottom:5px;">
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
    <div id="dashChartLoader" style="text-align:center;"><img src="layouts/vlayout/skins/softed/images/loading.gif" border="0" align="absmiddle"></div>
    <div id="" class="  details">

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
            <button style="float:left;" class="btn addButton" onclick="saveImage('widgetChartContainer','Pareto de Clientes');"><i class="icon-download icon-white"></i>&nbsp;<strong>Guardar para Análisis de Datos</strong></button>
            <button id="widgetChartContainerView" style="float:left;display:none;margin-left:10px;"class="btn addButton" onclick=""><i class="icon-signal icon-white"></i>&nbsp;<strong>  Ver Análisis</strong></button>
            <div id="widgetChartContainerLoader" style="text-align:center;display:none;"><img src="layouts/vlayout/skins/softed/images/loading.gif" border="0" align="absmiddle"></div>
        </div>
    </div>
    </div>
  </div>
</div>

{literal}

<script type="text/javascript">
 
 
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
  var chartData;var chartpar1=null;
  $( document ).ready(function() {
    //jQuery.noConflict();      
    chartData = getChartRelatedData();
     jQuery("#dashChartLoader").hide();    
    graficar();
    
  });
  
  var oTable=null;
  function graficar(){
      
     if (chartpar1) {
        chartpar1.destroy();
      }  

      jQuery('#widgetChartContainer').empty();

    /*jQuery.jqplot.sprintf.thousandsSeparator = '.';
    jQuery.jqplot.sprintf.decimalMark = ',';*/

    chartpar1 = jQuery.jqplot('widgetChartContainer', chartData, 
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
  
  function actualizar(){
    
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
    jQuery.ajax({
           async: false,
           data: {'tipo':tipo,'rango':edad_str,'sexo':sexo_str,'canal':canal_str,'programa':programa_str,'estatuto':estatuto_str,},
           url:  'index.php?module=Analisis&view=ParetoClientes&mode=Ajax',
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
                graficar();
              }else{
                chartData=chartDataAux;
                 jQuery("#dashChartLoader").hide();    
                jQuery("#widgetChartContainer").show();  
                  graficar();
              }
           },
            error: function (xhr, ajaxOptions, thrownError) {
              console.log(thrownError);
          }
         });
  }

</script>


{/literal}