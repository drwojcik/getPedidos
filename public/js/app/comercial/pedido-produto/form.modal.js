$(document).ready(function() {
	
	// mostra o MODAL FORM do formulario de cadastro Pedido
    $(".showform_contato_new").click(function(){
    	create_modal($(this), 'mbProspectContato');
    	return false;
    });
	// mostra o MODAL FORM do formulario FOLLOW UP
    $(".showform_prpctContato").click(function(){
    	create_modal($(this), 'mbProspectContato');
    	return false;
    });
 
    //exclui o registro via ajax
    $(".linkExcluir_prpctPedido").click(function(){
    	
    	
    	var link = $(this).attr('href');
		if (link == undefined){			
			$.MessageBox({
				type:		'danger',
				id:			'mbSemUrl',
				icon:		'time',
				title:		'Ops!',
				message:	'<p>Ocorreu um erro ao obter o link para excluir o registro.</p>',
			});
		}
		else {
			
			$.MessageBox({
				type:		'warning',
				id:			'mbExcluirAjax',
				icon:		'warning',
				title:		'Deseja excluir o registro?',
				message:	'',
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
				  			action: function() {
				  				//chama o metodo ajax para excluir o registro
			            		$.ajax({
			            			url: link,
			            			data: '',
			            			type: 'POST',
			            			beforeSend: function(){
			            				body_refresh();
			          			  	},
			            			success: function(data){
			            				body_refresh();
			            				
			            				var retornoArray = eval(data);
			            				var retorno = retornoArray[0];
			            				var msg = '';
			            				var table = '';
			            				var tableFollow = '';
			            				
			            				if (retorno.error == true){
			            					$.MessageBox({
			            						type:		'danger',
			            						id:			'mbErroAjax',
			            						icon:		'time',
			            						title:		'Ops!',
			            						message:	retorno.error_msg,
			            					});    					            					
			            				} else if (retorno.success == true) {
			            					$.MessageBox({
			            						type:		'success',
			            						id:			'mbSuccessAjax',
			            						icon:		'check',
			            						title:		'Sucesso!',
			            						message:	'Registro excluído.',
			            					});
			            					
			            					msg = retorno.success_msg_html;
			            					table = retorno.html;
			            					
			            					
			            					if (table != '')
				            					$("#table_PedidoProduto").html(table);
				            				
			            				} else {
			            					$.MessageBox({
			            						type:		'danger',
			            						id:			'mbErroAjax',
			            						icon:		'time',
			            						title:		'Ops!',
			            						message:	'Ocorreu um erro ao excluir o registro',
			            					});
			            				}    					            				    					            				    					            				    					            				
			            			}
			            		});
			            		
			            		return false;
				  			}
				  		},
		  		]
			});
			
	        return false;
				
		}
		
		return false;
	});
});
