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

<div class="dashboardWidgetHeader">
    {include file="dashboards/WidgetHeader.tpl"|@vtemplate_path:$MODULE_NAME}
</div>
<div class="dashboardWidgetContent">
    {include file="dashboards/DashBoardWidgetContents.tpl"|@vtemplate_path:$MODULE_NAME}
    {literal}
    <script type="text/javascript">
        $(function(){
                    $("#output").pivotUI(
    [ 
        {color: "blue", shape: "circle"}, 
        {color: "red", shape: "triangle"}
    ], 
    { 
        rows: ["color"], 
        cols: ["shape"] 
    }
    );
         });
    </script>

    <p><a href="index.html">&laquo; back to examples</a></p>
    <div id="output" style="margin: 10px;"></div>
    {/literal}
</div>





