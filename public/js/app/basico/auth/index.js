$(document).ready(function(){
	$('input[name=Login]').focus();

	$('#chk-mostrar-senha').on('ifChecked', function(event){
		$('input[name=Senha]').attr('type','text');
		return false;
	});
	$('#chk-mostrar-senha').on('ifUnchecked', function(event){
		$('input[name=Senha]').attr('type','password');
		return false;
	});

	$("form#Auth").submit(function(){
		$('#chk-mostrar-senha').iCheck('uncheck');
		$('input[name=Senha]').attr('type','password');
	});
});