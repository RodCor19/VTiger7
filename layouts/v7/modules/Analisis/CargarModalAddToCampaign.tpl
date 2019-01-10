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
{strip}

<div class="modelContainer">
	<div class="modal-header contentsBackground">
		<button class="close" aria-hidden="true" data-dismiss="modal" type="button" title="{vtranslate('LBL_CLOSE')}">x</button>
	    <h3>Exportar a Campaña</h3>
	</div>
	<form class="form-horizontal" name="formCargarCCEdiUni" id="formCargarCCEdiUni">
		<input type="hidden" name="module" value="{$MODULE}">
		<div class="quickCreateContent">
			<div class="modal-body tabbable">
				<div class="row-fluid">
                    <div class="inventoryLineItemHeader" style = "width: 55px; font-size: 14px; display: inline-block;">
                        <span class="alignTop"><b>Campañas:</b></span>&nbsp;&nbsp;
                    </div>
                    <select class="chzn-select lineItemTax" id="campaignname" name="campaignname" style="width: 475px; display: inline-block;">
                        {foreach from = $CAMPAIGNS item = c}
							<option value = "{$c.crmid}">{$c.campaignname}</option>
						{/foreach}
                    </select>
                </div>
			</div>
		</div>
		<input type = "hidden" id = "hidden" value = "{$VALESID}"/>
	</form>
	<div class="modal-footer">
		<div class=" pull-right cancelLinkContainer">
			<a class="cancelLink" type="reset" data-dismiss="modal">{vtranslate('LBL_CANCEL', $MODULE)}</a>
		</div>
		<button class="btn btn-success" name="saveButton" onclick = "submitCampaign(document.getElementById('campaignname'))"><strong>{vtranslate('LBL_SAVE', $MODULE)}</strong></button>
	</div>	
	<input type = "hidden" id = "clientesId" value = ""/>
</div>

{literal}
	<script type = "text/javascript">
		function submitCampaign(select){
			//alert(jQuery('#campaignname').val());
			var progressInstance= jQuery.progressIndicator({
		          'position' : 'html',
		          'blockInfo' : {
		              'enabled' : true
		          }
		      });
			Vtiger_Helper_Js.resultadoFiltroRankingClientes({
		        'action' : 'ResultadoFiltroRankingClientes',
		        'module' : 'Analisis'
		    }).then(
		    	function(data){
		    		//alert(jQuery(select).val());
		    		//jQuery('#clientesId').val(data['message']);

		    		//alert(jQuery('#clientesId').val());
		    		/*var contactsId = jQuery('#clientesId').val();
		    		var ids = contactsId.split(';');
		    		for(var i = 0; i < ids.length; i++){*/
		    		//console.log("crmid: " + jQuery('#campaignname option:selected').val());
		    		Vtiger_Helper_Js.addToCampaign({
					    'action' : 'AddToCampaign',
					    'module' : 'Analisis',
					    'contactsId' : data['message'],
					    'crmid' : jQuery(select).val()
					}).then(
						function(response){
							progressInstance.progressIndicator({'mode':'hide'});
							Vtiger_Helper_Js.showMessage({'text' : "Los clientes han sido agregados a la campaña de manera correcta"});
							app.hideModalWindow();
						},
						function(error, err){
							Vtiger_Helper_Js.showMessage({'text' : "Ha ocurrido un error", 'type' : 'error'});
						}
					);
		    		//}
		    	},
		    	function(error, err){
		            Vtiger_Helper_Js.showMessage({'text' : "Ha ocurrido un error", 'type' : 'error'});
		        }
		    );			
		} 
	</script>
{/literal}

{/strip}




