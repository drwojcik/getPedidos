$(document).ready(function(){
    	
	$("#box_tableFiltro").hide();
	$("#btnConfirma").hide();		
	
	//intercepta o botao submit do form e faz o envio via ajax
    $("#btnPesq").click(function(){
    	
    	if($(this).attr('data-id-referencia') != ''){
    		
    		var IdReferencia = '#'+$(this).attr('data-id-referencia');
    		var ValorReferencia = $(IdReferencia).val();
    		
    		var NameReferencia = $(this).attr('data-name-referencia')
    	} else{
    		
    		var ValorReferencia = null;
    		var NameReferencia = null;
    		
    	}
    		
    	//seta os dados do formulario
        var dadosForm = {
        		
        		Campo1:	    		$("#filtro_Campo1").val(),
        		Campo2:				$("#filtro_Campo2").val(),
        		Campo3:				$("#filtro_Campo3").val(),
        		NomeCampo1:			$(this).attr('data-name-campo1'), 
        		NomeCampo2:			$(this).attr('data-name-campo2'),    		
        		NomeCampo3:			$(this).attr('data-name-campo3'),
        		ValorReferencia:	ValorReferencia,
        		ColunaReferencia:   NameReferencia,
        		DataSelect:			$(this).attr('data-select'), 
        		Tabela:				$("#filtro_Tabela").val(),
        };
             	
        // utilizada ao final para fechar o modal
    	var objModal = $(this).parents('.modal');
    	
        $.ajax({
			url: '/basico/pesquisa-generica/pesquisa',
			data: {DadosForm: dadosForm},
			type: 'POST',
			beforeSend: function(){
				panel_refresh(objModal);
			},
			success: function(data){
				panel_refresh(objModal);
				
				var retornoArray = eval(data);
				var retorno = retornoArray[0];
				
				if (retorno.error == true){
					$.MessageBox({
						type:		'warning',
						id:			'mbErroAjax',
						icon:		'warning',
						title:		'Ops!',
						message:	retorno.error_msg_html,
					});
				} else if (retorno.success == true) {
					
					// atualiza a tabela de listagem
					$("#table_Filtro").html(retorno.html);

					$("#btnPesq").hide();
					$("#box_tableFiltro").show();
					$("#btnConfirma").show();

				}
				 
			}
		}); 
		// fim do ajax

        return false;
    });
    
    //Opção para realziar uma nova pesquisa
    $("#btnPesqNovamente").click(function(){
    	
    	if($(this).attr('data-id-referencia') != ''){
    		
    		var IdReferencia = '#'+$(this).attr('data-id-referencia');
    		var ValorReferencia = $(IdReferencia).val();
    		
    		var NameReferencia = $(this).attr('data-name-referencia')
    	} else{
    		
    		var ValorReferencia = null;
    		var NameReferencia = null;
    		
    	}
    	
    	//seta os dados do formulario
        var dadosForm = {
        		
        		Campo1:	    		$("#filtro_Campo1").val(),
        		Campo2:				$("#filtro_Campo2").val(),
        		Campo3:				$("#filtro_Campo3").val(),
        		NomeCampo1:			$(this).attr('data-name-campo1'), 
        		NomeCampo2:			$(this).attr('data-name-campo2'),    		
        		NomeCampo3:			$(this).attr('data-name-campo3'),
        		ValorReferencia:	ValorReferencia,
        		ColunaReferencia:   NameReferencia,
        		DataSelect:			$(this).attr('data-select'), 
        		Tabela:				$("#filtro_Tabela").val(),
        	       		
        };
        	
        // utilizada ao final para fechar o modal
    	var objModal = $(this).parents('.modal');
    	
        $.ajax({
			url: '/basico/pesquisa-generica/pesquisa',
			data: {DadosForm: dadosForm},
			type: 'POST',
			beforeSend: function(){
				panel_refresh(objModal);
			},
			success: function(data){
				panel_refresh(objModal);
				
				var retornoArray = eval(data);
				var retorno = retornoArray[0];
				
				if (retorno.error == true){
					$.MessageBox({
						type:		'warning',
						id:			'mbErroAjax',
						icon:		'warning',
						title:		'Ops!',
						message:	retorno.error_msg_html,
					});
				} else if (retorno.success == true) {
					
					// atualiza a tabela de listagem
					$("#table_Filtro").html(retorno.html);

					$("#btnPesq").hide();
					$("#box_tableFiltro").show();
					$("#btnConfirma").show();

				}
				 
			}
		}); 
		// fim do ajax
	        
        return false;
    });
    
    //Confirma a seleção de um registro no filtro
    $("#btnConfirma").click(function(){   	
    
    	//Verifica se um campo foi selecionado
	    if ($("input:radio[name='CodFiltro_chkaprovar']").is(":checked")){
	    	
	    	var objModal = $(this).parents('.modal');
	    	console.log(objModal);
	    	
	    	//Separa o Código do registro do nome para enviar aos respectivos campos da tela principal
	    	var strDados = $("input:radio[name='CodFiltro_chkaprovar']:checked").val();
	    	var infoDados = strDados.split("[&]");
	    	
	    	var idCampoOrigem = '#'+$(this).attr('data-cod-campo-origem');
	    
	    	var nomeCampoOrigem = '#'+$(this).attr('data-nome-campo-origem');
	    	
	    	$(idCampoOrigem).val(infoDados[0]);
	    	$(nomeCampoOrigem).val(infoDados[1]);
	    	
	    	$(nomeCampoOrigem).focus();	
	    	
	    	//fecha o modal	
	    	objModal.modal('toggle');
	    	objModal.remove();
        
		} else {
			
			$.MessageBox({
	    		type:		'warning',
	    		title:		'Ops! Acho que faltou alguma coisa...',
	    		message:	'Por favor, selecione um registro',
	
			});
			return false;
			
		}
    	 	
    });
    
    $("#btnFechar").click(function(){
    	
    	var objModal = $(this).parents('.modal');
    	objModal.modal('toggle');
    	objModal.remove();
    });
    
    $("#iconeFechar").click(function(){
    	
    	var objModal = $(this).parents('.modal');
    	objModal.modal('toggle');
    	objModal.remove();
    });

    
});

//function validaFormFiltro(){
//	var retorno = false;
//	
//    if ($('#filtro_Campo1').val() != '' || $('#filtro_Campo1').val() != 'undefined') {
//		retorno = true;
//	} else if ($('#filtro_Campo2').val() != '' || $('#filtro_Campo2').val() != 'undefined') {
//		retorno = true;
//	} else if ($('#filtro_Campo3').val() != '' || $('#filtro_Campo3').val() != 'undefined') {
//		retorno = true;
//	} 
//    
//	return retorno;
//}

//function validarNum(data){
//	if(/\D/.test(data)){
//		return false;
//	}
//}

