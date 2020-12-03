// ==================================================================
// ====================== TRABALHE CONOSCO ==========================
// ==================================================================

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

// ============================================
//======== CAD EXTERNO - CANDIDATO ============
// ============================================

function validaFormTrabConosco (){

	var validadoField2 = true;

	var validadoField1 = true;

	var validadoField3 = true;

	// Aba 1
	if ($("#nome").val() == ""){
		$("#nome").css("border","1px solid red");
		validadoField1 = false;
	} else {
		$("#nome").css("border","1px solid #999");
	}

	if ($("#celular").val() == ""){
		$("#celular").css("border","1px solid red");
		validadoField1 = false;
	} else {
		$("#celular").css("border","1px solid #999");
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
		if ($("#dipoHorarioEstagio").val() == ""){
			$("#dipoHorarioEstagio").css("border","1px solid red");
			validadoField2 = false;
		} else {
			$("#dipoHorarioEstagio").css("border","1px solid #999");
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

	// Aba 3 (arquivo Upload)
	if ($("#cv").val() == ""){
		$("#cv").css("border","1px solid red");
		validadoField3 = false;
	} else {
		$("#cv").css("border","1px solid #999");
	}


	// Se passou tudo, chama a funcao de cadastro
	if (validadoField2 && validadoField1 && validadoField3){
		$("#msgErro").hide(500);
		validaCadCandidatoExt();
	} else {
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
	  			$.getJSON('../admin/duplicidade.php?area=candidatoExt&param='+$("#cpf").val(), function(lista) {
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
	$.getJSON('../admin/listarCidadesJson.php?estado='+$("#uf").val(), function(lista) {
		for (index=0; index < lista.length; index++) {
			$("#cidadeSelecionada").removeAttr("disabled");
			$("#cidadeSelecionada").append("<option value='"+lista[index].id+"'>"+lista[index].cidade+"</option>");
			$("#carregandoCidades").hide(500);
		}
	});
})

// Recupera as coordenadas do endereco via Google Maps
$( "#endereco" ).blur(function() {
	/*$("#carregandoTextoMaps").attr("class","text-info");
	$("#carregandoTextoMaps").html("<i class='fa fa-spinner fa-pulse fa-2x fa-fw'></i>");
	$("#carregandoTextoMaps").show();*/
	$.getJSON('http://maps.googleapis.com/maps/api/geocode/json?address='+$("#endereco").val()+'&language=pt-BR&sensor=false', function(lista) {
		for (index=0; index < lista.results.length; index++) {
			$("#coord1").val(lista.results[0].geometry.location.lat);
			$("#coord2").val(lista.results[0].geometry.location.lng);
			$("#carregandoTextoMaps").hide();
		}
	});

	if ($("#endereco").val() == ""){
		$("#carregandoTextoMaps").hide();
	}
});

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

	if ($("#cargo").val() == "4"){ // Outros
		$("#estagiarioSec").hide(500);
		$("#advogadoSec").hide(500);
	}

})

// ====================== FIM CANDIDATO ==========================
// ===============================================================



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
