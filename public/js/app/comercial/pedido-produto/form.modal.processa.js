//..comercial/prospect-contato/form.modal.processa.js

$(function(){
	//intercepta o botao submit do form e faz o envio via ajax
    $("form#FrmPedidoProduto").submit(function(){

    	//seta os dados do formulario
    	//o nome dos campos enviados via ajax devem ser iguais aos nomes declarados para o form
        var dadosForm = {
        		idPedidoProduto:        $("#cntt_idPedidoProduto").val(),
        		PedidoIdPedido:			$("#prpct_idPedido").val(),
        		ProdutoIdProduto:		$("#cntt_ProdutoidProduto").val(),
        		QtdeProduto:			$("#cntt_QtdeProduto").val(),
        		     		
        };
        
        // utilizada ao final para fechar o modal
        var objModal = $(this).parents('.modal');
        
        $.ajax({
			url: urlSaveModalContato,
			data: dadosForm,
			type: 'POST',
			beforeSend: function(){
				panel_refresh(objModal);
			},
			success: function(data){
				panel_refresh(objModal);
				console.log(data);
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
					$.MessageBox({
						type:		'success',
						id:			'mbSucessoAjax',
						icon:		'warning',
						title:		'Sucesso!',
						message:	retorno.success_msg_html,
					});
					
					// atualiza a tabela de listagem
					$("#table_PedidoProduto").html(retorno.html);
				}
				
				//fecha o modal				
	        	objModal.modal('toggle');
			}
		}); 
		// fim do ajax
    	
        return false;
    });
});