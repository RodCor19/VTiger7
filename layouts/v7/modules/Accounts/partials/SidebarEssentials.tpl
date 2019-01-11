{*  +*************************************************************************************************
 *  The contents of this file are subject to the vtiger CRM Public License Version 1.1
 *  ("License"); You may not use this file except in compliance with the License
 *  The Original Code is: vtiger CRM Open Source
 *  The Initial Developer of the Original Code is vtiger.
 *  Portions created by vtiger are Copyright (C) vtiger.
 *  All Rights Reserved.
******************************************************************************************************}
<div class="sidebar-menu">
    <div class="module-filters" id="module-filters">
        <div class="sidebar-container lists-menu-container">
            <div class="menu-scroller scrollContainer" style="position:relative; top:0; left:0;">
                <div class="list-menu-content">
                    <div class="list-group" id="myList">   
                        <h6 class="lists-header " >
                            {vtranslate('LBL_MY_LIST',$MODULE)}
                        </h6>
                        <input type="hidden" name="allCvId" value="0">
                        <ul class="lists-menu">
                            <li style="font-size:12px;" class="listViewFilter"> 
                                <a class="filterName listViewFilterElipsis" href="index.php?module=Accounts&view=List&viewname=vdiarias&app=SALES" title="Ventas diarias" oncontextmenu="return false;" data-filter-id="vdiarias">Ventas diarias</a> 
                                <div class="pull-right">
                                    <span class="js-popover-container" style="cursor:pointer;">
                                        <span class="fa fa-angle-down" rel="popover" data-toggle="popover" aria-expanded="true" toggleclass="fa fa-square-o"  data-defaulttoggle="index.php?module=Accounts&view=List&viewname=&app=SALES" data-default="index.php?module=Accounts&view=List&viewname=&app=SALES" data-ismine="true" data-original-title="" title="">
                                        </span>
                                    </span>
                                </div>
                            </li>
                            <li style="font-size:12px;" class="listViewFilter"> 
                                <a class="filterName listViewFilterElipsis" href="index.php?module=Accounts&view=List&viewname=vmensuales&app=SALES" title="Ventas mensuales" oncontextmenu="return false;" data-filter-id="vmensuales">Ventas mensuales</a> 
                                <div class="pull-right">
                                    <span class="js-popover-container" style="cursor:pointer;">
                                        <span class="fa fa-angle-down" rel="popover" data-toggle="popover" aria-expanded="true" toggleclass="fa fa-square-o"  data-defaulttoggle="index.php?module=Accounts&view=List&viewname=&app=SALES" data-default="index.php?module=Accounts&view=List&viewname=&app=SALES" data-ismine="true" data-original-title="" title="">
                                        </span>
                                    </span>
                                </div>
                            </li>
                        </ul>
                        <div class="clearfix"> 
                            <a class="toggleFilterSize" data-more-text=" -8 más" data-less-text="Show less">
                            </a> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="module-filters">
    </div>
</div>
