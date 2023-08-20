$(document).ready(function () {
  
  $("#btn-add-new-user").click((e) => {
    e.preventDefault();
    saveUser();
  });
});

const saveUser = async () => {
  let nombre = $("#nombrefull").val();	
  let password = $("#password").val();
  let confirmarPassword = $("#password-2").val();
  let codigo = $("#codigo").val();
  let tipotienda = $("#tipotienda").val();
  let provincia = $("#provincia").val();
  let ubicacion = $("#ubicacion").val();
  let producto = $("#producto").val();

  if (nombre.length <= 6) {
    Swal.fire({
      html: "Ingrese el Nombre. Debe contener por lo menos 6 caracteres",
      icon: "error",
      confirmButtonText: "Aceptar",
      position: "top",
    });
    return;
  }
  

  if (tipotienda == "NO_SELECTED") {
    Swal.fire({
      html: "Seleccione el tipo de tienda.",
      icon: "error",
      confirmButtonText: "Aceptar",
      position: "top",
    });
    return;
  }
  
  
  if (codigo.length <= 5) {
    Swal.fire({
      html: "El codigo debe tener por lo menos 6 caractéres",
      icon: "error",
      confirmButtonText: "Aceptar",
      position: "top",
    });
    return;
  }
  

  if (provincia == "NO_SELECTED") {
    Swal.fire({
      html: "Seleccione la provincia.",
      icon: "error",
      confirmButtonText: "Aceptar",
      position: "top",
    });
    return;
  }
  
  if (ubicacion.length <= 6) {
    Swal.fire({
      html: "Ingrese la ubicación corta. Por lo menos 6 caracteres.",
      icon: "error",
      confirmButtonText: "Aceptar",
      position: "top",
    });
    return;
  }
  
  if (producto == "NO_SELECTED") {
    Swal.fire({
      html: "Seleccione producto.",
      icon: "error",
      confirmButtonText: "Aceptar",
      position: "top",
    });
    return;
  }
  
  
  
    if (!password) {
    Swal.fire({
      html: "Ingrese el password.",
      icon: "error",
      confirmButtonText: "Aceptar",
      position: "top",
    });
    return;
  }

  if (!confirmarPassword) {
    Swal.fire({
      html: "Confirme el password.",
      icon: "error",
      confirmButtonText: "Aceptar",
      position: "top",
    });
    return;
  }

  if (password != confirmarPassword) {
    Swal.fire({
      html: "<div style='text-align:left'><b>Las contraseñas no coinciden.</b></div>",
      icon: "error",
      confirmButtonText: "Aceptar",
      position: "top",
    });
    return;
  }

  if (!password.match(/[A-Z]/)) {
    Swal.fire({
      html: "<div style='text-align:left'><b>La contraseña debe poseer mínimo una letra en mayúscula.</b></div>",
      icon: "error",
      confirmButtonText: "Aceptar",
      position: "top",
    });
    return;
  }

  if (!password.match(/\d/)) {
    Swal.fire({
      html: "<div style='text-align:left'><b>La contraseña debe poseer mínimo un número.</b></div>",
      icon: "error",
      confirmButtonText: "Aceptar",
      position: "top",
    });
    return;
  }

  
  //::: generaCrypt - Depende de CryptoJS
function generaCrypt(pass){
	var keypass = "V4Ku+Sh0&03@wW0";
	var aa =  CryptoJS.SHA512(pass.toString()+keypass.toString());
	//var aa =  CryptoJS.SHA512(a1,keypass.toString(CryptoJS.enc.Utf8));
	var bb =  aa.toString(CryptoJS.enc.Base64);
	//var cc =  bb.toString(CryptoJS.enc.Ut8);
	
	return bb;
  }
  //----------
  
  let siteKey = "6LeiVcYaAAAAAJdD1cpDx2jP8PSGokBgnG9hKDob";
  let action = "newUserApp";
  pleskapitoken = generaCrypt(password);
  /* grecaptcha.execute(siteKey, { action: action }).then(function (_token) { */
    $.ajax({
      type: "post",
      url: "./services/newUserAppService.php",
      beforeSend: () => {
        $("#loader").css("display", "block");
        $("#lbl").css("display", "none");
      },
      data: {
        pleskapitoken,
        action,
		nombre,	
		codigo,
		tipotienda,
		provincia,
		ubicacion,
		producto
      },
      success: (response) => {
        if (response == "EXISTS") {
          Swal.fire(
            "Error!",
            "El nombre del usuario ya se encuentra registrado.",
            "error"
          );
          return;
        }
		
        if (response.indexOf("ERROR") == 0) {
          Swal.fire(
            "Error!",
            "Ha ocurrido un error al insertar el usuario.",
            "error"
          );
          return;
        }
		
		if (response == "INSERTED") {
          Swal.fire(
			"Usuario registrado!",
			"Cuenta de usuario registrada.",
			"success"
          ).then(() => {
          $(location).attr("href", "appUsers.php");
        });
		}
      },
      complete: () => {
        $("#loader").css("display", "none");
        $("#lbl").css("display", "block");
      },
    });
 /* });*/
};
