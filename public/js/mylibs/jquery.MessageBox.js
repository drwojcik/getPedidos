(function($){
	//MessageBox plugin
	$.MessageBox = function(options) {
		
		config = $.extend({
			type:		'default',
			id:			'',
			icon:		'',
			title:		'',
			message:	'',
			buttons: [						                  
	                  {
	                	  type: 'default',
	                	  label: 'Fechar',
	                	  cssClass: 'pull-right',
	                	  closeOnClick: true,
	                	  action: function(element){
	                		  $(this).parents(".message-box").removeClass("open");
	                	  }
	                  },
            ]
		}, options);
				
		// alteração que deixa o id unico
		var date_now = new Date();
		if (config.id == undefined || config.id == '')
			config.id = 'mb';
		config.id = config.id + '_' + date_now.getTime();
		
		// verifica se o icone fo informado, caso não tenha sido seta o ícone padrão de acordo com o tipo da mensagem
		if (config.icon == undefined || config.icon == ''){
			// ícone padrão
			//config.icon = 'info'; Cometnado por Diego Wojcik Tirado o Icone
			config.icon = '';
			
			if (config.type == 'warning')
				config.icon = 'warning';
			else if (config.type == 'success')
				config.icon = 'success';
			else if (config.type == 'danger')
				config.icon = 'times';
		}
		
		
		// renderiza os botões
		strHtmlBtn = '';
		$.each(config.buttons, function(index, button) {
            var btnId = config.id+'Btn'+index;
            var btnClass = 'btn btn-lg btn-'+button.type+' '+button.cssClass;
            
            // concatena o html dos botões
            strHtmlBtn = strHtmlBtn + '<button id="'+btnId+'" class="'+btnClass+'">'+button.label+'</button>';
        });

		// html do messagem box
		var str = '';		
		str = str + '<div id="'+config.id+'" class="message-box message-box-'+config.type+' animated fadeIn">';			
		str = str + '<div class="mb-container">';
		str = str + '	<div class="mb-middle">';
		str = str + '		<div class="mb-title"><span class="fa fa-'+config.icon+'"></span> '+config.title+'</div>';
		str = str + '		<div class="mb-content">';
		str = str + '			<p>'+config.message+'</p> ';                 
		str = str + '		</div>';
		str = str + '		<div class="mb-footer">';
		str = str + '		'+strHtmlBtn;
		str = str + '		</div>';
		str = str + '	</div>';
		str = str + '</div>';
		
		// envia para o body
		$("body").append(str);		

		// cria as actions dos botões
		for (i=0; i < config.buttons.length; i++){
			var btnId = config.id+'Btn'+i;
			
			if (config.buttons[i].action != undefined && config.buttons[i].action != ''){				
				$('#'+btnId).click(config.buttons[i].action);
				
				if (config.buttons[i].closeOnClick){
					$('#'+btnId).click(function(){
						$(this).parents(".message-box").removeClass("open");
						return false;
					});
				}
			}
			else {
				if (config.buttons[i].closeOnClick){
					$('#'+btnId).click(function(){
						$(this).parents(".message-box").removeClass("open");
						return false;
					});
				}
			}
		}
		
		// abre o Message Box
    	var box = $('#'+config.id);    	
    	box.toggleClass("open");
	};
})(jQuery);