// --> /js/app/basico/email-envio-automatico/form.js

$(document).ready(function(){
	
	//chama a função para 
	setDescricaoTipoEmail();
	
	//ao alterar o tipo do email
	$("#emenvauto_TipoEmail").change(function(){
		setDescricaoTipoEmail();
	});
	
	//botão SUBMIT do form
	$("form#GeraisEmailEnvioAutomatico").submit(function(){
		if ($("#emenvauto_TipoEmail").val() == ''){
			$.MessageBox({
				type:		'warning',
				title:		'Ops! Parece que faltou algo...',
				message:	'Informe o Tipo do Email',
			});
			return false;
		}
	});
	
	
	//botão NOVO do form
	$(".EmailEnvioAuto_btnNovo").click(function(){
		var link = $(this).find('a').attr('href');
		if (link == undefined){
			link = $(this).attr('data-location');			
		}
			
		if (link == undefined){
			$.MessageBox({
				type:		'danger',
				title:		'Ops!',
				message:	'Ocorreu um erro ao obter o link para um novo registro.',
			});
		}
		else {		
			window.location = link;
		}
	});
	
	//botão EXCLUIR do form
	$(".EmailEnvioAuto_btnExcluir").click(function(){
		var link = $(this).find('a').attr('href');
		if (link == undefined){
			link = $(this).attr('data-location');			
		}
			
		if (link == undefined){
			$.MessageBox({
				type:		'danger',
				title:		'Ops!',
				message:	'Ocorreu um erro ao obter o link para excluir o registro.',
			});
		}
		else {
			//Valida Acessos
			var v_acesso = $(this).data('acesso');
	    	var v_privilegio = $(this).data('privilegio');
	    	$.ajax({
	    		method: "POST",
	    		url: "/controle-acesso-valida-acesso",
	    		data: { ac: v_acesso, priv: v_privilegio },    	
	    		beforeSend: function(){
	    			body_refresh();
	    		},
	    		success: function(data){
	    			body_refresh();
	    			
	    			var retorno = JSON.parse(data);    			
	    			if (retorno.permitido == 'N'){    				
	    				$.MessageBox({
	    	        		type:		'warning',
	    	        		title:		'Ops!',
	    	        		message:	'Acesso negado',
	    	        	});
	    				return false;
	    			}
	    			else {
	    				//Valida Exlusão
	    				$.MessageBox({
	    					type:		'info',
	    					icon:		'question',
	    					title:		'Deseja excluir o registro?',
	    					message:	'<p>Tem certeza que deseja excluir o registro?</p>',
	    					buttons: [
	    						{
	    							type: 'default',
	    							label: 'Não',
	    							cssClass: 'pull-right',
	    							closeOnClick: true,
	    						},
	    						{
	    							type: 'success',
	    							label: 'Sim',
	    							cssClass: 'pull-right',
	    							closeOnClick: true,
	    							action: function(){
	    								window.location = link;
	    							}
	    						},
	    					]
	    				});	
	    		        return false;
	    			}
	    		}
	    	});
	    	
	    	
			
			
					
		}
	});
	
});

/**
 * Envia para o textarea 'Descrição do Tipo do Email' informações sobre o tipo do email selecionado.
 * 
 */
function setDescricaoTipoEmail(){
	var tipoEmail = $("#emenvauto_TipoEmail").val();
	var strDescricao = '';
	
	if (tipoEmail == ''){
		strDescricao = 'Para exibir a descrição selecione o Tipo do Email';
	}
	else if (tipoEmail == 'Envio de Fotos da Obra'){
		strDescricao = 'Esse tipo de agendamento consiste em verificar se na atual semana foram enviadas as fotos da obra. '
						+ 'Caso não tenham sido enviadas o sistema enviará um email para o engenheiro responsável da obra '
						+ 'solicitando que o mesmo faça o envio das fotos.'
						+ '<br><br>Abaixo selecione os dias os quais deseja que esse email seja enviado.';
	}
	else {
		strDescricao = 'No momento não há uma descrição disponível para esse tipo de email.';
	}
	
	$("#emenvauto_DescricaoTipoEmail").html(strDescricao);
}