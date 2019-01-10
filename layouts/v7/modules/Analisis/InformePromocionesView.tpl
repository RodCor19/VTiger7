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

  #tbl1 tr th, #tbl1 tr td{
    padding: 10px 15px;
    box-sizing: border-box;
  }

  #tbl1 tr th{
    border-bottom: 1px solid black;
  }

  #tbl2 tr th, #tbl2 tr td{
    padding: 10px 15px;
    box-sizing: border-box;
  }

  #tbl2 tr th{
    border-bottom: 1px solid black;
  }
</style>

<div class="detailViewContainer">
  <div class="row-fluid detailViewTitle">
    <span class="recordLabel font-x-x-large textOverflowEllipsis span pushDown" title="gika"><span class="">Informe de Promociones</span>&nbsp;</span>
    
  </div>
  <div class="row-fluid detailViewTitle"><span class="row-fluid">
    <span class="muted">¿Cuántas personas de acuerdo al rango de edad participan de una promoción?<br>¿Cuál es el importe de esas personas?<br>Cantidad de facturas por local.</span>
  </span></div>
  <div class="detailViewInfo row-fluid">

   <div class="row-fluid" style="padding-top:5px;">
    
    
    <span class="span3">
      <span class="pull-left" style="padding-left:10px;padding-top:5px;margin-top: 15px">
        <label for="edades">
          Promoción:
        </label>
      </span>
    </span>
    <span class="span3">
      {strip}
      <select id="promociones" class="select2" name="edades" style="width: 95%;margin-top: 15px;" onChange="javascript:actualizar();">
        {foreach item=promocion from=$promociones}
        <option value="{$promocion}">{$promocion}</option>

        {/foreach}

      </select>
      {/strip}
    </span>      
  <div id="widgetChartContainer" class="  details">
  <br><br>
  <table id="tbl1" width="100%" style = "margin-top: 45px;margin-bottom: 35px;">

    
    <thead>
      <tr style = "text-align: left">
        <th>Rango de edades</th>
        <th>Cantidad</th>
        <th>Porcentaje de Cantidad Total</th>
        <th>Importe</th>
        <th>Porcentaje de Importe Total</th>
      </tr>
    </thead>
    <tbody>
      <!--
      <tr>
        <td>De 25 a 30 años</td>
        <td>5</td>
        <td>10</td>
        <td>15</td>
        <td>20</td>
      </tr>-->
    </tbody>
  
  </table>

  <br><br>
  <table id="tbl2" width="100%" style = "margin-top: 45px;margin-bottom: 35px;">

    
    <thead style = "text-align: left">
      <tr>
        <th>Local</th>
        <th>Cantidad de Facturas</th>
      </tr>
    </thead>
    <tbody>
      <!--
      <tr>
        <td>De 25 a 30 años</td>
        <td>5</td>
        <td>10</td>
        <td>15</td>
        <td>20</td>
      </tr>-->
    </tbody>
  
  </table>
  
</div>
</div>
</div>
</div>
{literal}
<script type="text/javascript">
  

  var calcularTotal=0;
  $( document ).ready(function() {  
    graficar();
    
  });

  function graficar(){
    var promocion = jQuery('#promociones').val();
    var data = "";
    jQuery.ajax({
      url : "index.php?module=Analisis&view=InformePromociones&mode=Ajax",
      method : "POST",
      async : false,
      dataType : "JSON",
      data : {"promocion" : promocion}
    })
    .done(function(response){
      data = response;
    });
    console.log(data);
    var html = "";
    var d = data['data'];
    for(var i = 0; i < d.length; i++){
      html += "<tr><td>" + d[i]['edades'] + "</td><td>" + d[i]['cantidad'] + "</td><td>" + d[i]['porcentajeCantidad'] + "%</td><td>$" + d[i]['importe'] + "</td><td>" + d[i]['porcentajeImporte'] + "%</td></tr>";
    }
    jQuery('#tbl1 tbody').html(html);

    var html2 = "";
    var d2 = data['locales'];
    for(var i = 0; i < d2.length; i++){
      html2 += "<tr><td>" + d2[i]['local'] + "</td><td>" + d2[i]['cantidad'] + "</td></tr>";
    }
    jQuery('#tbl2 tbody').html(html2);
   
   
  }
  
  function actualizar(){
   graficar() 
 } 

</script>


{/literal}