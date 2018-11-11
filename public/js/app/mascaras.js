$(function() {
	// ------------------------------------------------------------------------------------------------
	// MASCARAS
	// ------------------------------------------------------------------------------------------------
	
	//CPF
	$(".mask_cpf").mask("999.999.999-99");
	
	
	//CNPJ
	$(".mask_cnpj").mask("99.999.999/9999-99");
	
	//CEP
	$(".mask_cep").mask("99999-999");
	
	//CNJ
	$(".mask_cnj").mask("9999999-99.9999.9.99.9999");
	//$(".mask_cnj").mask("#######-##.####.###.####");
	
	//ANIVERSARIO	
	$(".mask_aniversario").mask("99/99");
	
	//Data	
	$(".mask_datanormal").mask("99/99/9999");
	
	//Hora	
	$(".mask_hora").mask("99:99");
	
	//Hora_Prazo	
	$(".mask_hora_prazo").mask("999:99");

	$(".mask_hora_prazo_mil").mask("9999:99");

	//------------------------------------------------
	// FONE
	//------------------------------------------------
	// ## Ao carregar a pagina

	//no formulario
	$(".mask_fone").each(function(){
		if ($(this).val().length == 10){
			$(this).mask("(99) 9999-9999");
		}
		else if ($(this).val().length >= 11){
			$(this).mask("(99) 99999-9999");
		}
	});
	//na grid	
	$(".mask_fone_grid").each(function(){
		if ($(this).html().length == 10){
			$(this).mask("(99) 9999-9999");
		}
		else if ($(this).html().length >= 11){
			$(this).mask("(99) 99999-9999");
		}
	});
	
	// ## Ao digitar no campo
	$(".mask_fone").focusin(function(){
		$(this).unmask();
	});
	$(".mask_fone").focusout(function(){
		if ($(this).val().length == 10){
			$(this).mask("(99) 9999-9999");
		}
		else if ($(this).val().length >= 11){
			$(this).mask("(99) 99999-9999");
		}
	});
	
	// ------------------------------------------------------------------------------------------------	
});
