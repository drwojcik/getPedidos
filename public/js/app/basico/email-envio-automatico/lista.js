// --> /js/app/basico/email-envio-automatico/lista.js

$(document).ready(function(){
	//DataTable
	$("#tblEmailEnvioAutomatico").dataTable({
		"aaSorting": [[0,'desc']],	//ordena pela coluna 1
		"aoColumns": [
			{"sWidth": "10%"},
			{"sWidth": "70%"},
			{"sWidth": "10%",},
			{"sWidth": "10%", "bSortable": false},
  		]
	});
});