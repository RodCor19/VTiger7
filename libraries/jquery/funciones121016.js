function saveImage(id, nombre){
    jQuery('#'+id+'Loader').show();
     jQuery('#'+id+'View').hide();
    //alert('#'+id+'Loader');
	
    blah = jqplotToImg(jQuery('#'+id));
    jQuery.ajax({
       async: true,
       data: {"picture": blah,"id": id,"nombre": nombre},
       url:  'index.php?module=AnalisisDatos&action=SaveImage',
       type:"post",
       success: function(data) {
         var myArray = data.split('#%|:');
         //var arRes = myArray[1].split(':|%#');
         var addy=myArray[1];
         var num= addy.substr(0, addy.indexOf(':|%#')); 
         jQuery('#'+id+'Loader').hide();
         jQuery('#'+id+'View').attr("onClick","window.location.href = 'index.php?module=AnalisisDatos&view=Detail&record="+num+"';");
         jQuery('#'+id+'View').show();
       },
        error: function (xhr, ajaxOptions, thrownError) {
          console.log(thrownError);
      }
     });

}
function saveExcel(id,table,text){
    jQuery('#'+id+'Loader').show();
    jQuery('#'+id+'View').hide();
    //alert('#'+id+'Loader');
    
    //excel = jQuery('#pivot-table').html();

    var table= document.getElementById(table);
    var html = table.outerHTML;

    //add more symbols if needed...
    while (html.indexOf('á') != -1) html = html.replace('á', '&aacute;');
    while (html.indexOf('é') != -1) html = html.replace('é', '&eacute;');
    while (html.indexOf('í') != -1) html = html.replace('í', '&iacute;');
    while (html.indexOf('ó') != -1) html = html.replace('ó', '&oacute;');
    while (html.indexOf('ú') != -1) html = html.replace('ú', '&uacute;');
    while (html.indexOf('º') != -1) html = html.replace('º', '&ordm;');
    while (html.indexOf('ñ') != -1) html = html.replace('ñ', '&ntilde;');

    jQuery.ajax({
       async: true,
       data: {"html": html,"id": text},
       url:  'index.php?module=AnalisisDatos&action=SaveExcel',
       type:"post",
       success: function(data) {
        console.log(data);
         var myArray = data.split('#%|:');
         //var arRes = myArray[1].split(':|%#');
         var addy=myArray[1];
         var num= addy.substr(0, addy.indexOf(':|%#')); 
         jQuery('#'+id+'Loader').hide();
         jQuery('#'+id+'View').attr("onClick","window.location.href = 'index.php?module=AnalisisDatos&view=Detail&record="+num+"';");
         jQuery('#'+id+'View').show();
       },
        error: function (xhr, ajaxOptions, thrownError) {
          console.log(thrownError);
      }
     });

}
function jqplotToImg(obj) {
  var newCanvas = document.createElement("canvas");
  newCanvas.width = obj.find("canvas.jqplot-base-canvas").width();
  newCanvas.height = obj.find("canvas.jqplot-base-canvas").height()+10;
  var baseOffset = obj.find("canvas.jqplot-base-canvas").offset();
  
  // make white background for pasting
  var context = newCanvas.getContext("2d");
  context.fillStyle = "rgba(255,255,255,1)";
  context.fillRect(0, 0, newCanvas.width, newCanvas.height);
  
  obj.children().each(function () {
  // for the div's with the X and Y axis
    if (jQuery(this)[0].tagName.toLowerCase() == 'div') {
      // X axis is built with canvas
      jQuery(this).children("canvas").each(function() {
        var offset = jQuery(this).offset();
        newCanvas.getContext("2d").drawImage(this,
          offset.left - baseOffset.left,
          offset.top - baseOffset.top
        );
      });
      // Y axis got div inside, so we get the text and draw it on the canvas
      jQuery(this).children("div").each(function() {
        var offset = jQuery(this).offset();
        var context = newCanvas.getContext("2d");
        context.font = jQuery(this).css('font-style') + " " + jQuery(this).css('font-size') + " " + jQuery(this).css('font-family');
        context.fillStyle = jQuery(this).css('color');
        context.fillText(jQuery(this).text(),
          offset.left - baseOffset.left,
          offset.top - baseOffset.top + jQuery(this).height()
        );
      });
    } else if(jQuery(this)[0].tagName.toLowerCase() == 'canvas') {
      // all other canvas from the chart
      var offset = jQuery(this).offset();
      newCanvas.getContext("2d").drawImage(this,
        offset.left - baseOffset.left,
        offset.top - baseOffset.top
      );
    }
  });
  
  // add the point labels
  obj.children(".jqplot-point-label").each(function() {
    var offset = jQuery(this).offset();
    var context = newCanvas.getContext("2d");
    context.font = jQuery(this).css('font-style') + " " + jQuery(this).css('font-size') + " " + jQuery(this).css('font-family');
    context.fillStyle = jQuery(this).css('color');
    context.fillText(jQuery(this).text(),
      offset.left - baseOffset.left,
      offset.top - baseOffset.top + jQuery(this).height()*3/4
    );
  });
  
  // add the title
  obj.children("div.jqplot-title").each(function() {
    var offset = jQuery(this).offset();
    var context = newCanvas.getContext("2d");
    context.font = jQuery(this).css('font-style') + " " + jQuery(this).css('font-size') + " " + jQuery(this).css('font-family');
    context.textAlign = jQuery(this).css('text-align');
    context.fillStyle = jQuery(this).css('color');
    context.fillText(jQuery(this).text(),
      newCanvas.width / 2,
      offset.top - baseOffset.top + jQuery(this).height()
    );
  });
  
  // add the legend
  obj.children("table.jqplot-table-legend").each(function() {
    var offset = jQuery(this).offset();
    var context = newCanvas.getContext("2d");
    context.strokeStyle = jQuery(this).css('border-top-color');
    context.strokeRect(
      offset.left - baseOffset.left,
      offset.top - baseOffset.top,
      jQuery(this).width(),jQuery(this).height()
    );
    context.fillStyle = jQuery(this).css('background-color');
    context.fillRect(
      offset.left - baseOffset.left,
      offset.top - baseOffset.top,
      jQuery(this).width(),jQuery(this).height()
    );
  });
  
  // add the rectangles
  obj.find("div.jqplot-table-legend-swatch").each(function() {
    var offset = jQuery(this).offset();
    var context = newCanvas.getContext("2d");
    context.fillStyle = jQuery(this).css('background-color');
    context.fillRect(
      offset.left - baseOffset.left,
      offset.top - baseOffset.top,
      jQuery(this).parent().width(),jQuery(this).parent().height()
    );
  });
    
  obj.find("td.jqplot-table-legend").each(function() {
    var offset = jQuery(this).offset();
    var context = newCanvas.getContext("2d");
    context.font = jQuery(this).css('font-style') + " " + jQuery(this).css('font-size') + " " + jQuery(this).css('font-family');
    context.fillStyle = jQuery(this).css('color');
    context.textAlign = jQuery(this).css('text-align');
    context.textBaseline = jQuery(this).css('vertical-align');
    context.fillText(jQuery(this).text(),
      offset.left - baseOffset.left,
      offset.top - baseOffset.top + jQuery(this).height()/2 + parseInt(jQuery(this).css('padding-top').replace('px',''))
    );
  });

  // convert the image to base64 format
  return newCanvas.toDataURL("image/png");
}

function tableToExcel(table,name,esClase){
  if (typeof esClase === 'undefined') { esClase = false; }else{esClase=true;}
  numeral="#";
  if(esClase){
    numeral=".";
  }
  var chargeTable = jQuery(numeral+table);
  var chargeTableCloned = chargeTable.clone();
  chargeTable=normalize(numeral+table);
  date= new Date().getTime();
  date_submited=jQuery("#is_submited").val();
  ejecutar=false;
  //Hack IE, a veces hace submit 2 veces, pregunto que haya pasado mas de 1 segundo entre submit y submit
  if(date_submited==""){
    ejecutar=true;
  }else{
    if(date-date_submited>1000){
      ejecutar=true;  
    }  
  }
  if(ejecutar){
      var html=jQuery("<div>").append( jQuery(numeral+table).eq(0).clone()).html();
      while (html.indexOf('á') != -1) html = html.replace('á', '&aacute;');
      while (html.indexOf('Á') != -1) html = html.replace('Á', '&Aacute;');
      while (html.indexOf('é') != -1) html = html.replace('é', '&eacute;');
      while (html.indexOf('É') != -1) html = html.replace('É', '&Eacute;');
      while (html.indexOf('í') != -1) html = html.replace('í', '&iacute;');
      while (html.indexOf('Í') != -1) html = html.replace('Í', '&Iacute;');
      while (html.indexOf('ó') != -1) html = html.replace('ó', '&oacute;');
      while (html.indexOf('Ó') != -1) html = html.replace('Ó', '&Oacute;');
      while (html.indexOf('ú') != -1) html = html.replace('ú', '&uacute;');
      while (html.indexOf('Ú') != -1) html = html.replace('Ú', '&Uacute;');
      while (html.indexOf('º') != -1) html = html.replace('º', '&ordm;');
      while (html.indexOf('ñ') != -1) html = html.replace('ñ', '&ntilde;'); 
      while (html.indexOf('Ñ') != -1) html = html.replace('Ñ', '&Ntilde;');  
      jQuery("#datos_a_enviar").val(html);
      jQuery("#nombre_a_enviar").val(name);
      jQuery("#is_submited").val(date);
      jQuery("#FormularioExportacion").submit();
    
  }
  //chargeTable.after(chargeTableCloned);
  jQuery(numeral+table).html(chargeTableCloned);
  return false;
  
}

function normalize(table){
  jQuery(table).each(function() {
  var headerKey, headerPattern, headers, removeCount, separator;
  //headerKey = passedKey;
  jQuery(this).find('th[colspan], td[colspan]').each(function() {
    var cell, count, results;
    cell = jQuery(this);
    count = parseInt(cell.attr('colspan')) - 1;
    cell.removeAttr('colspan');
    results = [];
    while (count > 0) {
      cell.after(cell.clone());
      cell = cell;
      results.push(count--);
    }
    return results;
  });
  jQuery(this).find('th[rowspan], td[rowspan]').each(function() {
    var cell, count, index, results, row;
    cell = jQuery(this);
    row = cell.parent();
    index = cell.get(0).cellIndex;
    count = parseInt(cell.attr('rowspan')) - 1;
    cell.removeAttr('rowspan');
    results = [];
    while (count > 0) {
      row = row.next();
      row.find("td:nth-child(" + (index + 1) + "), th:nth-child(" + (index + 1) + ")").before(cell.clone());
      results.push(count--);
    }
    return results;
  });
  headers = [];
  separator = ' - ';
  removeCount = 0;
  headerPattern = null;
  jQuery(this).find('tr').each(function(i) {
    var cell, text;
    cell = jQuery(this).find('td:nth-child(1), th:nth-child(1)');
    text = cell.text().trim();
    if (i === 0) {
      headerKey || (headerKey = text);
      headerPattern = new RegExp("^" + headerKey + "jQuery", 'i');
      return jQuery(this).children().each(function() {
        return headers.push([jQuery(this).text().trim()]);
      });
    } else {
      if (text.match(headerPattern)) {
        jQuery(this).children().each(function(i) {
          var header;
          header = headers[i];
          text = jQuery(this).text().trim();
          if (header.indexOf(text) === -1) {
            return header.push(text);
          }
        });
        return jQuery(this).remove();
      } else {
        return false;
      }
    }
  });
  return jQuery(this).find('tr:first').children().each(function(i) {
    return jQuery(this).text(headers[i].join(separator));
  });
});

// ---
// generated by coffee-script 1.9.2
}



 var getFirstBrowserLanguage = function () {
    var nav = window.navigator,
    browserLanguagePropertyKeys = ['language', 'browserLanguage', 'systemLanguage', 'userLanguage'],
    i,
    language;

    // support for HTML 5.1 "navigator.languages"
    if (Array.isArray(nav.languages)) {
      for (i = 0; i < nav.languages.length; i++) {
        language = nav.languages[i];
        if (language && language.length) {
          //Me quedo con la primera parte del idioma, porqué si es Español Lat (es-419) están invertidos los separadores
          res=language.split('-');
          return res[0];
        }
      }
    }

    // support for other well known properties in browsers
    for (i = 0; i < browserLanguagePropertyKeys.length; i++) {
      language = nav[browserLanguagePropertyKeys[i]];
      if (language && language.length) {
        //return language;
        //Me quedo con la primera parte del idioma, porqué si es Español Lat (es-419) están invertidos los separadores
          res=language.split('-');
          return res[0];
      }
    }

    return null;
  };
  function getDecimalSeparator() {
    //fallback  
       var decSep = ".";

        try {
            // this works in FF, Chrome, IE, Safari and Opera
            var sep = parseFloat(3/2).toLocaleString(getFirstBrowserLanguage()).substring(1,2);
            if (sep === '.' || sep === ',') {
                decSep = sep;
            }
        } catch(e){}

        return decSep;
    }
  function getMilSeparator() {
    //fallback  
       var decSep = ".";

        try {
            // this works in FF, Chrome, IE, Safari and Opera
            var sep = parseFloat(1000).toLocaleString(getFirstBrowserLanguage()).substring(1,2);
            if (sep === '.' || sep === ',') {
                decSep = sep;
            }
        } catch(e){}

        return decSep;
    }  