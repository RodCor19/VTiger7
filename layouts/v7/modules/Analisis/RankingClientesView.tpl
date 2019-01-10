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
  .dataTables_processing{
    top:200px!important;
    background-color: #c2c2c2!important;
  }
</style>

<div class="detailViewContainer">
  <div class="row-fluid detailViewTitle">
    <span class="recordLabel font-x-x-large textOverflowEllipsis span pushDown" title="gika"><span class="">Ranking de  Clientes (Sólo Gaviotas Contado)</span>&nbsp;</span>
    
  </div>
  <div class="row-fluid detailViewTitle"><span class="row-fluid">
    <span class="muted">¿Cómo podemos segmentar a los clientes para armar un mailing?
    </span><br>
    <span class="muted">Consumo actual = gaviotas generadas esos últimos 12 meses</span><br>
    <span class="muted">Consumo anterior = gaviotas generadas el año anterior</span>
  </span></div>
  <div class="detailViewInfo row-fluid">
    <!-- mas nuevo todavia -->
    <div class="lp-filtro-grupo-titulo activo">
      <span title='gika'>
        <i class="icon-chevron-down"></i>
        <span>Filtros</span>
        <span class="badge"></span>
      </span>
    </div>
    <div class="lp-filtro-grupo" style="display: block;">

   <div class="row-fluid" style="padding-top:5px;">
    

    <!--nuevo-->
    <span class="span3">
      <span class="pull-left" style="padding-left:10px;padding-top:5px;">
        <label for="createdtime">
          Rango de Fechas:
        </label>
      </span>
    </span>
    <span class="span3">
      <input type="text" name="createdtime" id="createdtime" value="{$date_range}" class="dateRange widgetFilter dateField" data-date-format="dd-mm-yyyy">
    </span>
    <!--nuevo-->
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
  <div class="row-fluid" style="padding:5px 0;">
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

<div class="row-fluid">
  <!-- Promociones -->
  <span class="span3">
    <span class="pull-left" style="padding-left:10px;padding-top:5px;">
      <label for="tipo">
        Promociones:
      </label>
    </span>
  </span>
  <span class="span3" style="padding-top: 5px; padding-bottom: 5px;">
    <select multiple class="select2" style="width: 85%" name="promociones" onChange="javascript:actualizar()" id="promociones" >
    {foreach item=campo from=$promociones}
      <option value="{$campo[0]}" >
        {$campo[1]}
      </option>
    {/foreach}
  </select>
  </span>
  <!-- Promociones -->
</div>

<div class="row-fluid">
  <!-- vista -->

   <span class="span3">
    <span class="pull-left" style="padding-left:10px;padding-top:5px;">
      <label for="edades">
        Vista:
      </label>
    </span>
  </span>
  <span class="span3">
    <select  name="vistaSel" id="vistaSel" style="width: 73%"  onChange="javascript:actualizar();">

      <option value="G">
        Gaviotas
      </option>
      <option value="B">
        Boletas Canjeadas
      </option>
      <option value="S">
        Compras Scotia
      </option>         
    </select>
  </span>

  <!-- hasta aca es vista -->

  <!-- con vales -->

   <span class="span3">
    <span class="pull-left" style="padding-left:10px;padding-top:5px;">
      <label for="convales">
        Con Vales:
      </label>
    </span>
  </span>
  <span class="span3">
    <input type="checkbox" name="convales" id="convales" onChange="javascript:actualizar();">
  </span>

  <!-- hasta aca es con vales -->

</div>
<div class="row-fluid">


   <span class="span3">
    <span class="pull-left" style="padding-left:10px;padding-top:5px;">
      <label for="concanjes">
        Con Canjes:
      </label>
    </span>
  </span>
  <span class="span3">
    <input type="checkbox" name="concanjes" id="concanjes" onChange="javascript:actualizar();">
  </span>


   <span class="span3">
    <span class="pull-left" style="padding-left:10px;padding-top:5px;">
      <label for="tienegaviotas">
        Tiene Gaviotas:
      </label>
    </span>
  </span>
  <span class="span3">
    <input type="checkbox" name="tienegaviotas" id="tienegaviotas" onChange="javascript:actualizar();">
  </span>



</div>
<div class="row-fluid">
   <span class="span3">
    <span class="pull-left" style="padding-left:10px;padding-top:5px;">
      <label for="tienevisa">
        Tiene Visa:
      </label>
    </span>
  </span>
  <span class="span3">
    <input type="checkbox" name="tienevisa" id="tienevisa" onChange="javascript:actualizar();">
  </span>


   <span class="span3">
    <span class="pull-left" style="padding-left:10px;padding-top:5px;">
      <label for="tieneamex">
        Tiene American Express:
      </label>
    </span>
  </span>
  <span class="span3">
    <input type="checkbox" name="tieneamex" id="tieneamex" onChange="javascript:actualizar();">
  </span>

</div>


</div>
<div class="row-fluid"  style="padding-bottom: 10px;">
  <!-- mas nuevo todavia -->
  <div class="lp-filtro-grupo-titulo activo tabla-pivot">
    <span title='gika'>
      <i class="icon-chevron-right"></i>
      <span>Tabla Pivot</span>
      <span class="badge"></span>
    </span>
  </div>
  <div class="lp-filtro-grupo" style="display: block;">
    <div class="row-fluid">
      <!-- Campos pivot -->
      <span class="span3">
        <span class="pull-left" style="padding-left:10px;padding-top:5px;">
          <label for="campospivot">
            Campos Pivot:
          </label>
        </span>
      </span>
      <span class="span3">
        <select multiple class="select2" style="width: 85%" name="campospivot" id="campospivot" onChange="javascript:actualizarPivot();" >
          {foreach item=campo from=$campospivot}
          <option value="{$campo[0]}" >
              {$campo[1]}
            </option>
          {/foreach}
        </select>
      </span>
      <!-- Campos pivot -->
        <!-- tabla pivot -->
       <span class="span3">
        <span class="pull-left" style="padding-left:10px;padding-top:5px;">
          <label for="tablapivot">
            Tabla Pivot:
          </label>
        </span>
      </span>
      <span class="span3">
        <input type="checkbox" name="tablapivot" id="tablapivot" onChange="javascript:actualizarPivot();">
      </span>

      <!-- hasta aca es tabla pivot -->
    </div>
  </div>
</div>


<div id="widgetChartContainer" class="  details">
  <table id="example" class="display" cellspacing="0" width="100%" >
    <thead>
      <tr>
        <th>Documento</th>
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
      <a class="btn addButton" href="#" style="padding:4px 6px;float:left;font-weight:bold;margin-right:5px;font-family:'Helvetica Neue', Helvetica, Arial, sans-serif;" onclick=" cargarModalAddToCampaign();">
          Exportar a Campaña 
      </a>
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
<div id="tabla-pivot" hidden="hidden">
  <div id="dashChartLoader" style="text-align:center;">
        <img src="layouts/vlayout/skins/softed/images/loading.gif" border="0" align="absmiddle">
  </div>
  <div id="output" style="margin: 10px;"></div>
  <div class="row-fluid" style="margin-top: 10px;">
        <a class="btn addButton" href="#" style="padding: 4px 6px; float: left; font-weight: bold; margin-right: 5px; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;" onclick="exportarExcelPivot('RankingClientes');">
            Exportar a Excel
        </a>
        <form action="download.php" method="post" target="_blank" id="p_FormularioExportacion">
            <input type="hidden" id="p_datos_a_enviar" name="datos_a_enviar" />
            <input type="hidden" id="p_nombre_a_enviar" name="nombre_a_enviar" />
            <input type="hidden" id="p_is_submited" name="is_submited" />
        </form>
    </div>
</div>
</div>
</div>
</div>
{literal}
<script type="text/javascript">
  

  var calcularTotal=0;
  $( document ).ready(function() {
    jQuery('#dashChartLoader').hide();
    jQuery('#tabla-pivot').hide();
    //jQuery.noConflict();      
    graficar();
    //nuevo
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
    //nuevo
  });

  var oTable=null;
  function graficar(){
    calcularTotal=1;
    //nuevo
    var dateRangeVal = jQuery('.dateRange').val();
    var desde = "";
    var hasta = "";
    if (dateRangeVal.length > 0) {
      var dateRangeValComponents = dateRangeVal.split(',');      
      desde = dateRangeValComponents[0];
      hasta = dateRangeValComponents[1];
    }
    //nuevo
    var tipo = jQuery('#tipo').children('option:selected').val();
    var edad = jQuery('#edades').val();
    var canal = jQuery('#canal').val();
    var estatuto = jQuery('#estatuto').val();
    var sexo = jQuery('#sexo').val();
    var programa = jQuery('#programa').val();
    var vistaSel = jQuery('#vistaSel').val();
    //var sexo = jQuery('#sexo').children('option:selected').val();
    var vista = jQuery('#vista').children('option:selected').val();

    //filtros nuevos
    var conCanjes = jQuery('#concanjes').prop('checked');
    var conVales = jQuery('#convales').prop('checked');
    var tieneVisa = jQuery('#tienevisa').prop('checked');
    var tieneAmex = jQuery('#tieneamex').prop('checked');
    var tieneGaviotas = jQuery('#tienegaviotas').prop('checked');

    

    var sexo_str="";
    var edad_str="";
    var canal_str="";
    var estatuto_str="";
    var programa_str="";
    var desde_str ="";
    var hasta_str="";
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
    //if (desde!== null)desde_str=desde.join(',');
    //if (hasta!== null)hasta_str=hasta.join(',');

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
                  return"<a href='index.php?module=Contacts&view=Detail&record="+row[12]+"'>Ver</a>";
                },
                "aTargets": [ 12 ]
              },

              {
                  "mRender": function (data, type, row) {
                  return commaSeparateNumber(data);
              },
              "aTargets": [6,7,8]
              },
              
              ],
              "fnServerParams": function ( aoData ) {
                if(oTable!=null){
                 total_records=oTable.fnSettings().fnRecordsTotal()
                 display_records=oTable.fnSettings().fnRecordsDisplay()
               } 
               aoData.push( {"name": "tipo", "value": tipo},{"name": "rango", "value": edad_str},{"name": "sexo", "value": sexo_str},
                {"name": "canal", "value": canal_str},{"name": "estatuto", "value": estatuto_str},{"name": "vista", "value": vista},
                {"name": "vistaSel" , "value": vistaSel},
                {"name": "programa", "value": programa_str},{"name": "calcularTotal", "value": calcularTotal}
                ,{"name": "total_records", "value": total_records},{"name": "display_records", "value": display_records},{"name": "desde","value":desde},{"name": "hasta","value":hasta},{"name": "conCanjes","value":conCanjes},{"name": "conVales","value":conVales},
                {"name": "tieneVisa","value":tieneVisa},{"name": "tieneAmex","value":tieneAmex},
                {"name": "tieneGaviotas","value":tieneGaviotas});

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
    if(jQuery('#tablapivot').prop('checked'))
      actualizarPivot();
    else
      graficar(); 
  }

  function actualizarPivot(){
    var tablaPivot = jQuery('#tablapivot').prop('checked');
    if(!tablaPivot){
      jQuery("#widgetChartContainer").show();
      jQuery('#tabla-pivot').hide();
      jQuery('#dashChartLoader').hide();
      actualizar();
      return;
    }
    jQuery('#widgetChartContainer').hide();
    jQuery('#tabla-pivot').show();
    jQuery('#dashChartLoader').show();


    //sigo con la tabla pivot

    var dateRangeVal = jQuery('.dateRange').val();
    var desde = "";
    var hasta = "";
    if (dateRangeVal.length > 0) {
      var dateRangeValComponents = dateRangeVal.split(',');      
      desde = dateRangeValComponents[0];
      hasta = dateRangeValComponents[1];
    }
    //nuevo
    var tipo = jQuery('#tipo').children('option:selected').val();
    var edad = jQuery('#edades').val();
    var canal = jQuery('#canal').val();
    var estatuto = jQuery('#estatuto').val();
    var sexo = jQuery('#sexo').val();
    var programa = jQuery('#programa').val();
    var vistaSel = jQuery('#vistaSel').val();
    //var sexo = jQuery('#sexo').children('option:selected').val();
    var vista = jQuery('#vista').children('option:selected').val();

    var camposPivot = jQuery("#campospivot").val();
    if (camposPivot!== null) camposPivot = camposPivot.join(',');

    var promociones = jQuery("#promociones").val();
    if (promociones !== null) promociones = promociones.join(' |##| ');
    else promociones = "";


    //filtros nuevos
    var conCanjes = jQuery('#concanjes').prop('checked');
    var conVales = jQuery('#convales').prop('checked');
    var tieneVisa = jQuery('#tienevisa').prop('checked');
    var tieneAmex = jQuery('#tieneamex').prop('checked');
    var tieneGaviotas = jQuery('#tienegaviotas').prop('checked');

    

    var sexo_str="";
    var edad_str="";
    var canal_str="";
    var estatuto_str="";
    var programa_str="";
    var desde_str ="";
    var hasta_str="";
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

    var params = {"tipo": tipo,"rango": edad_str,"sexo": sexo_str,"canal": canal_str,"estatuto": estatuto_str,"vista": vista,
                  "vistaSel": vistaSel,"programa": programa_str,"desde":desde,"hasta":hasta,
                  "conCanjes": conCanjes, "conVales": conVales, "tieneVisa": tieneVisa, "tieneAmex": tieneAmex, "tieneGaviotas": tieneGaviotas, "campospivot":camposPivot, "promociones":promociones};


    jQuery.ajax({
      data:     params,
      url:    "index.php?module=Analisis&view=RankingClientes&mode=pivot",
      dataType:   'json',

      success: function (data)
      {
        //console.log(data);
        graficarTablaPivot(data);
      },

      error: function (xhr, ajaxOptions, thrownError)
      {
        console.error(thrownError);
      }
  });


  }

  function graficarTablaPivot(datos){
    console.log("graficarTablaPivot");
    console.log(datos);
    var derivers= jQuery.pivotUtilities.derivers,
          tpl     = jQuery.pivotUtilities.aggregatorTemplates,
          cols  = datos.cols,
          renders = jQuery.extend(
              // Tablas
              {
                  "Tabla": jQuery.pivotUtilities.renderers["Table"],
                  "Tabla con barras": jQuery.pivotUtilities.renderers["Table Barchart"],
                  "Heatmap": jQuery.pivotUtilities.renderers["Heatmap"],
                  "Heatmap por filas": jQuery.pivotUtilities.renderers["Row Heatmap"],
                  "Heatmap por columnas": jQuery.pivotUtilities.renderers["Col Heatmap"]
              },
              // Gráficos
              {
                  "Gráfico de Líneas": jQuery.pivotUtilities.c3_renderers["Line Chart"],
                  "Gráfico de Barras": jQuery.pivotUtilities.c3_renderers["Bar Chart"],
                  "Gráfico de Barras Apilado": jQuery.pivotUtilities.c3_renderers["Stacked Bar Chart"],
                  "Gráfico de Área": jQuery.pivotUtilities.c3_renderers["Area Chart"],
                  "Gráfico de Dispersión": jQuery.pivotUtilities.c3_renderers["Scatter Chart"]
              },
              // Treemap de D3
              jQuery.pivotUtilities.d3_renderers
          );

      
      // Ocultar GIF de preloading
      jQuery('#dashChartLoader').hide();

      // Ubicar gráfica
      jQuery('#output').remove();
      jQuery('#tabla-pivot').append('<div id="output" class="lp-tabla-pivot"></div>');

      // Si hay fechas, crear los derivers
      var fechas = {};

      if (datos.fech.length > 0)
      {
          datos.fech.forEach(function (campo)
          {
              fechas[campo + "(Año-Mes)"] = derivers.dateFormat(campo, '%y-%m');
              fechas[campo + "(Año)"] = derivers.dateFormat(campo, '%y');
          })
      }

      jQuery("#output").pivotUI(
          datos.data, 
          {
              rows:               {},
              cols:               {},
              renderers:          renders,
              derivedAttributes:  fechas
          },
          false,
          'es'
      );
  }

  function exportarExcelPivot (nombre)
{
    var $tabla  = jQuery('.pvtRendererArea'),
        html    = jQuery('<div>')
            .append($tabla.eq(0).clone())
            .html();

    // Normalizar caracteres raros
    while (html.indexOf('á') != -1) html = html.replace('á', '&aacute;');
    while (html.indexOf('Á') != -1) html = html.replace('Á', '&Aacute;');
    while (html.indexOf('é') != -1) html = html.replace('é', '&eacute;');
    while (html.indexOf('É') != -1) html = html.replace('É', '&Eacute;');
    while (html.indexOf('í') != -1) html = html.replace('í', '&iacute;');
    while (html.indexOf('Í') != -1) html = html.replace('Í', '&Iacute;');
    while (html.indexOf('ó') != -1) html = html.replace('ó', '&oacute;');
    while (html.indexOf('Ó') != -1) html = html.replace('Ó', '&Oacute;');
    while (html.indexOf('ú') != -1) html = html.replace('ú', '&uacute;');
    while (html.indexOf('Ú') != -1) html = html.replace('Ú', '&Uacute;');
    while (html.indexOf('º') != -1) html = html.replace('º', '&ordm;');
    while (html.indexOf('ñ') != -1) html = html.replace('ñ', '&ntilde;');
    while (html.indexOf('Ñ') != -1) html = html.replace('Ñ', '&Ntilde;');

    // Armar el form para enviar
    jQuery("#p_datos_a_enviar").val(html);
    jQuery("#p_nombre_a_enviar").val(nombre);
    jQuery("#p_is_submited").val((new Date()).getTime());
    jQuery("#p_FormularioExportacion").submit();

    return false;
}

 function cargarModalAddToCampaign(){
    Vtiger_Helper_Js.checkCantidadRanking({
        'action' : 'CheckCantidadRanking',
        'module' : 'Analisis'
    }).then(
        function(data){
            var params = {};
            params['module'] = 'Analisis';
            params['view'] = 'CargarModalAddToCampaign';
            //params['record'] = jQuery("#recordId").val();
            AppConnector.request(params).then(
                function(data) {
                    var callBackFunction = function(data) {
                    }
                    app.showModalWindow(data,function(data){
                        if(typeof callBackFunction == 'function'){
                            callBackFunction(data);
                        }
                    }, {'width':'505px'});
                },
                function(error) {
                    console.log("error");
                }
            );
        },
        function(error, err){
             Vtiger_Helper_Js.showMessage({'text' : "El ranking no tiene clientes", 'type' : 'error'});
        } 
     );
 
}

 function commaSeparateNumber(val) {
    /*while (/(\d+)(\d{3})/.test(val.toString())) {
        val = val.toString().replace(/(\d+)(\d{3})/, '$1' + '.' + '$2');
    }
    return val;*/
    num=parseFloat(val);
    var p = num.toFixed(2).split(".");
    return p[0].split("").reverse().reduce(function(acc, num, i, orig) {
        return  num + (i && !(i % 3) ? "." : "") + acc;
    }, "") + "," + p[1];

}

</script>


{/literal}