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
.jqplot-point-label{
  font-size: 1.25em;
  color: #CCC;
}
.jqplot-table-legend{
  font-size: 12px;
}
.jqplot-xaxis-tick{
  /*display: none;*/
}
</style>


<div class="detailViewContainer">
  <div class="row-fluid detailViewTitle">
    <span class="recordLabel font-x-x-large textOverflowEllipsis span pushDown" title="gika"><span class="">Ventas por mes</span>&nbsp;</span>
  </div><div class="row-fluid detailViewTitle"><span class="row-fluid"><span class="muted">¿Cu&aacute;nto vendemos por mes?<br>Vista Pesos IPC: Valor Mes x 1 + (IPC Actual - IPC Mes/Año) / 100
  <br>Perímetro:
  cada mes del año N, solo se muestra la venta de los locales que ya vendían el mes del año N-1
</span></span></div>
  <div class="detailViewInfo row-fluid">
  <div class="row-fluid">
  <div class="row-fluid">
    <span class="weight recordLabel font-x-large textOverflowEllipsis span pushDown" title="gika"><span class="">Filtros:</span>&nbsp;</span>
    </div>
    <div class="row-fluid">

      <span class="span3">
        <span class="pull-left" style="padding-left:10px;padding-top:5px;">
          <label for="edades">
            Familias:
          </label>
        </span>
      </span>

      <span class="span3">
        <select class="widgetFilter" name="familias" id="familias" onChange="javascript:actualizar();">
          <option value="">{vtranslate('LBL_ALL', $MODULE_NAME)}</option>
          {foreach key=USER_ID item=USER_NAME from=$familias}
          <option value="{$USER_NAME}">
              {$USER_NAME}
            </option>
          {/foreach}
        </select>

        
      </span>
    
      <span class="span3">
        <span class="pull-left" style="padding-left:10px;padding-top:5px;">
          <label for="edades">
            Rubros:
          </label>
        </span>
      </span>
      <span class="span3">
        <select class="widgetFilter" name="rubro" id="rubro" onChange="javascript:actualizar();">
          <option value="">{vtranslate('LBL_ALL', $MODULE_NAME)}</option>
          {foreach key=USER_ID item=USER_NAME from=$rubros}
          <option value="{$USER_NAME}">
              {$USER_NAME}
            </option>
          {/foreach}
        </select>
      </span>
    </div>
     <div class="row-fluid">
      <span class="span3">
        <span class="pull-left" style="padding-left:10px;padding-top:5px;">
          <label for="tipo">
            Locales Adheridos:
          </label>
        </span>
      </span>
      <span class="span3">
        <select class="widgetFilter" name="adherido" id="adherido" onChange="javascript:actualizar();">
          <option value="">{vtranslate('LBL_ALL', $MODULE_NAME)}</option>
          {foreach key=USER_ID item=USER_NAME from=$adheridos}
          <option value="{$USER_NAME}">
              {$USER_NAME}
            </option>
          {/foreach}
        </select>
      </span>
    
      <span class="span3">
        <span class="pull-left" style="padding-left:10px;padding-top:5px;">
          <label for="tipo">
            Localización:
          </label>
        </span>
      </span>
      <span class="span3">
        <select class="widgetFilter" name="localizacion" id="localizacion" onChange="javascript:actualizar();">
          <option value="">{vtranslate('LBL_ALL', $MODULE_NAME)}</option>
          {foreach key=USER_ID item=USER_NAME from=$localizacion}
          <option value="{$USER_NAME}">
              {$USER_NAME}
            </option>
          {/foreach}
        </select>
      </span>
</div>

    <div class="row-fluid">
{*M E*}      <span class="span3">
{*M E*}        <span class="pull-left" style="padding-left:10px;padding-top:5px;">
{*M E*}          <label for="local">
{*M E*}            Locales:
{*M E*}          </label>
{*M E*}        </span>
{*M E*}      </span>
{*M E*}      <span class="span3">
{*M E*}        <select class="widgetFilter" name="local" id="local" onChange="javascript:actualizar();">
{*M E*}          <option value="">{vtranslate('LBL_ALL', $MODULE_NAME)}</option>
{*M E*}          {foreach item=local from=$locales}
{*M E*}          <option value="{$local[1]}">
{*M E*}              {$local[0]}
{*M E*}            </option>
{*M E*}          {/foreach}
{*M E*}        </select>
{*M E*}      </span>
{*M E*}      <span class="span3">
{*M E*}        <span class="pull-left" style="padding-left:10px;padding-top:5px;">
{*M E*}          <label for="local">
{*M E*}            Forma de Pago:
{*M E*}          </label>
{*M E*}        </span>
{*M E*}      </span>
{*M E*}      <span class="span3">
{*M E*}        <select class="widgetFilter" name="formapago" id="formapago" onChange="javascript:actualizar();">
{*M E*}          <option value="" selected="selected">Todas</option>
{*M E*}          {foreach item=forma from=$forma_pago}
{*M E*}          <option value="{$forma[1]}">
{*M E*}              {$forma[0]}
{*M E*}            </option>
{*M E*}          {/foreach}
{*M E*}        </select>
{*M E*}      </span>

    </div>

    <div class="row-fluid">
     {strip}
      <span class="span3">
        <span class="pull-left" style="padding-left:10px;padding-top:5px;">
          <label for="tipo">
            Rango de fechas (m&aacute;ximo 1 a&ntilde;o):
          </label>
        </span>
      </span>
      <span class="span8">
        <p style="display:inline">Desde: </p>
        <input type="text" name="fecha1" id="fecha1" value="{$date_range}" class="dateRange widgetFilter dateField" data-date-format="dd-mm-yyyy">
        <p style="display:inline">   Hasta: </p>
        <input type="text" name="fecha2" id="fecha2" value="{$date_range}" class="dateRange widgetFilter dateField" data-date-format="dd-mm-yyyy">
        <input type="button" id="limpiar" value="Limpiar" onclick='javascript:jQuery(".dateField").val("")'>
      </span>
          {/strip}

    </div>
</div>
 <div class="row-fluid">
  <div class="row-fluid">
  <span class="weight recordLabel font-x-large textOverflowEllipsis span pushDown" title="gika"><span class="">Vistas:</span>&nbsp;</span>
  </div>
  <span class="span3">
      <div class="row-fluid">
      <div class="radio">
        <label><input type="radio" id="radioPesos" checked="checked" name="radio" onChange="javascript:actualizar();">En pesos</label>
      </div>
      <div class="radio">
        <label><input type="radio" id="radioUnidades" name="radio" onChange="javascript:actualizar();">En Cantidad de Facturas</label>
      </div>
      <div class="radio">
        <label><input type="radio" id="radioIpc" name="radio" onChange="javascript:actualizar();">En Pesos IPC</label>
      </div>
    </div>
  </span>
  <span class="span3">
      <div class="row-fluid">
      <div class="radio">
        <label><input type="radio" id="radioAcumulado" name="radio2" onChange="javascript:actualizar();">Acumulado</label>
      </div>
      <div class="radio">
        <label><input type="radio" id="radioNoAcumulado" checked="checked" name="radio2" onChange="javascript:actualizar();">No acumulado</label>
      </div>
    </div>
  </span>
  <span class="span3">
      <div class="row-fluid">
      <div class="radio">
        <label><input type="radio" id="radioPerimetro" name="radio3" onChange="javascript:actualizar();">Perimetro</label>
      </div>
      <div class="radio">
        <label><input type="radio" id="radioNoPerimetro"  checked="checked"  name="radio3" onChange="javascript:actualizar();">No perimetro</label>
      </div>
    </div>
  </span>
</div>
   <div id="dashChartLoader" style="text-align:center;"><img src="layouts/vlayout/skins/softed/images/loading.gif" border="0" align="absmiddle"></div>

    <div class="span12" style="overflow: hidden">
        <input class="widgetData" type=hidden value='{Vtiger_Util_Helper::toSafeHTML(ZEND_JSON::encode($DATA))}' />
        <div id="widgetChartContainer" style="height:500px;width:85%"></div>
        <div id="widgetChartContainer2" style="height:400px;width:85%"></div>
    </div>

  </div>
</div>

{literal}

<script type="text/javascript">

    function diffMeses(){
        if (jQuery("#createdtime").val()!=""){
            var aFecha1 = jQuery("#fecha1").val().split("-"); 
            var aFecha2 = jQuery("#fecha2").val().split("-"); 
            var fFecha1 = Date.UTC(aFecha1[0],aFecha1[1]-1,aFecha1[2]); 
            var fFecha2 = Date.UTC(aFecha2[0],aFecha2[1]-1,aFecha2[2]); 
            var dif = fFecha2 - fFecha1;
            var dias = Math.floor(dif / (1000 * 60 * 60 * 24)); 
            console.log(dias);
            if (dias>364 || dias < 0)
                return false;
        }
        return true;
    }

    function getDatos(){

        var fecha = "actual";
        if (jQuery("#fecha1").val() != "" && jQuery("#fecha2").val() != "")
            fecha = jQuery("#fecha1").val()+","+jQuery("#fecha2").val();

        var unidades = "pesos";
        if(jQuery("#radioUnidades").attr('checked')=="checked"){
            unidades = "unidades";
        }
        if(jQuery("#radioIpc").attr('checked')=="checked"){
            unidades = "ipc";
        }

        var acumulado = "true";
        if(jQuery("#radioNoAcumulado").attr('checked')=="checked"){
            acumulado = "false";
        }

        var perimetro = "true";
        if(jQuery("#radioNoPerimetro").attr('checked')=="checked"){
            perimetro = "false";
        }

        var familia = jQuery("#familias").val();
        var rubro = jQuery("#rubro").val();
        var localizacion = jQuery("#localizacion").val();
        var adherido = jQuery("#adherido").val();
        var nombreLocal = jQuery("#local").val();
        var formapago = jQuery("#formapago").val();

        var ret;

        jQuery.ajax({
            async: false,
/*M E*/            data:  {'unidades':unidades, 'acumulado': acumulado, 'perimetro': perimetro, 'familia':familia, 'rubro':rubro, 'localizacion': localizacion, 'adherido':adherido, 'fecha':fecha, 'nombreLocal':nombreLocal, 'formapago':formapago},
            url: 'index.php?module=Analisis&action=FiltrarGraficaMes',
            type:  'post',
            success:  function (response) {
                ret=  JSON.parse(response);
            }
        });/*
        console.log(ret);*/
        return ret;
    } 

    var chartData;var chartpar1=null;
    $( document ).ready(function() {
        jQuery("#dashChartLoader").hide();   
        //No hacer así, se llama 3 veces a la consulta
        /*jQuery("#radioPesos").click();
        jQuery("#radioNoAcumulado").click();
        jQuery("#radioNoPerimetro").click();*/
        actualizar();
        var dateRangeElement = jQuery('.dateRange');
        var dateChanged = false;
        if(dateRangeElement.length <= 0) {
            return;
        }
        var customParams = {
            calendars: 1,
            mode: 'datepicker',
            className : 'rangeCalendar',
            onChange: function(formated) {
                dateChanged = true;
                var element = jQuery(this).data('datepicker').el;
                jQuery(element).val(formated);
                jQuery("#dashChartLoader").focus();
            },
            onHide : function() {
                if(dateChanged){
                    if (jQuery("#fecha1").val() != "" && jQuery("#fecha2").val() != "") {
                        if (diffMeses()) {
                            actualizar();
                            dateChanged = false;
                        }
                        else{
                            var bootBoxModal = bootbox.alert("Se deben seleccionar un maximo de 12 meses");
                                bootBoxModal.on('hidden',function(e){
                                if(jQuery('#globalmodal').length > 0) {
                                    jQuery('body').addClass('modal-open');
                                }
                            })
                            jQuery("#createdtime").val("");
                        }
                    }
                    else{
                      jQuery(".datepicker").hide();
                    }
                }
            },
            onBeforeShow : function(elem) {
                jQuery(elem).css('z-index','3');
            },  
        }
        dateRangeElement.addClass('dateField').attr('data-date-format',"yyyy-mm-dd");
        app.registerEventForDatePickerFields(dateRangeElement,false,customParams);  
        jQuery(".dateRange").keydown(function (e)
        {
            e.preventDefault();
        });

    });

    var chartexm1,chartexm2=null;
  
    var actual, anterior;

    function actualizar(){
        jQuery("#dashChartLoader").show();    
        jQuery("#widgetChartContainer").hide(); 
        arr=getDatos();
        var arrayLength = arr[0][0].length;
        actual = arr[1];
        anterior = arr[2];
        jQuery("#dashChartLoader").hide();    
        jQuery("#widgetChartContainer").show();
        jQuery('.widgetData').val(JSON.stringify(arr[0]));
        graficar();
    }


  function graficar(){
      var jData = jQuery('.widgetData').val();
      chartData = JSON.parse(jData)

      var yaxislbl = '$';
      if(jQuery("#radioUnidades").attr('checked')=="checked"){
          yaxislbl  = 'U';
      }        
      
      formato="%'.0f";
      if(jQuery("#radioIpc").attr('checked')=="checked"){
        formato="%'.2f";
      }  

      if (chartexm1) {
          chartexm1.destroy();
      }  
      
      jQuery('#widgetChartContainer').empty();
      jQuery('#widgetChartContainer2').empty();
      chartexm1 = jQuery.jqplot('widgetChartContainer', chartData,
      { 
          animate: true,
          animateReplot: true,
          title:"Ventas", 
          axes:{
              xaxis:{
                  renderer: $.jqplot.CategoryAxisRenderer
              },
              
            yaxis:{
                autoscale:true,
                tickOptions:{showGridline:false,formatString: formato},          
                labelOptions: {
                    fontSize: '30pt'
                },
                label: yaxislbl,min:0,
              }
          },
          seriesColors:['#FB9869', '#5D9FB8'],
              highlighter: {
                  show: true, 
                  showLabel: true, 
                  tooltipAxes: 'y',
                  sizeAdjust: 7.5 , tooltipLocation : 'nw',
              },
                  // Set default options on all series, turn on smoothing.
          seriesDefaults: {
              rendererOptions: {
                  smooth: true
              }
          },
          
          legend: {
                      /*show: true,
                      location: 'ne'*/
                      
              renderer: jQuery.jqplot.EnhancedLegendRenderer,
              show: true, 
              location: 's', 
              placement: 'outsideGrid',
              marginTop:'15px',
              // Breaks the ledgend into horizontal.
              rendererOptions: {
                  numberRows: '1',
                  numberColumns: '3'
              },
              seriesToggle: true
              
          },
          noDataIndicator: {
            show: true,
            // Here, an animated gif image is rendered with some loading text.
            indicator: 'No hay datos disponibles..'
          },
          series:[
              {
                lineWidth:4,highlighter: {formatString: yaxislbl+' = %s'},label:'<span style="color:#FB9869; border: 1px solid"> '+actual+'</span>', yaxis: 'yaxis',
              }, 
              {
                  lineWidth:4,highlighter: {formatString: yaxislbl+' = %s'},label:'<span style="color:#5D9FB8; border: 1px solid"> '+anterior+'</span>', yaxis: 'yaxis',
              },
            {yaxis: 'yaxis'}
           
          ]
      }
    );


  }

</script>


{/literal}