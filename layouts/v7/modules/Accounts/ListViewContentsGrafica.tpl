{*+**********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
************************************************************************************}
{* modules/Vtiger/views/List.php *}

{* START YOUR IMPLEMENTATION FROM BELOW. Use {debug} for information *}
{include file="PicklistColorMap.tpl"|vtemplate_path:$MODULE}
{strip}
<div class="col-sm-12 col-xs-12 ">
	<div class="summaryWidgetContainer">
		<div class="widget_header clearfix">
			<h4>{vtranslate('LBL_GRAPHIC', $MODULE_NAME)}</h4>
		</div>
		<div class="widget_contents" id='contenido'>
		</div>
	</div>
</div>
{/strip}
{literal}
<script type="text/javascript">
  $(document).ready(function(){
  	$('#contenido').load('index.php?module=Analisis&view={/literal}{$VIEWNAME}{literal}');
  });
</script>
{/literal}
