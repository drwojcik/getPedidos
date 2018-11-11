/**
* TableGrid Plugin - JavaScript
* 
* @author Jonathan Fernando <jonathan@softwar.com.br>
* @since 2014-03-17
* @version 1.0 => 2014-03-17
* @version 1.1 => 2014-04-01
* @version 1.2 => 2014-04-01
* @version 1.3 => 2014-04-02
* @version 1.4 => 2014-05-30
*/
(function($){
	//TableGrid plugin
	$.fn.TableGrid = function(options) {
		//-----------------------------------------------------------
		//Configura��o padr�o
		//-----------------------------------------------------------
		var settings = $.extend({
			'theme':		'lighYellow',			
			'params':	[
				{
					'class':	'',
					'name':		'',
				},
			],
			'ajax': {
				'url':		'',
				'data':		'',
				'type':		'',
			},
		}, options);
		//-----------------------------------------------------------		
		
		$(this).each(function(){
			//ID inicial da tabela
			var tIniID = $(this).attr('id');
			var idTable = rID;
			if (tIniID == undefined)
				tIniID = '';

			//PASSO 1
			//Seta o random ID da tabela
			//verifica se a tabela j� n�o possui um random ID gerado anteriormente pelo plugin
			if (tIniID.indexOf('TG_') == -1){
				var rID = 'TG_' + randomString(8);
				var idTable = rID;
				$(this).attr('id',rID);
			}
			
			//Resgata a classs da tabela
			var tClass = $(this).attr('class');
			//Seta a classe default
			var defaultCssClass = 'TG_table TG_child';
			//Seta o theme
			var theme = 'TGTheme_'+settings.theme;
			$(this).attr('class',tClass+' '+defaultCssClass+' '+theme);
			//-----------------------------------------------------------

			//PASSO 2
			//Adiciona uma coluna � esquerda da tabela
			//Respons�vel por abrir/fechar o detail
			$(document).find('#'+idTable + ' > thead > tr').each(function(){			
				$(this).find('th:first').before('<th class="TG_detail">&nbsp;</th>');
			});
			$(document).find('#'+idTable + ' > tbody > tr').each(function(){
				$(this).find('td:first').before('<td class="detailClose"> <a href="#" class="getDetail_'+ idTable +' btn btn-sm"><i class="fa fa-plus-square"></i></a> </td>');
			});
			//-----------------------------------------------------------
			
			//PASSO 3		
			//conta o n�mero de colunas da tabela
			var numColumns = 0;
			numColumns = $(this).find('tbody > tr:first > td').length;
			//-----------------------------------------------------------
			
						
			//ao clicar no link para obter ou ocultar os detalhes
			$('.getDetail_'+ idTable).click(function(){
				
				//linha pai
				var objLinhaPai = $(this).parent().parent();
				
				//LOOP para definir os par�metros enviados via Ajax
				var ajaxParams = {};
				for(p=0; p < settings.params.length; p++){
					//busca o valor do parametro ID
					var selector = '.'+settings.params[p].class;			
					var paramIdValue = ( objLinhaPai.find(selector).val() == '' ? objLinhaPai.find(selector).html() : objLinhaPai.find(selector).val() );
				
					//seta o parametro para ser enviado via ajax
					ajaxParams[settings.params[p].name] = paramIdValue;
				}
				
				//loading gif
				var objLoading = $('<tr><td colspan="' + numColumns + '" style="border: medium none; padding: 5px 0px; text-align: center;"> <div class="imgLoad">&nbsp;</div> </td></tr>');
				
				if ($(this).parent().attr('class') == 'detailClose'){
					//altera a classe do elemento pai
					$(this).parent().attr('class','detailOpen');
					
					//busca os dados via ajax
					$.ajax({
						url: 	settings.ajax.url,
						data: 	ajaxParams,
						type:	settings.ajax.type,
						beforeSend: function(){
							objLinhaPai.after(objLoading);
						},
						success: function(retorno) {
							// remove a imagem de carregamento
							objLoading.remove();						
							
							// adiciona uma nova linha
							// e dentro dessa linha adiciona a tabela
							var trID 	= randomString(8);
							var trClass	= 'TG_detail';
							objLinhaPai.after('<tr id="'+trID+'" class="'+trClass+'"><td colspan="' + numColumns + '" class="">' + retorno + '</td></tr>');						
						}
					});
				}
				else if ($(this).parent().attr('class') == 'detailOpen') {
					//altera a classe do elemento pai
					$(this).parent().attr('class','detailClose');
					
					// remove o detail
					objLinhaPai.next().remove();
				}

				
				return false;
			});
			//-----------------------------------------------------------
		});		
		
		
		return this;
	};			
})(jQuery);

function randomString(length) {
	var chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    var result = '';
    for (var i = length; i > 0; --i) result += chars[Math.round(Math.random() * (chars.length - 1))];
    return result;
}