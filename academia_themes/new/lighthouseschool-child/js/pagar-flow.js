if(document.getElementById("pagar")){

function ValidateEmail(mail){
 	if (/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/.test(mail)){
    	return (true)
  	} else {
		return (false)
	}
}

document.addEventListener("DOMContentLoaded", () => {
    const cleaveRut = new Cleave("#rut", { delimiters: ["", "", "-"], blocks: [2, 3, 3, 1], uppercase: true });
});

var Fn = {
    validaRut: function (rutCompleto) {
        if (!/^[0-9]+[-|‐]{1}[0-9kK]{1}$/.test(rutCompleto)) return false;
        var tmp = rutCompleto.split("-");
        var digv = tmp[1];
        var rut = tmp[0];
        if (digv == "K") digv = "k";
        return Fn.dv(rut) == digv;
    },
    dv: function (T) {
        var M = 0,
            S = 1;
        for (; T; T = Math.floor(T / 10)) S = (S + (T % 10) * (9 - (M++ % 6))) % 11;
        return S ? S - 1 : "k";
    },
};

document.getElementById("pagar").addEventListener("click", function(){

    let form = document.getElementById("form-flow");

    // campos 
    let nombre = document.getElementById("nombre");
    let apellido = document.getElementById("apellido");
    let email = document.getElementById("email");
    let telefono = document.getElementById("telefono");
    let rut = document.getElementById("rut");

    let regiones = document.getElementById("regiones");
    let comunas = document.getElementById("comunas");
    let dir = document.getElementById("dir");

    let curso = document.getElementById("curso");
    let monto = document.getElementById("monto");
    let id_curso = document.getElementById("id_curso");

    if(nombre.value === ""){
        alert("Por favor completa el campo nombre para continuar");
        return false;
    }

    if(apellido.value === ""){
        alert("Por favor completa el campo apellido para continuar");
        return false;
    }

    if(email.value === ""){
        alert("Por favor completa el campo email para continuar");
        return false;
    }

    if(telefono.value === ""){
        alert("Por favor completa el campo telefono para continuar");
        return false;
    }

    if(!ValidateEmail(email.value)){
        alert("Por favor ingresa un email válido para continuar");
        return false;
    }

    if(rut.value === ""){
        alert("Por favor completa el campo rut para continuar");
        return false;
    }

    if (!Fn.validaRut(rut.value)) {
        alert("Por favor ingresa un rut valido para continuar");
        return false;
    }

    if(regiones.value === "" || regiones.value === "sin-region" ){
        alert("Por favor selecciona una región para continuar");
        return false;
    }

    if(comunas.value === "" || comunas.value === "sin-comuna" ){
        alert("Por favor selecciona una comuna para continuar");
        return false;
    }

    if(dir.value === ""){
        alert("Por favor ingresa una dirección para continuar");
        return false;
    }

    document.getElementById("pagar").innerHTML = "PROCESANDO...";

    if(curso.value != "" && monto.value != "" && id_curso.value != ""){

        form.submit();

    }

});

}
