// ==================================================
//======== CAD EXTERNO - CORRESPONDENTES ============
// ==================================================

function validaFormGecorpWeb (){

	var validadoField1 = true;

	if ($("#nome").val() == ""){
		$("#nome").css("border","1px solid red");
		validadoField1 = false;
	} else {
		$("#nome").css("border","1px solid #999");
	}

	if ($("#cpf").val() == ""){
		$("#cpf").css("border","1px solid red");
		validadoField1 = false;
	} // sem ELSE aqui

	if ($("#numOab").val() == ""){
		$("#numOab").css("border","1px solid red");
		validadoField1 = false;
	} else {
		$("#numOab").css("border","1px solid #999");
	}

	if ($("#endereco").val() == ""){
		$("#endereco").css("border","1px solid red");
		validadoField1 = false;
	} else {
		$("#endereco").css("border","1px solid #999");
	}

	if ($("#cep").val() == ""){
		$("#cep").css("border","1px solid red");
		validadoField1 = false;
	} else {
		$("#cep").css("border","1px solid #999");
	}

	if ($("#celular").val() == ""){
		$("#celular").css("border","1px solid red");
		validadoField1 = false;
	} else {
		$("#celular").css("border","1px solid #999");
	}

	// E-mail valido
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

	// Se CPF valido
	var val = $("#cpf").val();

	if (val.length < 14){
		$("#cpf").css("border","1px solid red");
		validadoField1 = false;
	}

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
  			$("#cpfInvalido").show(500);
  			$("#cpf").css("border","1px solid red");
  			$("#btnField1").removeAttr("class");
			$("#btnField1").attr("class", "btn btn-danger");
			$("#cadcanExt").removeAttr("disabled");
			$("#cadcanExt").attr("disabled", "disabled");
			validadoField1 = false;
  		} else {
  			$("#cpfInvalido").hide(500);
  			$("#cpf").css("border","1px solid #999");
  		}

    }

	// Verifica se o array de comarcas selecionadas nao esta vazio
	if (comarcas.length < 1){
		$("#uf").css("border","1px solid red");
		$("#municipioSelecionado").css("border","1px solid red");
		validadoField1 = false;
	} else {
		$("#uf").css("border","1px solid #999");
		$("#municipioSelecionado").css("border","1px solid #999");
	}

	// Se passou pela primeira parte, chama os valores por comarca
	if (validadoField1){
		$("#msgErro").hide(500);
		$("#fieldSet1").hide();
		$("#fieldSet2").hide();

		$("#carregandoValoresComarcas").show();

		$('#listaValoresComarcas').empty();

		for (i = 0; i < comarcas.length; i++) {

			$.getJSON('findComarcaById.php?id='+comarcas[i], function(lista) {
				for (index=0; index < lista.length; index++) {
					$("#listaValoresComarcas").append("<div class='row' id='linhaComarca-"+comarcas[i]+"'>"+
				            "<div class='form-group col-md-2'>"+
								"<strong>"+lista[index].municipio+" / "+lista[index].uf+" "+
				            "</div>"+
				            "<div class='form-group col-md-1'>"+
								"<input type='text' id='audienciaCivil-"+lista[index].id+"' name='audienciaCivil-"+lista[index].id+"' class='form-control' onkeyup='formataGrana(this)' placeholder='0,00'>"+
				            "</div>"+
				            "<div class='form-group col-md-1'>"+
								"<input type='text' id='audienciaCriminal-"+lista[index].id+"' name='audienciaCriminal-"+lista[index].id+"' class='form-control' onkeyup='formataGrana(this)' placeholder='0,00'>"+
				            "</div>"+
				            "<div class='form-group col-md-1'>"+
								"<input type='text' id='audienciaTrabalhista-"+lista[index].id+"' name='audienciaTrabalhista-"+lista[index].id+"' class='form-control' onkeyup='formataGrana(this)' placeholder='0,00'>"+
				            "</div>"+
				            "<div class='form-group col-md-1'>"+
								"<input type='text' id='audienciaProcon-"+lista[index].id+"' name='audienciaProcon-"+lista[index].id+"' class='form-control' onkeyup='formataGrana(this)' placeholder='0,00'>"+
				            "</div>"+
				            "<div class='form-group col-md-1'>"+
								"<input type='text' id='sessaoJulgamento-"+lista[index].id+"' name='sessaoJulgamento-"+lista[index].id+"' class='form-control' onkeyup='formataGrana(this)' placeholder='0,00'>"+
				            "</div>"+
				            "<div class='form-group col-md-1'>"+
								"<input type='text' id='preposto-"+lista[index].id+"' name='preposto-"+lista[index].id+"' class='form-control' onkeyup='formataGrana(this)' placeholder='0,00'>"+
				            "</div>"+
				            "<div class='form-group col-md-1'>"+
								"<input type='text' id='diligencia-"+lista[index].id+"' name='diligencia-"+lista[index].id+"' class='form-control' onkeyup='formataGrana(this)' placeholder='0,00'>"+
				            "</div>");
				}
			});

		}

		$("#fieldSet3").show(500);
		$("#carregandoValoresComarcas").hide(500);

	} else {
		$("#msgErro").show(500);
	}

}

// Modal Termos de Contrato
function chamaModalTermos(){
	$("#modalTermosContrato").modal();
}

// Confirma que concorda com os termos
function aceitaTermosContrato(){
	$("#termosCheck").val("ok");
	$("#botaoAceitaTermosContrato").removeAttr("class");
	$("#botaoAceitaTermosContrato").attr("class", "btn btn-success");
	$("#botaoAceitaTermosContrato").html("<i class='fa fa-check-circle'></i> Li e concordo com os termos de Contrato");
}

// Seleciona a lista de Comarcas de acordo com o ESTADO selecionado
$('#uf').on('change', function() {
  $("#carregandoComarcas").show();
	$("#carregandoComarcas").attr("class","text-primary");
	$("#carregandoComarcas").html("<i class='fa fa-spinner fa-pulse fa-fw'></i>");
	$("#municipioSelecionado").empty();
	$.getJSON('listarComarcasJson.php?uf='+$("#uf").val(), function(lista) {
		$("#municipioSelecionado").append("<option value=''>Selecione</option>");
		for (index=0; index < lista.length; index++) {
			$("#municipioSelecionado").removeAttr("disabled");
			$("#municipioSelecionado").append("<option value='"+lista[index].id+"'>"+lista[index].municipio+"</option>");
		}
		$("#carregandoComarcas").hide(500);
	});
});


// ========= Adiciona e/ou Remove comarcas no cadastro inicial
var comarcas = []; // Inicia o array de comarcas selecionadas
//console.log(comarcas);

$('#municipioSelecionado').on('change', function() {
	$("#carregandoComarcas").show();
	$("#carregandoComarcas").attr("class","text-primary");
	$("#carregandoComarcas").html("<i class='fa fa-spinner fa-pulse fa-fw'></i>");
	$.getJSON('findComarcaById.php?id='+$("#municipioSelecionado").val(), function(lista) {
		for (index=0; index < lista.length; index++) {
			$("#comarcasSelecionadas").append("<button id='botaoComarca-"+lista[index].id+"' onclick='removeComarca("+lista[index].id
				+")' type='button' class='btn btn-default' title='Clique para remover Comarca'>"
				+lista[index].municipio+" / "+lista[index].uf+" <span class='badge badge-light'><i class='fa fa-times-circle'></i></span> </button> ");
		}
		$("#carregandoComarcas").hide(500);
	});

	comarcas.push($("#municipioSelecionado").val()); // Adiciona a comarca selecionada ao array de comarcas
});

// Remove comarca selecionada - botao
function removeComarca(id){
	comarcas.splice(comarcas.indexOf(''+id+''), 1 );
	$("#botaoComarca-"+id).hide(500);
}

// Funcao chamada para fazer a validacao final do cadastro do correspondente (valores monetarios para cada comarca)
function validaValoresPorComarca(){

	var validadoFim = true;

	// Se passou tudo, chama a funcao de cadastro
	if (validadoFim){
		$("#msgErro").hide(500);
		$("#modalConfirmar").modal();
	} else {
		//$('html, body').animate({scrollTop:0}, 'slow');
		$("#msgErro").show(500);
	}
}

// Confirmar cadastro apos validacoes (botao 'finalizar' do MODAL)
function validaCadGecorpWeb(){
	$("#carregando").show();
	$("#carregandoTexto").show();

	/*var formulario = document.getElementById('formCadGecorpWeb');
	formulario.submit();*/

	// TOTO ===== Alterar para JSON/AJAX
	// Primeiro JSON/AJAX para cadastrar os dados principais (gerar ID para o correspondente recem-cadastrado)
	// Segundo JSON/AJAX para cadastrar os valores por comarca (fazendo relacionamento com o ID recem-criado do correspondente)
}

// Voltar cadastro tela inicial
function voltar(){
	$('#fieldSet3').hide(500);
	$('#fieldSet1').show(500);
	$('#fieldSet2').show(500);
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

// Verifica CPF valido e depois, se e duplicado
$( "#cpf" ).blur(function() {
	if ($("#cpf").val() != ""){

		if ($("#cpf").val().length < 14){
			$("#cpfInvalido").show(500);
			$("#cpf").css("border","1px solid red");
			$("#btnField1").removeAttr("class");
			$("#btnField1").attr("class", "btn btn-danger");
			$("#cadcanExt").removeAttr("disabled");
			$("#cadcanExt").attr("disabled", "disabled");
		}

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
	  			$("#cpfInvalido").show(500);
	  			$("#cpf").css("border","1px solid red");
	  			$("#btnField1").removeAttr("class");
				$("#btnField1").attr("class", "btn btn-danger");
				$("#cadcanExt").removeAttr("disabled");
				$("#cadcanExt").attr("disabled", "disabled");
	  		} else {
	  			$("#cpfInvalido").hide(500);
	  			$("#cpf").css("border","1px solid #999");
	  			/*$.getJSON('../admin/duplicidade.php?area=candidatoExt&param='+$("#cpf").val(), function(lista) {
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
				});*/
	  		}

	    }

	}

});


function formataGrana(i) {
	var v = i.value.replace(/\D/g,'');
	v = (v/100).toFixed(2) + '';
	v = v.replace(".", ",");
	v = v.replace(/(\d)(\d{3})(\d{3}),/g, "$1.$2.$3,");
	v = v.replace(/(\d)(\d{3}),/g, "$1.$2,");
	i.value = v;
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
