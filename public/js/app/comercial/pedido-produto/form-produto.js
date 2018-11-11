$(document).ready(function(){
	$("#cntt_NomeProduto").autocomplete({   
		serviceUrl:'/comercial/produto/listproduto',
		minChars:3,
		maxHeight:300,
		width: 600,
		zIndex: 9999,
		params: {cliente: $("#ped_CodRepresentada").val()},
		onSelect: function (suggestion) {
			if(suggestion.data == '0'){
				alert('Produto não encontrado!');
				$("#cntt_ProdutoidProduto").val('');
				$("#cntt_NomeProduto").val('');
				$("#cntt_ValorUnitario").val('');
			}else{
				$("#cntt_ProdutoidProduto").val(suggestion.data);
				$("#cntt_NomeProduto").val(suggestion.value);
				$("#cntt_ValorUnitario").val(suggestion.preco);
				//buscaTabelaPreco($("#ped_CodProduto").val());

			}
			
		}
	});
	$("#cntt_NomeProduto").focusout(function(){ 
		if($("#cntt_ProdutoidProduto").val() == ''){
			$("#cntt_NomeProduto").val('');
		}
		
	});
	//Alteração da tabela de Preço
	$("#ped_CodTabela").change(function(){ 
		mudaPrecoMaterial($("#ped_CodProduto").val(), $("#ped_CodTabela").val());		
	});
	
	$("#ped_Produto").focusout(function(){ 
		buscaTabelaPreco($("#ped_CodProduto").val());		
	});
	
	//calcula o valor total
	$("#cntt_QtdeProduto").change(function(){ 

		if(($("#cntt_ValorUnitario").val() != '') || ($("#cntt_ValorUnitario").val() != 0)){
			var valorPreco = converteMoedaFloat($("#cntt_ValorUnitario").val());
			var qtde = $("#cntt_QtdeProduto").val();
			var subtotal = valorPreco * qtde;
			$("#cntt_SubTotal").val(subtotal.toFixed(2).replace(".",","));
		}else{
			
		}
	});	

	
	
	
});

function buscaTabelaPreco(material){
	$.ajax({
		url:  '/representacao/material-tabela-preco/get-combo',
		data:  {produto: material},
		type: 'GET',
		success: function(data){
			retorno = JSON.parse(data);			
			if (retorno.erro == 1){
				alert('Ocorreu um erro ao buscar os preços.');
			} else if (retorno.sucesso == 1) {
				
				$("#ped_PrecoOriginal").val(retorno.valor);
				$("#ped_Preco").val(retorno.valor);
				$("#ped_Unidade").val(retorno.unidade);
				$("#ped_CodTabela").html(retorno.options);
				$("#ped_CodTabela").selectpicker('refresh');
				
			} 
	       				
		}
	});
	
}

function mudaPrecoMaterial(material, tab){
	$.ajax({
		url: '/representacao/material-tabela-preco/get-combo-preco',
		data: {produto: material, tabela: tab},
		type: 'GET',
		success: function(data){
			retorno = JSON.parse(data);
			if(retorno.erro == 1){
				alert('Ocorreu um erro ao buscar os preços.');				
			} else if (retorno.sucesso == 1) {
				$("#ped_PrecoOriginal").val(retorno.valor);
				$("#ped_Preco").val(retorno.valor);
			}		
		}
	});
}

function converteMoedaFloat(valor){
    if(valor === ""){
       valor =  0;
    }else{
       valor = valor.replace(".","");
       valor = valor.replace(" ","");
       valor = valor.replace("R$","");
       valor = valor.replace(",",".");
       valor = parseFloat(valor);
    }
    return valor;

 }
function converteFloatDecimal(valor){
	if(valor === ""){
		valor =  0;
	}else{
		valor = valor.replace(".",",");
	}
	return valor;
	
}


