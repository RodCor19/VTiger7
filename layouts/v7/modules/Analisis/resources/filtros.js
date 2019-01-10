var filtros = [];
window.onload = function(){
	
	getFiltros();
	document.getElementById("abrirModalFiltro").addEventListener('click',function(){
		$("#modalFiltro").modal();
	})

	document.getElementById("guardarFiltro").addEventListener('click',function(){		
		if(!!filtros[document.getElementById('nombreFiltro').value]){
			Vtiger_Helper_Js.showConfirmationBox({message:"Ya existe un filtro con este nombre, desea sobreescribirlo"}).then(
				function(e) {
					guardarFiltro();
				},
				function(error, err) {
					
				}
			);
		}else{
			guardarFiltro();
		}
	})
	

	document.getElementById("selectFiltros").addEventListener("change",function(){
		var filtro = filtros[this.value];
		if(filtro){
			var params = {cols:filtro.columnas,rows:filtro.filas};
			if(filtro.filtros.fecha){
				document.getElementById("createdtime").value = filtro.filtros.fecha;
			}
			graficar(params);
		}
	})

	function guardarFiltro(){
		var datos = getInfo();
		var params = {
			module : 'Analisis',
			view : 'Filtros',
			mode: 'create',
			datos : datos
		}
		AppConnector.request(params).then(function(response){
			console.log(response);
			response = JSON.parse(response);
			if(response.success){
				Vtiger_Helper_Js.showMessage({text:"Filtro creado correctamente"});
				agregarFiltro(response.data);
			}else{
				Vtiger_Helper_Js.showPnotify(response.message? response.message : "Error al guardar el filtro");
			}
		});
	}

	function getFiltros(){
		var tabla = document.getElementById('nombreTabla')? document.getElementById('nombreTabla').value : "";		
		filtros = [];
		var params = {
			module : 'Analisis',
			view : 'Filtros',
			mode : 'getdata',
			tabla : tabla
		}
		AppConnector.request(params).then(function(response){
			response = JSON.parse(response);
			console.log(response)
			if(response.success){
				for(var i = 0;i<response.data.length; agregarFiltro(response.data[i++]));
			}
		})
	}

	function agregarFiltro(filtro){
		var existe = filtros[filtro.nombre];
		var select = document.getElementById('selectFiltros');
		var option = document.createElement('option');
		option.value = option.textContent = filtro.nombre;				
		filtros[filtro.nombre] = filtro;
		if(!existe) select.appendChild(option);

	}

	//Funcion que retorna los parametros actuales de filtro
	function getInfo(){
		var info = {nombre:"",filas:[],columnas:[],filtros:{fecha:null},tabla:"",publico:false}; //Agregar aggregator (vals), aggregatorname, tabla
		var nombre = document.getElementById('nombreFiltro').value;
		var fecha = document.getElementById('createdtime').value;
		var publico = document.getElementById('publicoFiltro').checked;
		var rows = $(".pvtRows .pvtAttr");
		for(var x=0 ; x<rows.length ; info.filas.push(	getNombre(rows[x++]	)	));
		var cols = $(".pvtCols .pvtAttr");
		for(var x=0 ; x<cols.length ; info.columnas.push(	getNombre(cols[x++])	));
		var tabla = document.getElementById('nombreTabla')? document.getElementById('nombreTabla').value : "";
		info.nombre = nombre;
		info.filtros.fecha = fecha;
		info.tabla = tabla;
		info.publico = publico;
		var aggregator = $(".pvtAggregator").val();
		var aggregatorVal = $(".pvtAttrDropdown").val();
		console.log(info);
		return info;
	}
	function getNombre(span){
		var ar = span.textContent.split(" ");
		ar.pop();
		return ar.join(" ");
	}
	
}

	//Que hacer cuando se seleciona el filtro
	function seleccionarFiltro(){
		var select = document.getElementById('selectFiltros').value;	
		if(select!=""){
			return !!filtros[select]? filtros[select] : "";
		}	
	}