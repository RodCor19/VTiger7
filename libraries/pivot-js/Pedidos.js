function ageBucket(row, field){
  var age = Math.abs(((new Date().getTime()) - row[field.dataSource])/1000/60/60/24);
  switch (true){
    case (age < 31):
      return '000 - 030'
    case (age < 61):
      return '031 - 060'
    case (age < 91):
      return '061 - 090'
    case (age < 121):
      return '091 - 120'
    default:
      return '121+'
  }
};

var oTable=null;

// Define the structure of fields, if this is not defined then all fields will be assumed
// to be strings.  Name must match csv header row (which must exist) in order to parse correctly.
var fields = [
    // filterable fields
    {name: 'Articulo', type: 'string', filterable: true},
	{name: 'Factura', type: 'string', filterable: true},
	{name: 'Estado', type: 'string', filterable: true,columnLabelable: true},
    {name: 'Fecha', type: 'date', filterable: true},
    {name: 'Edad', type: 'int', filterable: true ,columnLabelable: true},
    {name: 'Comentario', type: 'string', filterable: true},
	{name: 'Contacto',      type:'string',   filterable: true},
	{name: 'Cuenta',      type:'string',   filterable: true},
	{name: 'Usuario',      type:'string',   filterable: true},
	{name: 'PrecioUnitario',      type:'int',   filterable: true},

    // psuedo fields
    {name: 'Año', type: 'int', filterable: true, pseudo: true, columnLabelable: true,
      pseudoFunction: function(row){ return new Date(row.Fecha).getFullYear() }},
	{name: 'Mes', type: 'int', filterable: true, pseudo: true, columnLabelable: true,
      pseudoFunction: function(row){ return new Date(row.Fecha).getMonth() +1}},
    
    // summary fields
    {name: 'Cantidad',     type: 'int',  rowLabelable: false, summarizable: 'count', displayFunction: function(value){ return value}},
	{name: 'Precio',     type: 'float',  rowLabelable: false, summarizable: 'sum', displayFunction: function(value){ return accounting.formatMoney(value)}},
	{name: 'Rentabilidad',     type: 'float',  rowLabelable: false, summarizable: 'sum', displayFunction: function(value){ return accounting.formatMoney(value)}},
]

  function setupPivot(input){
    input.callbacks = {afterUpdateResults: function(){

       oTable = $('#results > table').dataTable({
        "sDom": "<'row'<'span6'l><'span6'f>>t<'row'<'span6'i><'span6'p>>",
        "iDisplayLength": 50,
        "aLengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]],
        "sPaginationType": "bootstrap",
        "oLanguage": {
          "sLengthMenu": "_MENU_ Registros por Pagina"
        }
      });

     // var oSettings = oTable.fnSettings();
     

      
    }};
    $('#pivot-demo').pivot_display('setup', input);
  };

  // from json data:
  var json_string = '[["last_name","first_name","zip_code","billed_amount","last_billed_date"],' +
                    ' ["Jackson", "Robert", 34471, 100.00, "Tue, 24 Jan 2012 00:00:00 +0000"],' +
                    ' ["Smith", "Jon", 34471, 173.20, "Mon, 13 Feb 2012 00:00:00 +0000"]]';
  var field_definitions = [{name: 'last_name',   type: 'string',   filterable: true, rowLabelable: true},
        {name: 'first_name',        type: 'string',   filterable: true, rowLabelable: true},
        {name: 'zip_code',          type: 'integer',  filterable: true},
        {name: 'pseudo_zip',        type: 'integer',  filterable: true },
        {name: 'billed_amount',     type: 'float',    rowLabelable: false, summarizable: 'count'},
        {name: 'last_billed_date',  type: 'date',     filterable: true,columnLabelable: true}]                  

  $(document).ready(function() {
  
    setupPivot({json: json_string, fields: field_definitions, filters: {}, rowLabels:["last_name","first_name","zip_code"],columnLabels:[] ,summaries:[]})
	

    // prevent dropdown from closing after selection
    $('.stop-propagation').click(function(event){
      event.stopPropagation();
    });
	 
   
    /* Add event listeners to the two range filtering inputs */
    $('#min').keyup( function() { oTable.fnDraw(); } );
    $('#max').keyup( function() { oTable.fnDraw(); } );

    
  });

  function write_to_excel(tableid) {
      var table= document.getElementById(tableid);
      var html = table.outerHTML;
      window.open('data:application/vnd.ms-excel,' + escape(html));
}
var tableToExcel = (function() {
  var uri = 'data:application/vnd.ms-excel;base64,'
    , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>'
    , base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) }
    , format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; }) }
  return function(table, name) {
    if (!table.nodeType) table = document.getElementById(table)
    var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}
    window.location.href = uri + base64(format(template, ctx))
  }
})()