{strip}
<div class="dashboardWidgetHeader">
    {include file="dashboards/WidgetHeader.tpl"|@vtemplate_path:$MODULE_NAME} 
</div>
<div class="dashboardWidgetContent" style="height:150px!important;">
<style type="text/css">
.jqplot-point-label{
  font-size: 1.25em;
  color: #CCC;
}
.jqplot-table-legend{
  font-size: 12px;
}

</style>


<div class="detailViewContainer">
{if $dashboard == false}
  <div class="row-fluid detailViewTitle">
    <span class="recordLabel font-x-x-large textOverflowEllipsis span pushDown" title="gika"><span class="">Ventas por d&iacute;a</span>&nbsp;</span>
  </div><div class="row-fluid detailViewTitle"><span class="row-fluid"><span class="muted">¿Cu&aacute;nto vendemos por d&iacute;a?
</span></span></div>
{/if}
  <div class="detailViewInfo row-fluid">
{if $dashboard == false}
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
{*M E*}          <option value="{$local}">
{*M E*}              {$local}
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
{*M E*}          <option value="Contado">Contado</option>
{*M E*}          <option value="Credito">Crédito</option>
{*M E*}        </select>
{*M E*}      </span>
    </div>

    <div class="row-fluid">
     {strip}
      <span class="span3">
        <span class="pull-left" style="padding-left:10px;padding-top:5px;">
          <label for="tipo">
            Rango de fechas (m&aacute;ximo 31 d&iacute;as):
          </label>
        </span>
      </span>
      <span class="span4">
        <input type="text" name="createdtime" id="createdtime" value="{$date_range}" class="dateRange widgetFilter dateField" data-date-format="dd-mm-yyyy">
        <input type="button" id="limpiar" value="Limpiar" onclick='javascript:jQuery("#createdtime").val("")'>
      </span>
    {/strip}

    </div>
</div>
 <div class="row-fluid">
  <div class="row-fluid">
  <span class="weight recordLabel font-x-large textOverflowEllipsis span pushDown" title="gika"><span class="">Vistas:</span>&nbsp;</span>
  </div>
  <span class="span5">
      <div class="row-fluid">
      <div class="radio">
        <label><input type="radio" id="radioPesos" name="radio" onChange="javascript:actualizar();">En pesos</label>
      </div>
      <div class="radio">
        <label><input type="radio" id="radioUnidades" name="radio" onChange="javascript:actualizar();">En Cantidad de Facturas</label>
      </div>
    </div>
  </span>
  <span class="span6">
      <div class="row-fluid">
      <div class="radio">
        <label><input type="radio" id="radioAcumulado" name="radio2" onChange="javascript:actualizar();">Acumulado</label>
      </div>
      <div class="radio">
        <label><input type="radio" id="radioNoAcumulado" name="radio2" onChange="javascript:actualizar();">No acumulado</label>
      </div>
    </div>
  </span>
</div>
{/if}
   <div id="dashChartLoaderDia" style="text-align:center; "><img src="layouts/vlayout/skins/softed/images/loading.gif" border="0" align="absmiddle"></div>

    <div class="span12" style="overflow: hidden">
        <input class="widgetDataDia" type=hidden value='{Vtiger_Util_Helper::toSafeHTML(ZEND_JSON::encode($DATA))}' />
        <div id="widgetChartContainerDia" style="height:250px;width:85%"></div>
    </div>

  </div>
</div>
  </div>
{literal}

<script type="text/javascript">
{/literal}{if $dashboard==false}{literal}

    function diffDias(){
        if (jQuery("#createdtime").val()!=""){
            var aFechas = jQuery("#createdtime").val().split(',');
            var aFecha1 = aFechas[0].split('-'); 
            var aFecha2 = aFechas[1].split('-'); 
            var fFecha1 = Date.UTC(aFecha1[0],aFecha1[1]-1,aFecha1[2]); 
            var fFecha2 = Date.UTC(aFecha2[0],aFecha2[1]-1,aFecha2[2]); 
            var dif = fFecha2 - fFecha1;
            var dias = Math.floor(dif / (1000 * 60 * 60 * 24)); 
            console.log(dias);
            if (dias>31)
                return false;
        }
        return true;
    }
{/literal}{/if}{literal}

    function getDatos(){

{/literal}{if $dashboard==false}{literal}

        var fecha = "actual";
        if (jQuery("#createdtime").val()!="")
            fecha = jQuery("#createdtime").val();

        var unidades = "false";
        if(jQuery("#radioUnidades").attr('checked')=="checked"){
            unidades = "true";
        }

        var acumulado = "true";
        if(jQuery("#radioNoAcumulado").attr('checked')=="checked"){
            acumulado = "false";
        }

        var familia = jQuery("#familias").val();
        var rubro = jQuery("#rubro").val();
        var localizacion = jQuery("#localizacion").val();
        var adherido = jQuery("#adherido").val();
        var nombreLocal = jQuery("#local").val();
        var formapago = jQuery("#formapago").val();
{/literal}{else}{literal}

        var fecha = "actual";
        var unidades = "false";
        var acumulado = "false";
        var familia = "";
        var rubro = "";
        var localizacion = "";
        var adherido ="";   
        var formapago ="";   
/*M E*/        var nombreLocal = "";

{/literal}{/if}{literal}

        var ret;

        jQuery.ajax({
            async: true,
/*M E*/            data:  {'unidades':unidades, 'acumulado': acumulado, 'familia':familia, 'rubro':rubro, 'localizacion': localizacion, 'adherido':adherido, 'fecha':fecha, 'nombreLocal':nombreLocal, 'formapago':formapago},
            url: 'index.php?module=Analisis&action=FiltrarGraficaDia',
            type:  'post',
            success:  function (response) {
                ret=  JSON.parse(response);
                arr=ret;
                actual = arr[1];
                anterior = arr[2];
                jQuery("#dashChartLoaderDia").hide();    
                jQuery("#widgetChartContainerDia").show();
                jQuery('.widgetDataDia').val(JSON.stringify(arr[0]));
                //alert("graficar");
                var jData = jQuery('.widgetDataDia').val();
      chartData = JSON.parse(jData)
      //alert("function graficar");
      var yaxislbl = '$';
      {/literal}{if $dashboard==false}{literal}  
      if(jQuery("#radioUnidades").attr('checked')=="checked"){
          yaxislbl  = 'U';
      }        
      {/literal}{/if}{literal}  

      if (chartexm1VD) {
          chartexm1VD=null;
      }  
      
      jQuery('#widgetChartContainerDia').empty();
      chartexm1VD = jQuery.jqplot('widgetChartContainerDia', chartData,
      { 
          animate: true,
          animateReplot: true,
          title:"Ventas", 
          axes:{
              xaxis:{
                  renderer: jQuery.jqplot.CategoryAxisRenderer,
                  tickOptions: {
                      angle: -90,
                      fontSize: '10pt'
                  }
              },
              
            yaxis:{
                autoscale:true,
                tickOptions:{showGridline:false,formatString: "%'.0f"},          
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
              marginTop:'5px',
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
        });
        return ret;
    } 

    var chartData;var chartpar1=null;
    $( document ).ready(function() {
        jQuery("#dashChartLoaderDia").hide();   

        {/literal}{if $dashboard==false}{literal}  

        jQuery("#radioPesos").click();
        jQuery("#radioNoAcumulado").click();
        var dateRangeElement = jQuery('.dateRange');
        var dateChanged = false;
        if(dateRangeElement.length <= 0) {
            return;
        }
        var customParams = {
            calendars: 2,
            mode: 'range',
            className : 'rangeCalendar',
            onChange: function(formated) {
                var codigo = getDatos();
                dateChanged = true;
                var element = jQuery(this).data('datepicker').el;
                jQuery(element).val(formated);
                jQuery("#dashChartLoaderDia").focus();
            },
            onHide : function() {
                if(dateChanged){
                  if (diffDias()) {
                      actualizar();
                      dateChanged = false;
                  }
                  else{
                      var bootBoxModal = bootbox.alert("Se deben seleccionar un maximo de 31 dias");
                          bootBoxModal.on('hidden',function(e){
                          if(jQuery('#globalmodal').length > 0) {
                              jQuery('body').addClass('modal-open');
                          }
                      })
                      jQuery("#createdtime").val("");
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

        {/literal}{else}{literal}

          actualizar();

        {/literal}{/if}{literal}

    });

    var chartexm1VD,chartexm2=null;
  

    function actualizar(){
        jQuery("#dashChartLoaderDia").show();    
        jQuery("#widgetChartContainerDia").hide(); 
        arr=getDatos();
    }


  function graficar(){
      var jData = jQuery('.widgetDataDia').val();
      chartData = JSON.parse(jData)
      //alert("function graficar");
      var yaxislbl = '$';
      {/literal}{if $dashboard==false}{literal}  
      if(jQuery("#radioUnidades").attr('checked')=="checked"){
          yaxislbl  = 'U';
      }        
      {/literal}{/if}{literal}  

      if (chartexm1VD) {
          chartexm1VD=null;
      }  
      
      jQuery('#widgetChartContainerDia').empty();
      chartexm1VD = jQuery.jqplot('widgetChartContainerDia', chartData,
      { 
          animate: true,
          animateReplot: true,
          title:"Ventas", 
          axes:{
              xaxis:{
                  renderer: jQuery.jqplot.CategoryAxisRenderer,
                  tickOptions: {
                      angle: -90,
                      fontSize: '10pt'
                  }
              },
              
            yaxis:{
                autoscale:true,
                tickOptions:{showGridline:false,formatString: "%'.0f"},          
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
              marginTop:'5px',
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