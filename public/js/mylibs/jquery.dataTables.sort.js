/* Date Sort (d/m/Y) - BEGIN */
jQuery.extend( jQuery.fn.dataTableExt.oSort, {
    "date-uk-pre": function ( a ) {
        var ukDatea = a.split('/');
        return (ukDatea[2] + ukDatea[1] + ukDatea[0]) * 1;
    },
 
    "date-uk-asc": function ( a, b ) {
        return ((a < b) ? -1 : ((a > b) ? 1 : 0));
    },
 
    "date-uk-desc": function ( a, b ) {
        return ((a < b) ? 1 : ((a > b) ? -1 : 0));
    }
} );
/* Date Sort (d/m/Y) - END */


/* Campo DateTime-short (dd/mm/yy hh:mm) - BEGIN */
$.fn.dataTableExt.oSort['datetime-short-asc'] = function(a,b) {
	var dia = a.substring(0, 2);
	var mes = a.substring(3, 5);
	var ano = a.substring(6, 8);
	var hr  = a.substring(9, 11);
	var min = a.substring(12, 14);
	var str1 = ano + mes + dia + hr + min;

	var dia = b.substring(0, 2);
	var mes = b.substring(3, 5);
	var ano = b.substring(6, 8);
	var hr  = b.substring(9, 11);
	var min = b.substring(12, 14);
	var str2 = ano + mes + dia + hr + min;

	return ((str1 < str2) ? -1 : ((str1 > str2) ?  1 : 0));
};

$.fn.dataTableExt.oSort['datetime-short-desc'] = function(a,b) {
	var dia = a.substring(0, 2);
	var mes = a.substring(3, 5);
	var ano = a.substring(6, 8);
	var hr  = a.substring(9, 11);
	var min = a.substring(12, 14);
	var str1 = ano + mes + dia + hr + min;

	var dia = b.substring(0, 2);
	var mes = b.substring(3, 5);
	var ano = b.substring(6, 8);
	var hr  = b.substring(9, 11);
	var min = b.substring(12, 14);
	var str2 = ano + mes + dia + hr + min;
	
	return ((str1 < str2) ? 1 : ((str1 > str2) ?  -1 : 0));
};
/* Campo DateTime-short (dd/mm/yy hh:mm) - END */


/* Currency Sort - BEGIN */
jQuery.extend( jQuery.fn.dataTableExt.oSort, {
    "currency-pre": function ( a ) {
		a = a.toLowerCase().replace(/\./g, '');
		a = a.toLowerCase().replace(',', '.');
	
        a = (a==="-") ? 0 : a.replace( /[^\d\-\.]/g, "" );
        
        return parseFloat( a );
    },
 
    "currency-asc": function ( a, b ) {
        return a - b;
    },
 
    "currency-desc": function ( a, b ) {
        return b - a;
    }
} );
/* Currency Sort - END */

/* Campo Orçamento - BEGIN */
$.fn.dataTableExt.oSort['field-orcamento-asc'] = function(a,b) {
	var x = a.substring(5,9) + a.substring(0,4);			
	var y = b.substring(5,9) + b.substring(0,4);
	return ((x < y) ? -1 : ((x > y) ?  1 : 0));
};

$.fn.dataTableExt.oSort['field-orcamento-desc'] = function(a,b) {
	var x = a.substring(5,9) + a.substring(0,4);			
	var y = b.substring(5,9) + b.substring(0,4);
	return ((x < y) ?  1 : ((x > y) ? -1 : 0));
};
/* Campo Orçamento - END */