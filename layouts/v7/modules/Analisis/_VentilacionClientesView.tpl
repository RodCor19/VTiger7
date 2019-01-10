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
    <span class="recordLabel font-x-x-large textOverflowEllipsis span pushDown" title="gika"><span class="">Ventilación de los Clientes</span>&nbsp;</span>
  </div>
  <div class="row-fluid detailViewTitle"><span class="row-fluid"><span class="muted">¿Cuántos clientes tenemos en cada programa? Son consistentes esos programas?
</span></span></div>
  <div class="detailViewInfo row-fluid">
    <div class="row-fluid">
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
    </div>
    
     <div class="row-fluid" style="padding-top:5px;">
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
    </div>
        <div class="row-fluid" style="padding:5px 0;">
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
    <div class="row-fluid">
        <div class="span8" style="margin-left:5px;">
            <a class="btn addButton" href="#" style="padding:4px 6px;float:left;font-weight:bold;margin-right:5px;font-family:'Helvetica Neue', Helvetica, Arial, sans-serif;" onclick=" tableToExcel('pivot-table','Ventilacion de los Clientes');">  Exportar a Excel  </a>
            <button style="float:left;" class="btn addButton" onclick="saveExcel('widgetChartContainer','results','Ventilacion de los Clientes');"><i class="icon-download icon-white"></i>&nbsp;<strong>Guardar para Análisis de Datos</strong></button>
            <button id="widgetChartContainerView" style="float:left;display:none;margin-left:10px;"class="btn addButton" onclick=""><i class="icon-signal icon-white"></i>&nbsp;<strong>  Ver Análisis</strong></button>
            <div id="widgetChartContainerLoader" style="text-align:center;display:none;"><img src="layouts/vlayout/skins/softed/images/loading.gif" border="0" align="absmiddle"></div>
        </div>
        <form action="download.php" method="post" target="_blank" id="FormularioExportacion">
            <input type="hidden" id="datos_a_enviar" name="datos_a_enviar" />
            <input type="hidden" id="nombre_a_enviar" name="nombre_a_enviar" />
            <input type="hidden" id="is_submited" name="is_submited" />
          </form>
    </div>
    </div>
  </div>
</div>

{literal}

<script type="text/javascript">

  var field_definitions = [
          {name: 'Programa',   type: 'string',   filterable: true, rowLabelable: true},
          {name: 'Generico',   type: 'string',   filterable: true, rowLabelable: true},
          {name: 'Todas Personas',   type: 'string',   filterable: true, rowLabelable: true},
          {name: 'Comprador Frecuente',   type: 'string',   filterable: true, rowLabelable: true},
          {name: 'Socio Afin',   type: 'string',   filterable: true, rowLabelable: true},
          {name: 'Los 1',   type: 'string',   filterable: true, rowLabelable: true},
          {name: 'Socio J&J',   type: 'string',   filterable: true, rowLabelable: true},
          {name: 'Programas Gaviotas',   type: 'string',   filterable: true, rowLabelable: true},
          {name: 'Administrativo',   type: 'string',   filterable: true, rowLabelable: true},
          {name: 'Funcionario',   type: 'string',   filterable: true, rowLabelable: true},
          {name: 'Proveedor',   type: 'string',   filterable: true, rowLabelable: true},
          {name: 'WTC Member',   type: 'string',   filterable: true, rowLabelable: true},
          {name: 'Club de Golf',   type: 'string',   filterable: true, rowLabelable: true},
          {name: 'Tarjeta Beneficios',   type: 'string',   filterable: true, rowLabelable: true},
          {name: 'Orden',   type: 'string',   filterable: false, rowLabelable: true},
          ]   
 

  var chartData;
  var orden;
  $( document ).ready(function() {
    jQuery.noConflict();      
    //chartData = getChartRelatedData();
    var jData ={/literal}{$DATA}{literal};
    //chartData = JSON.parse(jData);
    chartData = jData;
     jQuery("#dashChartLoader").hide();    

    //datos=jData;

    //datos.sort(sortFunction);
    datos=sortCopy(chartData);
    orden=["Programa"];
    for (var i = datos.length - 1; i >= 0; i--) {
      if( datos[i][0]!="Programa"){
          orden.push(datos[i][0]);
      }
    };
    //orden.replace(/,+$/,'');
    orden.push("Orden");
    graficar();
    
  });
  function sortCopy(arr) { 
  return arr.slice(0).sort(sortFunction);
}
  function sortFunction(a, b) {
      var valA=a[14];
      var valB=b[14];
      valA=valA.replace(new RegExp(/[^0-9A-Za-z ]/g),"");
      valB=valB.replace(new RegExp(/[^0-9A-Za-z ]/g),"");

      return parseInt(valA)-parseInt(valB);
      /*if (valA === valB) {
          return 0;
      }
      else {
        if(valA< valB){
          return -1;
        }else{
          return 1;
        }
          //return (valA < valB) ? -1 : 1;
      }*/
  }
  var oTable=null;
  function graficar(){
      //'Programa', 'Todas Personas','Comprador Frecuente', 'Los 1',  'Programas Gaviotas','Funcionario', 'WTC Member', 'Tarjeta Beneficios','Orden'


      setupPivot({json: chartData, fields: field_definitions, filters: {}, rowLabels:
        orden,columnLabels:[] ,summaries:[]})
          
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

        },"aoColumnDefs": [
                 { "bVisible": false, "aTargets": [ 8 ] },
        ],
              "aaSorting": [[8,'desc']],
      });

     // var oSettings = oTable.fnSettings();
     

      
    }};
    jQuery('#pivot-demo').pivot_display('setup', input);


    var rows = document.getElementById("pivot-table").getElementsByTagName('tr');
    var columns = rows[1].getElementsByTagName('td');
    jQuery( columns[1] ).css("background-color", "#00B4E6");jQuery( columns[1] ).css("color", "#FFF");
    var columns = rows[2].getElementsByTagName('td');
    jQuery( columns[2] ).css("background-color", "#00B4E6");jQuery( columns[2] ).css("color", "#FFF");
    var columns = rows[3].getElementsByTagName('td');
    jQuery( columns[3] ).css("background-color", "#00B4E6");jQuery( columns[3] ).css("color", "#FFF");
    var columns = rows[4].getElementsByTagName('td');
    jQuery( columns[4] ).css("background-color", "#00B4E6");jQuery( columns[4] ).css("color", "#FFF");
    var columns = rows[5].getElementsByTagName('td');
    jQuery( columns[5] ).css("background-color", "#00B4E6");jQuery( columns[5] ).css("color", "#FFF");
    var columns = rows[6].getElementsByTagName('td');
    jQuery( columns[6] ).css("background-color", "#00B4E6");jQuery( columns[6] ).css("color", "#FFF");
    var columns = rows[7].getElementsByTagName('td');
    jQuery( columns[7] ).css("background-color", "#00B4E6");jQuery( columns[7] ).css("color", "#FFF");
   /* var columns = rows[7].getElementsByTagName('td');
    jQuery( columns[7] ).css("background-color", "#00B4E6");jQuery( columns[7] ).css("color", "#FFF");
    var columns = rows[8].getElementsByTagName('td');
    jQuery( columns[8] ).css("background-color", "#00B4E6");jQuery( columns[8] ).css("color", "#FFF");
    var columns = rows[9].getElementsByTagName('td');
    jQuery( columns[9] ).css("background-color", "#00B4E6");jQuery( columns[9] ).css("color", "#FFF");
    var columns = rows[10].getElementsByTagName('td');
    jQuery( columns[10] ).css("background-color", "#00B4E6");jQuery( columns[10] ).css("color", "#FFF");
    var columns = rows[11].getElementsByTagName('td');
    jQuery( columns[11] ).css("background-color", "#00B4E6");jQuery( columns[11] ).css("color", "#FFF");
    var columns = rows[12].getElementsByTagName('td');
    jQuery( columns[12] ).css("background-color", "#00B4E6");jQuery( columns[12] ).css("color", "#FFF");
   */
  };

  function actualizar(){
    
   
    var tipo = jQuery('#tipo').children('option:selected').val();
     var edad = jQuery('#edades').val();
    var canal = jQuery('#canal').val();
    var estatuto = jQuery('#estatuto').val();
    var sexo = jQuery('#sexo').val();
    var programa = jQuery('#programa').val();
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
    
     jQuery("#dashChartLoader").show();    
    jQuery("#widgetChartContainer").hide(); 
    jQuery.ajax({
           async: false,
           data: {'tipo':tipo,'rango':edad_str,'sexo':sexo_str,'canal':canal_str,'estatuto':estatuto_str,'programa':programa_str},
           url:  'index.php?module=Analisis&view=VentilacionClientes&mode=Ajax',
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