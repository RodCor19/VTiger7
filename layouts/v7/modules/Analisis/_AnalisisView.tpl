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
{literal}
<style type="text/css">
.pvtAxisLabel,.pvtRowLabel,.pvtTotalLabel{color: #000;}</style>
 <script type="text/javascript">
    var arr;
     function MailsRebotados(){
       //jQuery("#dashChartLoader2").show();
        jQuery.ajax({
           async: false,
           data: {},
           url:  'index.php?module=Analisis&view=MailsRebotados&mode=data',
           dataType:"json",
           success: function(data) {
             arr=data;
            jQuery("#dashChartLoader2").hide();    
           },
            error: function (xhr, ajaxOptions, thrownError) {
              console.log(thrownError);
          }
         });
    }
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
           url:  'index.php?module=Analisis&view=MailsRebotados&mode=Ajax',
           dataType:"json",
           success: function(data) {
             arr=data;
                  graficar();
                
           },
            error: function (xhr, ajaxOptions, thrownError) {
              console.log(thrownError);
          }
         });
    }

    function graficar(){
      var derivers =     $.pivotUtilities.derivers;
        var tpl =          $.pivotUtilities.aggregatorTemplates;
        jQuery('#output').remove();
        jQuery('#details').append('<div id="output"></div>');
        $("#output").pivotUI(
            arr, 
            { 
              aggregators: {
                            "Cantidad de Emails":      function() { return tpl.count()() }
                        },
                rows: ["Correo","Nombre","Celular","Documento","Canal Activo","Asunto","Fecha","Empleado Modificó","Link"], 
                cols: [] ,
                 exportCSV: true
            }
        );

    }
    $(function(){
       actualizar();
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
</script>{/literal}
<div class="detailViewContainer">
  <div class="row-fluid detailViewTitle">
    <span class="recordLabel font-x-x-large textOverflowEllipsis span pushDown" title="gika"><span class="">Correos Rebotados</span>&nbsp;</span>
  </div>
  <div class="row-fluid detailViewTitle"><span class="row-fluid"><span class="muted">¿Cuáles son los correos rebotados? De dónde vienen? Quién puede corregirlos?
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
     <div class="row-fluid" style="padding-top:5px;">
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
    <div class=" details">
      <div id="details" class="row-fluid" style="margin: 5px;">
        <div id="output" style="margin: 10px;"></div>
      </div>
      <div class="row-fluid" style="margin-top:15px;">
        <div class="span8" style="margin-left:5px;">
             <a class="btn addButton" href="#" style="padding:4px 6px;float:left;font-weight:bold;margin-right:5px;font-family:'Helvetica Neue', Helvetica, Arial, sans-serif;" onclick=" tableToExcel('pvtRendererArea','Correos Rebotados',1);">  Exportar a Excel  </a>
            <button style="float:left;" class="btn addButton" onclick="saveExcel('results','output','Correos Rebotados');"><i class="icon-download icon-white"></i>&nbsp;<strong>Guardar para Análisis de Datos</strong></button>
            <button id="resultsView" style="float:left;display:none;margin-left:10px;"class="btn addButton" onclick=""><i class="icon-signal icon-white"></i>&nbsp;<strong>  Ver Análisis</strong></button>
            <div id="resultsLoader" style="text-align:center;display:none;"><img src="layouts/vlayout/skins/softed/images/loading.gif" border="0" align="absmiddle"></div>
        </div>
        <form action="download.php" method="post" target="_blank" id="FormularioExportacion">
            <input type="hidden" id="datos_a_enviar" name="datos_a_enviar" />
            <input type="hidden" id="nombre_a_enviar" name="nombre_a_enviar" />
            <input type="hidden" id="is_submited" name="is_submited" />
          </form>
    </div>
    </div>

    