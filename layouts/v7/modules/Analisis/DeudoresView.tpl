<div class="span10" style="overflow: hidden;padding: 2em">
	<div class="summaryWidgetContainer">
		<div class="widget_header row-fluid">
			<span class="span8">
				<h4 class="textOverflowEllipsis">Informes</h4>
			</span>
		</div>
		<div class="links"><br>
			<div class="row-fluid">
				<div class="span2.4">
					<a id="deudoresbtn" href="index.php?module=Analisis&view=ChequesDiferidos" class="btn btn-primary" style="font-size: 11px"><strong> Cheques Diferidos</strong> </a>
				</div>
				<div class="span2.4">
					<a id="btnMorosidad" href="index.php?module=Analisis&view=Morosidad" class="btn btn-primary" style="font-size: 11px"><strong> Morosidad por Cliente</strong></a>
				</div>
				<div class="span2.4">
					<a id="btnSaldosContables" href="index.php?module=Analisis&view=SaldosContables" class="btn btn-primary" style="font-size: 11px"><strong> Saldos Contables</strong></a>
				</div>
				<div class="span2.4">
					<a id="btnIndiceMorosidad" href="index.php?module=Analisis&view=IndiceMorosidad" class="btn btn-primary" style="font-size: 11px"><strong> Índice de Morosidad</strong></a>
				</div>
				<div class="span2.4">
					<a id="btnIndiceRotacion" href="index.php?module=Analisis&view=IndiceRotacion" class="btn btn-primary" style="font-size: 11px"><strong> Índice de Rotación</strong></a>
				</div>
			</div>

		</div>
	</div>

	<div class="summaryWidgetContainer">
		<div class="widget_header row-fluid">
			<span class="span8">
				<h4 class="textOverflowEllipsis">Procesos</h4>
			</span>
		</div>
		<div class="links"><br>
			
			<div class="row-fluid">
				<div class="span2.4">
					<a id="actualizarSaldos" href="#" class="btn btn-primary" style="font-size: 11px; margin-top: 10px;"><strong> Actualizar Saldos</strong> </a>
				</div>
			</div>
		</div>
	</div>
</div>

{literal}
<script type="text/javascript">
	document.getElementById("actualizarSaldos").addEventListener ('click',function(e){				
		e.preventDefault();
		
        
		llamarAlAjax();
	});
	function llamarAlAjax(){
	var progressInstance= jQuery.progressIndicator({
            'position' : 'html',
            'blockInfo' : {
                'enabled' : true
            },
            'message' : 'Actualizando los saldos, no cierre la página'
        });

		jQuery.ajax({    
            data: {},

            url: 'importar_elementos_saldos_cont.php',
            success:  function (response) {
            	progressInstance.progressIndicator({'mode':'hide'});
                Vtiger_Helper_Js.showMessage({text : "Se actualizaron los Saldos"});
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) { 
                //console.log("Status: " + textStatus); 
                //console.log("Error: " + errorThrown);
				Vtiger_Helper_Js.showMessage({'text' : "Hubo un error al actualizar los Saldos", 'type' : 'error'});

            }  
        });
	}
</script>
{/literal}