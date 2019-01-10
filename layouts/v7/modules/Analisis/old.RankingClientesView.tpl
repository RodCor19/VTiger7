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
  th, th a, th:hover, th a:hover{
    color: #000;
  }
  .dataTables_wrapper {
    padding: 10px 5px;
  }
</style>

<div class="detailViewContainer">
  <div class="row-fluid detailViewTitle">
    <span class="recordLabel font-x-x-large textOverflowEllipsis span pushDown" title="gika"><span class="">Ranking de  Clientes</span>&nbsp;</span>
    
  </div>
  <div class="row-fluid detailViewTitle"><span class="row-fluid">
    <span class="muted">¿Cómo podemos segmentar a los clientes para armar un mailing?
    </span><br>
    <span class="muted">Consumo actual = gaviotas generadas esos últimos 12 meses</span><br>
    <span class="muted">Consumo anterior = gaviotas generadas el año anterior</span>
    </span></div>
  <div class="detailViewInfo row-fluid">
   
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
    </div>
    <!--<div id="dashChartLoader" style="text-align:center;"><img src="layouts/vlayout/skins/softed/images/loading.gif" border="0" align="absmiddle"></div>-->
    <div id="widgetChartContainer" class="  details">
      <table id="example" class="display" cellspacing="0" width="100%" >
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Mail</th>
                <th>Teléfono</th>
                <th>Fecha de Nac.</th>
                <th>Consumo Actual</th>
                <th>Consumo Anterior</th>
                <th>Evolución</th>
                <th>Dirección</th>
                <th>Código Postal</th>
                <th>Ciudad</th>
                <th>Link</th>
            </tr>
        </thead>
 
        
    </table>
    <div class="row-fluid">
        <div class="span8" style="margin-left:5px;">
            <a class="btn addButton" href="#" style="padding:4px 6px;float:left;font-weight:bold;margin-right:5px;font-family:'Helvetica Neue', Helvetica, Arial, sans-serif;" onclick=" tableToExcel('example_wrapper','Ranking de Clientes');">  Exportar a Excel  </a>
            <button style="float:left;" class="btn addButton" onclick="saveExcel('widgetChartContainer','example','Ranking de Clientes');"><i class="icon-download icon-white"></i>&nbsp;<strong>Guardar para Análisis de Datos</strong></button>
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
</div>
{literal}
<style type="text/css">
  .dataTables_wrapper .dataTables_processing {
    top:300px;
   } 
</style>
<script type="text/javascript">
  var calcularTotal=0;
  $( document ).ready(function() {
    jQuery.noConflict();      
    graficar();
  });
  var oTable=null;
  function graficar(){
    calcularTotal=1;
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
    total_records=0;
    display_records=0;
    if(oTable!=null){
      total_records=oTable.fnSettings().fnRecordsTotal()
      display_records=oTable.fnSettings().fnRecordsDisplay()
    }
    
    if (sexo!== null)sexo_str=sexo.join(',');
    if (estatuto!== null)estatuto_str=estatuto.join(',');
    if (edad!== null)edad_str=edad.join(',');
    if (canal!== null)canal_str=canal.join(',');
    if (programa!== null)programa_str=programa.join(',');
    oTable=jQuery('#example').dataTable( {
        "bDestroy": true,
        "bProcessing": true,
         "sScrollX": "100%",
        "bScrollCollapse": true,
        "bServerSide": true,
        "sAjaxSource": "index.php?module=Analisis&view=RankingClientes&mode=Ajax",
        "aLengthMenu": [[100, 500, 1000,2000,5000,10000,50000, -1], [100, 500, 1000,2000,5000,10000,50000, "Todos"]],
        "iDisplayLength": 100,
        "aaSorting": [[6,'desc']],
        "aoColumnDefs": [
            {
                // `data` refers to the data for the cell (defined by `mData`, which
                // defaults to the column being worked with, in this case is the first
                // Using `row[0]` is equivalent.
                "mRender": function ( data, type, row ) {
                    return"<a href='index.php?module=Contacts&view=Detail&record="+row[0]+"'>Ver</a>";
                },
                "aTargets": [ 12 ]
            }
            
        ],
        "fnServerParams": function ( aoData ) {
            if(oTable!=null){
             total_records=oTable.fnSettings().fnRecordsTotal()
             display_records=oTable.fnSettings().fnRecordsDisplay()
            } 
            aoData.push( {"name": "tipo", "value": tipo},{"name": "rango", "value": edad_str},{"name": "sexo", "value": sexo_str},
              {"name": "canal", "value": canal_str},{"name": "estatuto", "value": estatuto_str},{"name": "vista", "value": vista},
              {"name": "programa", "value": programa_str},{"name": "calcularTotal", "value": calcularTotal}
              ,{"name": "total_records", "value": total_records},{"name": "display_records", "value": display_records} );
        },
        
        "oLanguage": {
            "sProcessing":     "Procesando...",
            "sSearch":         "Buscar&nbsp;:",
            "sLengthMenu":     "Mostrando _MENU_ filas",
            "sInfo":           "Mostrando _START_ a _END_ de _TOTAL_ filas",
            "sInfoEmpty":      "No hay datos disponibles",
            "sInfoFiltered":   "(filtradas de _MAX_ filas totales)",
            "sInfoPostFix":    "",
            "sLoadingRecords": "Cargando...",
            "sZeroRecords":    "No hay datos disponibles",
            "sEmptyTable":     "No hay datos disponibles",
          "sUrl": "",
          "oPaginate": {
            "sFirst":    "Primera",
            "sPrevious": "Anterior",
            "sNext":     "Siguiente",
            "sLast":     "Última"
          }
        }

          
        
    } );

    calcularTotal=0;
  }
  
  function actualizar(){
   graficar() 
  }

</script>


{/literal}