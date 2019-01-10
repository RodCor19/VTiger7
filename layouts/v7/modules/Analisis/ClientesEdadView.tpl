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
    <span class="recordLabel font-x-x-large textOverflowEllipsis span pushDown" title="gika"><span class="">Clientes por Edad y Sexo</span>&nbsp;</span>
  </div>
  <div class="row-fluid detailViewTitle"><span class="row-fluid"><span class="muted">Clientes Activos: Cantidad de clientes que generaron Gaviotas, realizaron Canjes o utilizaron Vales.</span></span></div>
  <div class="detailViewInfo row-fluid">
       
    <div class="row-fluid">
      <span class="span">
        <span class="pull-right" style="padding-left:10px;padding-top:5px;">
          <label for="createdtime">
        Fecha de Envío entre:
          </label>
        </span>
      </span>
      <span class="span4">
        <input type="text" name="createdtime" id="createdtime" value="{$date_range}" class="dateRange widgetFilter dateField" data-date-format="dd-mm-yyyy">
      </span>
    </div>
    

    <div class="  details">
     {strip}
    {if count($DATA) gt 0 }
      <span id="pivot-detail" style="margin: 10px;"></span>
       <div id="results" style="margin: 10px;"></div>
       <div style="float:left;">
            <input type="hidden" name="min" id="min">
          </div>
          <div style="float:left;">
            <input type="hidden" name="max" id="max">
          </div>
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
  var field_definitions = [
          {name: 'Rango',   type: 'string',   filterable: true, rowLabelable: true},
          {name: 'Frecuencia', type: 'float',   filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,2,'.',',')}},
          {name: 'Consumo', type: 'float',   filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,2,'.',',')}},
          {name: 'Hombres', type: 'float',   filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,'.',',')}},
          {name: 'Mujeres', type: 'float',   filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,'.',',')}}
          ]   
 
  
  var chartData;
  $( document ).ready(function() {
    jQuery.noConflict();      
    //chartData = getChartRelatedData();
    var jData ={/literal}{$DATA}{literal};
    //chartData = JSON.parse(jData);
    chartData = jData;
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
  
  var oTable=null;
  function graficar(){
      
      setupPivot({json: chartData, fields: field_definitions, filters: {}, rowLabels:["Rango","Frecuencia","Consumo","Hombres","Mujeres"],columnLabels:[] ,summaries:[]})
          

      // prevent dropdown from closing after selection
      jQuery('.stop-propagation').click(function(event){
        event.stopPropagation();
      });
     
     
      jQuery('#min').keyup( function() { oTable.fnDraw(); } );
      jQuery('#max').keyup( function() { oTable.fnDraw(); } );

      


  }
  function setupPivot(input){
    input.callbacks = {afterUpdateResults: function(){

       oTable = jQuery('#results > table').dataTable({
        "sDom": "<'row'<'span6'l><'span6'f>>t<'row'<'span6'i><'span6'p>>",
        "iDisplayLength": 20,
        "aLengthMenu": [[20, 40, 60, -1], [20, 40, 60, "Todos"]],
        "sPaginationType": "bootstrap",
        "oLanguage": {
          "sLengthMenu": "_MENU_ Registros por Pagina",
          "sZeroRecords": "No hay datos disponibles",
          "sInfo": "Viendo desde _START_ a _END_ de _TOTAL_ registros",
          "sInfoEmpty": "No hay datos disponibles",
          "sNext": "Siguiente",
          "sLast": "Última",
          "sFirst": "Primera",
           "sPrevious": "Anterior",
           "sLoadingRecords": "Cargando ...",
           "sSearch": "Filtrar por:"

        }
      });

     // var oSettings = oTable.fnSettings();
     

      
    }};
    jQuery('#pivot-demo').pivot_display('setup', input);
   
  };

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

    jQuery.ajax({
           async: false,
           data: {'createdtime':createdtime},
           url:  'index.php?module=Analisis&view=ClientesEdad&mode=Ajax',
           dataType:"json",
           success: function(data) {
             arr=data;
              //var data = JSON.parse(arr);
              var chartDataAux = [];
              var arrayLength = data[0].length;
              if(arrayLength>0){
                
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

</script>


{/literal}