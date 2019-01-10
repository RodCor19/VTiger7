{strip}
<div class="dashboardWidgetHeader">
    {include file="dashboards/WidgetHeader.tpl"|@vtemplate_path:$MODULE_NAME}	
</div>
<div class="dashboardWidgetContent" style="height:150px!important;">
	
	<div class="detailViewContainer" style="height:50px;">
	  	<div class="detailViewInfo row-fluid" style="height:50px;">
	    	<div id="dashChartLoaderGaviotas" style="text-align:center;"><img src="layouts/vlayout/skins/softed/images/loading.gif" border="0" align="absmiddle"></div>
		    <div  class="" style="height:50px;">
		       <div id="" style="height:50px;">
				    {if count($DATA) gt 0 }
				      	<input class="widgetDataGaviotas" type=hidden value='{Vtiger_Util_Helper::toSafeHTML(ZEND_JSON::encode($DATA))}' />
				       	<div id="widgetChartContainerGaviotas" class="  details widgetChartContainerGaviotas" style="height:50px!important;width:50%;float:left;min-height:200px;"></div>
				       	<div id="widgetChartContainer2Gaviotas" class="  details widgetChartContainer2Gaviotas" style="height:50px;width:50%;float:left;min-height:200px;"></div>
				    {else}
				      	<span class="noDataMsg">
				        	{vtranslate('LBL_NO')} {vtranslate($MODULE_NAME, $MODULE_NAME)} {vtranslate('LBL_MATCHED_THIS_CRITERIA')}
				      	</span>
				    {/if}
		      </div>

		    </div>

	    </div>

	</div>
	

</div>
{/strip}

{literal}
<style type="text/css">
	.widgetChartContainerGaviotas{
		height:50px;
		min-height:50px;
	}
</style>
<script type="text/javascript">
 	var vista='%';
  	var chartData;
	var chartpar1=null;
	var chartpar2=null;
 
	function  getChartRelatedData() {
      	var jData = jQuery('.widgetDataGaviotas').val();
      	var data = JSON.parse(jData);
	    return data;
    }

	jQuery( document ).ready(function() {
		//alert("gavss");
    	chartData = getChartRelatedData();
    	graficar("");
    	jQuery("#dashChartLoaderGaviotas").hide();    
  	});
  
  	var oTable=null;
  
  	function graficar(tipo){
      
     	if (chartpar1) {
        	chartpar1.destroy();
      	} if (chartpar2) {
        	chartpar2.destroy();
      	}  

      	jQuery('#widgetChartContainerGaviotas').empty();

     	ver='value'; 
     	if(vista=='%'){
        	ver='percent'
      	}
      	//var containerHeight = $("#widgetChartContainerGaviotas").height();
      	//alert(containerHeight);
     	chartpar1 = jQuery.jqplot ('widgetChartContainerGaviotas', [chartData[0]], 
    	{  
	      	animate: true,
	      	animateReplot: true,
	      	title:"Gaviotas Generadas", 
	      	seriesDefaults: {
		        renderer: jQuery.jqplot.PieRenderer, 
		        rendererOptions: {
		          	showDataLabels: true,
		          	dataLabels: ver,
		          	dataLabelThreshold: 0, 
		          
		        },
	      	}, 
	      	legend: { 
	      		show:true, 
            	placement: 'outside',
	      		location: 's',
	      		seriesToggle: true,
	      		rendererOptions: {
                        numberColumns: 2,
                        numberRows : 2
                    }
	      	},
	       	noDataIndicator: {
	        	show: true,
	        	indicator: 'No hay datos disponibles..'
	      	},
	     	grid: {
	            drawBorder: false, 
	            drawGridlines: false,
	            background: '#ffffff',
	            shadow:false
	        },
    	});

	  	if(tipo!="clientes")   {
	    	chartpar2 = jQuery.jqplot ('widgetChartContainer2Gaviotas', [chartData[1]], 
	      	{  
	        	animate: true,
	        	animateReplot: true,
	        	title:"Costo Correspondiente", 
	        	seriesDefaults: {
		          	// Make this a pie chart.
		          	renderer: jQuery.jqplot.PieRenderer, 
		          	rendererOptions: {
		            	// Put data labels on the pie slices.
		            	// By default, labels show the percentage of the slice.
		            	showDataLabels: true,
		            	dataLabels: ver,
		            	dataLabelThreshold: 0, 
		          	},
	        	}, 
		
	        	legend: {
		      		show:true, 
	            	placement: 'outside',
		      		location: 's',
		      		seriesToggle: true,
		      		rendererOptions: {
                        numberColumns: 2,
                        numberRows : 2
                    }
	      		},
	         	noDataIndicator: {
		          	show: true,
		          	indicator: 'No hay datos disponibles..'
	        	},
	        	grid: {
	              	drawBorder: false, 
	              	drawGridlines: false,
	              	background: '#ffffff',
	              	shadow:false
	          	},
	      	});
		}
  	}
 

</script>


{/literal}