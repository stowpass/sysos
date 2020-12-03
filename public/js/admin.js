// ====================== LOGIN INICIO ==========================
// ==============================================================

function validarLogin(){
	var email = $("#email").val();
	var senha = $("#senha").val();

	var validado = true;

	/*if (email == ""){
		$("#msgErro").show(500);
		$("#tipoErro").html("Favor, informe o <strong>E-mail</strong>!");
		validado = false;
		$("#email").css("border","1px solid red");
	} else {
		$("#email").css("border","1px solid #999");
	}*/

	if (senha == ""){
		$("#msgErro").show(500);
		$("#tipoErro").html("Favor, informe a <strong>Senha</strong>!");
		validado = false;
		$("#senha").css("border","1px solid red");
	} else {
		$("#senha").css("border","1px solid #999");
	}

	var sEmail	= $("#email").val();
	// filtros
	var emailFilter=/^.+@.+\..{2,}$/;
	var illegalChars= /[\(\)\<\>\,\;\:\\\/\"\[\]]/
	// condição
	if(!(emailFilter.test(sEmail))||sEmail.match(illegalChars)){
		$("#msgErro").show(500);
		$("#tipoErro").html("Favor, informe um <strong>E-mail</strong> v&aacute;lido!");
		$("#email").css("border","1px solid red");
		validado = false;
	}else{
		$("#email").css("border","1px solid #999");
	}

	if (email == "" && senha == ""){
		$("#msgErro").show(500);
		$("#tipoErro").html("Favor, informe os campos em destaque!");
		validado = false;
	}

	if (validado){
		login();
	}
}

function limpar(){
	$("#email").css("border","1px solid #999");
	$("#senha").css("border","1px solid #999");
	$("#msgErro").hide(500);
	$("#dadosCliente").hide(500);
	$("#dadosAgendamento").hide(500);
}

function login() {
	$("#caregando").show();
	$("#carregandoTexto").show();
	$.getJSON('login.php?email='+$("#email").val()+'&senha='+$("#senha").val()+'', function(lista) {
		for (index=0; index < lista.length; index++) {
			if(lista[index].loginOk == "S"){
				$("#caregando").hide(500);
				$("#carregandoTexto").hide(500);
				$("#msgErro").hide(500);
				window.location.href='home.php';
			} else {
				$("#caregando").hide();
				$("#carregandoTexto").hide(500);
				$("#msgErro").show(500);
				$("#tipoErro").html("Acesso negado. Verifique os campos informados!");
			}
		}// if FOR
	});
}


// ========================= Alterar senha
function mudarSenha(){

	var validado = true;

	if ($("#minhaSenhaAtual").val() == ""){
		$("#msgErroMeusDados").show(500);
		validado = false;
		$("#minhaSenhaAtual").css("border","1px solid red");
	} else {
		$("#minhaSenhaAtual").css("border","1px solid #999");
	}

	if ($("#minhaNovaSenha").val() == ""){
		$("#msgErroMeusDados").show(500);
		validado = false;
		$("#minhaNovaSenha").css("border","1px solid red");
	} else {
		$("#minhaNovaSenha").css("border","1px solid #999");
	}


	if (validado){
		$('#carregandoTextoMeusDados').show();
		$.getJSON('meusDados.php?area=verificaSenhaAtual&minhaNovaSenha='+$("#minhaNovaSenha").val()+'&minhaSenhaAtual='+$("#minhaSenhaAtual").val(), function(lista) {
			for (index=0; index < lista.length; index++) {
				if(lista[index].verificado == "S"){
					$("#msgSucessoMeusDados").show(500);
					$("#msgErroMeusDados").hide();
					$("#carregandoTextoMeusDados").hide();
					$("#minhaSenhaAtual").css("border","1px solid #999");
					$("#erroSenhaDif").hide(500);
				} else {
					$("#msgSucessoMeusDados").hide(500);
					$("#msgErroMeusDados").show(500);
					$('#carregandoTextoMeusDados').hide();
					$("#minhaSenhaAtual").css("border","1px solid red");
					$("#erroSenhaDif").show(500);
				}
			}// if FOR
		});
	}

}

function fecharMeusDados (){
	$('#textoMeusDados').show();
	$('#carregandoTextoMeusDados').hide();
	$("#msgSucessoMeusDados").hide();
	$("#msgErroMeusDados").hide();
	$("#erroSenhaDif").hide();
	$("#minhaSenhaAtual").css("border","1px solid #999");
	$("#minhaNovaSenha").css("border","1px solid #999");
}

// ====================== LOGIN FINAL ==========================
// =============================================================


// ====================== CANDIDATO ==========================
// ===========================================================

// Redireciona com id do cargo
$('#cargoSelMenuCan').change(function() {
	$('#textoCandidatoP').hide(); $('#carregandoTextoCandidatoP').show();
    window.location = 'candidatoCad.php?idCargo='+$("#cargoSelMenuCan").val();
});

function aprovaCandidato(id){
	$("#carregandoTextoAprovDesaprov").show();
	$.getJSON('aprovarCandidato.php?area=aprovarReprovar&valorAprovacao=S&id='+id, function(lista) {
		for (index=0; index < lista.length; index++) {
			if(lista[index].encontrado == "S"){
				$("#aprovaReprova-"+id).html("<div class='alert alert-success'>Candidato <strong>APROVADO</strong> no Processo Seletivo!</div>");
				$("#aprovaReprova-"+id).show(500);
				$("#candAprovado-"+id).hide();
				$("#candReprovado-"+id).hide();
				$("#candBanco-"+id).hide();
				$("#iconAprovReprov-"+id).html("<i class='text-success fa fa-thumbs-up fa-lg'></i>");
				$("#carregandoTextoAprovDesaprov").hide();
			} else {
				$("#aprovaReprova-"+id).html("");
				$("#aprovaReprova-"+id).hide();
				$("#carregandoTextoAprovDesaprov").hide();
			}
		}// if FOR
	});
}

function ReprovaCandidato(id){
	$("#carregandoTextoAprovDesaprov").show();
	$.getJSON('aprovarCandidato.php?area=aprovarReprovar&valorAprovacao=N&id='+id, function(lista) {
		for (index=0; index < lista.length; index++) {
			if(lista[index].encontrado == "S"){
				$("#aprovaReprova-"+id).html("<div class='alert alert-danger'>Candidato <strong>REPROVADO</strong> no Processo Seletivo!</div>");
				$("#aprovaReprova-"+id).show(500);
				$("#candAprovado-"+id).hide();
				$("#candReprovado-"+id).hide();
				$("#candBanco-"+id).hide();
				$("#iconAprovReprov-"+id).html("<i class='text-danger fa fa-thumbs-down fa-lg'></i>");
				$("#carregandoTextoAprovDesaprov").hide();
			} else {
				$("#aprovaReprova-"+id).html("");
				$("#aprovaReprova-"+id).hide();
				$("#carregandoTextoAprovDesaprov").hide();
			}
		}// if FOR
	});
}


function BandoDeVagas(id){
	$("#carregandoTextoAprovDesaprov").show();
	$.getJSON('aprovarCandidato.php?area=aprovarReprovar&valorAprovacao=B&id='+id, function(lista) {
		for (index=0; index < lista.length; index++) {
			if(lista[index].encontrado == "S"){
				$("#aprovaReprova-"+id).html("<div class='alert alert-warning'>Candidato adicionado ao <strong>BANCO DE VAGAS</strong> !</div>");
				$("#aprovaReprova-"+id).show(500);
				$("#candAprovado-"+id).hide();
				$("#candReprovado-"+id).hide();
				$("#candBanco-"+id).hide();
				$("#iconAprovReprov-"+id).html("<i class='text-warning fa fa-address-book fa-lg'></i>");
				$("#carregandoTextoAprovDesaprov").hide();
			} else {
				$("#aprovaReprova-"+id).html("");
				$("#aprovaReprova-"+id).hide();
				$("#carregandoTextoAprovDesaprov").hide();
			}
		}// if FOR
	});
}

function validaCadCandidato(){

	var validado = true;

	// Aba 1
	if ($("#nome").val() == ""){
		$("#nome").css("border","1px solid red");
		validadoField1 = false;
		$("#btnField1").removeAttr("class");
		$("#btnField1").attr("class", "btn btn-danger");
	} else {
		$("#nome").css("border","1px solid #999");
		$("#btnField1").removeAttr("class");
		$("#btnField1").attr("class", "btn btn-defaut");
	}

	if ($("#telefone").val() == ""){
		$("#telefone").css("border","1px solid red");
		validadoField1 = false;
		$("#btnField1").removeAttr("class");
		$("#btnField1").attr("class", "btn btn-danger");
	} else {
		$("#telefone").css("border","1px solid #999");
		$("#btnField1").removeAttr("class");
		$("#btnField1").attr("class", "btn btn-defaut");
	}

	if ($("#endereco").val() == ""){
		$("#endereco").css("border","1px solid red");
		validadoField1 = false;
		$("#btnField1").removeAttr("class");
		$("#btnField1").attr("class", "btn btn-danger");
	} else {
		$("#endereco").css("border","1px solid #999");
		$("#btnField1").removeAttr("class");
		$("#btnField1").attr("class", "btn btn-defaut");
	}

	// Aba 2
	var sEmail	= $("#email").val();
	var emailFilter=/^.+@.+\..{2,}$/;
	var illegalChars= /[\(\)\<\>\,\;\:\\\/\"\[\]]/

	if(!(emailFilter.test(sEmail))||sEmail.match(illegalChars)){
		$("#btnField2").removeAttr("class");
		$("#btnField2").attr("class", "btn btn-danger");
		$("#email").css("border","1px solid red");
		$("#erroEmailInvalido").show();
		validado = false;
	}else{
		$("#email").css("border","1px solid #999");
		$("#btnField2").removeAttr("class");
		$("#btnField2").attr("class", "btn btn-defaut");
		$("#erroEmailInvalido").hide(500);
	}

	if ($("#senha").val() == ""){
		$("#senha").css("border","1px solid red");
		validadoField1 = false;
		$("#btnField2").removeAttr("class");
		$("#btnField2").attr("class", "btn btn-danger");
	} else {
		$("#senha").css("border","1px solid #999");
		$("#btnField2").removeAttr("class");
		$("#btnField2").attr("class", "btn btn-defaut");
	}

	if ($("#processoSeletivo").val() == ""){
		$("#processoSeletivo").css("border","1px solid red");
		validadoField1 = false;
		$("#btnField2").removeAttr("class");
		$("#btnField2").attr("class", "btn btn-danger");
	} else {
		$("#processoSeletivo").css("border","1px solid #999");
		$("#btnField2").removeAttr("class");
		$("#btnField2").attr("class", "btn btn-defaut");
	}

	if (validado){
		$("#msgErro").hide(500);
		$('html, body').animate({scrollTop:0}, 'slow');
		$("#modalConfirmar").modal();
	} else {
		$("#msgSucesso").hide();
		$("#msgErro").show(500);
		$('html, body').animate({scrollTop:0}, 'slow');
	}
}

function concluirCadCandidato(){
	$('html, body').animate({scrollTop:0}, 'slow');
	$("#carregando").show();
	$("#carregandoTexto").show();

	$.getJSON('candidatoSave.php?idCargo='+$("#cargo").val()+
		'&nome='+$("#nome").val()+
		'&telefone='+$("#telefone").val()+
		'&nascimento='+$("#nascimento").val()+
		'&urlFoto='+$("#urlFoto").val()+
		'&endereco='+$("#endereco").val()+
		//'&idCandidato='+$("#idCandidato").val()+ // Caso esteja EDITANDO

		'&idProcessoSeletivo='+$("#processoSeletivo").val()+ // Aba 2
		'&email='+$("#email").val()+
		'&senha='+$("#senha").val()+

		'&obs='+$("#obs").val(), function(lista) {
		for (index=0; index < lista.length; index++) {
			if(lista[index].cadastrou == "S"){
				$("#carregando").hide(500);
				$("#carregandoTexto").hide(500);
				limparCadCandidato();
				$("#msgSucesso").show(500);
				$("#nome").focus();
			} else {
				$("#carregando").hide(500);
				$("#carregandoTexto").hide(500);
				$("#msgSucesso").hide(500);
				$("#msgErro").show(500);
			}
		}// if FOR
	});
}

function limparCadCandidato(){
	$("#erroEmailInvalido").hide(500);
	$("#carregando").hide(500);
	$("#carregandoTexto").hide(500);
	$("#msgSucesso").hide(500);
	$("#msgErro").hide(500);
	$("#formCadCandidato").trigger("reset");
	$("#nome").focus();
	field1();
	$("#nome").css("border","1px solid #999");
	$("#telefone").css("border","1px solid #999");
	$("#email").css("border","1px solid #999");
	$("#senha").css("border","1px solid #999");
	$("#endereco").css("border","1px solid #999");
}

// ============================================
//======== CAD EXTERNO - CANDIDATO ============
// ============================================
function abreAba1 (){

	$("#field1").show(500);
	$("#field2").hide(500);
	$("#field3").hide(500);

	$("#btnField1").removeAttr("class");
	$("#btnField1").attr("class", "btn btn-primary active");

	$("#btnField2").removeAttr("class");
	$("#btnField2").attr("class", "btn btn-default");

	$("#btnField3").removeAttr("class");
	$("#btnField3").attr("class", "btn btn-default");

}

function abreAba2 (){

	var validadoField1 = true;

	// Aba 1
	if ($("#nome").val() == ""){
		$("#nome").css("border","1px solid red");
		validadoField1 = false;
	} else {
		$("#nome").css("border","1px solid #999");
	}

	if ($("#cpf").val() == ""){
		$("#cpf").css("border","1px solid red");
		validadoField1 = false;
	} else {
		$("#cpf").css("border","1px solid #999");
	}

	if ($("#rg").val() == ""){
		$("#rg").css("border","1px solid red");
		validadoField1 = false;
	} else {
		$("#rg").css("border","1px solid #999");
	}

	if ($("#expRg").val() == ""){
		$("#expRg").css("border","1px solid red");
		validadoField1 = false;
	} else {
		$("#expRg").css("border","1px solid #999");
	}

	if ($("#celular").val() == ""){
		$("#celular").css("border","1px solid red");
		validadoField1 = false;
	} else {
		$("#celular").css("border","1px solid #999");
	}

	if ($("#nascimento").val() == ""){
		$("#nascimento").css("border","1px solid red");
		validadoField1 = false;
	} else {
		$("#nascimento").css("border","1px solid #999");
	}

	if ($("#uf").val() == ""){
		$("#uf").css("border","1px solid red");
		validadoField1 = false;
	} else {
		$("#uf").css("border","1px solid #999");
	}

	if ($("#cidadeSelecionada").val() == null){
		$("#cidadeSelecionada").css("border","1px solid red");
		validadoField1 = false;
	} else {
		$("#cidadeSelecionada").css("border","1px solid #999");
	}

	if ($("#endereco").val() == ""){
		$("#endereco").css("border","1px solid red");
		validadoField1 = false;
	} else {
		$("#endereco").css("border","1px solid #999");
	}

	if ($("#estadoCivil").val() == ""){
		$("#estadoCivil").css("border","1px solid red");
		validadoField1 = false;
	} else {
		$("#estadoCivil").css("border","1px solid #999");
	}

	if ($("#filiacao").val() == ""){
		$("#filiacao").css("border","1px solid red");
		validadoField1 = false;
	} else {
		$("#filiacao").css("border","1px solid #999");
	}

	if ($("#tituloEleitor").val() == ""){
		$("#tituloEleitor").css("border","1px solid red");
		validadoField1 = false;
	} else {
		$("#tituloEleitor").css("border","1px solid #999");
	}

	if ($("#tituloEleitorZona").val() == ""){
		$("#tituloEleitorZona").css("border","1px solid red");
		validadoField1 = false;
	} else {
		$("#tituloEleitorZona").css("border","1px solid #999");
	}

	if ($("#tituloEleitorSecao").val() == ""){
		$("#tituloEleitorSecao").css("border","1px solid red");
		validadoField1 = false;
	} else {
		$("#tituloEleitorSecao").css("border","1px solid #999");
	}

	var sEmail	= $("#email").val();
	var emailFilter=/^.+@.+\..{2,}$/;
	var illegalChars= /[\(\)\<\>\,\;\:\\\/\"\[\]]/

	if(!(emailFilter.test(sEmail))||sEmail.match(illegalChars)){
		$("#email").css("border","1px solid red");
		$("#erroEmailInvalido").show();
		validadoField1 = false;
	}else{
		$("#email").css("border","1px solid #999");
		$("#erroEmailInvalido").hide(500);
	}

	if (validadoField1){
		$("#field1").hide(500);
		$("#field2").show(500);
		$("#field3").hide(500);

		$("#btnField2").removeAttr("class");
		$("#btnField2").attr("class", "btn btn-primary active");

		$("#btnField1").removeAttr("class");
		$("#btnField1").attr("class", "btn btn-default");

		$("#btnField3").removeAttr("class");
		$("#btnField3").attr("class", "btn btn-default");

		$("#msgErro").hide(500);
	} else {
		$("#btnField1").removeAttr("class");
		$("#btnField1").attr("class", "btn btn-danger");
		$('html, body').animate({scrollTop:0}, 'slow');
		$("#msgErro").show(500);
	}



}

function abreAba3 (){

	var validadoField2 = true;

	// Aba 2
	if ($("#cargo").val() == ""){
		$("#cargo").css("border","1px solid red");
		validadoField2 = false;
	} else {
		$("#cargo").css("border","1px solid #999");
	}

	if ($("#formacao").val() == ""){
		$("#formacao").css("border","1px solid red");
		validadoField2 = false;
	} else {
		$("#formacao").css("border","1px solid #999");
	}

	if ($('#cargo').val() == "3"){ // caso seja advogado
		if ($("#numOab").val() == ""){
			$("#numOab").css("border","1px solid red");
			validadoField2 = false;
		} else {
			$("#numOab").css("border","1px solid #999");
		}
		if ($("#areaAtuacao").val() == ""){
			$("#areaAtuacao").css("border","1px solid red");
			validadoField2 = false;
		} else {
			$("#areaAtuacao").css("border","1px solid #999");
		}
	} else {
		$("#numOab").css("border","1px solid #999");
		$("#areaAtuacao").css("border","1px solid #999");
	}

	if ($('#cargo').val() == "5"){ // caso seja Estagio
		if ($("#semestreEstagio").val() == ""){
			$("#semestreEstagio").css("border","1px solid red");
			validadoField2 = false;
		} else {
			$("#semestreEstagio").css("border","1px solid #999");
		}
		if ($("#matriculaEstagio").val() == ""){
			$("#matriculaEstagio").css("border","1px solid red");
			validadoField2 = false;
		} else {
			$("#matriculaEstagio").css("border","1px solid #999");
		}
		if ($("#areaAtuacaoEstagio").val() == ""){
			$("#areaAtuacaoEstagio").css("border","1px solid red");
			validadoField2 = false;
		} else {
			$("#areaAtuacaoEstagio").css("border","1px solid #999");
		}
		if ($("#dipoHorarioEstagio").val() == ""){
			$("#dipoHorarioEstagio").css("border","1px solid red");
			validadoField2 = false;
		} else {
			$("#dipoHorarioEstagio").css("border","1px solid #999");
		}
		if ($("#possuiTransporteEstagio").val() == ""){
			$("#possuiTransporteEstagio").css("border","1px solid red");
			validadoField2 = false;
		} else {
			$("#possuiTransporteEstagio").css("border","1px solid #999");
		}
		if ($("#partAudienciaEstagio").val() == ""){
			$("#partAudienciaEstagio").css("border","1px solid red");
			validadoField2 = false;
		} else {
			$("#partAudienciaEstagio").css("border","1px solid #999");
		}

		$("#areaAtuacao").val($("#areaAtuacaoEstagio").val()); // Seta a Area de Atuacao com o mesmo valor da Area de Atuacao 2 (para nao mexer na insercao via banco)

	} else {
		$("#semestreEstagio").css("border","1px solid #999");
		$("#matriculaEstagio").css("border","1px solid #999");
		$("#areaAtuacaoEstagio").css("border","1px solid #999");
		$("#dipoHorarioEstagio").css("border","1px solid #999");
		$("#possuiTransporteEstagio").css("border","1px solid #999");
		$("#partAudienciaEstagio").css("border","1px solid #999");
	}

	if ($("#agenciaBradesco").val() != ""){
		if ($("#agenciaBradescoDigito").val() == ""){
			$("#agenciaBradescoDigito").css("border","1px solid red");
			validadoField2 = false;
		} else {
			$("#agenciaBradescoDigito").css("border","1px solid #999");
		}
	}

	if ($("#contaBradesco").val() != ""){
		if ($("#contaBradescoDigito").val() == ""){
			$("#contaBradescoDigito").css("border","1px solid red");
			validadoField2 = false;
		} else {
			$("#contaBradescoDigito").css("border","1px solid #999");
		}
	}

	if (validadoField2){
		$("#field1").hide(500);
		$("#field2").hide(500);
		$("#field3").show(500);

		$("#btnField3").removeAttr("class");
		$("#btnField3").attr("class", "btn btn-primary active");

		$("#btnField2").removeAttr("class");
		$("#btnField2").attr("class", "btn btn-default");

		$("#btnField1").removeAttr("class");
		$("#btnField1").attr("class", "btn btn-default");

		$("#msgErro").hide(500);
	} else {
		$("#btnField2").removeAttr("class");
		$("#btnField2").attr("class", "btn btn-danger");
		$('html, body').animate({scrollTop:0}, 'slow');
		$("#msgErro").show(500);
	}

}

function validaCadCandidatoExt(){
	$("#msgErro").hide(500);
	$('html, body').animate({scrollTop:0}, 'slow');
	$("#modalConfirmar").modal();
}

// Confirmar cadastro apos validacoes (botao 'finalizar' do MODAL)
function confirmarCandidatoExt(){
	$("#carregando").show();
	$("#carregandoTexto").show();

	var formulario = document.getElementById('formCadCandidatoExt');
	formulario.submit();
}

// Verifica CPF valido e depois, se e duplicado
$( "#cpf" ).blur(function() {
	if ($("#cpf").val() != ""){

		var val = $("#cpf").val();

	    if (val.match(/^\d{3}\.\d{3}\.\d{3}\-\d{2}$/) != null) {
	        var val1 = val.substring(0, 3);
	        var val2 = val.substring(4, 7);
	        var val3 = val.substring(8, 11);
	        var val4 = val.substring(12, 14);

	        var i;
	        var number;
	        var result = true;

	        number = (val1 + val2 + val3 + val4);

	        s = number;
	        c = s.substr(0, 9);
	        var dv = s.substr(9, 2);
	        var d1 = 0;

	        for (i = 0; i < 9; i++) {
	            d1 += c.charAt(i) * (10 - i);
	        }

	        if (d1 == 0)
	            result = false;

	        d1 = 11 - (d1 % 11);
	        if (d1 > 9) d1 = 0;

	        if (dv.charAt(0) != d1)
	            result = false;

	        d1 *= 2;
	        for (i = 0; i < 9; i++) {
	            d1 += c.charAt(i) * (11 - i);
	        }

	        d1 = 11 - (d1 % 11);
	        if (d1 > 9) d1 = 0;

	        if (dv.charAt(1) != d1)
	            result = false;

	  		if (!result){
	  			$("#cpfDuplicado").html("<i class='fa fa-exclamation-triangle'></i> CPF inv&aacute;lido.");
	  			$("#cpf").css("border","1px solid red");
	  			$("#btnField1").removeAttr("class");
				$("#btnField1").attr("class", "btn btn-danger");
				$("#cadcanExt").removeAttr("disabled");
				$("#cadcanExt").attr("disabled", "disabled");
	  		} else {
	  			//$("#cpfDuplicado").html("");
	  			$.getJSON('admin/duplicidade.php?area=candidatoExt&param='+$("#cpf").val(), function(lista) {
				for (index=0; index < lista.length; index++) {
						if(lista[index].duplicado == "S"){
							$("#cpf").css("border","1px solid red");
							$("#btnField1").removeAttr("class");
							$("#btnField1").attr("class", "btn btn-danger");
							$("#cpfDuplicado").html("<i class='fa fa-exclamation-triangle'></i> CPF j&aacute; cadastrado.");
							$("#cadcanExt").removeAttr("disabled");
							$("#cadcanExt").attr("disabled", "disabled");
						} else {
							$("#cpf").css("border","1px solid #999");
							$("#btnField1").removeAttr("class");
							$("#btnField1").attr("class", "btn btn-primary");
							$("#cadcanExt").removeAttr("disabled");
							$("#cpfDuplicado").html("");
						}
					}
				});
	  		}

	    }

	}

});

// Abre div de advogado, caso o cargo selecionado seja advogado
/*$('#cargo').on('change', function() {
  if ($('#cargo').val() == "3"){
		$('#rowAdv').show(500);
	} else {
		$('#rowAdv').hide(500);
		$("#numOab").empty();
		$("#areaAtuacao").val('');
		$("#numOab").css("border","1px solid #999");
		$("#areaAtuacao").css("border","1px solid #999");
	}
})*/

// Seleciona a lista de cidades de acordo com o ESTADO selecionado
$('#uf').on('change', function() {
  $("#carregandoCidades").show();
	$("#carregandoCidades").attr("class","text-primary");
	$("#carregandoCidades").html("<i class='fa fa-spinner fa-pulse fa-fw'></i>");
	$("#cidadeSelecionada").empty();
	$.getJSON('admin/listarCidadesJson.php?estado='+$("#uf").val(), function(lista) {
		for (index=0; index < lista.length; index++) {
			$("#cidadeSelecionada").removeAttr("disabled");
			$("#cidadeSelecionada").append("<option value='"+lista[index].id+"'>"+lista[index].cidade+"</option>");
			$("#carregandoCidades").hide(500);
		}
	});
})

// ==============================================================================
// ====== DEPRICATED - Coordenadas agora sao capturadas na funcao fillInAddress()
// ==============================================================================

// Recupera as coordenadas do endereco via Google Maps
//$( "#endereco" ).blur(function() {
	/*$("#carregandoTextoMaps").attr("class","text-info");
	$("#carregandoTextoMaps").html("<i class='fa fa-spinner fa-pulse fa-2x fa-fw'></i>");
	$("#carregandoTextoMaps").show();*/
	/*$.getJSON('http://maps.googleapis.com/maps/api/geocode/json?address='+$("#endereco").val()+'&language=pt-BR&sensor=false', function(lista) {
		for (index=0; index < lista.results.length; index++) {
			$("#coord1").val(lista.results[0].geometry.location.lat);
			$("#coord2").val(lista.results[0].geometry.location.lng);
			$("#carregandoTextoMaps").hide();
		}
	});

	if ($("#endereco").val() == ""){
		$("#carregandoTextoMaps").hide();
	}
});*/

function limparCadCandidatoExt(){
	$("#erroEmailInvalido").hide(500);
	$("#carregando").hide(500);
	$("#carregandoTexto").hide(500);
	$("#msgSucesso").hide(500);
	$("#msgErro").hide(500);
	$("#formCadCandidatoExt").trigger("reset");
	$("#nome").focus();
	field1();
	$("#nome").css("border","1px solid #999");
	$("#cpf").css("border","1px solid #999");
	$("#rg").css("border","1px solid #999");
	$("#expRg").css("border","1px solid #999");
	$("#telefone").css("border","1px solid #999");
	$("#celular").css("border","1px solid #999");
	$("#nascimento").css("border","1px solid #999");
	$("#uf").css("border","1px solid #999");
	$("#cidadeSelecionada").css("border","1px solid #999");
	$("#estadoCivil").css("border","1px solid #999");
	$("#filiacao").css("border","1px solid #999");
	$("#endereco").css("border","1px solid #999");
	$("#email").css("border","1px solid #999");
	$("#tituloEleitor").css("border","1px solid #999");
	$("#numOab").css("border","1px solid #999");
	$("#formacao").css("border","1px solid #999");
	$("#areaAtuacao").css("border","1px solid #999");
	$("#agenciaBradescoDigito").css("border","1px solid #999");
	$("#contaBradescoDigito").css("border","1px solid #999");
	$("#tituloEleitorZona").css("border","1px solid #999");
	$("#tituloEleitorSecao").css("border","1px solid #999");

	$("#cpfDuplicado").html('');

	$("#cidadeSelecionada").empty();
	$("#cidadeSelecionada").attr("disabled","disabled");

	$('html, body').animate({scrollTop:0}, 'slow');
}

// Abre campos de acordo com o cargo selecionado
$('#cargo').on('change', function() {

	if ($("#cargo").val() == "3"){ // Advogado
		$("#estagiarioSec").hide(500);
		$("#advogadoSec").show(500);
	}

	if ($("#cargo").val() == "5"){ // Estagiario
		$("#advogadoSec").hide(500);
		$("#estagiarioSec").show(500);
	}

	if ($("#cargo").val() == "1"){ // Outros
		$("#estagiarioSec").hide(500);
		$("#advogadoSec").hide(500);
	}

})

// ====================== FIM CANDIDATO ==========================
// ===============================================================


// ====================== TRABALHE CONOSCO - ADMIN ======================
// ======================================================================
function autorizarAvaliacao(id){
	$("#carregandoTextoAprovDesaprov").show();
	$.getJSON('aprovarCandidato.php?area=moveToCandidate&valor=SA&id='+id, function(lista) {
		for (index=0; index < lista.length; index++) {
			if(lista[index].encontrado == "S"){
				$("#moveToCandidate-"+id).html("<div class='alert alert-success'>Candidato <strong>AUTORIZADO</strong>!</div>");
				$("#moveToCandidate-"+id).show(500);
				$("#candBanco-"+id).hide();
				$("#iconAprovReprov-"+id).html("<i class='text-success fa fa-thumbs-up fa-lg'></i>");
				$("#carregandoTextoAprovDesaprov").hide();
			} else {
				$("#moveToCandidate-"+id).html("");
				$("#moveToCandidate-"+id).hide();
				$("#carregandoTextoAprovDesaprov").hide();
			}
		}// if FOR
	});
}

function negarAvaliacao(id){
	$("#carregandoTextoAprovDesaprov").show();
	$.getJSON('aprovarCandidato.php?area=moveToCandidate&valor=S&id='+id, function(lista) {
		for (index=0; index < lista.length; index++) {
			if(lista[index].encontrado == "S"){
				$("#moveToCandidate-"+id).html("<div class='alert alert-danger'>Candidato <strong>NEGADO</strong></div>");
				$("#moveToCandidate-"+id).show(500);
				$("#candBanco-"+id).hide();
				$("#iconAprovReprov-"+id).html("<i class='text-danger fa fa-thumbs-down fa-lg'></i>");
				$("#carregandoTextoAprovDesaprov").hide();
			} else {
				$("#moveToCandidate-"+id).html("");
				$("#moveToCandidate-"+id).hide();
				$("#carregandoTextoAprovDesaprov").hide();
			}
		}// if FOR
	});
}


// Confirmar cadastro apos validacoes (botao 'finalizar' do MODAL)
function confirmarEnvio(){
	$("#carregando").show();
	$("#carregandoTexto").show();

	var formulario = document.getElementById('formAval');
	formulario.submit();
}


// ==================== CONTRATACAO =========================
// ==========================================================

function field1 (){
	$("#field1").show(500);
	$("#field2").hide(500);
	$("#field3").hide(500);

	$("#btnField1").removeAttr("class");
	$("#btnField1").attr("class", "btn btn-primary active");

	$("#btnField2").removeAttr("class");
	$("#btnField2").attr("class", "btn btn-default");

	$("#btnField3").removeAttr("class");
	$("#btnField3").attr("class", "btn btn-default");
}

function field2 (){
	$("#field1").hide(500);
	$("#field2").show(500);
	$("#field3").hide(500);

	$("#btnField2").removeAttr("class");
	$("#btnField2").attr("class", "btn btn-primary active");

	$("#btnField1").removeAttr("class");
	$("#btnField1").attr("class", "btn btn-default");

	$("#btnField3").removeAttr("class");
	$("#btnField3").attr("class", "btn btn-default");
}

function field3 (){
	$("#field1").hide(500);
	$("#field2").hide(500);
	$("#field3").show(500);

	$("#btnField3").removeAttr("class");
	$("#btnField3").attr("class", "btn btn-primary active");

	$("#btnField2").removeAttr("class");
	$("#btnField2").attr("class", "btn btn-default");

	$("#btnField1").removeAttr("class");
	$("#btnField1").attr("class", "btn btn-default");
}


function selAumentoQuadro (){
	$("#iSubstituicao").hide(500);
	$("#iAumentoQuadro").show(500);
	$("#divColabSubs").hide(500);
	$("#colaboradorSubstituido").val('');

	$("#btnAumentoQuadro").removeAttr("class");
	$("#btnAumentoQuadro").attr("class", "btn btn-info active");

	$("#btnSubstituicao").removeAttr("class");
	$("#btnSubstituicao").attr("class", "btn btn-default");

	$("#motivoContratacao").val("A");
}

function selSubstituicao (){
	$("#iSubstituicao").show(500);
	$("#iAumentoQuadro").hide(500);
	$("#divColabSubs").show(500);

	$("#btnSubstituicao").removeAttr("class");
	$("#btnSubstituicao").attr("class", "btn btn-info active");

	$("#btnAumentoQuadro").removeAttr("class");
	$("#btnAumentoQuadro").attr("class", "btn btn-default");

	$("#motivoContratacao").val("S");
}


function selAdv (){
	$("#iAdv").show(500);
	$("#iEstagio").hide(500);
	$("#iFunc").hide(500);

	$("#btnAdv").removeAttr("class");
	$("#btnAdv").attr("class", "btn btn-info active");

	$("#btnEstagio").removeAttr("class");
	$("#btnEstagio").attr("class", "btn btn-default");

	$("#btnFunc").removeAttr("class");
	$("#btnFunc").attr("class", "btn btn-default");

	$("#divCargo").hide(500);
	$("#cargo").val('');

	$("#modalidadeContratacao").val("A");
}

function selEstagio (){
	$("#iAdv").hide(500);
	$("#iEstagio").show(500);
	$("#iFunc").hide(500);

	$("#btnEstagio").removeAttr("class");
	$("#btnEstagio").attr("class", "btn btn-info active");

	$("#btnAdv").removeAttr("class");
	$("#btnAdv").attr("class", "btn btn-default");

	$("#btnFunc").removeAttr("class");
	$("#btnFunc").attr("class", "btn btn-default");

	$("#divCargo").hide(500);
	$("#cargo").val('');

	$("#modalidadeContratacao").val("E");
}


function selFunc (){
	$("#iAdv").hide(500);
	$("#iEstagio").hide(500);
	$("#iFunc").show(500);

	$("#btnFunc").removeAttr("class");
	$("#btnFunc").attr("class", "btn btn-info active");

	$("#btnAdv").removeAttr("class");
	$("#btnAdv").attr("class", "btn btn-default");

	$("#btnEstagio").removeAttr("class");
	$("#btnEstagio").attr("class", "btn btn-default");

	$("#divCargo").show(500);

	$("#modalidadeContratacao").val("F");
}


function validaSolicitarContratacao(){
	var validadoField1 = true;
	var validadoField2 = true;
	var validado = true;

	if ($("#equipe").val() == ""){
		$("#equipe").css("border","1px solid red");
		validadoField1 = false;
		$("#btnField1").removeAttr("class");
		$("#btnField1").attr("class", "btn btn-danger");
	} else {
		$("#equipe").css("border","1px solid #999");
		$("#btnField1").removeAttr("class");
		$("#btnField1").attr("class", "btn btn-defaut");
	}

	if ($("#pontoFocal").val() == ""){
		$("#pontoFocal").css("border","1px solid red");
		validadoField1 = false;
		$("#btnField1").removeAttr("class");
		$("#btnField1").attr("class", "btn btn-danger");
	} else {
		$("#pontoFocal").css("border","1px solid #999");
		$("#btnField1").removeAttr("class");
		$("#btnField1").attr("class", "btn btn-defaut");
	}

	if ($("#motivoContratacao").val() == ""){
		$("#lblMotivo").attr("class", "text-danger");
		validadoField1 = false;
		$("#btnField1").removeAttr("class");
		$("#btnField1").attr("class", "btn btn-danger");
	} else {
		$("#lblMotivo").removeAttr("class");
		$("#btnField1").removeAttr("class");
		$("#btnField1").attr("class", "btn btn-defaut");
	}

	if ($("#motivoContratacao").val() == "S"){
		if ($("#colaboradorSubstituido").val() == ""){
			$("#colaboradorSubstituido").css("border","1px solid red");
			validadoField1 = false;
			$("#btnField1").removeAttr("class");
			$("#btnField1").attr("class", "btn btn-danger");
		} else {
			$("#colaboradorSubstituido").css("border","1px solid #999");
			$("#btnField1").removeAttr("class");
			$("#btnField1").attr("class", "btn btn-defaut");
		}
	} else {
		$("#colaboradorSubstituido").css("border","1px solid #999");
	}

	if ($("#modalidadeContratacao").val() == ""){
		$("#lblModalidade").attr("class","text-danger");
		validadoField1 = false;
		$("#btnField1").removeAttr("class");
		$("#btnField1").attr("class", "btn btn-danger");
	} else {
		$("#lblModalidade").removeAttr("class");
		$("#btnField1").removeAttr("class");
		$("#btnField1").attr("class", "btn btn-defaut");
	}

	if ($("#modalidadeContratacao").val() == "F"){
		if ($("#cargo").val() == ""){
			$("#cargo").css("border","1px solid red");
			validadoField1 = false;
			$("#btnField1").removeAttr("class");
			$("#btnField1").attr("class", "btn btn-danger");
		} else {
			$("#cargo").css("border","1px solid #999");
			$("#btnField1").removeAttr("class");
			$("#btnField1").attr("class", "btn btn-defaut");
		}
	} else {
		$("#cargo").css("border","1px solid #999");
	}

	// Valida FIELD 1
	if (!validadoField1){
		$("#btnField1").removeAttr("class");
		$("#btnField1").attr("class", "btn btn-danger");
		validado = false;
	} else {
		$("#btnField1").removeAttr("class");
		$("#btnField1").attr("class", "btn btn-defaut");
	}


	if ($("#nomeNovoColaborador").val() == ""){
		$("#nomeNovoColaborador").css("border","1px solid red");
		validadoField2 = false;
		$("#btnField2").removeAttr("class");
		$("#btnField2").attr("class", "btn btn-danger");
	} else {
		$("#nomeNovoColaborador").css("border","1px solid #999");
		$("#btnField2").removeAttr("class");
		$("#btnField2").attr("class", "btn btn-defaut");
	}


	if ($("#emailSugerido").val() == ""){
		$("#emailSugerido").css("border","1px solid red");
		validadoField2 = false;
		$("#btnField2").removeAttr("class");
		$("#btnField2").attr("class", "btn btn-danger");
	} else {
		$("#emailSugerido").css("border","1px solid #999");
		$("#btnField2").removeAttr("class");
		$("#btnField2").attr("class", "btn btn-defaut");
	}


	// Valida FIELD 2
	if (!validadoField2){
		$("#btnField2").removeAttr("class");
		$("#btnField2").attr("class", "btn btn-danger");
		validado = false;
	} else {
		$("#btnField2").removeAttr("class");
		$("#btnField2").attr("class", "btn btn-defaut");
	}

	if (validado){
		$("#msgErro").hide(500);
		$('html, body').animate({scrollTop:0}, 'slow');
		$("#modalConfirmar").modal();
	} else {
		$("#msgSucesso").hide();
		$("#msgErro").show(500);
		$('html, body').animate({scrollTop:0}, 'slow');
	}
}


// Confirmar cadastro apos validacoes (botao 'finalizar' do MODAL)
function concluirCadastroSolicitacao(){

	$('html, body').animate({scrollTop:0}, 'slow');
	$("#carregando").show();
	$("#carregandoTexto").show();

	$.getJSON('contratacaoSave.php?idEquipe='+$("#equipe").val()+
		'&pontoFocal='+$("#pontoFocal").val()+
		'&motivoContratacao='+$("#motivoContratacao").val()+
		'&colaboradorSubstituido='+$("#colaboradorSubstituido").val()+
		'&modalidadeContratacao='+$("#modalidadeContratacao").val()+
		'&idCargo='+$("#cargo").val()+
		'&horarioTrabalho='+$("#horarioTrabalho").val()+
		'&grupoEmails='+$("#grupoEmails").val()+
		'&pastaRede='+$("#pastaRede").val()+

		'&nomeNovoColaborador='+$("#nomeNovoColaborador").val()+ // GDH comeca aqui
		'&emailSugerido='+$("#emailSugerido").val()+
		'&baseAlocacaoSelecionada='+$("#baseAlocacaoSelecionada").val()+
		'&dataInicio='+$("#dataInicio").val()+

		'&obs='+$("#obs").val(), function(lista) {
		for (index=0; index < lista.length; index++) {
			if(lista[index].cadastrou == "S"){
				$("#carregando").hide(500);
				$("#carregandoTexto").hide(500);
				limparContratacao();
				$("#msgSucesso").show(500);
				$("#equipe").focus();
			} else {
				$("#carregando").hide(500);
				$("#carregandoTexto").hide(500);
				$("#msgSucesso").hide(500);
				$("#msgErro").show(500);
			}
		}// if FOR
	});

}


function limparContratacao(){
	$("#msgErro").hide(500);
	$("#msgSucesso").hide(500);
	$("#formSolicitaContratacao").trigger("reset");
	$("#equipe").css("border","1px solid #999");
	$("#pontoFocal").css("border","1px solid #999");
	$("#nomeNovoColaborador").css("border","1px solid #999");
	$("#emailSugerido").css("border","1px solid #999");
	$("#colaboradorSubstituido").css("border","1px solid #999");
	$("#cargo").css("border","1px solid #999");
	$("#lblMotivo").removeAttr("class");
	$("#lblModalidade").removeAttr("class");

	selAumentoQuadro();
	$("#btnAumentoQuadro").removeAttr("class");
	$("#btnAumentoQuadro").attr("class", "btn btn-default");
	$("#iAumentoQuadro").hide();

	selAdv();
	$("#btnAdv").removeAttr("class");
	$("#btnAdv").attr("class", "btn btn-default");
	$("#iAdv").hide();

	$("#motivoContratacao").val('');
	$("#modalidadeContratacao").val('');
	$("#baseAlocacaoSelecionada").val('');

	field1();

	$('html, body').animate({scrollTop:0}, 'slow');
}

// ============================ PROCESSO SELETIVO =========================
// ========================================================================

// Redireciona com id do cargo
$('#cargoSelMenu').change(function() {
	$('#textoProcessoSeletP').hide(); $('#carregandoTextoProcessoSeletP').show();
    window.location = 'processoCad.php?idCargo='+$("#cargoSelMenu").val();
});

function selecionarPergunta(id){
	var qntdAtual = $("#quantEscolhas").val();
	var qntdNova = qntdAtual + "1";

	$("#btnPerg-"+id).removeAttr("class");
	$("#btnPerg-"+id).attr("class", "btn btn-info");
	$("#btnPerg-"+id).attr("title", "Para desmarcar, clique no icone verde na acima");
	$("#btnPerg-"+id).html("<i class='fa fa-check'></i>");
	$("#quantEscolhas").val(qntdNova);

	if (qntdNova == "11111"){
		$(".semClasse").attr("disabled","disabled");
	} else {
		$(".semClasse").removeAttr("disabled");
	}

	if (qntdNova == "1"){
		$("#pergunta1").val(id);
		$("#btnPerg-"+id).attr("disabled","disabled");
	}

	if (qntdNova == "11"){
		$("#pergunta2").val(id);
		$("#btnPerg-"+id).attr("disabled","disabled");
	}

	if (qntdNova == "111"){
		$("#pergunta3").val(id);
		$("#btnPerg-"+id).attr("disabled","disabled");
	}

	if (qntdNova == "1111"){
		$("#pergunta4").val(id);
		$("#btnPerg-"+id).attr("disabled","disabled");
	}

	if (qntdNova == "11111"){
		$("#pergunta5").val(id);
		$("#btnPerg-"+id).attr("disabled","disabled");
		$(".semClasse").attr("title", "Limite de perguntas selecionadas atingido");
	}

}

function desmarcaPergunta(){
	$("#quantEscolhas").val('');

	for (i = 1; i < 100; i++) {
	   $("#btnPerg-"+i).removeAttr("class");
		$("#btnPerg-"+i).attr("class", "btn btn-default semClasse");
		$("#btnPerg-"+i).attr("title", "Clique para selecionar esta pergunta");
		$("#btnPerg-"+i).html("<i class='fa fa-minus'></i>");
		$("#btnPerg-"+i).removeAttr("disabled");
	}

	$("#pergunta1").val('');
	$("#pergunta2").val('');
	$("#pergunta3").val('');
	$("#pergunta4").val('');
	$("#pergunta5").val('');
}

function limparFormProcessoSeletivo(){
	$("#msgErro").hide(500);
	$("#msgSucesso").hide(500);
	$('#formProcessoSeletivo').trigger("reset");
	$("#nomeProcesso").css("border","1px solid #999");
	$("#cargo").css("border","1px solid #999");
	$("#dataInicio").css("border","1px solid #999");
	field1();
}

function validaProcessoSeletivo(){
	var validadoField1 = true;
	var validadoField2 = true;
	var validado = true;

	if ($("#nomeProcesso").val() == ""){
		$("#nomeProcesso").css("border","1px solid red");
		validadoField1 = false;
		$("#btnField1").removeAttr("class");
		$("#btnField1").attr("class", "btn btn-danger");
	} else {
		$("#nomeProcesso").css("border","1px solid #999");
		$("#btnField1").removeAttr("class");
		$("#btnField1").attr("class", "btn btn-defaut");
	}

	if ($("#cargo").val() == ""){
		$("#cargo").css("border","1px solid red");
		validadoField1 = false;
		$("#btnField1").removeAttr("class");
		$("#btnField1").attr("class", "btn btn-danger");
	} else {
		$("#cargo").css("border","1px solid #999");
		$("#btnField1").removeAttr("class");
		$("#btnField1").attr("class", "btn btn-defaut");
	}

	if ($("#dataInicio").val() == ""){
		$("#dataInicio").css("border","1px solid red");
		validadoField1 = false;
	} else {
		$("#dataInicio").css("border","1px solid #999");
	}

	// Valida FIELD 1
	if (!validadoField1){
		$("#btnField1").removeAttr("class");
		$("#btnField1").attr("class", "btn btn-danger");
		validado = false;
	} else {
		$("#btnField1").removeAttr("class");
		$("#btnField1").attr("class", "btn btn-defaut");
	}

	// Valida se selecionou as 5 perguntas
	for (i = 1; i < 6; i++) {
		if ($("#pergunta"+i).val() == ""){
			validadoField2 = false;
		}
	}

	// Valida FIELD 2
	if (!validadoField2){
		$("#btnField2").removeAttr("class");
		$("#btnField2").attr("class", "btn btn-danger");
		validado = false;
	} else {
		$("#btnField2").removeAttr("class");
		$("#btnField2").attr("class", "btn btn-defaut");
	}

	if (validado){
		$("#msgErro").hide(500);
		$('html, body').animate({scrollTop:0}, 'slow');
		$("#modalConfirmar").modal();
	} else {
		$("#msgSucesso").hide();
		$("#msgErro").show(500);
		$('html, body').animate({scrollTop:0}, 'slow');
	}
}


// Confirmar cadastro apos validacoes (botao 'finalizar' do MODAL)
function concluirCadastroProcessoSeletivo(){

	$('html, body').animate({scrollTop:0}, 'slow');
	$("#carregando").show();
	$("#carregandoTexto").show();

	$.getJSON('processoSave.php?nomeProcesso='+$("#nomeProcesso").val()+
		'&idCargo='+$("#cargo").val()+
		'&dataInicio='+$("#dataInicio").val()+
		'&obs='+$("#obs").val()+
		'&idProcesso='+$("#idProcesso").val()+ // Caso seja Edicao

		'&pergunta1='+$("#pergunta1").val()+ // Comecam aqui as perguntas
		'&pergunta2='+$("#pergunta2").val()+
		'&pergunta3='+$("#pergunta3").val()+
		'&pergunta4='+$("#pergunta4").val()+
		'&pergunta5='+$("#pergunta5").val(), function(lista) {
		for (index=0; index < lista.length; index++) {
			if(lista[index].cadastrou == "S"){
				$("#carregando").hide(500);
				$("#carregandoTexto").hide(500);
				limparFormProcessoSeletivo();
				$("#msgSucesso").show(500);
			} else {
				$("#carregando").hide(500);
				$("#carregandoTexto").hide(500);
				$("#msgSucesso").hide(500);
				$("#msgErro").show(500);
			}
		}// if FOR
	});

}

// ========================= Excluir registro (Funcao chamada em todas as requisicoes de exclusoes do sistema)
function excluir(id, area){
	$("#carregandoTexto").show();
	$.getJSON('excluir.php?area='+area+'&id='+id, function(lista) {
		for (index=0; index < lista.length; index++) {
			if(lista[index].excluido == "S"){
				$("#tr-"+id).hide(1000);
				$("#msgSucesso").show(500);
				$("#carregandoTexto").hide();
			} else {
				$("#msgSucesso").hide(500);
				$("#msgErro").show(500);
			}
		}// if FOR
	});
}

// So permite digitar numeros
function somenteNumeros(num) {
    var er = /[^0-9.]/;
    er.lastIndex = 0;
    var campo = num;
    if (er.test(campo.value)) {
      campo.value = "";
    }
}

// =====================================================================
// Autocomplete do endereco - via Google Places API

var placeSearch, autocomplete;
var componentForm = {
street_number: 'short_name',
route: 'long_name',
locality: 'long_name',
administrative_area_level_1: 'short_name',
country: 'long_name',
postal_code: 'short_name'
};

function initAutocomplete() {
// Create the autocomplete object, restricting the search to geographical
// location types.
autocomplete = new google.maps.places.Autocomplete(
    /** @type {!HTMLInputElement} */(document.getElementById('endereco')),
    {types: ['geocode']});

// When the user selects an address from the dropdown, populate the address
// fields in the form.
autocomplete.addListener('place_changed', fillInAddress);
}

// [START region_fillform]
function fillInAddress() {
// Get the place details from the autocomplete object.
var place = autocomplete.getPlace();

// Setting Lat and Lng
var latitude = place.geometry.location.lat();
var longitude = place.geometry.location.lng();
document.getElementById("coord1").value = latitude;
document.getElementById("coord2").value = longitude;

for (var component in componentForm) {
  document.getElementById(component).value = '';
  document.getElementById(component).disabled = false;
}

// Get each component of the address from the place details
// and fill the corresponding field on the form.
for (var i = 0; i < place.address_components.length; i++) {
  var addressType = place.address_components[i].types[0];
  if (componentForm[addressType]) {
    var val = place.address_components[i][componentForm[addressType]];
    document.getElementById(addressType).value = val;
  }
}
}
// [END region_fillform]

// [START region_geolocation]
// Bias the autocomplete object to the user's geographical location,
// as supplied by the browser's 'navigator.geolocation' object.
function geolocate() {
if (navigator.geolocation) {
  navigator.geolocation.getCurrentPosition(function(position) {
    var geolocation = {
      lat: position.coords.latitude,
      lng: position.coords.longitude
    };
    var circle = new google.maps.Circle({
      center: geolocation,
      radius: position.coords.accuracy
    });
    autocomplete.setBounds(circle.getBounds());
  });
}
}
// ======================== FIM ===========================
// ========================================================
