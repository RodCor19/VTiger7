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
  <div class="row-fluid detailViewTitle"  style="padding-bottom:10px;"><a href="index.php?module=Analisis&view=Deudores">Deudores</a>
    <span class="recordLabel font-x-x-large textOverflowEllipsis span pushDown" title="gika"><span class="">Saldos Contables</span>&nbsp;</span>
    <span class="row-fluid">
      <span class="muted"></span>
    </span>
  </div>
  <div class="row-fluid detailViewTitle"><span class="row-fluid"><span class="muted"></span></span></div>
  <div class="detailViewInfo row-fluid">
       
    <div class="detailViewInfo row-fluid">
        <div class="row-fluid">
      <span class="span2">
        <span class="pull-left" style="padding-left:10px;padding-top:5px;">
          <label>
        Fecha:
          </label>
        </span>
      </span>
      <span class="span2">
      <select class="select2" style="width: 85%" name="mes" id="mes" onChange="javascript:actualizar();" >
          {foreach item=mes from=$meses}
          <option value="{$mes['num']}" {if $mes['num'] == $mesActual}selected=""{/if}>
              {$mes['nombre']}
            </option>
          {/foreach}
        </select>
      </span>
      <span class="span2">
        <select class="select2" style="width: 85%" name="anio" id="anio" onChange="javascript:actualizar();" >
          {foreach item=anio from=$anios}
          <option value="{$anio}" {if $anio == $anioActual}selected=""{/if}>
              {$anio}
            </option>
          {/foreach}
        </select>
      </span>
       <span class="span2">
{*M E*}        <span class="pull-left" style="padding-left:10px;padding-top:5px;">
{*M E*}          <label for="local">
{*M E*}            Locales:
{*M E*}          </label>
{*M E*}        </span>
{*M E*}      </span>
{*M E*}      <span class="span3">
{*M E*}        <select multiple class="select2" style="width: 85%" name="local" id="local" onChange="javascript:actualizar();" >
{*M E*} <!--         <option value="">{vtranslate('LBL_ALL', $MODULE_NAME)}</option>-->
{*M E*}          {foreach item=local from=$locales}
{*M E*}          <option value="{$local[1]}" {if $local[1]== $idLocal}selected=""{/if}>
{*M E*}              {$local[0]}
{*M E*}            </option>
{*M E*}          {/foreach}
{*M E*}        </select>
{*M E*}      </span>
    </div>
    
    <div class="  details">
      <span id="pivot-detail" style="margin: 10px;"></span>
      <ul class="dropdown-menu stop-propagation" style="display:none;"> <div id="row-label-fields"></div>
          </ul>
       <div id="results" style="margin: 10px;"></div>
       <div style="float:left;">
            <input type="hidden" name="min" id="min">
          </div>
          <div style="float:left;">
            <input type="hidden" name="max" id="max">
          </div>
    <div class="row-fluid">
        <div class="span8" style="margin-left:5px;">
            <a class="btn addButton" href="#" style="padding:4px 6px;float:left;font-weight:bold;margin-right:5px;font-family:'Helvetica Neue', Helvetica, Arial, sans-serif;" onclick=" tableToExcel('pivot-table','Saldos Contables');">  Exportar a Excel  </a>
            <button style="float:left;" class="btn addButton" onclick="saveExcel('results','results','Saldos Contables');"><i class="icon-download icon-white"></i>&nbsp;<strong>Guardar para Análisis de Datos</strong></button>
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
  </div>
</div>

{literal}
<style type="text/css">
.color1{
  background-color: #BDD7EE!important;
}
.color2{
  background-color: #F8CBAD!important;
}
.color3{
  background-color: #C6E0B4!important;
}
.color4{
  background-color: #BFBFBF!important;
}
.color5{
  background-color: #e6ccff!important;
}
th.color1,th.color2{
  color: #000!important;
}
th.color3,th.color4,th.color5{
  color: #000!important;
}

tr.myodd td{
  background-color: #BFBFBF!important;
}
tr.myeven td{
  background-color: #f9f9f9!important;
}
</style>

<script type="text/javascript">
var decimalSep=',';
var milSep='.';
var field_definitions=[];

function pushIfNew(obj) {
  for (var i = 0; i < field_definitions.length; i++) {
    if (field_definitions[i].name === obj.name) { // modify whatever property you need
      return;
    }
  }
  field_definitions.push(obj);
}


  

  var chartData;
  $( document ).ready(function() {

    decimalSep=getDecimalSeparator();
    milSep=getMilSeparator();

    //alert(getFirstBrowserLanguage());


    //  alert(decimalSep);


    // Nro || Nombre Fantasía || Contrato || Deuda a Vencer || Deuda Vencida || Total Deuda || % Morosidad
    field_definitions = [
          {name: 'Nro',   type: 'string',   filterable: true, rowLabelable: true},
          {name: 'Nombre Fantasía',   type: 'string',   filterable: true, rowLabelable: true},
          {name: 'Contrato',   type: 'int',   filterable: true, rowLabelable: true},
          {name: 'Deuda a Vencer',   type: 'float',    filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,milSep,decimalSep)}},
          {name: 'Deuda Vencida',   type: 'float',    filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,milSep,decimalSep)}},
          {name: 'Total Deuda',   type: 'float',    filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,milSep,decimalSep)}},
          {name: '% Morosidad',   type: 'float',    filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,milSep,decimalSep)}},
          {name: 'Deuda a Vencer (Mes Año Anterior)',   type: 'float',    filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,milSep,decimalSep)}},
          {name: 'Deuda Vencida (Mes Año Anterior)',   type: 'float',    filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,milSep,decimalSep)}},
          {name: 'Total Deuda (Mes Año Anterior)',   type: 'float',    filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,milSep,decimalSep)}},
          {name: '% Morosidad (Mes Año Anterior)',   type: 'float',    filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,milSep,decimalSep)}},
          {name: 'Deuda a Vencer (Variacion)',   type: 'float',    filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,milSep,decimalSep)}},
          {name: 'Deuda Vencida (Variacion)',   type: 'float',    filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,milSep,decimalSep)}},
          {name: 'Total Deuda (Variacion)',   type: 'float',    filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,milSep,decimalSep)}},
          {name: '% Morosidad (Variacion)',   type: 'float',    filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,milSep,decimalSep)}},
          
        {name: 'Orden', type: 'int', filterable: false, pseudo: true,
            pseudoFunction: function(row){
              var ret="";
              switch (row.Nro) {
                  case 0:
                      ret = 1;
                      break;
                  
                  default:
                    ret=0;
                    break;    
              }
              return ret;
          }},
          ]   
 

    jQuery.extend( jQuery.fn.dataTableExt.oSort, {
        "numeric-comma-pre": function ( a ) {
            var x = (a == "-") ? 0 : a.replace( /\./g, "" );
            x = (a == "-") ? 0 : x.replace( /,/, "." );
            return parseFloat( x );
        },
     
        "numeric-comma-asc": function ( a, b ) {
            return ((a < b) ? -1 : ((a > b) ? 1 : 0));
        },
     
        "numeric-comma-desc": function ( a, b ) {
            return ((a < b) ? 1 : ((a > b) ? -1 : 0));
        }
    } );
    //jQuery.noConflict();      
    //chartData = getChartRelatedData();
    //var jData ={/literal}{$DATA}{literal};
    //chartData = JSON.parse(jData);
    //chartData = jData;
    //graficar();

    actualizar();   
  });
  
  var oTable=null;
  function graficar(){
      
    var vista = jQuery("#vista").val();
    var agrupar = jQuery("#agrupar").val();
    var filas='';

    //// Nro || Nombre Fantasía || Contrato || Deuda a Vencer || Deuda Vencida || Total Deuda || % Morosidad
    //// Deuda a Vencer (Mes Año Anterior) || Deuda Vencida (Mes Año Anterior) || Total Deuda (Mes Año Anterior) || % Morosidad (Mes Año Anterior)
    filas= ["Nro", "Nombre Fantasía", "Contrato", "Deuda a Vencer", 
        "Deuda Vencida", "Total Deuda", "% Morosidad", "Deuda a Vencer (Mes Año Anterior)", "Deuda Vencida (Mes Año Anterior)", "Total Deuda (Mes Año Anterior)", "% Morosidad (Mes Año Anterior)", "Deuda a Vencer (Variacion)", "Deuda Vencida (Variacion)", "Total Deuda (Variacion)", "% Morosidad (Variacion)","Orden"];
            nroOrden = 15;
            ordenNumerico=[0,2,3,4,5,6,7,8,9,10,11,12,13,14];
            

      setupPivot({json: chartData, fields: field_definitions, filters: {}, rowLabels:filas,columnLabels:[] ,summaries:[]})
          

      // prevent dropdown from closing after selection
      jQuery('.stop-propagation').click(function(event){
        event.stopPropagation();
      });
     
     
      jQuery('#min').keyup( function() { oTable.fnDraw(); } );
      jQuery('#max').keyup( function() { oTable.fnDraw(); } );

      


  }
  function setupPivot(input){
    input.callbacks = {afterUpdateResults: function(){
       // columns array
      var aoColumns = [];
      //aoColumns.push(null);
      //
      
      var total=null;
      var nTotal=0;
      
      vista = jQuery("#vista").val();
      ordenDefault=[[15,'desc']];
      local="";      
      local_ant="";      
       oTable = jQuery('#results > table').dataTable({
        "sDom": "<'row'<'span6'l><'span6'f>>t<'row'<'span6'i><'span6'p>>",
        "iDisplayLength": 20,
        "aLengthMenu": [[20, 40, 60, -1], [20, 40, 60, "Todos"]],
        "sPaginationType": "bootstrap",
        "aaSorting": ordenDefault,
        "aaSortingFixed": [[nroOrden,'asc']],
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

        },
        
        "aoColumnDefs": [
            {"sType": "numeric-comma", "aTargets": ordenNumerico }, 
            { "bVisible": false, "aTargets": [ nroOrden ] }
        ],
         // "aoColumns": aoColumns

         "fnRowCallback": function( row, data, index ) {
            if(index==0){
                local_ant=data[0];
                jQuery(row).removeClass('myodd myeven odd even');
                clase="myodd";
                jQuery(row).addClass(clase);
            }else{
              if(local_ant==data[0]){
                jQuery(row).removeClass('myodd myeven odd even');
                jQuery(row).addClass(clase);
              }else{
                local_ant=data[0];
                if(clase=="myodd"){
                  clase="myeven";
                }else{
                  clase="myodd";
                }
                   
                jQuery(row).removeClass('myodd myeven odd even');
                jQuery(row).addClass(clase);
              }
            }    
            
          }
      });

     // var oSettings = oTable.fnSettings();
     

      
    }};
    jQuery('#pivot-demo').pivot_display('setup', input);
   
  };

  function actualizar(){
    var anio = jQuery("#anio").val();
    var mes = jQuery("#mes").val();
    var createdtime = {};
    createdtime.start = "1-" + mes + "-" + anio;
    createdtime.end =  "31-" + mes + "-" + anio;

    var fechaAnterior = {};
    fechaAnterior.start = "1-" + mes + "-" + (parseInt(anio) - 1);
    fechaAnterior.end = "31-" + mes + "-" + (parseInt(anio) - 1);
    console.log(JSON.stringify(fechaAnterior));

    var nombreLocal_str ="";
    var familia_str ="";
    var rubro_str ="";

    var nombreLocal = jQuery("#local").val();
    if (nombreLocal!== null)nombreLocal_str=nombreLocal.join(',');
    
    
    

    jQuery.ajax({
           async: false,
           data: {'createdtime':createdtime, 'nombreLocal':nombreLocal_str, 'fechaAnterior':fechaAnterior },
           url:  'index.php?module=Analisis&action=SaldosContables',
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