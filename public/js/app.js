// ====================== LOGIN INICIO ==========================
// ==============================================================

function validarLogin(){
	//var email = $("#email").val();
	//var senha = $("#senha").val();

	var cpf = $("#cpf").val();

	var validado = true;

	/*if (email == ""){
		$("#msgErro").show(500);
		$("#tipoErro").html("Favor, informe o <strong>E-mail</strong>!");
		validado = false;
		$("#email").css("border","1px solid red");
	} else {
		$("#email").css("border","1px solid #999");
	}*/

	/*if (senha == ""){
		$("#msgErro").show(500);
		$("#tipoErro").html("Favor, informe a <strong>Senha</strong>!");
		validado = false;
		$("#senha").css("border","1px solid red");
	} else {
		$("#senha").css("border","1px solid #999");
	}*/

	/*var sEmail	= $("#email").val();
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
	}*/

	if (cpf == ""){
		$("#msgErro").show(500);
		$("#tipoErro").html("Favor, informe o <strong>CPF</strong>!");
		validado = false;
		$("#cpf").css("border","1px solid red");
	} else {
		$("#cpf").css("border","1px solid #999");
	}

	if (validado){
		//login();
		loginPadrao();
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

function loginPadrao() { // Login sem JSON
	$("#caregando").show();
	$("#carregandoTexto").show();

	var formulario = document.getElementById('formLogin');
	formulario.submit();
}
// ====================== LOGIN FINAL ==========================
// =======================================================



// Validação Respostas
function validaRespostas(){

	var validado = true;

	if ($("#resposta1").val() == ""){
		validado = false;
		$("#resposta1").css("border","1px solid red");
	} else {
		$("#resposta1").css("border","1px solid #999");
	}

	if ($("#resposta2").val() == ""){
		validado = false;
		$("#resposta2").css("border","1px solid red");
	} else {
		$("#resposta2").css("border","1px solid #999");
	}

	if ($("#resposta3").val() == ""){
		validado = false;
		$("#resposta3").css("border","1px solid red");
	} else {
		$("#resposta3").css("border","1px solid #999");
	}

	if ($("#resposta4").val() == ""){
		validado = false;
		$("#resposta4").css("border","1px solid red");
	} else {
		$("#resposta4").css("border","1px solid #999");
	}

	if ($("#resposta5").val() == ""){
		validado = false;
		$("#resposta5").css("border","1px solid red");
	} else {
		$("#resposta5").css("border","1px solid #999");
	}


	if (validado){
		$("#modalConfirmar").modal(); // Chama modal de confirmacao
		$('html, body').animate({scrollTop:0}, 'slow');
	} else {
		$("#msgErro").show(500);
		$('html, body').animate({scrollTop:0}, 'slow');
	}
}


// Validação Respostas - ADVOGADO
function validaRespostasAdvCivil(){

	var validado = true;

	for (i = 1; i < 7; i++) {
		if ($("#respostaCivil"+i).val() == ""){
			validado = false;
			$("#respostaCivil"+i+"Txt").html("<span class='text-danger'>Resposta ausente</span>");
		}
	}


	for (i = 7; i < 10; i++) {
		if ($("#respostaCivil"+i).val() == ""){
			validado = false;
			$("#respostaCivil"+i).css("border","1px solid red");
		} else {
			$("#respostaCivil"+i).css("border","1px solid #999");
		}
	}


	if (validado){
		$("#msgErro").hide(500);
		$("#modalConfirmar").modal(); // Chama modal de confirmacao
		$('html, body').animate({scrollTop:0}, 'slow');
	} else {
		$("#msgErro").show(500);
		$('html, body').animate({scrollTop:0}, 'slow');
	}
}


// Validação Respostas - ESTAGIARIO
function validaRespostasEstagioAdvCivil(){

	var validado = true;

	for (i = 1; i < 11; i++) {
		if ($("#respostaCivil"+i).val() == ""){
			validado = false;
			$("#respostaCivil"+i+"Txt").html("<span class='text-danger'>Resposta ausente</span>");
		}
	}


	for (i = 10; i < 11; i++) {
		if ($("#respostaCivil"+i).val() == ""){
			validado = false;
			$("#respostaCivil"+i).css("border","1px solid red");
		} else {
			$("#respostaCivil"+i).css("border","1px solid #999");
		}
	}


	if (validado){
		$("#msgErro").hide(500);
		$("#modalConfirmar").modal(); // Chama modal de confirmacao
		$('html, body').animate({scrollTop:0}, 'slow');
	} else {
		$("#msgErro").show(500);
		$('html, body').animate({scrollTop:0}, 'slow');
	}
}


// Validação Respostas - ADVOGADO
function validaRespostasAdvTrabalhista(){

    var validado = true;

    var o = [1, 2, 10, 11, 12, 13, 15, 16]

    var t = [3, 4, 5, 6, 7, 8, 9, 14, 17]

    o.forEach(function (i) {
        if ($("#respostaTrabalhista"+i).val() == ""){
			validado = false;
			$("#respostaTrabalhista"+i+"Txt").html("<span class='text-danger'>Resposta ausente</span>");
		}
    })

    t.forEach(function (i) {
        if ($("#respostaTrabalhista"+i).val() == ""){
			validado = false;
			$("#respostaTrabalhista"+i).css("border","1px solid red");
		} else {
			$("#respostaTrabalhista"+i).css("border","1px solid #999");
		}
    })

	if (validado){
		$("#msgErro").hide(500);
		$("#modalConfirmar").modal(); // Chama modal de confirmacao
		$('html, body').animate({scrollTop:0}, 'slow');
	} else {
		$("#msgErro").show(500);
		$('html, body').animate({scrollTop:0}, 'slow');
	}
}

// Validação Respostas - ESTAGIARIO
function validaRespostasEstagioAdvTrabalhista(){

	var validado = true;

	/*for (i = 1; i < 3; i++) {
		if ($("#respostaTrabalhista"+i).val() == ""){
			validado = false;
			$("#respostaTrabalhista"+i+"Txt").html("<span class='text-danger'>Resposta ausente</span>");
		}
	}*/


	for (i = 1; i < 4; i++) {
		if ($("#respostaTrabalhista"+i).val() == ""){
			validado = false;
			$("#respostaTrabalhista"+i).css("border","1px solid red");
		} else {
			$("#respostaTrabalhista"+i).css("border","1px solid #999");
		}
	}


	if (validado){
		$("#msgErro").hide(500);
		$("#modalConfirmar").modal(); // Chama modal de confirmacao
		$('html, body').animate({scrollTop:0}, 'slow');
	} else {
		$("#msgErro").show(500);
		$('html, body').animate({scrollTop:0}, 'slow');
	}
}


// Validação Respostas - ADVOGADO
function validaRespostasAdvAmbas(){

	var validado = true;

	for (i = 1; i < 7; i++) {
		if ($("#respostaCivil"+i).val() == ""){
			validado = false;
			$("#respostaCivil"+i+"Txt").html("<span class='text-danger'>Resposta ausente</span>");
		}
	}

	for (i = 7; i < 10; i++) {
		if ($("#respostaCivil"+i).val() == ""){
			validado = false;
			$("#respostaCivil"+i).css("border","1px solid red");
		} else {
			$("#respostaCivil"+i).css("border","1px solid #999");
		}
    }

    var o = [1, 2, 10, 11, 12, 13, 15, 16]

    var t = [3, 4, 5, 6, 7, 8, 9, 14, 17]

    o.forEach(function (i) {
        if ($("#respostaTrabalhista"+i).val() == ""){
			validado = false;
			$("#respostaTrabalhista"+i+"Txt").html("<span class='text-danger'>Resposta ausente</span>");
		}
    })

    t.forEach(function (i) {
        if ($("#respostaTrabalhista"+i).val() == ""){
			validado = false;
			$("#respostaTrabalhista"+i).css("border","1px solid red");
		} else {
			$("#respostaTrabalhista"+i).css("border","1px solid #999");
		}
    })

	if (validado){
		$("#msgErro").hide(500);
		$("#modalConfirmar").modal(); // Chama modal de confirmacao
		$('html, body').animate({scrollTop:0}, 'slow');
	} else {
		$("#msgErro").show(500);
		$('html, body').animate({scrollTop:0}, 'slow');
	}
}


// Validação Respostas - ESTAGIARIO
function validaRespostasEstadioAdvAmbas(){

	var validado = true;

	for (i = 1; i < 10; i++) {
		if ($("#respostaCivil"+i).val() == ""){
			validado = false;
			$("#respostaCivil"+i+"Txt").html("<span class='text-danger'>Resposta ausente</span>");
		}
	}


	for (i = 10; i < 11; i++) {
		if ($("#respostaCivil"+i).val() == ""){
			validado = false;
			$("#respostaCivil"+i).css("border","1px solid red");
		} else {
			$("#respostaCivil"+i).css("border","1px solid #999");
		}
	}

	/*for (i = 1; i < 3; i++) {
		if ($("#respostaTrabalhista"+i).val() == ""){
			validado = false;
			$("#respostaTrabalhista"+i+"Txt").html("<span class='text-danger'>Resposta ausente</span>");
		}
	}*/


	for (i = 1; i < 4; i++) {
		if ($("#respostaTrabalhista"+i).val() == ""){
			validado = false;
			$("#respostaTrabalhista"+i).css("border","1px solid red");
		} else {
			$("#respostaTrabalhista"+i).css("border","1px solid #999");
		}
	}

	if (validado){
		$("#msgErro").hide(500);
		$("#modalConfirmar").modal(); // Chama modal de confirmacao
		$('html, body').animate({scrollTop:0}, 'slow');
	} else {
		$("#msgErro").show(500);
		$('html, body').animate({scrollTop:0}, 'slow');
	}
}


// Confirmar cadastro apos validacoes (botao 'finalizar' do MODAL)
function confirmarEnvio(){
	$("#carregando").show();
	$("#carregandoTexto").show();

	var formulario = document.getElementById('formAval');
	formulario.submit();
}


function hideRespostas(){
	$("#minhasRespostas").hide(500);
	$("#hideResp").html("<i class='fa fa-caret-square-down'></i>");
	$("#hideResp").removeAttr("onclick");
	$("#hideResp").attr("onclick","showRespostas()");
}

function showRespostas(){
	$("#minhasRespostas").show(500);
	$("#hideResp").html("<i class='fa fa-caret-square-up'></i>");
	$("#hideResp").removeAttr("onclick");
	$("#hideResp").attr("onclick","hideRespostas()");
}
