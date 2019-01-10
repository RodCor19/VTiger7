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
    <link href="libraries/pivot/js/pivot.css" rel="stylesheet" />
    <script src="libraries/pivot/js/jquery-ui-1.9.2.custom.min.js"></script>
    <script src="libraries/pivot/js/pivot.js"></script>
    <script src="libraries/pivot/js/gchart_renderers.js"></script>
    <!--<script src="libraries/pivot/js/nrecopivot.js"></script>  -->
    <script src="libraries/pivot/js/nrecopivottableext.js?v20151118"></script>

<style type="text/css">
.pvtAxisLabel,.pvtRowLabel,.pvtTotalLabel{color: #000;}
.pvtColLabel{color:#222 !important;}
.pvtRowLabel > a{color:#222;}
</style>
 <script type="text/javascript">
    var arr,table;
    var paramsDefault = { aggregatorName : "Cuenta",
                          vals: ["Local"],
                          rows: ['Estado'],
                          cols: ['Asignado a']
                        };
     function getData(){
       //jQuery("#dashChartLoader2").show();
        jQuery.ajax({
           async: false,
           data: {},
           url:  'index.php?module=Project&view=Analisis&mode=data',
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
      console.log(createdtime);

      //PARTE AGREGADA PARA FILTROS CARGADOS

      //PARTE AGREGADA PARA FILTROS CARGADOS

      /*var edad = jQuery('#edades').val();
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
      */
      
       jQuery.ajax({
           async: false,
           data: {'createdtime':createdtime},
           url:  'index.php?module=Analisis&view=Quejas&mode=Ajax',
           dataType:"json",
           success: function(data) {
            console.log(data);
             arr=data;
                  graficar(null);
                
           },
            error: function (xhr, ajaxOptions, thrownError) {
              console.log(thrownError);
          }
         });
    }
    
    function graficar(params){
      if(!params) params = paramsDefault;
      console.log(params);
      var nrecoPivotExt = new NRecoPivotTableExtensions({
      drillDownHandler: function (dataFilter) {
        console.log(dataFilter);
         }
      });
      var sampleData = arr;
      var stdRendererNames = ["Table","Table  Barchart","Heatmap","Row Heatmap","Col Heatmap"];
      var wrappedRenderers = $.extend( {}, $.pivotUtilities.renderers);
      var tpl = $.pivotUtilities.aggregatorTemplates;
      $.each(stdRendererNames, function() {
        var rName = this;
        wrappedRenderers[rName] = nrecoPivotExt.wrapTableRenderer(wrappedRenderers[rName]);
      });
      
      var pvtOpts = {
        menuLimit: 50,
        renderers: wrappedRenderers,
        rendererOptions: { sort: { direction : "desc", column_key : [ 2014 ]} },
        unusedAttrsVertical: false,
        /*aggregators: {  
                        "Suma de Horas" : function (){  return tpl.sum('Horas')();}
        },
        /*
        derivedAttributes : { 'Horas': function(data){ return parseFloat(data['Horas']);},
                              //'Costo': function(data){ return data['Costo']!='null'? parseFloat(data['Costo']) : 'Indefinido';}
                              //'Estado Tarea': function(data){ return data['Estado Tarea'] == "" ? "Indefinido" : "Abierto";}
                            },*/
        renderers:{
            "Tabla":                  $.pivotUtilities.renderers["Table"],
            /*"Tabla con barras":       $.pivotUtilities.renderers["Table Barchart"],
            "Heatmap":                $.pivotUtilities.renderers["Heatmap"],
            "Heatmap por filas":      $.pivotUtilities.renderers["Row Heatmap"],
            "Heatmap por columnas":    $.pivotUtilities.renderers["Col Heatmap"]*/
          },                   
        aggregatorName : "Cuenta",
        vals: ["Local"],
        rows: params.rows,
        cols: params.cols,
      }

    // Lenguaje spanish para pivot
    $.pivotUtilities.locales.es = {
        localeStrings:{
            renderError: "Ocurrió un error durante la interpretación de la tabla dinámica.",
            computeError: "Ocurrió un error durante el cálculo de la tabla dinámica.",
            uiRenderError: "Ocurrió un error durante el dibujado de la tabla dinámica.",
            selectAll: "Seleccionar todo",
            selectNone: "Deseleccionar todo",
            tooMany: "(demasiados valores)",
            filterResults: "Filtrar resultados",
            totals: "Totales",
            vs: "vs",
            by: "por",
        },
        aggregators : (function(tpl) {
            return {
            "Cuenta":                             tpl.count($.pivotUtilities.frFmtInt),
            
          };
          })(tpl)
        }
        //$("#pivot").empty();
        $('#pivot').pivotUI(sampleData, pvtOpts,true,'es');
     
    

     /* $('#pivot').DataTable({
     "bJQueryUI":true,
      "bSort":false,
      "bPaginate":true,
      "sPaginationType":"full_numbers",
       "iDisplayLength": 10
      });*/
      
      //pagination
     /* function pages(){
      $('.details').each(function(){  //podemos tener varios en la misma página

      var container = $(this);
      var total_pages = $('.page',container).length;
      $('.page',container).hide();
      $('.page:first',container).show();
      var controls = $('.paginacion_controls');
      if (total_pages > 1){
      for(i=0;i<total_pages;i++){ if (i > 0) controls.append(" | ");
      controls.append("<a href='#' class='control_link' goto='"+i+"'>"+(i+1)+"</a>")
      }
      }

      });

      $('.controls_link').live('click',function(e){

      goto = $(this).attr("to") ;
      container = $(this).parent().prev();
      $('.page',container).hide();
      var total_pages = $('.page',container).length;
      $('.page:nth('+goto+')',container).fadeIn();
      e.preventDefault();

      });
    };*/
      //

      

    }
    $(function(){
       actualizar();
        //graficar(null);        
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
<input type="hidden" name="nombreTabla" id="nombreTabla" value="{$_TABLA}">
<div class="detailViewContainer">
  <div class="row-fluid detailViewTitle">
    <span class="recordLabel font-x-x-large textOverflowEllipsis span pushDown" title="gika"><span class="">Analisis de Quejas</span>&nbsp;</span>
  </div>
  <div class="row-fluid detailViewTitle"><span class="row-fluid"><span class="muted">
</span></span></div>
  <div class="detailViewInfo row-fluid">
  <br>
    <div class="row-fluid">
      <span class="span3">
        <span class="pull-left" style="padding-left:10px;padding-top:5px;">
          <label for="createdtime">
        Fecha entre:
          </label>
        </span>
      </span>
      <span class="span3">
        <input type="text" name="createdtime" id="createdtime" value="{$date_range}" class="dateRange widgetFilter dateField" data-date-format="dd-mm-yyyy">
      </span>   
      <span class="span3">
        <select id="selectFiltros">
          <option>Personalizado</option>
        </select>
      </span>
      <span class="span3">
        <button class="btn pull-right" id="abrirModalFiltro">Guardar Filtro</button>
      </span>
    </div>    
    <div class=" details" >
    
      <div id="details" class="row-fluid" style=" margin: 5px;">
        <div id="pivot" class="page" style="overflow: auto"></div>
        <div class="paginacion_controls"></div>
        <div id="output" style="margin: 10px;"></div>
      </div>
      <div class="row-fluid" style="margin-top:15px;">
        <div class="span8" style="margin-left:5px;">
             <a class="btn addButton" href="#" style="padding:4px 6px;float:left;font-weight:bold;margin-right:5px;font-family:'Helvetica Neue', Helvetica, Arial, sans-serif;" onclick=" tableToExcel('pvtRendererArea','Analisis Proyectos',1);">  Exportar a Excel  </a>
        </div>
        </div>
        <form action="download.php" method="post" target="_blank" id="FormularioExportacion">
            <input type="hidden" id="datos_a_enviar" name="datos_a_enviar" />
            <input type="hidden" id="nombre_a_enviar" name="nombre_a_enviar" />
            <input type="hidden" id="is_submited" name="is_submited" />
          </form>
    </div>
    </div>

    {include file='modalGuardarFiltro.tpl'|vtemplate_path:$MODULE_NAME}