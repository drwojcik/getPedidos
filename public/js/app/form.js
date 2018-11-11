
// FUNCTIONS
function getCmbAjax(urlAjax, urlParams, selectorCmb){
	$.ajax({
		url: urlAjax,
		data: urlParams,
		type: 'GET',
		success: function(data){
			retorno = JSON.parse(data);			
			if (retorno.erro == 1){
				alert('Ocorreu um erro ao buscar os dados da combo. ' + selectorCmb);
			} else if (retorno.sucesso == 1) {
				$(selectorCmb).html(retorno.options);
				$(selectorCmb).selectpicker('refresh');
			}
		},
		error: function(e){
			alert('Falha no processamento ajax. ' + e);
		}
	});
}