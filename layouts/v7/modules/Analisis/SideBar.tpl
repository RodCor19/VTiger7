{strip}
<!--unSelectedQuickLink-->
<div class="sideBarContents">
	<div class="quickLinksDiv">
		<p onclick="window.location.href=''" class="selectedQuickLink">
			<a class="quickLinks" href="">
				<strong>Analisis</strong>
			</a></p>	
	</div>
	<div class="clearfix"></div>
	<div class="quickWidgetContainer accordion">
		<div class="quickWidget">
			<div class="accordion-heading accordion-toggle quickWidgetHeader" data-target="#Contacts_sideBar_LBL_RECENTLY_MODIFIED" data-toggle="collapse" data-parent="#quickWidgets" data-label="LBL_RECENTLY_MODIFIED" data-widget-url="module=Contacts&amp;view=IndexAjax&amp;mode=showActiveRecords">
				<span class="pull-left">
					<img class="imageElement" data-rightimage="layouts/vlayout/skins/images/rightArrowWhite.png" data-downimage="layouts/vlayout/skins/images/downArrowWhite.png" src="layouts/vlayout/skins/images/downArrowWhite.png"></span>
				<h5 class="title widgetTextOverflowEllipsis pull-right" title="Modificado Recientemente">Gráficas</h5>
				<div class="clearfix"></div>
			</div>
			<div class="widgetContainer accordion-body in collapse" id="Contacts_sideBar_LBL_RECENTLY_MODIFIED" data-url="module=Contacts&amp;view=IndexAjax&amp;mode=showActiveRecords" style="height: auto;">
				<div class="recordNamesList">
					<div class="row-fluid">
						<div class="span10">
							<ul class="nav nav-list">
								{if $AnalisisCampania == '0'}
								<li>
									<a  href="index.php?module=Analisis&amp;view=AnalisisCampania" title="Analísis de Campaña">Análisis de Campaña</a>
								</li>
								{/if}
								{if $Beneficios == '0'}
								<li>
									<a  href="index.php?module=Analisis&amp;view=Beneficios" title="Beneficios">Beneficios</a>
								</li>
								{/if}
								{if $ClientesPorMes == '0'}
								<li>
									<a  href="index.php?module=Analisis&amp;view=Clientes" title="Clientes por Mes">Clientes por Mes</a>
								</li>
								{/if}
								{if $ContratosVentas == '0'}
								<li>
									<a  href="index.php?module=Analisis&amp;view=Contratos" title="Contratos">Contratos y ventas</a>
								</li>
								{/if}
								{if $CorreosRebotados == '0'}
								<li>
									<a href="index.php?module=Analisis&amp;view=Analisis" title="Correos Rebotados">Correos Rebotados</a>
								</li>
								{/if}
								{if $Deudores == '0'}
								<li>
									<a  href="index.php?module=Analisis&amp;view=Deudores" title="Deudores">Deudores</a>
								</li>
								{/if}
								{if $DesempenioPromocion == '0'}
								<li>
									<a  href="index.php?module=Analisis&amp;view=DesempenioPromocion" title="Desempeño de la promoci&oacute;n por d&iacute;a">Desempeño de la Promoción</a>
								</li>
								{/if}
								{if $Gaviotas == '0'}
								<li>
									<a  href="index.php?module=Analisis&amp;view=Gaviotas" title="Gaviotas">Gaviotas</a>
								</li>
								{/if}
								{if $MailsPorPersona == '0'}					
								<li>
									<a  href="index.php?module=Analisis&amp;view=List" title="Mails por Fecha">
									Mails por Persona</a>
								</li>
								{/if}
								{if $ParetoClientes == '0'}
								<li>
									<a  href="index.php?module=Analisis&amp;view=ParetoClientes" title="Pareto Clientes">Pareto Clientes</a>
								</li>
								{/if}
								{if $ParetoLocales == '0'}
								<li>
									<a  href="index.php?module=Analisis&amp;view=ParetoCuentas" title="Pareto Locales">Pareto Locales</a>
								</li>
								{/if}
								{if $Quejas == '0'}
								<li>
									<a  href="index.php?module=Analisis&amp;view=Quejas" title="Contratos">Quejas</a>
								</li>
								{/if}
								{if $RankingClientes == '0'}
								<li>
									<a  href="index.php?module=Analisis&amp;view=RankingClientes" title="Ranking de Clientes">Ranking de Clientes</a>
								</li>
								{/if}
								{if $VentasPorDia == '0'}
								<li>
									<a  href="index.php?module=Analisis&amp;view=VentasPorDia" title="Ventas por d&iacute;a">Ventas por d&iacute;a</a>
								</li>
								{/if}
								{if $VentasPorMes == '0'}
								<li>
									<a  href="index.php?module=Analisis&amp;view=VentasPorMes" title="Ventas por mes">Ventas por mes</a>
								</li>
								{/if}
								{if $VentilacionClientes == '0'}
								<li>
									<a  href="index.php?module=Analisis&amp;view=VentilacionClientes" title="Ventilación Clientes">Ventilación Clientes</a>
								</li>
								{/if}
								<li>
						         <a  href="index.php?module=Analisis&amp;view=InformePromociones" title="Informe de Promociones">Informe de Promociones</a>
						        </li>
								<!--Agregados por Manuel Estefanell-->
								
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
		
	</div>
</div>
{/strip}