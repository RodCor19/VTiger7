{strip}
<div class="plot" id="chart"></div>
<br>
<div>
  <table class="table table-borderless">
    <tbody id='fields'>
      <tr><td class="fieldLabel alignMiddle"><label>Inicio :</label></td><td class="fieldValue"><input class="inputElement" type="date" name="Inicio"></td></tr>
      <tr><td class="fieldLabel alignMiddle"><label>Fin :</label></td><td class="fieldValue"><input class="inputElement" type="date" name="Fin"></td></tr>
    </tbody>
  </table>
  <div class="textAlignCenter">
    <button id="btnBuscar" class="btn btn-success">Buscar</button>
  </div>
</div>

{/strip}
{literal}
<script type="text/javascript">
  $(document).ready(function(){
    var record = {/literal}{$record}{literal};
    var grafica = function(datos, labelText, format, intervalo){
      $('#chart').empty();
      $.jqplot.config.enablePlugins = true;
      var plot1 = $.jqplot('chart', [datos], {
        axes:{
          xaxis:{
            renderer:$.jqplot.DateAxisRenderer,
            tickInterval: intervalo, 
            rendererOptions:{
              tickRenderer:$.jqplot.CanvasAxisTickRenderer
            },
            tickOptions:{
              formatString: format
            },
            label: labelText
          },
          yaxis:{
            rendererOptions:{
              tickRenderer:$.jqplot.CanvasAxisTickRenderer
            },
            tickOptions: {
              formatString: "$%'d"
            },
          }
        },
        cursor:{
          zoom:true,
          looseZoom: true
        }
      });
    };
    grafica({/literal}{json_encode($valores)}{literal}, '{/literal}{$label}{literal}', '{/literal}{$itemsLabels}{literal}', {/literal}{$intervalo}{literal});
    $('#btnBuscar').click(function(e) {
      var campoInicio = $("#fields .inputElement")[0];
      var campoFin = $("#fields .inputElement")[1];
      var params = {
        'module' : 'Accounts',
        'action' : 'DataInvoice',
        'record' : record,
        'inicio' : $(campoInicio).val(),
        'fin' : $(campoFin).val()
      };
      app.request.get({data:params}).then(
        function(error,data) {
          if (data.success) {
            data = data['data'];
            grafica(data.valores, data.label, data.itemsLabels, data.intervalo);
            if(data.rotacion){
              var val1 = $(campoInicio).val();
              $(campoInicio).val($(campoFin).val());
              $(campoFin).val(val1);
            }
          } else {
            app.helper.showErrorNotification({'message': data.error});
          }
        },
        function(error,err){

        }
        );
    });
  });
</script>
{/literal}