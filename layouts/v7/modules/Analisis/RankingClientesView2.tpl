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
    <span class="recordLabel font-x-x-large textOverflowEllipsis span pushDown" title="gika"><span class="">Ranking de  Clientes</span>&nbsp;</span>
    <span class="row-fluid"><span class="muted">Se visualizan los 100 primeros registros. Si se selecciona la Vista "Todos" puede demorar en cargar.</span></span>
  </div>
  <div class="detailViewInfo row-fluid">
    <div class="row-fluid">
      <span class="span3">
        <span class="pull-left" style="padding-left:10px;padding-top:5px;">
          <label for="edades">
            Vista:
          </label>
        </span>
      </span>
      <span class="span3">
        <select class="select2" name="vista" id="vista" style="width: 73%"  onChange="javascript:actualizar();">
          
          <option value="mayor">
              Mayor Evolución
            </option>
          <option value="menor">
              Menor Evolución
            </option>
          <option value="todos">
              Todos
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
    <div id="dashChartLoader" style="text-align:center;"><img src="layouts/vlayout/skins/softed/images/loading.gif" border="0" align="absmiddle"></div>
    <div id="widgetChartContainer" class="  details">

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
          {name: 'Nombre',   type: 'string',   filterable: true, rowLabelable: true},
          {name: 'Apellido',   type: 'string',   filterable: true, rowLabelable: true},
          {name: 'Mail',   type: 'string',   filterable: true, rowLabelable: true},
          {name: 'Teléfono',   type: 'string',   filterable: true, rowLabelable: true},
          {name: 'Fecha de Nac.',   type: 'string',   filterable: true, rowLabelable: true},
          {name: 'Consumo Actual',   type: 'float',   filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,'.',',')}},
          {name: 'Consumo Anterior',   type: 'float',   filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,'.',',')}},
          {name: 'Evolución',   type: 'float',   filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,'.',',')}},
          ]   
 

  var chartData;
  $( document ).ready(function() {
    jQuery.noConflict();      
    //chartData = getChartRelatedData();
    var jData ={/literal}{$DATA}{literal};
    //chartData = JSON.parse(jData);
    chartData = jData;
     jQuery("#dashChartLoader").hide();    
    graficar();
    
  });
  
  var oTable=null;
  function graficar(){
      
      setupPivot({json: chartData, fields: field_definitions, filters: {}, rowLabels:['Nombre', 'Apellido','Mail', 
        'Teléfono','Fecha de Nac.', 'Consumo Actual', 'Consumo Anterior', 'Evolución'],columnLabels:[] ,summaries:[]})
          

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
    
   
    var tipo = jQuery('#tipo').children('option:selected').val();
     var edad = jQuery('#edades').val();
    var canal = jQuery('#canal').val();
    var estatuto = jQuery('#estatuto').val();
    var sexo = jQuery('#sexo').val();
    var programa = jQuery('#programa').val();
    //var sexo = jQuery('#sexo').children('option:selected').val();
     var vista = jQuery('#vista').children('option:selected').val();
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
           data: {'tipo':tipo,'rango':edad_str,'sexo':sexo_str,'canal':canal_str,'estatuto':estatuto_str,'vista':vista,'programa':programa_str},
           url:  'index.php?module=Analisis&view=RankingClientes&mode=Ajax',
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