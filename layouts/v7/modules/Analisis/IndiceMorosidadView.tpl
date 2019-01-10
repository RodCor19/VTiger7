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
    <span class="recordLabel font-x-x-large textOverflowEllipsis span pushDown" title="gika"><span class="">Índice de Morosidad</span>&nbsp;</span>
    <span class="row-fluid">
      <span class="muted"></span>
    </span>
  </div>

  <div class="row-fluid detailViewTitle"><span class="row-fluid"><span class="muted"></span></span></div>
  <div class="detailViewInfo row-fluid">
    
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
            <a class="btn addButton" href="#" style="padding:4px 6px;float:left;font-weight:bold;margin-right:5px;font-family:'Helvetica Neue', Helvetica, Arial, sans-serif;" onclick=" tableToExcel('pivot-table','Indice de Morosidad');">  Exportar a Excel  </a>
            <button style="float:left;" class="btn addButton" onclick="saveExcel('results','results','Indice de Morosidad');"><i class="icon-download icon-white"></i>&nbsp;<strong>Guardar para Análisis de Datos</strong></button>
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


    field_definitions = [
          {name: 'Fecha',   type: 'string',   filterable: true, rowLabelable: true},
          {name: 'Deuda Vencida',   type: 'float',    filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,milSep,decimalSep)}},
          {name: '% Variacion',   type: 'float',    filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,milSep,decimalSep)}},
          {name: 'Total Deuda',   type: 'float',    filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,milSep,decimalSep)}},
          {name: '% Morosidad',   type: 'float',    filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,milSep,decimalSep)}},
          {name: 'Variacion Morosidad (pp)',   type: 'float',    filterable: true, rowLabelable: true,displayFunction: function(value){ return accounting.formatNumber(value,0,milSep,decimalSep)}},
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

    ////"Fecha", "Deuda Vencida", "Variacion", "Total Deuda", "% Morosidad", "Variacion Morosidad (pp)", "Orden"
    filas= ["Fecha", "Deuda Vencida", "% Variacion", "Total Deuda", "% Morosidad", "Variacion Morosidad (pp)", "Orden"];
    nroOrden = 6;
    ordenNumerico=[1,2,3,4,5];
            

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
      ordenDefault=[[6,'desc']];
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
      

    jQuery.ajax({
           async: false,
           data: {},
           url:  'index.php?module=Analisis&action=IndiceMorosidad',
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